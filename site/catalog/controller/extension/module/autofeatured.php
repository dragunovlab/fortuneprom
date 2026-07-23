<?php
class ControllerExtensionModuleAutoFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/autofeatured');

		

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}
		$this->load->model('extension/module/autofeatured');
		
		$product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : 0;
		$results = $this->model_extension_module_autofeatured->getProductAutoFeatured($product_id,$setting['limit'],$setting['attribute'],$setting['category_source'],$setting['category'],$setting['sortby'],$setting['in_stock'],$setting['one_cat']);
		$data['heading_title'] = $setting['titleview'] ? $setting['name'] . $results['text'] : $setting['name'];
		foreach ($results['products'] as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
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
			return $this->load->view('extension/module/autofeatured', $data);
		}
	}
}

