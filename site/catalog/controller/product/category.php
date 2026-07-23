<?php
class ControllerProductCategory extends Controller {
	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			if (!empty($category_info['seo_h1'])) {
				$data['heading_title'] = $category_info['seo_h1'];
			} else {
				$data['heading_title'] = $category_info['name'];
			}

			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['description_bottom'] = html_entity_decode($category_info['description_bottom'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');

			$results = $this->model_catalog_category->getCategories($category_id);

foreach ($results as $result) {
    // Попытка взять картинку из категории
    if (!empty($result['image'])) {
        $image = $this->model_tool_image->resize($result['image'], 80, 80);
    } else {
        // Попытка взять картинку из первого товара категории
        $filter_data = array(
            'filter_category_id'  => $result['category_id'],
            'filter_sub_category' => true,
            'start'               => 0,
            'limit'               => 1
        );

        $product_info = $this->model_catalog_product->getProducts($filter_data);

        if (!empty($product_info) && !empty($product_info[0]['image'])) {
            $image = $this->model_tool_image->resize($product_info[0]['image'], 80, 80);
        } else {
            $image = $this->model_tool_image->resize('placeholder.png', 80, 80);
        }
    }

    // Собираем подкатегорию
    $data['categories'][] = array(
        'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts(array(
            'filter_category_id'  => $result['category_id'],
            'filter_sub_category' => true
        )) . ')' : ''),
        'thumb' => $image,
        'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
    );
}





			$is_reduktory_category = ((int)$category_id === 236) || !empty($virtual_category);

			if ($is_reduktory_category) {
				$data['reduktory_hub_links'] = array(
					array('title' => 'Редукторы NMRV', 'text' => 'Червячные редукторы по типоразмерам и модификациям.', 'href' => 'https://fortuneprom.kz/reduktory/chervyachnye/nmrv/', 'icon' => 'fa-cogs'),
					array('title' => 'NMRV 050', 'text' => 'Типоразмер 050 и точные исполнения.', 'href' => 'https://fortuneprom.kz/reduktory/chervyachnye/nmrv/nmrv-050/', 'icon' => 'fa-sliders'),
					array('title' => 'NMRV 075', 'text' => 'Модификации NMRV 075 для промышленности.', 'href' => 'https://fortuneprom.kz/reduktory/chervyachnye/nmrv/nmrv-075/', 'icon' => 'fa-cog'),
					array('title' => '1Ц2У', 'text' => 'Цилиндрические двухступенчатые редукторы.', 'href' => 'https://fortuneprom.kz/reduktory/cilindricheskie/1ts2u/', 'icon' => 'fa-gears'),
					array('title' => '1Ц2У-200', 'text' => 'Типоразмер для точного модельного спроса.', 'href' => 'https://fortuneprom.kz/reduktory/cilindricheskie/1ts2u/1ts2u-200/', 'icon' => 'fa-wrench'),
					array('title' => 'Редукторы 3МП', 'text' => 'Планетарные редукторы с высоким моментом.', 'href' => 'https://fortuneprom.kz/reduktory/planetarnye/3mp/', 'icon' => 'fa-cogs'),
				);
			} else {
				$data['reduktory_hub_links'] = array();
			}

			// Build full reduktory category tree (3 levels)
			$data['category_tree'] = array();
			$reducer_category_ids = array_merge(array(236), range(2442, 2527), range(2623, 2657));
			$reducer_category_context = in_array((int)$category_id, $reducer_category_ids, true);
			if ($reducer_category_context) {
				try {
					$cm = $this->model_catalog_category;
					$categories = $cm->getCategories(236);
					if (empty($categories)) {
						$data['category_tree'][] = array('name' => 'Загрузка...', 'count' => 0, 'href' => '', 'children' => array());
					}
					$tree_source = array();
					$tree_category_ids = array();
					foreach ($categories as $t) {
						if (!in_array((int)$t['category_id'], $reducer_category_ids, true)) continue;
						$t['children'] = array();
						$tree_category_ids[] = (int)$t['category_id'];
						
						$sl = array();
						foreach ($cm->getCategories($t['category_id']) as $s) {
							$s['children'] = array();
							$tree_category_ids[] = (int)$s['category_id'];
							$tl = array();
							foreach ($cm->getCategories($s['category_id']) as $tp) {
								$tree_category_ids[] = (int)$tp['category_id'];
								$tl[] = $tp;
							}
							$s['children'] = $tl;
							$sl[] = $s;
						}
						$t['children'] = $sl;
						$tree_source[] = $t;
					}

					$category_counts = $this->getReducerCategoryProductCounts($tree_category_ids);

					foreach ($tree_source as $t) {
						$tc = isset($category_counts[(int)$t['category_id']]) ? (int)$category_counts[(int)$t['category_id']] : 0;
						if (!$tc) continue;
						$sl = array();
						foreach ($t['children'] as $s) {
							$sc = isset($category_counts[(int)$s['category_id']]) ? (int)$category_counts[(int)$s['category_id']] : 0;
							if (!$sc) continue;
							$tl = array();
							foreach ($s['children'] as $tp) {
								$tpc = isset($category_counts[(int)$tp['category_id']]) ? (int)$category_counts[(int)$tp['category_id']] : 0;
								if (!$tpc) continue;
								$tl[] = array('name' => $tp['name'], 'count' => $tpc, 'href' => $this->url->link('product/category', 'path=' . $tp['category_id']));
							}
							$sl[] = array('name' => $s['name'], 'count' => $sc, 'href' => $this->url->link('product/category', 'path=' . $s['category_id']), 'children' => $tl);
						}
						$data['category_tree'][] = array('name' => $t['name'], 'count' => $tc, 'href' => $this->url->link('product/category', 'path=' . $t['category_id']), 'children' => $sl);
					}
				} catch (Exception $e) {
					$data['category_tree'] = array(array('name' => 'Error: ' . $e->getMessage(), 'count' => 0, 'href' => '#', 'children' => array()));
				}
			}

			$data['products'] = array();
			$reducer_filter_context = $reducer_category_context;
			$reducer_filter_definitions = $this->getReducerFilterDefinitions();
			$reducer_active_filters = $reducer_filter_context ? $this->getReducerActiveFilters($reducer_filter_definitions) : array();
			$reducer_filter_params_present = $reducer_filter_context ? $this->hasReducerFilterParams($reducer_filter_definitions) : false;
			$reducer_filter_url = $this->getReducerFilterUrlPart($reducer_active_filters);

			$filter_data_base = array(
				'filter_category_id' => $category_id,
				'filter_sub_category' => true,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order
			);

			$filter_data = $filter_data_base;
			$filter_data['start'] = ($page - 1) * $limit;
			$filter_data['limit'] = $limit;

			$data['reducer_filters'] = array('enabled' => false);

			if ($reducer_filter_context) {
				$reducer_all_filter_data = $filter_data_base;
				$reducer_all_filter_data['start'] = 0;
				$reducer_all_filter_data['limit'] = 10000;
				$reducer_all_results = $this->model_catalog_product->getProducts($reducer_all_filter_data);
				$reducer_filtered_results = $this->filterReducerProducts($reducer_all_results, $reducer_active_filters, $reducer_filter_definitions);
				$data['reducer_filters'] = $this->buildReducerFilters($reducer_all_results, $reducer_active_filters, $reducer_filter_definitions, (string)$this->request->get['path']);
			}

			if ($reducer_filter_params_present && method_exists($this->document, 'setRobots')) {
				$this->document->setRobots('noindex,follow');
			}

			if ($reducer_filter_context && $reducer_active_filters) {
				$product_total = count($reducer_filtered_results);

				if ($product_total && (($page - 1) * $limit >= $product_total)) {
					$page = 1;
				}

				$results = array_slice($reducer_filtered_results, ($page - 1) * $limit, $limit, true);
			} else {
				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				$results = $this->model_catalog_product->getProducts($filter_data);
			}

			foreach ($results as $result) {
				$product_href = $this->getCanonicalProductHref((int)$result['product_id']);

				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}

				$price = 'Цена по запросу';
				$special = false;
				$tax = false;

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'price_no_format' => 0,
					'special_no_format' => false,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $product_href,
					'canonical_href' => $product_href
				);
			}

			$url = '';

			if ($reducer_filter_url) {
				$url .= $reducer_filter_url;
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if ($reducer_filter_url) {
				$url .= $reducer_filter_url;
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if ($reducer_filter_url) {
				$url .= $reducer_filter_url;
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($reducer_filter_params_present) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
			} else {
				if ($page == 1) {
				    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
				} else {
					$this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. $page), 'canonical');
				}

				if ($page > 1) {
				    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
				}

				if ($limit && ceil($product_total / $limit) > $page) {
				    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1)), 'next');
				}
			}

			// JSON-LD BreadcrumbList
			$schema_bc = array('@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => array());
			$schema_bc_position = 1;
			foreach ($data['breadcrumbs'] as $i => $crumb) {
				$crumb_name = trim(strip_tags(html_entity_decode($crumb['text'], ENT_QUOTES, 'UTF-8')));

				if ($crumb_name === '') {
					$crumb_name = ($i === 0) ? 'FortunePROM.kz' : '';
				}

				if ($crumb_name === '') {
					continue;
				}

				$schema_bc['itemListElement'][] = array('@type' => 'ListItem', 'position' => $schema_bc_position++, 'name' => $crumb_name, 'item' => $crumb['href']);
			}
			$schema_html = '<script type="application/ld+json">' . json_encode($schema_bc, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';

			// JSON-LD CollectionPage + ItemList
			$currUrlCat = $this->url->link('product/category', 'path=' . $category_info['category_id'] . ($page > 1 ? '&page=' . (int)$page : ''));
			$schema_coll = array(
				'@context' => 'https://schema.org',
				'@type' => 'CollectionPage',
				'name' => $category_info['meta_title'] ?: $category_info['name'],
				'description' => $category_info['meta_description'] ?: '',
				'url' => $currUrlCat,
				'mainEntity' => array(
					'@type' => 'ItemList',
					'itemListElement' => array()
				)
			);
			if (!empty($data['products'])) {
				foreach ($data['products'] as $pi => $pdata) {
					$schema_coll['mainEntity']['itemListElement'][] = array(
						'@type' => 'ListItem',
						'position' => $pi + 1,
						'url' => !empty($pdata['canonical_href']) ? $pdata['canonical_href'] : $pdata['href']
					);
				}
			}
			$schema_html .= '<script type="application/ld+json">' . json_encode($schema_coll, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';

			// JSON-LD FAQPage (for main Category 'Редукторы' - ID 236)
			if ($category_info['category_id'] == 236) {
				$schema_faq = array(
					'@context' => 'https://schema.org',
					'@type' => 'FAQPage',
					'mainEntity' => array(
						array(
							'@type' => 'Question',
							'name' => 'Какие типы промышленных редукторов вы поставляете?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Мы поставляем червячные редукторы (NMRV, Ч, 2Ч), цилиндрические (1Ц2У, Ц2У, РК), коническо-цилиндрические (КЦ1, КЦ2), планетарные (3МП, 4МЦ2С), крановые (ВК, ВКУ) и импортные мотор-редукторы ведущих брендов.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Предоставляется ли гарантия на редукторы?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Да, на все поставляемые промышленные редукторы и мотор-редукторы распространяется официальная заводская гарантия 12 месяцев со дня отгрузки. Каждый редуктор поставляется с техническим паспортом изделия.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Возможна ли доставка редукторов по Казахстану?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Да, мы осуществляем быструю доставку редукторов во все регионы Республики Казахстан (Астана, Шымкент, Караганда, Актобе, Уральск, Павлодар, Усть-Каменогорск и др.) надежными транспортными компаниями. По Алматы возможен самовывоз со склада.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Осуществляете ли вы подбор редуктора по характеристикам?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Да, наши инженеры помогут правильно подобрать редуктор или мотор-редуктор по ключевым параметрам: передаточное число, крутящий момент, мощность электродвигателя, габаритные размеры и монтажное исполнение (лапы, фланец, полый вал).'
							)
						)
					)
				);
				$schema_html .= '<script type="application/ld+json">' . json_encode($schema_faq, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
			}

			// JSON-LD FAQPage (for Category 'Мотор-редукторы' - ID 2446)
			if ((int)$category_info['category_id'] === 2446) {
				$schema_faq = array(
					'@context' => 'https://schema.org',
					'@type' => 'FAQPage',
					'mainEntity' => array(
						array(
							'@type' => 'Question',
							'name' => 'Каковы особенности мотор-редукторов и для чего они служат?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Мотор-редуктор объединяет в себе асинхронный электродвигатель и редуктор в одном компактном корпусе. Служит для снижения частоты вращения вала и кратного увеличения крутящего момента на выходном валу.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Какие типы мотор-редукторов наиболее популярны в Казахстане?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'В промышленности широко используются червячные мотор-редукторы (компактные, недорогие, самотормозящиеся), соосно-цилиндрические (высокий КПД, высокая мощность), коническо-цилиндрические (для тяжелых угловых передач) и планетарные 3МП (высокий крутящий момент).'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Как правильно подобрать мотор-редуктор?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Для подбора требуется знать: требуемую частоту вращения выходного вала (об/мин), крутящий момент (Н·м) или мощность электродвигателя (кВт), тип монтажного исполнения (на лапах, на фланце), а также режим и продолжительность работы (сервис-фактор).'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Предоставляется ли гарантия на мотор-редукторы?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Да, компания Fortune PROM предоставляет официальную гарантию 12 месяцев на все типы мотор-редукторов, а также обеспечивает клиентов паспортами изделий, чертежами и квалифицированной технической поддержкой.'
							)
						)
					)
				);
				$schema_html .= '<script type="application/ld+json">' . json_encode($schema_faq, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
			}

			// JSON-LD FAQPage (for Category 'Червячные редукторы' - ID 2442)
			if ((int)$category_info['category_id'] === 2442) {
				$schema_faq = array(
					'@context' => 'https://schema.org',
					'@type' => 'FAQPage',
					'mainEntity' => array(
						array(
							'@type' => 'Question',
							'name' => 'Что такое червячный редуктор и в чем его принцип работы?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Червячный редуктор — это механизм, передающий вращение между скрещивающимися осями (обычно под углом 90°) с помощью червяка (винтового шнека с резьбой) и червячного колеса. Это обеспечивает плавность хода и высокое передаточное число в одной ступени.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Какое масло заливается в червячные редукторы NMRV?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'В редукторы габаритов от 025 до 090 на заводе заливается синтетическое масло ISO VG 320, рассчитанное на весь срок службы без замены (работа при температурах от -25°C до +50°C). В тяжелые редукторы (габариты 110-150) заливается минеральное масло, требующее периодической замены.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Что означает эффект самоторможения червячной пары?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Это свойство червячной передачи препятствовать передаче крутящего момента от выходного вала (колеса) к входному (червяку). Самоторможение возникает при малых углах подъема винтовой линии червяка (обычно при передаточных числах i ≥ 30).'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Каковы недостатки червячных редукторов по сравнению с цилиндрическими?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'К основным недостаткам относятся: более низкий КПД (из-за повышенного трения скольжения), склонность к нагреву при интенсивной постоянной работе, и недопустимость высоких ударных нагрузок на выходной вал.'
							)
						)
					)
				);
				$schema_html .= '<script type="application/ld+json">' . json_encode($schema_faq, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
			}

			// JSON-LD FAQPage (for Category 'Планетарные редукторы' - ID 2445)
			if ((int)$category_info['category_id'] === 2445) {
				$schema_faq = array(
					'@context' => 'https://schema.org',
					'@type' => 'FAQPage',
					'mainEntity' => array(
						array(
							'@type' => 'Question',
							'name' => 'Что такое планетарный редуктор и в чем его ключевые преимущества?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Планетарный редуктор — это соосный механизм, использующий планетарную передачу (солнечная шестерня, сателлиты, водило и коронная шестерня). Главные преимущества: высокая удельная мощность, компактность, соосность валов и способность передавать колоссальный крутящий момент.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Для каких задач подходят редукторы серии 3МП?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Редукторы и мотор-редукторы серии 3МП широко применяются в приводах бетоносмесителей, дозаторов, мешалок химических и пищевых производств, оросительных систем, лебедок, конвейеров и элеваторов, где требуется низкая частота вращения при высоком крутящем моменте.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Какие монтажные исполнения бывают у планетарных редукторов 3МП?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Они выпускаются в двух основных исполнениях: на лапах (для горизонтального монтажа на плиту) и фланцевые (для вертикального или горизонтального соединения непосредственно с рабочим органом машины). Валы могут быть цилиндрическими или коническими.'
							)
						),
						array(
							'@type' => 'Question',
							'name' => 'Предоставляется ли гарантия на планетарные редукторы?',
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text' => 'Да, компания Fortune PROM предоставляет официальную гарантию 12 месяцев на все типы планетарных редукторов 3МП, а также обеспечивает клиентов техническими паспортами, чертежами и схемами монтажа.'
							)
						)
					)
				);
				$schema_html .= '<script type="application/ld+json">' . json_encode($schema_faq, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$output = $this->load->view('product/category', $data);

			if ($reducer_filter_params_present && stripos($output, 'name="robots"') === false) {
				$output = str_ireplace('</head>', '<meta name="robots" content="noindex,follow" />' . "\n" . '</head>', $output);
			}

			$this->response->setOutput($output);
			if (!empty($schema_html)) $this->response->appendOutput($schema_html);
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	private function getReducerCategoryProductCounts($category_ids) {
		$ids = array();

		foreach ($category_ids as $category_id) {
			$category_id = (int)$category_id;

			if ($category_id > 0) {
				$ids[$category_id] = $category_id;
			}
		}

		if (!$ids) {
			return array();
		}

		$sql = "SELECT cp.path_id AS category_id, COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE cp.path_id IN (" . implode(',', $ids) . ") AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY cp.path_id";
		$query = $this->db->query($sql);
		$counts = array();

		foreach ($query->rows as $row) {
			$counts[(int)$row['category_id']] = (int)$row['total'];
		}

		return $counts;
	}

	private function getReducerFilterDefinitions() {
		return array(
			'fp_type' => array(
				'title' => 'Тип редуктора',
				'icon' => 'fa-cogs',
				'options' => array(
					array('value' => 'worm', 'label' => 'Червячные', 'tokens' => array('червяч', 'nmrv', 'nrv', '2ч', 'ч-')),
					array('value' => 'cylindrical', 'label' => 'Цилиндрические', 'tokens' => array('цилиндр', '1ц2у', 'ц2у', 'рцд', 'ц3у', 'цтнд')),
					array('value' => 'bevel_cylindrical', 'label' => 'Коническо-цилиндрические', 'tokens' => array('коническо-цилиндр', 'коническо цилиндр', 'кц')),
					array('value' => 'planetary', 'label' => 'Планетарные', 'tokens' => array('планетар', '3мп', '4мц2с')),
					array('value' => 'motor', 'label' => 'Мотор-редукторы', 'tokens' => array('мотор-редуктор', 'мотор редуктор', 'motor-reduktor')),
					array('value' => 'crane', 'label' => 'Крановые', 'tokens' => array('кранов', 'вк-', 'вку', 'рк-'))
				)
			),
			'fp_series' => array(
				'title' => 'Серия / бренд',
				'icon' => 'fa-tags',
				'options' => array(
					array('value' => 'nmrv', 'label' => 'NMRV', 'tokens' => array('nmrv')),
					array('value' => '1ts2u', 'label' => '1Ц2У', 'tokens' => array('1ц2у', '1ts2u')),
					array('value' => 'ts2u', 'label' => 'Ц2У', 'tokens' => array('ц2у', 'ts2u')),
					array('value' => '3mp', 'label' => '3МП', 'tokens' => array('3мп', '3mp')),
					array('value' => '4mts2s', 'label' => '4МЦ2С', 'tokens' => array('4мц2с', '4mts2s')),
					array('value' => 'rk', 'label' => 'РК', 'tokens' => array('рк-', 'rk-')),
					array('value' => 'vk', 'label' => 'ВК', 'tokens' => array('вк-', 'vk-')),
					array('value' => 'bonfiglioli', 'label' => 'Bonfiglioli', 'tokens' => array('bonfiglioli')),
					array('value' => 'lenze', 'label' => 'Lenze', 'tokens' => array('lenze')),
					array('value' => 'bauer', 'label' => 'Bauer', 'tokens' => array('bauer'))
				)
			),
			'fp_power' => array(
				'title' => 'Мощность двигателя',
				'icon' => 'fa-bolt',
				'options' => array(
					array('value' => '0-37', 'label' => '0,37 кВт', 'tokens' => array('0,37', '0.37', '0-37')),
					array('value' => '0-75', 'label' => '0,75 кВт', 'tokens' => array('0,75', '0.75', '0-75')),
					array('value' => '1-5', 'label' => '1,5 кВт', 'tokens' => array('1,5', '1.5', '1-5')),
					array('value' => '2-2', 'label' => '2,2 кВт', 'tokens' => array('2,2', '2.2', '2-2')),
					array('value' => '3', 'label' => '3 кВт', 'patterns' => array('/(^|[^0-9])3([,.]0)?([^0-9]|$)/u')),
					array('value' => '5-5', 'label' => '5,5 кВт', 'tokens' => array('5,5', '5.5', '5-5')),
					array('value' => '7-5', 'label' => '7,5 кВт', 'tokens' => array('7,5', '7.5', '7-5')),
					array('value' => '11', 'label' => '11 кВт', 'patterns' => array('/(^|[^0-9])11([,.]0)?([^0-9]|$)/u'))
				)
			),
			'fp_ratio' => array(
				'title' => 'Передаточное число',
				'icon' => 'fa-sliders',
				'options' => array(
					array('value' => '10', 'label' => 'i=10', 'patterns' => array('/(^|[^0-9])10([^0-9]|$)/u')),
					array('value' => '20', 'label' => 'i=20', 'patterns' => array('/(^|[^0-9])20([^0-9]|$)/u')),
					array('value' => '25', 'label' => 'i=25', 'patterns' => array('/(^|[^0-9])25([^0-9]|$)/u')),
					array('value' => '30', 'label' => 'i=30', 'patterns' => array('/(^|[^0-9])30([^0-9]|$)/u')),
					array('value' => '40', 'label' => 'i=40', 'patterns' => array('/(^|[^0-9])40([^0-9]|$)/u')),
					array('value' => '50', 'label' => 'i=50', 'patterns' => array('/(^|[^0-9])50([^0-9]|$)/u')),
					array('value' => '60', 'label' => 'i=60', 'patterns' => array('/(^|[^0-9])60([^0-9]|$)/u'))
				)
			),
			'fp_mount' => array(
				'title' => 'Исполнение',
				'icon' => 'fa-wrench',
				'options' => array(
					array('value' => 'b3', 'label' => 'B3 / на лапах', 'tokens' => array('b3', 'в3', 'на лапах', 'лапы')),
					array('value' => 'b5', 'label' => 'B5 / фланец', 'tokens' => array('b5', 'в5', 'фланец', 'фланцев')),
					array('value' => 'b14', 'label' => 'B14', 'tokens' => array('b14', 'в14')),
					array('value' => 'v3', 'label' => 'V3', 'tokens' => array('v3', 'в3')),
					array('value' => 'hollow', 'label' => 'Полый вал', 'tokens' => array('полый вал', 'полого вала')),
					array('value' => 'solid', 'label' => 'Цилиндрический вал', 'tokens' => array('цилиндрический вал', 'сплошной вал'))
				)
			)
		);
	}

	private function getReducerActiveFilters($definitions) {
		$active = array();

		foreach ($definitions as $key => $group) {
			if (!isset($this->request->get[$key])) {
				continue;
			}

			$value = preg_replace('/[^a-z0-9\\-]/i', '', (string)$this->request->get[$key]);

			foreach ($group['options'] as $option) {
				if ($option['value'] === $value) {
					$active[$key] = $value;
					break;
				}
			}
		}

		return $active;
	}

	private function hasReducerFilterParams($definitions) {
		foreach (array_keys($definitions) as $key) {
			if (isset($this->request->get[$key])) {
				return true;
			}
		}

		return false;
	}

	private function getReducerFilterUrlPart($active_filters) {
		$url = '';

		foreach ($active_filters as $key => $value) {
			$url .= '&' . $key . '=' . rawurlencode($value);
		}

		return $url;
	}

	private function buildReducerFilters($products, $active_filters, $definitions, $path) {
		$filters = array(
			'enabled' => true,
			'title' => 'Подбор редуктора',
			'subtitle' => 'Фильтр по типу, серии и параметрам',
			'total' => count($products),
			'active_total' => count($this->filterReducerProducts($products, $active_filters, $definitions)),
			'reset_href' => $this->url->link('product/category', 'path=' . $path),
			'active_chips' => array(),
			'groups' => array(),
			'landing_links' => array(
				array('label' => 'NMRV', 'href' => 'https://fortuneprom.kz/reduktory/chervyachnye/nmrv/'),
				array('label' => 'NMRV 050', 'href' => 'https://fortuneprom.kz/reduktory/chervyachnye/nmrv/nmrv-050/'),
				array('label' => 'NMRV 075', 'href' => 'https://fortuneprom.kz/reduktory/chervyachnye/nmrv/nmrv-075/'),
				array('label' => '1Ц2У', 'href' => 'https://fortuneprom.kz/reduktory/cilindricheskie/1ts2u/'),
				array('label' => '1Ц2У-200', 'href' => 'https://fortuneprom.kz/reduktory/cilindricheskie/1ts2u/1ts2u-200/'),
				array('label' => '3МП', 'href' => 'https://fortuneprom.kz/reduktory/planetarnye/3mp/')
			)
		);

		foreach ($definitions as $key => $group) {
			$filter_group = array(
				'key' => $key,
				'title' => $group['title'],
				'icon' => $group['icon'],
				'options' => array()
			);

			foreach ($group['options'] as $option) {
				$count_filters = $active_filters;
				$count_filters[$key] = $option['value'];
				$count = count($this->filterReducerProducts($products, $count_filters, $definitions));
				$is_active = isset($active_filters[$key]) && $active_filters[$key] === $option['value'];

				$filter_group['options'][] = array(
					'label' => $option['label'],
					'value' => $option['value'],
					'count' => $count,
					'active' => $is_active,
					'disabled' => ($count < 1 && !$is_active),
					'href' => $this->getReducerFilterHref($path, $active_filters, $key, $is_active ? '' : $option['value'])
				);

				if ($is_active) {
					$filters['active_chips'][] = array(
						'label' => $group['title'] . ': ' . $option['label'],
						'href' => $this->getReducerFilterHref($path, $active_filters, $key, '')
					);
				}
			}

			$filters['groups'][] = $filter_group;
		}

		return $filters;
	}

	private function getReducerFilterHref($path, $active_filters, $override_key = '', $override_value = '') {
		$params = array('path' => $path);

		foreach ($active_filters as $key => $value) {
			if ($key !== $override_key) {
				$params[$key] = $value;
			}
		}

		if ($override_key && $override_value !== '') {
			$params[$override_key] = $override_value;
		}

		$query = array();

		foreach ($params as $key => $value) {
			$query[] = $key . '=' . rawurlencode($value);
		}

		return $this->url->link('product/category', implode('&', $query));
	}

	private function filterReducerProducts($products, $active_filters, $definitions) {
		if (!$active_filters) {
			return $products;
		}

		$filtered = array();

		foreach ($products as $product_id => $product) {
			if ($this->reducerProductMatchesFilters($product, $active_filters, $definitions)) {
				$filtered[$product_id] = $product;
			}
		}

		return $filtered;
	}

	private function reducerProductMatchesFilters($product, $active_filters, $definitions) {
		foreach ($active_filters as $key => $value) {
			if (empty($definitions[$key])) {
				continue;
			}

			$matched_option = null;

			foreach ($definitions[$key]['options'] as $option) {
				if ($option['value'] === $value) {
					$matched_option = $option;
					break;
				}
			}

			if (!$matched_option || !$this->reducerProductMatchesOption($product, $matched_option)) {
				return false;
			}
		}

		return true;
	}

	private function reducerProductMatchesOption($product, $option) {
		$text = $this->getReducerProductSearchText($product);

		if (!empty($option['tokens'])) {
			foreach ($option['tokens'] as $token) {
				if (strpos($text, utf8_strtolower($token)) !== false) {
					return true;
				}
			}
		}

		if (!empty($option['patterns'])) {
			foreach ($option['patterns'] as $pattern) {
				if (preg_match($pattern, $text)) {
					return true;
				}
			}
		}

		return false;
	}

	private function getReducerProductSearchText($product) {
		$parts = array(
			isset($product['name']) ? $product['name'] : '',
			isset($product['model']) ? $product['model'] : '',
			isset($product['sku']) ? $product['sku'] : '',
			isset($product['manufacturer']) ? $product['manufacturer'] : '',
			isset($product['description']) ? $product['description'] : ''
		);

		$text = html_entity_decode(strip_tags(implode(' ', $parts)), ENT_QUOTES, 'UTF-8');
		$text = utf8_strtolower($text);
		$text = str_replace(array('ё'), array('е'), $text);

		return $text;
	}

	private function getCanonicalProductHref($product_id) {
		$canonical_direct_urls = array();

		if (isset($canonical_direct_urls[$product_id])) {
			return $canonical_direct_urls[$product_id];
		}

		$canonical_paths = array(
			400101703 => '236_2442_2449_2463',
			2174 => '236_2442_2449',
			2122 => '236_2443_2453_2473',
			2167 => '236_2446_2509',
			2168 => '236_2446_2509',
			2186 => '236_2446_2509',
			2187 => '236_2446_2509',
			2157 => '236_2446_2509',
			2152 => '236_2444_2504',
			2153 => '236_2443_2623',
			2154 => '236_2443_2624',
			2155 => '236_2443_2624',
			2156 => '236_2442_2625',
			2160 => '236_2446_2512',
			2128 => '236_2446_2508',
			2129 => '236_2446_2508',
			2130 => '236_2446_2508',
			2141 => '236_2446_2507',
			2142 => '236_2446_2511',
			2158 => '236_2446_2507',
			2159 => '236_2446_2508',
			2161 => '236_2446_2510',
			2162 => '236_2446_2507',
			2163 => '236_2446_2507',
			2164 => '236_2446_2508',
			2165 => '236_2446_2506',
			2166 => '236_2446_2506',
			2169 => '236_2446_2508',
			2170 => '236_2446_2509',
			2171 => '236_2446_2509',
			2172 => '236_2446_2507',
			2173 => '236_2446_2507',
			2175 => '236_2446_2509',
			2176 => '236_2446_2508',
			2177 => '236_2446_2508',
			2178 => '236_2446_2506',
			2179 => '236_2446_2506',
			2180 => '236_2446_2506',
			2181 => '236_2446_2506',
			2182 => '236_2446_2509',
			2183 => '236_2446_2508',
			2184 => '236_2446_2506',
			2185 => '236_2446_2507',
			2212 => '236_2446_2506',
			2213 => '236_2446_2506',
			400101679 => '236_2442_2449_2467',
			400101680 => '236_2442_2449_2466',
			400101688 => '236_2442_2526',
			400101693 => '236_2442_2489_2493',
			2116 => '236_2442_2489_2492',
			400101742 => '236_2442_2489_2492',
			400101810 => '236_2442_2449_2464',
			400102003 => '236_2517',
			400102004 => '236_2517',
			400101931 => '236_2443_2454_2637',
			400102435 => '236_2443_2454_2633',
			400102334 => '236_2443_2454_2634',
			400101711 => '236_2443_2454_2635',
			400101713 => '236_2443_2454_2635',
			400102432 => '236_2443_2454_2635',
			2115 => '236_2443_2454_2636',
			400102333 => '236_2443_2454_2636',
			2117 => '236_2443_2454_2636',
			400102332 => '236_2443_2454_2636',
			2118 => '236_2443_2454_2636',
			400101932 => '236_2443_2454_2637',
			400102436 => '236_2443_2454_2637',
			400101933 => '236_2443_2454_2638',
			400102434 => '236_2443_2454_2638',
			400102433 => '236_2443_2454_2639',
			400102445 => '236_2443_2454_2639',
			2200 => '236_2447_2640',
			2201 => '236_2447_2640',
			2202 => '236_2447_2640',
			400103275 => '236_2447_2640',
			2203 => '236_2447_2641',
			2204 => '236_2447_2641',
			2205 => '236_2447_2641',
			2206 => '236_2447_2641',
			2207 => '236_2447_2641',
			2208 => '236_2447_2641',
			2113 => '236_2443_2527',
			400102062 => '236_2443_2527',
			400102063 => '236_2443_2453_2470',
			2119 => '236_2443_2453_2470',
			2120 => '236_2443_2453_2629',
			400102146 => '236_2443_2453_2629',
			2121 => '236_2443_2453_2630',
			400102002 => '236_2443_2453_2630',
			400102145 => '236_2443_2453_2473',
			2111 => '236_2443_2453_2631',
			400101646 => '236_2443_2453_2631',
			2112 => '236_2443_2453_2632',
			2114 => '236_2443_2453_2632',
			400102066 => '236_2446_2509',
			400102067 => '236_2446_2509',
			400101790 => '236_2446_2509',
			400101792 => '236_2446_2509',
			400102134 => '236_2446_2509',
			400102150 => '236_2446_2508',
			400101999 => '236_2446_2507',
			2229 => '236_2446_2507',
			2230 => '236_2446_2507',
			2231 => '236_2446_2507',
			2232 => '236_2446_2507',
			2233 => '236_2446_2507',
			2234 => '236_2446_2507',
			2235 => '236_2446_2507',
			400102077 => '236_2446_2507',
			213 => '59_127',
			1176 => '59_127',
			1412 => '59_127',
			2188 => '236_2516',
			2190 => '236_2516',
			2191 => '236_2516',
			2192 => '236_2443_2623',
			2193 => '236_2443_2623',
			400101995 => '236_2442_2449',
			400101996 => '236_2442_2449',
			2236 => '236_2445_2458_2486',
			2237 => '236_2445_2458_2626',
			2238 => '236_2445_2458_2487',
			2239 => '236_2445_2458_2488',
			2240 => '236_2445_2458_2484',
			2241 => '236_2445_2458_2627',
			2242 => '236_2445_2458_2485',
			2132 => '236_2446_2459',
			2133 => '236_2446_2459',
			2134 => '236_2446_2459',
			2135 => '236_2446_2459',
			2136 => '236_2446_2459',
			2137 => '236_2446_2459',
			2138 => '236_2446_2459',
			2139 => '236_2446_2459',
			2140 => '236_2446_2459',
			2143 => '236_2444_2456_2628',
			2144 => '236_2444_2456_2476',
			2145 => '236_2444_2456_2477',
			2146 => '236_2444_2456_2478',
			2147 => '236_2444_2456_2479',
			2148 => '236_2444_2457_2482',
			2149 => '236_2444_2457_2483',
			2150 => '236_2444_2457_2480',
			2151 => '236_2444_2457_2481',
			2123 => '236_2442_2450_2642',
			2124 => '236_2442_2450_2643',
			2125 => '236_2442_2452_2644',
			2126 => '236_2442_2452_2645',
			2127 => '236_2442_2452_2646',
			2189 => '236_2446_2653',
			2194 => '236_2443_2650',
			2195 => '236_2443_2650',
			2199 => '236_2443_2652',
			2209 => '236_2446_2648',
			2210 => '236_2442_2489_2647',
			2211 => '236_2442_2489_2647',
			2214 => '236_2446_2649',
			2215 => '236_2446_2648',
			2216 => '236_2446_2648',
			2217 => '236_2446_2648',
			2218 => '236_2446_2648',
			2219 => '236_2446_2648',
			2220 => '236_2446_2648',
			2221 => '236_2446_2648',
			2222 => '236_2446_2648',
			2223 => '236_2446_2648',
			2224 => '236_2446_2648',
			2225 => '236_2446_2648',
			2226 => '236_2446_2648',
			2227 => '236_2446_2648',
			2228 => '236_2446_2648',
			400101672 => '236_2442_2449_2465',
			400101681 => '236_2442_2449_2466',
			400101709 => '236_2442_2449_2466',
			400101787 => '236_2444_2457_2483',
			400101863 => '236_2446_2654',
			400101867 => '236_2442_2489_2647',
			400101900 => '236_2442_2449_2465',
			400101919 => '236_2443_2655',
			400101997 => '236_2442_2449_2464',
			400102000 => '236_2442_2489_2491',
			400102001 => '236_2442_2489_2491',
			400102140 => '236_2444_2457_2483',
			400102144 => '236_2442_2489_2492',
			400102149 => '236_2446_2508',
			400102203 => '236_2442_2657',
			400102206 => '236_2444_2457_2483',
			400102210 => '236_2443_2651',
			400102294 => '236_2443_2650',
			400102295 => '236_2443_2650',
			400102336 => '236_2446_2508',
			400102429 => '236_2444_2457_2481',
			400102430 => '236_2444_2457_2480',
			400102431 => '236_2443_2651',
			400102438 => '236_2442_2489_2492',
			400103274 => '236_2447_2640',
			2196 => '236_2443_2455',
			2197 => '236_2443_2455',
			2198 => '236_2443_2455'
		);

		if (isset($canonical_paths[$product_id])) {
			return $this->url->link('product/product', 'path=' . $canonical_paths[$product_id] . '&product_id=' . (int)$product_id);
		}

		return $this->url->link('product/product', 'product_id=' . (int)$product_id);
	}
}
