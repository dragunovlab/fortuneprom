<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		$limit = !empty($setting['limit']) ? (int)$setting['limit'] : 4;
		$route = isset($this->request->get['route']) ? $this->request->get['route'] : 'common/home';

		if ($route === 'common/home') {
			$limit = max(5, $limit);
		}

		if (!empty($setting['product'])) {
			$product_infos = array();

			foreach (array_slice($setting['product'], 0, $limit) as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$product_infos[$product_info['product_id']] = $product_info;
				}
			}

			if (count($product_infos) < $limit) {
				$fallback_products = $this->model_catalog_product->getProducts(array(
					'sort'  => 'p.date_added',
					'order' => 'DESC',
					'start' => 0,
					'limit' => $limit * 3
				));

				foreach ($fallback_products as $product_info) {
					$product_infos[$product_info['product_id']] = $product_info;

					if (count($product_infos) >= $limit) {
						break;
					}
				}
			}

			foreach ($product_infos as $product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = 'Цена по запросу';
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = false;
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = false;
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/module/featured', $data);
		}
	}
}
