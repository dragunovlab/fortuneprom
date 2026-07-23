<?php
class ControllerProductProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('product/product');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

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
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

			$url = '';

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

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if (!isset($this->request->get['path'])) {
				foreach ($this->getPrimaryCategoryBreadcrumbs($product_id) as $breadcrumb) {
					$data['breadcrumbs'][] = $breadcrumb;
				}
			}

			$brand_motor_reducer_seo = $this->getBrandMotorReducerSeoMap();
			$one_ts2u_reducer_seo = $this->getOneTs2uReducerSeoMap();
			$ts2u_reducer_seo = $this->getTs2uReducerSeoMap();
			$crane_reducer_seo = $this->getCraneReducerSeoMap();
			$remaining_reducer_seo = $this->getRemainingReducerSeoMap();
			$priority_reducer_meta = $this->getPriorityReducerMetaMap();
			$seo_meta = array();

			foreach (array($priority_reducer_meta, $remaining_reducer_seo, $one_ts2u_reducer_seo, $ts2u_reducer_seo, $crane_reducer_seo, $brand_motor_reducer_seo) as $seo_map) {
				if (isset($seo_map[(int)$product_id])) {
					$seo_meta = $seo_map[(int)$product_id];
					break;
				}
			}

			if ($product_id == 2174) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2449&product_id=2174');
			} elseif ($product_id == 215) {
				$product_url = $this->url->link('product/product', 'path=59_130&product_id=215');
			} elseif ($product_id == 214) {
				$product_url = $this->url->link('product/product', 'path=59_130&product_id=214');
			} elseif ($product_id == 1175) {
				$product_url = $this->url->link('product/product', 'path=59_127&product_id=1175');
			} elseif ($product_id == 2122) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2453_2473&product_id=2122');
			} elseif ($product_id == 2167) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=2167');
			} elseif ($product_id == 2168) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=2168');
			} elseif ($product_id == 2186) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=2186');
			} elseif ($product_id == 2187) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=2187');
			} elseif ($product_id == 2157) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=2157');
			} elseif ($product_id == 2152) {
				$product_url = $this->url->link('product/product', 'path=236_2444_2504&product_id=2152');
			} elseif ($product_id == 2153) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2623&product_id=2153');
			} elseif ($product_id == 2154) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2624&product_id=2154');
			} elseif ($product_id == 2155) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2624&product_id=2155');
			} elseif ($product_id == 2156) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2625&product_id=2156');
			} elseif ($product_id == 2160) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2512&product_id=2160');
			} elseif ($product_id == 2128) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2508&product_id=2128');
			} elseif ($product_id == 2129) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2508&product_id=2129');
			} elseif ($product_id == 2130) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2508&product_id=2130');
			} elseif ($product_id == 2141) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2507&product_id=2141');
			} elseif ($product_id == 2212) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2506&product_id=2212');
			} elseif ($product_id == 2213) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2506&product_id=2213');
			} elseif ($product_id == 213) {
				$product_url = $this->url->link('product/product', 'path=59_127&product_id=213');
			} elseif ($product_id == 1176) {
				$product_url = $this->url->link('product/product', 'path=59_127&product_id=1176');
			} elseif ($product_id == 1412) {
				$product_url = $this->url->link('product/product', 'path=59_127&product_id=1412');
			} elseif ($product_id == 2188) {
				$product_url = $this->url->link('product/product', 'path=236_2516&product_id=2188');
			} elseif ($product_id == 2190) {
				$product_url = $this->url->link('product/product', 'path=236_2516&product_id=2190');
			} elseif ($product_id == 2191) {
				$product_url = $this->url->link('product/product', 'path=236_2516&product_id=2191');
			} elseif ($product_id == 2192) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2623&product_id=2192');
			} elseif ($product_id == 2193) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2623&product_id=2193');
			} elseif ($product_id == 400101703) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2449_2463&product_id=400101703');
			} elseif ($product_id == 400101679) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2449_2467&product_id=400101679');
			} elseif ($product_id == 400101680) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2449_2466&product_id=400101680');
			} elseif ($product_id == 400101688) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2526&product_id=400101688');
			} elseif ($product_id == 400101693) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2489_2493&product_id=400101693');
			} elseif ($product_id == 2116) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2489_2492&product_id=2116');
			} elseif ($product_id == 400101742) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2489_2492&product_id=400101742');
			} elseif ($product_id == 400101810) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2449_2464&product_id=400101810');
			} elseif ($product_id == 400102003) {
				$product_url = $this->url->link('product/product', 'path=236_2517&product_id=400102003');
			} elseif ($product_id == 400102004) {
				$product_url = $this->url->link('product/product', 'path=236_2517&product_id=400102004');
			} elseif ($product_id == 400101931) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2454_2637&product_id=400101931');
			} elseif ($product_id == 2113) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2527&product_id=2113');
			} elseif ($product_id == 400102062) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2527&product_id=400102062');
			} elseif ($product_id == 400102063) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2453_2470&product_id=400102063');
			} elseif ($product_id == 400102066) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=400102066');
			} elseif ($product_id == 400102067) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=400102067');
			} elseif ($product_id == 400101790) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=400101790');
			} elseif ($product_id == 400101792) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=400101792');
			} elseif ($product_id == 400102134) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2509&product_id=400102134');
			} elseif ($product_id == 400102150) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2508&product_id=400102150');
			} elseif ($product_id == 400101999) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2507&product_id=400101999');
			} elseif (in_array((int)$product_id, array(2229, 2231, 2232, 2233, 2234, 2235), true)) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2507&product_id=' . (int)$product_id);
			} elseif ($product_id == 2230) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2507&product_id=2230');
			} elseif ($product_id == 400102077) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2507&product_id=400102077');
			} elseif (in_array((int)$product_id, array(2236, 2237, 2238, 2239, 2240, 2241, 2242), true)) {
				$planetary_3mp_paths = array(2236 => '236_2445_2458_2486', 2237 => '236_2445_2458_2626', 2238 => '236_2445_2458_2487', 2239 => '236_2445_2458_2488', 2240 => '236_2445_2458_2484', 2241 => '236_2445_2458_2627', 2242 => '236_2445_2458_2485');
				$product_url = $this->url->link('product/product', 'path=' . $planetary_3mp_paths[(int)$product_id] . '&product_id=' . (int)$product_id);
			} elseif (in_array((int)$product_id, array(2132, 2133, 2134, 2135, 2136, 2137, 2138, 2139, 2140), true)) {
				$product_url = $this->url->link('product/product', 'path=236_2446_2459&product_id=' . (int)$product_id);
			} elseif (in_array((int)$product_id, array(2143, 2144, 2145, 2146, 2147, 2148, 2149, 2150, 2151), true)) {
				$kts_paths = array(2143 => '236_2444_2456_2628', 2144 => '236_2444_2456_2476', 2145 => '236_2444_2456_2477', 2146 => '236_2444_2456_2478', 2147 => '236_2444_2456_2479', 2148 => '236_2444_2457_2482', 2149 => '236_2444_2457_2483', 2150 => '236_2444_2457_2480', 2151 => '236_2444_2457_2481');
				$product_url = $this->url->link('product/product', 'path=' . $kts_paths[(int)$product_id] . '&product_id=' . (int)$product_id);
			} elseif ($product_id == 400101995) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2449&product_id=400101995');
			} elseif ($product_id == 400101996) {
				$product_url = $this->url->link('product/product', 'path=236_2442_2449&product_id=400101996');
			} elseif ($product_id == 2196) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2455&product_id=2196');
			} elseif ($product_id == 2197) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2455&product_id=2197');
			} elseif ($product_id == 2198) {
				$product_url = $this->url->link('product/product', 'path=236_2443_2455&product_id=2198');
			} elseif (isset($one_ts2u_reducer_seo[(int)$product_id])) {
				$product_url = $this->url->link('product/product', 'path=' . $one_ts2u_reducer_seo[(int)$product_id]['path'] . '&product_id=' . (int)$product_id);
			} elseif (isset($ts2u_reducer_seo[(int)$product_id])) {
				$product_url = $this->url->link('product/product', 'path=' . $ts2u_reducer_seo[(int)$product_id]['path'] . '&product_id=' . (int)$product_id);
			} elseif (isset($crane_reducer_seo[(int)$product_id])) {
				$product_url = $this->url->link('product/product', 'path=' . $crane_reducer_seo[(int)$product_id]['path'] . '&product_id=' . (int)$product_id);
			} elseif (isset($remaining_reducer_seo[(int)$product_id])) {
				$product_url = $this->url->link('product/product', 'path=' . $remaining_reducer_seo[(int)$product_id]['path'] . '&product_id=' . (int)$product_id);
			} elseif (isset($brand_motor_reducer_seo[(int)$product_id])) {
				$product_url = $this->url->link('product/product', 'path=' . $brand_motor_reducer_seo[(int)$product_id]['path'] . '&product_id=' . (int)$product_id);
			} else {
				$product_url = $this->url->link('product/product', 'product_id=' . (int)$product_info['product_id']);
			}
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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
				'text' => $product_info['name'],
				'href' => $product_url
			);

			if (!empty($seo_meta['title'])) {
				$this->document->setTitle($seo_meta['title']);
			} else {
				$this->document->setTitle($product_info['meta_title']);
			}

			if (!empty($seo_meta['description'])) {
				$this->document->setDescription($seo_meta['description']);
			} else {
				$this->document->setDescription($product_info['meta_description']);
			}
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($product_url, 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			if ($product_id == 215) {
				$data['heading_title'] = 'Валковая дробилка Xuanshi';
			} elseif ($product_id == 214) {
				$data['heading_title'] = 'Валковая дробилка';
			} elseif ($product_id == 1175) {
				$data['heading_title'] = 'Конусная дробилка КСД-600 (ДРО-592)';
			} elseif ($product_id == 2122) {
				$data['heading_title'] = 'Редуктор 1Ц2У-200 цилиндрический двухступенчатый';
			} elseif ($product_id == 2167) {
				$data['heading_title'] = 'Конические мотор-редукторы Lenze GKR';
			} elseif ($product_id == 2168) {
				$data['heading_title'] = 'Коническо-цилиндрические мотор-редукторы Lenze GKS';
			} elseif ($product_id == 2186) {
				$data['heading_title'] = 'Коническо-цилиндрические мотор-редукторы Siemens Motox B/K';
			} elseif ($product_id == 2187) {
				$data['heading_title'] = 'Коническо-цилиндрические мотор-редукторы Siti серии MBH';
			} elseif ($product_id == 2188) {
				$data['heading_title'] = 'Маятниковый редуктор Siti серии RP2';
			} elseif ($product_id == 2190) {
				$data['heading_title'] = 'Цилиндрический насадной мотор-редуктор Siti серии MPD';
			} elseif ($product_id == 2191) {
				$data['heading_title'] = 'Цилиндрический насадной редуктор Siti серии RFV';
			} elseif ($product_id == 2192) {
				$data['heading_title'] = 'Цилиндрический редуктор Siti серии M с параллельными валами';
			} elseif ($product_id == 2193) {
				$data['heading_title'] = 'Цилиндрический редуктор Siti серии Z с параллельными осями';
			} elseif ($product_id == 2157) {
				$data['heading_title'] = 'Конический мотор-редуктор Bauer серии BK';
			} elseif ($product_id == 2152) {
				$data['heading_title'] = 'Коническо-цилиндрические редукторы Yilmaz серии K';
			} elseif ($product_id == 2153) {
				$data['heading_title'] = 'Плоско-цилиндрические редукторы Yilmaz серии D';
			} elseif ($product_id == 2154) {
				$data['heading_title'] = 'Соосно-цилиндрические редукторы Yilmaz серии M';
			} elseif ($product_id == 2155) {
				$data['heading_title'] = 'Соосно-цилиндрические редукторы Yilmaz серии N';
			} elseif ($product_id == 2156) {
				$data['heading_title'] = 'Червячные редукторы Yilmaz серии E';
			} elseif ($product_id == 2160) {
				$data['heading_title'] = 'Монорельсовый мотор-редуктор Bauer серии BM/VM';
			} elseif ($product_id == 2128) {
				$data['heading_title'] = 'Плоский цилиндрический мотор-редуктор F37';
			} elseif ($product_id == 2129) {
				$data['heading_title'] = 'Плоский цилиндрический мотор-редуктор F47';
			} elseif ($product_id == 2130) {
				$data['heading_title'] = 'Плоский цилиндрический мотор-редуктор F157';
			} elseif ($product_id == 2141) {
				$data['heading_title'] = 'Цилиндрический соосный мотор-редуктор 5МПО1М-10ВК';
			} elseif ($product_id == 2212) {
				$data['heading_title'] = 'Мотор-редуктор 5Ч-125А червячный';
			} elseif ($product_id == 2213) {
				$data['heading_title'] = 'Мотор-редуктор 5Ч-80 червячный';
			} elseif ($product_id == 400101679) {
				$data['heading_title'] = 'Мотор-редуктор NMRV 110-40, 70 об/мин, 3 кВт';
			} elseif ($product_id == 400101680) {
				$data['heading_title'] = 'Мотор-редуктор NMRV 090-10-2,2/1500-B5-B3';
			} elseif ($product_id == 400101688) {
				$data['heading_title'] = 'Редуктор FRT 50-70 AC24 IEC71-B14 G514';
			} elseif ($product_id == 400101693) {
				$data['heading_title'] = 'Редуктор Ч-125-63-52-КЦ червячный';
			} elseif ($product_id == 2116) {
				$data['heading_title'] = 'Редуктор Ч-100-31,5-52-2 червячный';
			} elseif ($product_id == 400101742) {
				$data['heading_title'] = 'Редуктор Ч-100-15-52 червячный';
			} elseif ($product_id == 400101810) {
				$data['heading_title'] = 'Мотор-редуктор NMRV-S063 червячный';
			} elseif ($product_id == 400102003) {
				$data['heading_title'] = 'Редуктор BLY27-87, 7,5 кВт циклоидальный';
			} elseif ($product_id == 400102004) {
				$data['heading_title'] = 'Редуктор BLD4-87 циклоидальный';
			} elseif ($product_id == 400101931) {
				$data['heading_title'] = 'Редуктор Ц2У-250-31,5-12-КК цилиндрический';
			} elseif ($product_id == 2113) {
				$data['heading_title'] = 'Редуктор ЦДН-630 цилиндрический';
			} elseif ($product_id == 400102062) {
				$data['heading_title'] = 'Редуктор ЦДН-710-31,5-21-ЦЦ цилиндрический';
			} elseif ($product_id == 400102063) {
				$data['heading_title'] = 'Редуктор 1Ц2У-100-31,5-12-КК-У2 цилиндрический';
			} elseif ($product_id == 400102066) {
				$data['heading_title'] = 'Мотор-редуктор KA77-16,6-87-11 кВт B8 с реактивной тягой';
			} elseif ($product_id == 400102067) {
				$data['heading_title'] = 'Мотор-редуктор KA77-16,6-87-7,5 кВт B8 с тормозом';
			} elseif ($product_id == 400101790) {
				$data['heading_title'] = 'Мотор-редуктор Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LC4';
			} elseif ($product_id == 400101792) {
				$data['heading_title'] = 'Мотор-редуктор Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LA4 FD R';
			} elseif ($product_id == 400102134) {
				$data['heading_title'] = 'Мотор-редуктор Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LLA4 FD R';
			} elseif ($product_id == 400102150) {
				$data['heading_title'] = 'Мотор-редуктор FAF-S57-24.96-56-1,1';
			} elseif ($product_id == 400101999) {
				$data['heading_title'] = 'Мотор-редуктор 4МЦ2С-125-35,5-11';
			} elseif (in_array((int)$product_id, array(2229, 2231, 2232, 2233, 2234, 2235), true)) {
				$four_mts2s_size_headings = array(2229 => '50', 2231 => '80', 2232 => '100', 2233 => '160', 2234 => '125', 2235 => '140');
				$data['heading_title'] = 'Мотор-редуктор 4МЦ2С-' . $four_mts2s_size_headings[(int)$product_id] . ' цилиндрический';
			} elseif ($product_id == 2230) {
				$data['heading_title'] = 'Мотор-редуктор 4МЦ2С-63 цилиндрический';
			} elseif (in_array((int)$product_id, array(2236, 2237, 2238, 2239, 2240, 2241, 2242), true)) {
				$planetary_3mp_sizes = array(2236 => '50', 2237 => '63', 2238 => '80', 2239 => '100', 2240 => '25', 2241 => '125', 2242 => '40');
				$planetary_3mp_size = $planetary_3mp_sizes[(int)$product_id];
				$data['heading_title'] = 'Мотор-редуктор планетарный 3МП-' . $planetary_3mp_size . ' (1МПз-' . $planetary_3mp_size . ')';
			} elseif (in_array((int)$product_id, array(2132, 2133, 2134, 2135, 2136, 2137, 2138, 2139, 2140), true)) {
				$rc_sizes = array(2132 => '17', 2133 => '37', 2134 => '47', 2135 => '57', 2136 => '67', 2137 => '147', 2138 => '137', 2139 => '107', 2140 => '97');
				$rc_size = $rc_sizes[(int)$product_id];
				$data['heading_title'] = 'Мотор-редуктор RC' . $rc_size . ' / RCF' . $rc_size;
			} elseif (in_array((int)$product_id, array(2143, 2144, 2145, 2146, 2147, 2148, 2149, 2150, 2151), true)) {
				$kts_series = array(2143 => 'КЦ1', 2144 => 'КЦ1', 2145 => 'КЦ1', 2146 => 'КЦ1', 2147 => 'КЦ1', 2148 => 'КЦ2', 2149 => 'КЦ2', 2150 => 'КЦ2', 2151 => 'КЦ2');
				$kts_sizes = array(2143 => '200', 2144 => '250', 2145 => '300', 2146 => '400', 2147 => '500', 2148 => '1000', 2149 => '1300', 2150 => '500', 2151 => '750');
				$kts_series_name = $kts_series[(int)$product_id];
				$kts_size = $kts_sizes[(int)$product_id];
				$data['heading_title'] = 'Редуктор ' . $kts_series_name . '-' . $kts_size . ' коническо-цилиндрический' . ($kts_series_name == 'КЦ2' ? ' трехступенчатый' : '');
			} elseif (isset($one_ts2u_reducer_seo[(int)$product_id])) {
				$data['heading_title'] = $one_ts2u_reducer_seo[(int)$product_id]['h1'];
			} elseif (isset($ts2u_reducer_seo[(int)$product_id])) {
				$data['heading_title'] = $ts2u_reducer_seo[(int)$product_id]['h1'];
			} elseif (isset($crane_reducer_seo[(int)$product_id])) {
				$data['heading_title'] = $crane_reducer_seo[(int)$product_id]['h1'];
			} elseif (isset($remaining_reducer_seo[(int)$product_id])) {
				$data['heading_title'] = $remaining_reducer_seo[(int)$product_id]['h1'];
			} elseif (isset($brand_motor_reducer_seo[(int)$product_id])) {
				$data['heading_title'] = $brand_motor_reducer_seo[(int)$product_id]['h1'];
			} else {
				$data['heading_title'] = $product_info['name'];
			}

			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

			$this->load->model('catalog/review');

			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_delivery'] = $this->language->get('tab_delivery');
			$data['tab_payment'] = $this->language->get('tab_payment');
			$data['tab_faq'] = $this->language->get('tab_faq');

			if ($data['tab_attribute'] == 'tab_attribute') {
				$data['tab_attribute'] = 'Характеристики';
			}

			if ($data['tab_delivery'] == 'tab_delivery') {
				$data['tab_delivery'] = 'Доставка';
			}

			if ($data['tab_payment'] == 'tab_payment') {
				$data['tab_payment'] = 'Оплата';
			}

			if ($data['tab_faq'] == 'tab_faq') {
				$data['tab_faq'] = 'FAQ';
			}

			$data['entry_plus'] = $this->language->get('entry_plus');
			$data['entry_minus'] = $this->language->get('entry_minus');
			$data['btn_add_new_review'] = $this->language->get('btn_add_new_review');

			if ($data['entry_plus'] == 'entry_plus' || $data['entry_plus'] === '') {
				$data['entry_plus'] = 'Что понравилось';
			}

			if ($data['entry_minus'] == 'entry_minus' || $data['entry_minus'] === '') {
				$data['entry_minus'] = 'Что можно улучшить';
			}

			if ($data['btn_add_new_review'] == 'btn_add_new_review' || $data['btn_add_new_review'] === '') {
				$data['btn_add_new_review'] = 'Отправить отзыв';
			}

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			$description = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			// Some imported descriptions contain broken closers like </p&gt, which can swallow the tab markup.
			$description = preg_replace('/<\/([a-z][a-z0-9]*)\s*(?:&gt;?|&amp;gt;?)/i', '</$1>', $description);
			$data['description'] = $description !== null ? $description : html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			if ($product_info['image']) {
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'));
			} else {
				$data['popup'] = '';
			}

			if ($product_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
				);
			}

			$data['price'] = 'Цена по запросу';
			$data['special'] = false;

			$schema_price_value = 0;
			$data['has_price_schema'] = false;
			$data['schema_price'] = '';
			$data['currency_microdata'] = isset($this->session->data['currency']) ? $this->session->data['currency'] : 'KZT';

			$data['tax'] = false;

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						$price = false;

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$data['rating'] = (int)$product_info['rating'];

			// Captcha
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}

			$data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
			$data['product_spec_rows'] = array();

			if (!empty($product_info['model'])) {
				$data['product_spec_rows'][] = array(
					'name'  => 'Модель',
					'value' => $product_info['model']
				);
			}

			if (!empty($product_info['sku'])) {
				$data['product_spec_rows'][] = array(
					'name'  => 'Артикул',
					'value' => $product_info['sku']
				);
			}

			if (!empty($product_info['manufacturer'])) {
				$data['product_spec_rows'][] = array(
					'name'  => 'Производитель',
					'value' => $product_info['manufacturer']
				);
			}

			if ((string)$data['stock'] !== '') {
				$data['product_spec_rows'][] = array(
					'name'  => 'Наличие',
					'value' => $data['stock']
				);
			}

			if ((float)$product_info['weight'] > 0) {
				$weight = rtrim(rtrim(number_format((float)$product_info['weight'], 2, $this->language->get('decimal_point'), $this->language->get('thousand_point')), '0'), $this->language->get('decimal_point'));

				$data['product_spec_rows'][] = array(
					'name'  => 'Вес',
					'value' => trim($weight . ' ' . $product_info['weight_class'])
				);
			}

			if ((float)$product_info['length'] > 0 && (float)$product_info['width'] > 0 && (float)$product_info['height'] > 0) {
				$length = rtrim(rtrim(number_format((float)$product_info['length'], 2, $this->language->get('decimal_point'), $this->language->get('thousand_point')), '0'), $this->language->get('decimal_point'));
				$width = rtrim(rtrim(number_format((float)$product_info['width'], 2, $this->language->get('decimal_point'), $this->language->get('thousand_point')), '0'), $this->language->get('decimal_point'));
				$height = rtrim(rtrim(number_format((float)$product_info['height'], 2, $this->language->get('decimal_point'), $this->language->get('thousand_point')), '0'), $this->language->get('decimal_point'));

				$data['product_spec_rows'][] = array(
					'name'  => 'Габариты (Д × Ш × В)',
					'value' => trim($length . ' × ' . $width . ' × ' . $height . ' ' . $product_info['length_class'])
				);
			}

			if ($product_id == 215) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Тип оборудования', 'value' => 'валковая дробилка'),
					array('name' => 'Модель / Серия', 'value' => 'Xuanshi 2PG (двухвалковая)'),
					array('name' => 'Назначение', 'value' => 'среднее и мелкое дробление, измельчение вязких и влажных материалов'),
					array('name' => 'Количество валков', 'value' => '2 (двухвалковая компоновка)'),
					array('name' => 'Макс. размер питания', 'value' => '25 – 60 мм (в зависимости от модели)'),
					array('name' => 'Фракция на выходе', 'value' => '2 – 20 мм (регулируемая)'),
					array('name' => 'Производительность', 'value' => '5 – 80 т/ч'),
					array('name' => 'Мощность привода', 'value' => 'от 2 × 5.5 до 2 × 45 кВт'),
					array('name' => 'Масса оборудования', 'value' => 'от 1.8 до 9.5 т'),
					array('name' => 'Гарантия', 'value' => '12 месяцев'),
					array('name' => 'Категория', 'value' => 'Дробильное оборудование > Валковые дробилки')
				));
			} elseif ($product_id == 214) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Тип оборудования', 'value' => 'валковая дробилка'),
					array('name' => 'Модель / Серия', 'value' => 'серия 2PG (двухвалковая)'),
					array('name' => 'Назначение', 'value' => 'среднее и мелкое дробление, измельчение вязких и влажных материалов'),
					array('name' => 'Количество валков', 'value' => '2 (двухвалковая компоновка)'),
					array('name' => 'Макс. размер питания', 'value' => '25 – 60 мм (в зависимости от модели)'),
					array('name' => 'Фракция на выходе', 'value' => '2 – 20 мм (регулируемая)'),
					array('name' => 'Производительность', 'value' => '5 – 80 т/ч'),
					array('name' => 'Мощность привода', 'value' => 'от 2 × 5.5 до 2 × 45 кВт'),
					array('name' => 'Масса оборудования', 'value' => 'от 1.8 до 9.5 т'),
					array('name' => 'Гарантия', 'value' => '12 месяцев'),
					array('name' => 'Категория', 'value' => 'Дробильное оборудование > Валковые дробилки')
				));
			} elseif ($product_id == 1175) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Тип оборудования', 'value' => 'конусная дробилка'),
					array('name' => 'Модель / Серия', 'value' => 'КСД-600 (ДРО-592)'),
					array('name' => 'Назначение', 'value' => 'среднее и мелкое дробление'),
					array('name' => 'Диаметр конуса', 'value' => '600 мм'),
					array('name' => 'Разгрузочная щель', 'value' => '12 – 35 мм'),
					array('name' => 'Кусок питания (макс.)', 'value' => 'до 90 мм'),
					array('name' => 'Производительность', 'value' => '19 – 40 м³/ч'),
					array('name' => 'Мощность двигателя', 'value' => '30 кВт'),
					array('name' => 'Масса оборудования', 'value' => '7.2 т (в сборе)'),
					array('name' => 'Гарантия', 'value' => '12 месяцев'),
					array('name' => 'Категория', 'value' => 'Дробильное оборудование > Конусные дробилки')
				));
			} elseif ($product_id == 2122) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '1Ц2У'),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический двухступенчатый горизонтальный'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая косозубая'),
					array('name' => 'Межосевое расстояние', 'value' => '200 мм (80 + 120 мм)'),
					array('name' => 'Передаточные числа', 'value' => 'i = 8; 10; 12.5; 16; 20; 25; 31.5; 40'),
					array('name' => 'Номинальный крутящий момент', 'value' => 'до 2500 Н·м'),
					array('name' => 'Радиальная нагрузка на тихоходном валу', 'value' => 'до 16000 Н'),
					array('name' => 'КПД', 'value' => 'не менее 0.97 (97%)'),
					array('name' => 'Диаметр выходного вала', 'value' => '65 мм (цилиндрический), 60 мм (конический)'),
					array('name' => 'Масса без масла', 'value' => 'около 170 кг'),
					array('name' => 'Объем масла', 'value' => 'около 7 л'),
					array('name' => 'Монтаж', 'value' => 'горизонтальное исполнение на лапах'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Редукторы 1Ц2У > 1Ц2У-200')
				));
			} elseif ($product_id == 2167) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'Lenze GKR'),
					array('name' => 'Тип привода', 'value' => 'конический мотор-редуктор'),
					array('name' => 'Бренд', 'value' => 'Lenze'),
					array('name' => 'Конструкция', 'value' => 'модульная'),
					array('name' => 'Диапазон мощности', 'value' => '0,06-5,5 кВт'),
					array('name' => 'Диапазон крутящего момента', 'value' => '190-11790 Н·м'),
					array('name' => 'Класс защиты', 'value' => 'IP55'),
					array('name' => 'Класс изоляции', 'value' => 'F'),
					array('name' => 'Исполнение валов', 'value' => 'уточняется по комплектации и шильдику'),
					array('name' => 'Подбор аналога', 'value' => 'по модели, передаточному числу, мощности, валам и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 2168) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Lenze'),
					array('name' => 'Серия', 'value' => 'GKS'),
					array('name' => 'Тип привода', 'value' => 'коническо-цилиндрический мотор-редуктор'),
					array('name' => 'Тип передачи', 'value' => 'угловая коническо-цилиндрическая передача'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на валу, лапах или фланце, по исполнению'),
					array('name' => 'Передаточное число', 'value' => 'подбирается под выходные обороты и нагрузку'),
					array('name' => 'Назначение', 'value' => 'приводы конвейеров, транспортеров, дозаторов, упаковочного и технологического оборудования'),
					array('name' => 'Подбор аналога', 'value' => 'по серии GKS, передаточному числу, мощности, валам, фланцу и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 2186) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Siemens'),
					array('name' => 'Серия', 'value' => 'Motox B/K'),
					array('name' => 'Тип привода', 'value' => 'коническо-цилиндрический мотор-редуктор'),
					array('name' => 'Тип передачи', 'value' => 'угловая коническо-цилиндрическая передача'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на лапах, фланце, валу или реактивной тяге'),
					array('name' => 'Передаточное число', 'value' => 'подбирается под выходные обороты и момент'),
					array('name' => 'Назначение', 'value' => 'конвейеры, транспортеры, смесители, дозаторы и производственные линии'),
					array('name' => 'Подбор аналога', 'value' => 'по шильдику Siemens Motox, передаточному числу, мощности, валу, фланцу и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 2187) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Siti'),
					array('name' => 'Серия', 'value' => 'MBH'),
					array('name' => 'Тип привода', 'value' => 'коническо-цилиндрический мотор-редуктор'),
					array('name' => 'Тип передачи', 'value' => 'угловая коническо-цилиндрическая передача'),
					array('name' => 'Мощность', 'value' => 'подбирается по нагрузке и режиму работы'),
					array('name' => 'Крутящий момент', 'value' => 'подбирается по расчетной нагрузке оборудования'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на лапах, фланце, валу или реактивной тяге'),
					array('name' => 'Подбор аналога', 'value' => 'по серии MBH, передаточному числу, мощности, моменту, валу, фланцу и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 2157) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'Bauer BK'),
					array('name' => 'Тип привода', 'value' => 'конический мотор-редуктор'),
					array('name' => 'Бренд', 'value' => 'Bauer'),
					array('name' => 'Тип передачи', 'value' => 'коническая передача под углом 90°'),
					array('name' => 'Тип вала', 'value' => 'полый или сплошной, по исполнению'),
					array('name' => 'Класс защиты', 'value' => 'IP65'),
					array('name' => 'Варианты монтажа', 'value' => 'на валу, на лапах, на фланец или на лицевую поверхность'),
					array('name' => 'Клеммная коробка', 'value' => 'поворот с шагом 90°'),
					array('name' => 'Инвертор', 'value' => 'возможна комплектация под частотный привод'),
					array('name' => 'Подбор аналога', 'value' => 'по шильдику, передаточному числу, валам, фланцу и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 2152) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Yilmaz'),
					array('name' => 'Серия', 'value' => 'K'),
					array('name' => 'Тип редуктора', 'value' => 'коническо-цилиндрический редуктор'),
					array('name' => 'Тип передачи', 'value' => 'коническо-цилиндрическая передача под углом 90°'),
					array('name' => 'Типоразмер', 'value' => 'подбирается по нагрузке и передаточному числу'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на лапах, фланце или на валу, по исполнению'),
					array('name' => 'Назначение', 'value' => 'приводы конвейеров, смесителей, дозаторов и промышленного оборудования с угловой компоновкой'),
					array('name' => 'Подбор аналога', 'value' => 'по серии K, передаточному числу, моменту, валам, фланцу и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Коническо-цилиндрические редукторы > Редукторы серии K')
				));
			} elseif ($product_id == 2153) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Yilmaz'),
					array('name' => 'Серия', 'value' => 'D'),
					array('name' => 'Тип редуктора', 'value' => 'плоско-цилиндрический редуктор'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая передача с параллельными валами'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на валу, лапах, фланце или реактивной тяге'),
					array('name' => 'Подбор аналога', 'value' => 'по серии D, типоразмеру, моменту, передаточному числу, валам и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Плоско-цилиндрические редукторы')
				));
			} elseif ($product_id == 2154) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Yilmaz'),
					array('name' => 'Серия', 'value' => 'M'),
					array('name' => 'Тип редуктора', 'value' => 'соосно-цилиндрический редуктор'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая передача с соосным расположением валов'),
					array('name' => 'Исполнение валов', 'value' => 'входной и выходной валы по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на лапах или фланце, по исполнению'),
					array('name' => 'Подбор аналога', 'value' => 'по серии M, типоразмеру, моменту, передаточному числу, валам и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Соосно-цилиндрические редукторы')
				));
			} elseif ($product_id == 2155) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Yilmaz'),
					array('name' => 'Серия', 'value' => 'N'),
					array('name' => 'Тип редуктора', 'value' => 'соосно-цилиндрический редуктор'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая передача с соосным расположением валов'),
					array('name' => 'Исполнение валов', 'value' => 'по комплектации и монтажному исполнению'),
					array('name' => 'Монтаж', 'value' => 'на лапах или фланце, по исполнению'),
					array('name' => 'Подбор аналога', 'value' => 'по серии N, типоразмеру, моменту, передаточному числу, валам и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Соосно-цилиндрические редукторы')
				));
			} elseif ($product_id == 2156) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Yilmaz'),
					array('name' => 'Серия', 'value' => 'E'),
					array('name' => 'Тип редуктора', 'value' => 'червячный редуктор'),
					array('name' => 'Тип передачи', 'value' => 'червячная передача'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на лапах, фланце или валу, по исполнению'),
					array('name' => 'Подбор аналога', 'value' => 'по серии E, типоразмеру, передаточному числу, валам и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Червячные редукторы Yilmaz')
				));
			} elseif ($product_id == 2160) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Bauer'),
					array('name' => 'Серия', 'value' => 'BM / VM'),
					array('name' => 'Тип привода', 'value' => 'монорельсовый крановый мотор-редуктор'),
					array('name' => 'Назначение', 'value' => 'приводы монорельсовых тележек, тельферов, крановых механизмов и подвесных транспортных систем'),
					array('name' => 'Передаточное число', 'value' => 'подбирается по скорости перемещения и нагрузке'),
					array('name' => 'Мощность двигателя', 'value' => 'подбирается под грузоподъемность и режим работы'),
					array('name' => 'Тормоз', 'value' => 'уточняется по комплектации и шильдику'),
					array('name' => 'Монтаж', 'value' => 'под крановую или монорельсовую тележку, по исполнению'),
					array('name' => 'Подбор аналога', 'value' => 'по модели Bauer, нагрузке, скорости, передаточному числу, креплению и присоединительным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Крановые мотор-редукторы')
				));
			} elseif ($product_id == 2128) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'F'),
					array('name' => 'Модель', 'value' => 'F37'),
					array('name' => 'Тип привода', 'value' => 'плоский цилиндрический мотор-редуктор'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая передача с параллельными валами'),
					array('name' => 'Типоразмер', 'value' => '37'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на лапах, фланце, валу или реактивной тяге'),
					array('name' => 'Назначение', 'value' => 'конвейеры, транспортеры, дозаторы и оборудование с ограниченным местом под привод'),
					array('name' => 'Подбор аналога', 'value' => 'по модели F37, моменту, передаточному числу, мощности, валам и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Плоские цилиндрические мотор-редукторы')
				));
			} elseif ($product_id == 2129) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'F'),
					array('name' => 'Модель', 'value' => 'F47'),
					array('name' => 'Тип привода', 'value' => 'плоский цилиндрический мотор-редуктор'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая передача с параллельными валами'),
					array('name' => 'Типоразмер', 'value' => '47'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на лапах, фланце, валу или реактивной тяге'),
					array('name' => 'Назначение', 'value' => 'конвейеры, транспортеры, дозаторы и промышленные линии с компактной компоновкой'),
					array('name' => 'Подбор аналога', 'value' => 'по модели F47, моменту, передаточному числу, мощности, валам и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Плоские цилиндрические мотор-редукторы')
				));
			} elseif ($product_id == 2130) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'F'),
					array('name' => 'Модель', 'value' => 'F157'),
					array('name' => 'Тип привода', 'value' => 'плоский цилиндрический мотор-редуктор тяжелого типоразмера'),
					array('name' => 'Тип передачи', 'value' => 'двух- или трехступенчатая цилиндрическая передача с параллельными валами'),
					array('name' => 'Типоразмер', 'value' => '157'),
					array('name' => 'Исполнение валов', 'value' => 'полый или сплошной вал, по комплектации'),
					array('name' => 'Монтаж', 'value' => 'на лапах, фланце, валу или реактивной тяге'),
					array('name' => 'Назначение', 'value' => 'тяжелые конвейеры, транспортеры, смесители и промышленные линии'),
					array('name' => 'Подбор аналога', 'value' => 'по модели F157, моменту, передаточному числу, мощности, валам и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Плоские цилиндрические мотор-редукторы')
				));
			} elseif ($product_id == 2141) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '5МПО1М'),
					array('name' => 'Модель', 'value' => '5МПО1М-10ВК'),
					array('name' => 'Тип привода', 'value' => 'цилиндрический соосный мотор-редуктор'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая передача с соосным расположением валов'),
					array('name' => 'Типоразмер', 'value' => '10'),
					array('name' => 'Монтажное исполнение', 'value' => 'ВК, уточняется по шильдику и чертежу'),
					array('name' => 'Назначение', 'value' => 'общепромышленные приводы конвейеров, транспортеров, дозаторов и технологического оборудования'),
					array('name' => 'Подбор аналога', 'value' => 'по модели 5МПО1М, передаточному числу, мощности, валам и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы')
				));
			} elseif ($product_id == 2212) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '5Ч'),
					array('name' => 'Модель', 'value' => '5Ч-125А'),
					array('name' => 'Тип привода', 'value' => 'червячный мотор-редуктор'),
					array('name' => 'Тип редуктора', 'value' => 'червячный одноступенчатый'),
					array('name' => 'Межосевое расстояние', 'value' => '125 мм'),
					array('name' => 'Входная частота вращения', 'value' => 'до 1500 об/мин'),
					array('name' => 'Корпус', 'value' => 'чугунный, промышленное исполнение'),
					array('name' => 'Назначение', 'value' => 'снижение оборотов и увеличение крутящего момента в промышленном приводе'),
					array('name' => 'Подбор аналога', 'value' => 'по модели 5Ч-125А, передаточному числу, мощности двигателя, валам и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Червячные мотор-редукторы')
				));
			} elseif ($product_id == 2213) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '5Ч'),
					array('name' => 'Тип привода', 'value' => 'червячный мотор-редуктор'),
					array('name' => 'Тип редуктора', 'value' => 'червячный одноступенчатый'),
					array('name' => 'Типоразмер', 'value' => '5Ч-80'),
					array('name' => 'Межосевое расстояние', 'value' => '80 мм'),
					array('name' => 'Входная частота вращения', 'value' => 'до 1500 об/мин'),
					array('name' => 'Корпус', 'value' => 'чугун'),
					array('name' => 'Назначение', 'value' => 'снижение оборотов и увеличение крутящего момента в промышленном приводе'),
					array('name' => 'Подбор аналога', 'value' => 'по шильдику, передаточному числу, мощности двигателя, валам и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Червячные мотор-редукторы')
				));
			} elseif ($product_id == 400101672) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'NMRV'),
					array('name' => 'Типоразмер редуктора', 'value' => '075'),
					array('name' => 'Передаточное отношение', 'value' => 'i = 60'),
					array('name' => 'Мощность электродвигателя', 'value' => '1,5 кВт'),
					array('name' => 'Синхронная скорость двигателя', 'value' => '1500 об/мин'),
					array('name' => 'Фактическая скорость на выходе', 'value' => '~23-25 об/мин'),
					array('name' => 'Расчетный выходной момент', 'value' => '~380-410 Н·м'),
					array('name' => 'Монтажное исполнение', 'value' => 'B3, горизонтальная установка на лапах'),
					array('name' => 'Выходной вал', 'value' => 'полый, 28 мм'),
					array('name' => 'Питание двигателя', 'value' => '220/380 В или 380/660 В, 50 Гц'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Редукторы NMRV > NMRV 075')
				));
			} elseif ($product_id == 400101680) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'NMRV'),
					array('name' => 'Типоразмер редуктора', 'value' => '090'),
					array('name' => 'Тип привода', 'value' => 'червячный мотор-редуктор'),
					array('name' => 'Передаточное отношение', 'value' => 'i = 10'),
					array('name' => 'Мощность электродвигателя', 'value' => '2,2 кВт'),
					array('name' => 'Синхронная скорость двигателя', 'value' => '1500 об/мин'),
					array('name' => 'Частота вращения выходного вала', 'value' => 'около 142 об/мин'),
					array('name' => 'Номинальный выходной момент', 'value' => '132 Н·м'),
					array('name' => 'Монтаж', 'value' => 'B5 фланец + B3 горизонтальное исполнение'),
					array('name' => 'Полый выходной вал', 'value' => '35 / 38 / 40 мм'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Редукторы NMRV > NMRV 090')
				));
			} elseif ($product_id == 400101679) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'NMRV'),
					array('name' => 'Типоразмер редуктора', 'value' => '110'),
					array('name' => 'Тип привода', 'value' => 'червячный мотор-редуктор'),
					array('name' => 'Передаточное отношение', 'value' => 'i = 40'),
					array('name' => 'Мощность электродвигателя', 'value' => '3 кВт'),
					array('name' => 'Частота вращения выходного вала', 'value' => '70 об/мин'),
					array('name' => 'Конструкция', 'value' => 'червячная угловая передача'),
					array('name' => 'Монтаж', 'value' => 'уточняется по шильдику и присоединительным размерам'),
					array('name' => 'Подбор аналога', 'value' => 'по типоразмеру, передаточному числу, мощности, валу, фланцу и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Редукторы NMRV > NMRV 110')
				));
			} elseif ($product_id == 400101688) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'RT / FRT'),
					array('name' => 'Тип редуктора', 'value' => 'одноступенчатый червячный редуктор'),
					array('name' => 'Типоразмер', 'value' => '50'),
					array('name' => 'Передаточное отношение', 'value' => 'i = 70'),
					array('name' => 'Исполнение', 'value' => 'AC24'),
					array('name' => 'Входной фланец', 'value' => 'IEC 71-B14'),
					array('name' => 'Код комплектации', 'value' => 'G514'),
					array('name' => 'Монтаж', 'value' => 'универсальный корпус, фланец или крепление на корпусе'),
					array('name' => 'Подбор аналога', 'value' => 'по серии, типоразмеру, передаточному числу, фланцу IEC, валу и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Редукторы RT/FRT')
				));
			} elseif ($product_id == 400101693) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'Ч'),
					array('name' => 'Модель', 'value' => 'Ч-125-63-52-КЦ'),
					array('name' => 'Тип редуктора', 'value' => 'червячный одноступенчатый'),
					array('name' => 'Межосевое расстояние', 'value' => '125 мм'),
					array('name' => 'Передаточное число', 'value' => 'i = 63'),
					array('name' => 'Вариант сборки', 'value' => '52-КЦ'),
					array('name' => 'Назначение', 'value' => 'снижение оборотов и увеличение крутящего момента в промышленном приводе'),
					array('name' => 'Подбор аналога', 'value' => 'по модели, передаточному числу, варианту сборки, валам и присоединительным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Редукторы Ч > Ч-125')
				));
			} elseif ($product_id == 2116) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'Ч'),
					array('name' => 'Модель', 'value' => 'Ч-100-31,5-52-2'),
					array('name' => 'Тип редуктора', 'value' => 'червячный одноступенчатый'),
					array('name' => 'Межосевое расстояние', 'value' => '100 мм'),
					array('name' => 'Передаточное число', 'value' => 'i = 31,5'),
					array('name' => 'Вариант сборки', 'value' => '52-2'),
					array('name' => 'Назначение', 'value' => 'увеличение крутящего момента и снижение оборотов в промышленном приводе'),
					array('name' => 'Подбор аналога', 'value' => 'по модели Ч-100, передаточному числу, варианту сборки, валам и присоединительным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Редукторы Ч > Ч-100')
				));
			} elseif ($product_id == 400101742) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'Ч'),
					array('name' => 'Модель', 'value' => 'Ч-100-15-52'),
					array('name' => 'Тип редуктора', 'value' => 'червячный одноступенчатый'),
					array('name' => 'Межосевое расстояние', 'value' => '100 мм'),
					array('name' => 'Передаточное число', 'value' => 'i = 15'),
					array('name' => 'Вариант сборки', 'value' => '52'),
					array('name' => 'Назначение', 'value' => 'увеличение крутящего момента и снижение оборотов'),
					array('name' => 'Подбор аналога', 'value' => 'по модели, передаточному числу, варианту сборки, валам и присоединительным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Редукторы Ч > Ч-100')
				));
			} elseif ($product_id == 400101810) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'NMRV-S'),
					array('name' => 'Типоразмер редуктора', 'value' => '063'),
					array('name' => 'Тип привода', 'value' => 'червячный мотор-редуктор'),
					array('name' => 'Корпус', 'value' => 'универсальный корпус для разных монтажных положений'),
					array('name' => 'Двигатель', 'value' => 'подбирается по мощности, оборотам и фланцу IEC'),
					array('name' => 'Назначение', 'value' => 'компактные промышленные приводы малой и средней мощности'),
					array('name' => 'Подбор аналога', 'value' => 'по типоразмеру 063, передаточному числу, мощности, валу, фланцу и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Червячные редукторы > Редукторы NMRV > NMRV 063')
				));
			} elseif ($product_id == 400102003) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'BLY'),
					array('name' => 'Модель', 'value' => 'BLY27-87'),
					array('name' => 'Тип редуктора', 'value' => 'циклоидальный редуктор'),
					array('name' => 'Мощность двигателя', 'value' => '7,5 кВт'),
					array('name' => 'Типоразмер', 'value' => '87'),
					array('name' => 'Исполнение', 'value' => 'BLY, горизонтальная компоновка'),
					array('name' => 'Назначение', 'value' => 'снижение оборотов и увеличение крутящего момента в промышленном приводе'),
					array('name' => 'Подбор аналога', 'value' => 'по модели, мощности, передаточному числу, валам, фланцу и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Редукторы циклоидальные')
				));
			} elseif ($product_id == 400102004) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'BLD'),
					array('name' => 'Модель', 'value' => 'BLD4-87'),
					array('name' => 'Тип редуктора', 'value' => 'циклоидальный редуктор'),
					array('name' => 'Типоразмер', 'value' => '87'),
					array('name' => 'Исполнение', 'value' => 'BLD, вертикальная компоновка'),
					array('name' => 'Передаточное число', 'value' => 'уточняется по шильдику или заявке'),
					array('name' => 'Назначение', 'value' => 'промышленные приводы с высоким крутящим моментом и компактной передачей'),
					array('name' => 'Подбор аналога', 'value' => 'по модели, передаточному числу, мощности, валам и присоединительным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Редукторы циклоидальные')
				));
			} elseif ($product_id == 400101931) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'Ц2У'),
					array('name' => 'Модель', 'value' => 'Ц2У-250-31,5-12-КК'),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический двухступенчатый'),
					array('name' => 'Межосевое расстояние', 'value' => '250 мм'),
					array('name' => 'Передаточное число', 'value' => 'i = 31,5'),
					array('name' => 'Вариант сборки', 'value' => '12'),
					array('name' => 'Концы валов', 'value' => 'КК, конические концы быстроходного и тихоходного валов'),
					array('name' => 'Назначение', 'value' => 'промышленные приводы конвейеров, смесителей, дробилок и тяжелых механизмов'),
					array('name' => 'Подбор аналога', 'value' => 'по межосевому расстоянию, передаточному числу, сборке, валам и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Редукторы Ц2У > Ц2У-250')
				));
			} elseif (isset($ts2u_reducer_seo[(int)$product_id])) {
				$ts2u = $ts2u_reducer_seo[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'Ц2У'),
					array('name' => 'Модель', 'value' => $ts2u['model']),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический двухступенчатый горизонтальный'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая косозубая'),
					array('name' => 'Типоразмер', 'value' => $ts2u['size']),
					array('name' => 'Межосевое расстояние', 'value' => $ts2u['axis']),
					array('name' => 'Передаточное число', 'value' => $ts2u['ratio']),
					array('name' => 'Вариант сборки', 'value' => $ts2u['assembly']),
					array('name' => 'Концы валов', 'value' => $ts2u['shaft']),
					array('name' => 'Климатическое исполнение', 'value' => $ts2u['climate']),
					array('name' => 'Монтаж', 'value' => 'горизонтальное исполнение на лапах'),
					array('name' => 'Назначение', 'value' => 'приводы конвейеров, транспортеров, дробилок, смесителей, подъемников и промышленного оборудования'),
					array('name' => 'Подбор аналога', 'value' => 'по модели ' . $ts2u['model'] . ', передаточному числу, валам, варианту сборки, монтажным и присоединительным размерам'),
					array('name' => 'Категория', 'value' => $ts2u['category'])
				));
			} elseif (isset($crane_reducer_seo[(int)$product_id])) {
				$crane_reducer = $crane_reducer_seo[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => $crane_reducer['series']),
					array('name' => 'Модель', 'value' => $crane_reducer['model']),
					array('name' => 'Тип редуктора', 'value' => $crane_reducer['type']),
					array('name' => 'Типоразмер', 'value' => $crane_reducer['size']),
					array('name' => 'Компоновка', 'value' => $crane_reducer['layout']),
					array('name' => 'Назначение', 'value' => 'приводы крановых механизмов, тележек, мостовых кранов, подъемно-транспортного оборудования и тяжелых промышленных узлов'),
					array('name' => 'Подбор аналога', 'value' => 'по модели ' . $crane_reducer['model'] . ', передаточному числу, валам, монтажу, схеме сборки и присоединительным размерам'),
					array('name' => 'Категория', 'value' => $crane_reducer['category'])
				));
			} elseif ($product_id == 2113) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'ЦДН'),
					array('name' => 'Модель', 'value' => 'ЦДН-630'),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический двухступенчатый тяжелой серии'),
					array('name' => 'Межосевое расстояние', 'value' => '630 мм'),
					array('name' => 'Передаточное число', 'value' => 'уточняется по исполнению, часто i = 50'),
					array('name' => 'Номинальный момент', 'value' => 'уточняется по шильдику и исполнению'),
					array('name' => 'Концы валов', 'value' => 'ЦЦ или другое исполнение по комплектации'),
					array('name' => 'Назначение', 'value' => 'тяжело нагруженные ленточные конвейеры, дробилки, мельницы и промышленные механизмы'),
					array('name' => 'Подбор аналога', 'value' => 'по серии ЦДН, межосевому расстоянию, моменту, передаточному числу, валам и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Редукторы ЦДН')
				));
			} elseif ($product_id == 400102062) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'ЦДН'),
					array('name' => 'Модель', 'value' => 'ЦДН-710-31,5-21-ЦЦ'),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический двухступенчатый тяжелой серии'),
					array('name' => 'Межосевое расстояние', 'value' => '710 мм'),
					array('name' => 'Передаточное число', 'value' => 'i = 31,5'),
					array('name' => 'Номинальный момент', 'value' => '21 кН·м'),
					array('name' => 'Концы валов', 'value' => 'ЦЦ, цилиндрические концы входного и выходного валов'),
					array('name' => 'Назначение', 'value' => 'тяжело нагруженные конвейеры, дробилки, мельницы и промышленные механизмы'),
					array('name' => 'Подбор аналога', 'value' => 'по серии, моменту, передаточному числу, валам, сборке и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Редукторы ЦДН')
				));
			} elseif ($product_id == 400102063) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '1Ц2У'),
					array('name' => 'Модель', 'value' => '1Ц2У-100-31,5-12-КК-У2'),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический двухступенчатый узкий'),
					array('name' => 'Межосевое расстояние', 'value' => '100 мм'),
					array('name' => 'Передаточное число', 'value' => 'i = 31,5'),
					array('name' => 'Вариант сборки', 'value' => '12'),
					array('name' => 'Концы валов', 'value' => 'КК, конические концы валов'),
					array('name' => 'Климатическое исполнение', 'value' => 'У2'),
					array('name' => 'Подбор аналога', 'value' => 'по типоразмеру 100, передаточному числу, сборке, валам и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Редукторы 1Ц2У > 1Ц2У-100')
				));
			} elseif (isset($one_ts2u_reducer_seo[(int)$product_id])) {
				$one_ts2u = $one_ts2u_reducer_seo[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '1Ц2У'),
					array('name' => 'Модель', 'value' => $one_ts2u['model']),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический двухступенчатый горизонтальный'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая косозубая'),
					array('name' => 'Типоразмер', 'value' => $one_ts2u['size']),
					array('name' => 'Межосевое расстояние', 'value' => $one_ts2u['axis']),
					array('name' => 'Передаточное число', 'value' => $one_ts2u['ratio']),
					array('name' => 'Вариант сборки', 'value' => $one_ts2u['assembly']),
					array('name' => 'Концы валов', 'value' => $one_ts2u['shaft']),
					array('name' => 'Климатическое исполнение', 'value' => $one_ts2u['climate']),
					array('name' => 'Монтаж', 'value' => 'горизонтальное исполнение на лапах'),
					array('name' => 'Назначение', 'value' => 'приводы конвейеров, транспортеров, подъемников, дробилок, смесителей, станков и технологического оборудования'),
					array('name' => 'Подбор аналога', 'value' => 'по модели ' . $one_ts2u['model'] . ', передаточному числу, валам, варианту сборки, монтажным и присоединительным размерам'),
					array('name' => 'Категория', 'value' => $one_ts2u['category'])
				));
			} elseif ($product_id == 400102066) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'KA77'),
					array('name' => 'Модель', 'value' => 'KA77-16,6-87-11,0-B8'),
					array('name' => 'Тип привода', 'value' => 'коническо-цилиндрический мотор-редуктор'),
					array('name' => 'Мощность двигателя', 'value' => '11 кВт'),
					array('name' => 'Выходная скорость', 'value' => '87 об/мин'),
					array('name' => 'Передаточное число', 'value' => 'i = 16,6'),
					array('name' => 'Монтажное положение', 'value' => 'B8'),
					array('name' => 'Комплектация', 'value' => 'реактивная тяга'),
					array('name' => 'Подбор аналога', 'value' => 'по модели KA77, мощности, передаточному числу, валу, креплению и реактивной тяге'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 400102067) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'KA77'),
					array('name' => 'Модель', 'value' => 'KA77-16,6-87-7,5-B8'),
					array('name' => 'Тип привода', 'value' => 'коническо-цилиндрический мотор-редуктор'),
					array('name' => 'Мощность двигателя', 'value' => '7,5 кВт'),
					array('name' => 'Выходная скорость', 'value' => '87 об/мин'),
					array('name' => 'Передаточное число', 'value' => 'i = 16,6'),
					array('name' => 'Монтажное положение', 'value' => 'B8'),
					array('name' => 'Комплектация', 'value' => 'тормоз и реактивная тяга'),
					array('name' => 'Подбор аналога', 'value' => 'по модели KA77, мощности, тормозу, передаточному числу, валу и монтажу'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 400101790) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Производитель', 'value' => 'Bonfiglioli'),
					array('name' => 'Серия', 'value' => 'A'),
					array('name' => 'Модель', 'value' => 'A 50 2 UH50 16.6 S4 B8 M4LC4'),
					array('name' => 'Тип редуктора', 'value' => 'коническо-цилиндрический, 2-ступенчатый'),
					array('name' => 'Мощность двигателя', 'value' => '11 кВт'),
					array('name' => 'Частота выходного вала', 'value' => '87 об/мин'),
					array('name' => 'Передаточное число', 'value' => 'i = 16,6'),
					array('name' => 'Выходной крутящий момент', 'value' => '1 137 Н·м'),
					array('name' => 'Выходной вал', 'value' => 'полый 50 мм со шпонкой'),
					array('name' => 'Монтажное положение', 'value' => 'B8'),
					array('name' => 'Степень защиты', 'value' => 'IP55'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 400101792) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Производитель', 'value' => 'Bonfiglioli'),
					array('name' => 'Серия', 'value' => 'A'),
					array('name' => 'Модель', 'value' => 'A 50 2 UH50 16.6 S4 B8 M4LA4 FD R'),
					array('name' => 'Тип редуктора', 'value' => 'коническо-цилиндрический, 2-ступенчатый'),
					array('name' => 'Мощность двигателя', 'value' => '7,5 кВт'),
					array('name' => 'Частота выходного вала', 'value' => '87 об/мин'),
					array('name' => 'Передаточное число', 'value' => 'i = 16,6'),
					array('name' => 'Выходной крутящий момент', 'value' => '~775 Н·м'),
					array('name' => 'Выходной вал', 'value' => 'полый 50 мм со шпонкой'),
					array('name' => 'Монтажное положение', 'value' => 'B8'),
					array('name' => 'Степень защиты', 'value' => 'IP55'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 400102134) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Производитель', 'value' => 'Bonfiglioli'),
					array('name' => 'Серия', 'value' => 'A'),
					array('name' => 'Модель', 'value' => 'A 50 2 UH50 16.6 S4 B8 M4LLA4 FD R'),
					array('name' => 'Тип редуктора', 'value' => 'коническо-цилиндрический, 2-ступенчатый'),
					array('name' => 'Мощность двигателя', 'value' => '7,5 кВт'),
					array('name' => 'Частота вращения двигателя', 'value' => '1440 об/мин'),
					array('name' => 'Частота выходного вала', 'value' => '87 об/мин'),
					array('name' => 'Передаточное число', 'value' => 'i = 16,6'),
					array('name' => 'Выходной крутящий момент', 'value' => '~775 Н·м'),
					array('name' => 'Выходной вал', 'value' => 'полый 50 мм со шпонкой'),
					array('name' => 'Монтажное положение', 'value' => 'B8'),
					array('name' => 'Тип двигателя', 'value' => 'M4LLA4, 4-полюсный'),
					array('name' => 'Материал корпуса', 'value' => 'чугун'),
					array('name' => 'Степень защиты', 'value' => 'IP55'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Конические мотор-редукторы')
				));
			} elseif ($product_id == 400102150) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'FAF-S57'),
					array('name' => 'Тип привода', 'value' => 'плоский цилиндрический мотор-редуктор'),
					array('name' => 'Компоновка', 'value' => 'параллельные оси валов'),
					array('name' => 'Передаточное число', 'value' => 'i = 24,96'),
					array('name' => 'Мощность двигателя', 'value' => '1,1 кВт'),
					array('name' => 'Частота выходного вала', 'value' => 'около 56 об/мин'),
					array('name' => 'Монтажное исполнение', 'value' => 'FAF, фланцевое исполнение'),
					array('name' => 'Назначение', 'value' => 'приводы конвейеров, транспортеров, дозаторов и компактного технологического оборудования'),
					array('name' => 'Подбор аналога', 'value' => 'по передаточному числу, оборотам, мощности, валам, фланцу и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Плоские цилиндрические мотор-редукторы')
				));
			} elseif ($product_id == 400101999) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '4МЦ2С'),
					array('name' => 'Тип привода', 'value' => 'цилиндрический соосный мотор-редуктор'),
					array('name' => 'Типоразмер', 'value' => '125'),
					array('name' => 'Передаточное число', 'value' => 'i = 35,5'),
					array('name' => 'Мощность двигателя', 'value' => '11 кВт'),
					array('name' => 'Выходной крутящий момент', 'value' => '1340 Н·м'),
					array('name' => 'Конструкция', 'value' => 'двухступенчатая цилиндрическая передача'),
					array('name' => 'Назначение', 'value' => 'промышленные приводы конвейеров, транспортеров и технологического оборудования'),
					array('name' => 'Подбор аналога', 'value' => 'по модели, передаточному числу, мощности, моменту, валам и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы')
				));
			} elseif (in_array((int)$product_id, array(2229, 2231, 2232, 2233, 2234, 2235), true)) {
				$four_mts2s_sizes = array(2229 => '50', 2231 => '80', 2232 => '100', 2233 => '160', 2234 => '125', 2235 => '140');
				$four_mts2s_size = $four_mts2s_sizes[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '4МЦ2С'),
					array('name' => 'Модель', 'value' => '4МЦ2С-' . $four_mts2s_size),
					array('name' => 'Тип привода', 'value' => 'цилиндрический соосный мотор-редуктор'),
					array('name' => 'Типоразмер', 'value' => $four_mts2s_size),
					array('name' => 'Передаточное число', 'value' => 'подбирается по исполнению и требуемым оборотам'),
					array('name' => 'Мощность двигателя', 'value' => 'подбирается под нагрузку механизма'),
					array('name' => 'Выходной момент', 'value' => 'зависит от передаточного числа и мощности двигателя'),
					array('name' => 'Компоновка', 'value' => 'соосное расположение входного и выходного валов'),
					array('name' => 'Конструкция', 'value' => 'цилиндрическая зубчатая передача в корпусе мотор-редуктора'),
					array('name' => 'Назначение', 'value' => 'промышленные приводы конвейеров, транспортеров, дозаторов, смесителей и технологических линий'),
					array('name' => 'Подбор аналога', 'value' => 'по типоразмеру ' . $four_mts2s_size . ', оборотам, мощности, моменту, валам, креплению и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы')
				));
			} elseif ($product_id == 2230) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '4МЦ2С'),
					array('name' => 'Модель', 'value' => '4МЦ2С-63'),
					array('name' => 'Тип привода', 'value' => 'цилиндрический соосный мотор-редуктор'),
					array('name' => 'Типоразмер', 'value' => '63'),
					array('name' => 'Передаточное число', 'value' => 'подбирается по исполнению'),
					array('name' => 'Мощность двигателя', 'value' => 'подбирается под нагрузку механизма'),
					array('name' => 'Выходной момент', 'value' => 'зависит от передаточного числа и мощности двигателя'),
					array('name' => 'Компоновка', 'value' => 'соосное расположение входного и выходного валов'),
					array('name' => 'Назначение', 'value' => 'промышленные приводы конвейеров, транспортеров, дозаторов и технологических линий'),
					array('name' => 'Подбор аналога', 'value' => 'по типоразмеру 63, оборотам, мощности, моменту, валам, креплению и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы')
				));
			} elseif ($product_id == 400102077) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '4МЦ2С'),
					array('name' => 'Модель', 'value' => '4МЦ2С-63-71-1,1-G110'),
					array('name' => 'Тип привода', 'value' => 'цилиндрический соосный мотор-редуктор'),
					array('name' => 'Типоразмер', 'value' => '63'),
					array('name' => 'Выходная скорость', 'value' => '71 об/мин'),
					array('name' => 'Мощность двигателя', 'value' => '1,1 кВт'),
					array('name' => 'Выходной крутящий момент', 'value' => '148 Н·м'),
					array('name' => 'Диаметр выходного вала', 'value' => '28 мм'),
					array('name' => 'Монтажное исполнение', 'value' => 'G110, на лапах'),
					array('name' => 'Назначение', 'value' => 'промышленные приводы конвейеров, транспортеров, дозаторов и технологических линий'),
					array('name' => 'Подбор аналога', 'value' => 'по модели 4МЦ2С-63, оборотам, мощности, моменту, валу, креплению и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы')
				));
			} elseif (in_array((int)$product_id, array(2236, 2237, 2238, 2239, 2240, 2241, 2242), true)) {
				$planetary_3mp_sizes = array(2236 => '50', 2237 => '63', 2238 => '80', 2239 => '100', 2240 => '25', 2241 => '125', 2242 => '40');
				$planetary_3mp_size = $planetary_3mp_sizes[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '3МП'),
					array('name' => 'Альтернативное обозначение', 'value' => '1МПз-' . $planetary_3mp_size),
					array('name' => 'Модель', 'value' => '3МП-' . $planetary_3mp_size),
					array('name' => 'Тип привода', 'value' => 'планетарный мотор-редуктор'),
					array('name' => 'Типоразмер', 'value' => $planetary_3mp_size),
					array('name' => 'Передаточное число', 'value' => 'подбирается по исполнению и требуемым оборотам'),
					array('name' => 'Мощность двигателя', 'value' => 'подбирается под нагрузку механизма'),
					array('name' => 'Выходной момент', 'value' => 'зависит от типоразмера, передаточного числа и мощности двигателя'),
					array('name' => 'Компоновка', 'value' => 'соосная планетарная передача с компактным корпусом'),
					array('name' => 'Назначение', 'value' => 'приводы конвейеров, лебедок, дозаторов, смесителей, элеваторов и технологических линий'),
					array('name' => 'Подбор аналога', 'value' => 'по типоразмеру ' . $planetary_3mp_size . ', оборотам, мощности, моменту, валам, креплению и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Планетарные редукторы > Редукторы 3МП > 3МП-' . $planetary_3mp_size)
				));
			} elseif (in_array((int)$product_id, array(2132, 2133, 2134, 2135, 2136, 2137, 2138, 2139, 2140), true)) {
				$rc_sizes = array(2132 => '17', 2133 => '37', 2134 => '47', 2135 => '57', 2136 => '67', 2137 => '147', 2138 => '137', 2139 => '107', 2140 => '97');
				$rc_size = $rc_sizes[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => 'RC / RCF'),
					array('name' => 'Модель', 'value' => 'RC' . $rc_size . ' / RCF' . $rc_size),
					array('name' => 'Тип привода', 'value' => 'цилиндрический мотор-редуктор'),
					array('name' => 'Типоразмер', 'value' => $rc_size),
					array('name' => 'Исполнение', 'value' => 'RC и фланцевое RCF, подбирается по монтажу'),
					array('name' => 'Передаточное число', 'value' => 'подбирается по требуемым оборотам'),
					array('name' => 'Мощность двигателя', 'value' => 'подбирается под нагрузку механизма'),
					array('name' => 'Выходной момент', 'value' => 'зависит от типоразмера, передаточного числа и двигателя'),
					array('name' => 'Назначение', 'value' => 'приводы конвейеров, транспортеров, дозаторов, насосов, мешалок и технологических линий'),
					array('name' => 'Подбор аналога', 'value' => 'по типоразмеру RC' . $rc_size . ', оборотам, мощности, моменту, валам, фланцу и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Мотор-редукторы RC')
				));
			} elseif (in_array((int)$product_id, array(2143, 2144, 2145, 2146, 2147, 2148, 2149, 2150, 2151), true)) {
				$kts_series = array(2143 => 'КЦ1', 2144 => 'КЦ1', 2145 => 'КЦ1', 2146 => 'КЦ1', 2147 => 'КЦ1', 2148 => 'КЦ2', 2149 => 'КЦ2', 2150 => 'КЦ2', 2151 => 'КЦ2');
				$kts_sizes = array(2143 => '200', 2144 => '250', 2145 => '300', 2146 => '400', 2147 => '500', 2148 => '1000', 2149 => '1300', 2150 => '500', 2151 => '750');
				$kts_series_name = $kts_series[(int)$product_id];
				$kts_size = $kts_sizes[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => $kts_series_name),
					array('name' => 'Модель', 'value' => $kts_series_name . '-' . $kts_size),
					array('name' => 'Тип редуктора', 'value' => 'коническо-цилиндрический редуктор'),
					array('name' => 'Типоразмер', 'value' => $kts_size),
					array('name' => 'Ступени', 'value' => $kts_series_name == 'КЦ2' ? 'трехступенчатое исполнение' : 'коническо-цилиндрическая передача'),
					array('name' => 'Передаточное число', 'value' => 'подбирается по требуемым оборотам и моменту'),
					array('name' => 'Крутящий момент', 'value' => 'зависит от типоразмера, передаточного числа и режима нагрузки'),
					array('name' => 'Назначение', 'value' => 'приводы конвейеров, дробилок, смесителей, подъемно-транспортного и технологического оборудования'),
					array('name' => 'Подбор аналога', 'value' => 'по модели ' . $kts_series_name . '-' . $kts_size . ', передаточному числу, валам, креплению и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Коническо-цилиндрические редукторы > Редукторы ' . $kts_series_name . ' > ' . $kts_series_name . '-' . $kts_size)
				));
			} elseif (isset($brand_motor_reducer_seo[(int)$product_id]) && (int)$product_id !== 2142) {
				$brand_motor = $brand_motor_reducer_seo[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => $brand_motor['brand']),
					array('name' => 'Серия', 'value' => $brand_motor['series']),
					array('name' => 'Модель', 'value' => $brand_motor['model']),
					array('name' => 'Тип привода', 'value' => $brand_motor['type']),
					array('name' => 'Компоновка', 'value' => $brand_motor['layout']),
					array('name' => 'Передаточное число', 'value' => 'подбирается по требуемым оборотам и нагрузке механизма'),
					array('name' => 'Мощность двигателя', 'value' => 'подбирается под момент, режим работы и пусковые нагрузки'),
					array('name' => 'Выходной момент', 'value' => 'зависит от типоразмера, передаточного числа, двигателя и сервис-фактора'),
					array('name' => 'Назначение', 'value' => $brand_motor['application']),
					array('name' => 'Подбор аналога', 'value' => $brand_motor['selection']),
					array('name' => 'Категория', 'value' => $brand_motor['category'])
				));
			} elseif ($product_id == 2188) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Siti'),
					array('name' => 'Серия', 'value' => 'RP2'),
					array('name' => 'Тип редуктора', 'value' => 'маятниковый насадной редуктор'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая или коническо-цилиндрическая, по исполнению'),
					array('name' => 'Исполнение вала', 'value' => 'полый вал для установки на приводной вал механизма'),
					array('name' => 'Фиксация корпуса', 'value' => 'через реактивную тягу или моментный рычаг'),
					array('name' => 'Монтаж', 'value' => 'насадное исполнение без отдельной фундаментной рамы'),
					array('name' => 'Назначение', 'value' => 'ленточные конвейеры, транспортеры, дробильные и технологические линии'),
					array('name' => 'Подбор аналога', 'value' => 'по серии RP2, моменту, передаточному числу, валу, креплению и габаритам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Редукторы насадные')
				));
			} elseif ($product_id == 2190) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Siti'),
					array('name' => 'Серия', 'value' => 'MPD'),
					array('name' => 'Тип привода', 'value' => 'цилиндрический насадной мотор-редуктор'),
					array('name' => 'Компоновка валов', 'value' => 'параллельные валы, полый выходной вал'),
					array('name' => 'Монтаж', 'value' => 'на вал механизма через реактивную тягу'),
					array('name' => 'Подбор', 'value' => 'по моменту, передаточному числу, валу, фланцу двигателя и монтажному положению'),
					array('name' => 'Категория', 'value' => 'Редукторы > Редукторы насадные')
				));
			} elseif ($product_id == 2191) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Siti'),
					array('name' => 'Серия', 'value' => 'RFV'),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический насадной редуктор'),
					array('name' => 'Компоновка валов', 'value' => 'параллельные валы, полый выходной вал'),
					array('name' => 'Монтаж', 'value' => 'на вал конвейера или технологического механизма'),
					array('name' => 'Подбор', 'value' => 'по моменту, передаточному числу, валу, креплению и габаритам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Редукторы насадные')
				));
			} elseif ($product_id == 2192) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Siti'),
					array('name' => 'Серия', 'value' => 'M'),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический редуктор с параллельными валами'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая передача'),
					array('name' => 'Исполнение валов', 'value' => 'по комплектации: полый или сплошной вал'),
					array('name' => 'Подбор', 'value' => 'по моменту, передаточному числу, валам, фланцу и монтажным размерам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Плоско-цилиндрические редукторы')
				));
			} elseif ($product_id == 2193) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Бренд', 'value' => 'Siti'),
					array('name' => 'Серия', 'value' => 'Z'),
					array('name' => 'Тип редуктора', 'value' => 'цилиндрический редуктор с параллельными осями'),
					array('name' => 'Тип передачи', 'value' => 'цилиндрическая передача с параллельным расположением валов'),
					array('name' => 'Исполнение', 'value' => 'компактная плоско-цилиндрическая компоновка'),
					array('name' => 'Подбор', 'value' => 'по моменту, передаточному числу, валам, монтажу и габаритам'),
					array('name' => 'Категория', 'value' => 'Редукторы > Цилиндрические редукторы > Плоско-цилиндрические редукторы')
				));
			} elseif ($product_id == 2142) {
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => '3МВз'),
					array('name' => 'Тип передачи', 'value' => 'одноступенчатая волновая зубчатая передача'),
					array('name' => 'Типоразмеры', 'value' => '3МВз-63, 3МВз-80, 3МВз-160'),
					array('name' => 'Передаточные отношения', 'value' => 'i = 78-275'),
					array('name' => 'Частота вращения выходного вала', 'value' => '6,3-35,5 об/мин'),
					array('name' => 'Номинальный крутящий момент', 'value' => '40-1250 Н·м'),
					array('name' => 'Мощность электродвигателя', 'value' => '0,12-2,2 кВт'),
					array('name' => 'Режим работы', 'value' => 'S1, продолжительный режим'),
					array('name' => 'Питание', 'value' => '380 В, 50 Гц'),
					array('name' => 'Монтаж', 'value' => 'на лапах или фланцевое исполнение'),
					array('name' => 'Климатическое исполнение', 'value' => 'У3'),
					array('name' => 'Категория', 'value' => 'Редукторы > Мотор-редукторы > Волновые мотор-редукторы')
				));
			} elseif (isset($remaining_reducer_seo[(int)$product_id])) {
				$remaining_reducer = $remaining_reducer_seo[(int)$product_id];
				$data['product_spec_rows'] = array_merge($data['product_spec_rows'], array(
					array('name' => 'Серия', 'value' => $remaining_reducer['series']),
					array('name' => 'Модель', 'value' => $remaining_reducer['model']),
					array('name' => 'Тип оборудования', 'value' => $remaining_reducer['type']),
					array('name' => 'Компоновка', 'value' => $remaining_reducer['layout']),
					array('name' => 'Назначение', 'value' => $remaining_reducer['application']),
					array('name' => 'Подбор', 'value' => $remaining_reducer['selection']),
					array('name' => 'Что проверить', 'value' => $remaining_reducer['checks']),
					array('name' => 'Категория', 'value' => $remaining_reducer['category'])
				));
			}

			$data['product_delivery_items'] = array(
				array(
					'icon' => 'fa-truck',
					'title' => 'Доставка по Казахстану',
					'text' => 'Отправляем промышленное оборудование транспортными компаниями по Алматы, Астане, Шымкенту и регионам РК.'
				),
				array(
					'icon' => 'fa-clock-o',
					'title' => 'Сроки после согласования',
					'text' => 'Обычно доставка занимает 1-5 рабочих дней: точный срок зависит от наличия, города и выбранного перевозчика.'
				),
				array(
					'icon' => 'fa-map-marker',
					'title' => 'Самовывоз в Алматы',
					'text' => 'Забрать заказ можно со склада и офиса: г. Алматы, ул. Фаворского, 21. Время визита лучше согласовать заранее.'
				),
				array(
					'icon' => 'fa-archive',
					'title' => 'Упаковка под оборудование',
					'text' => 'Для габаритных и тяжёлых позиций подбираем упаковку и способ перевозки отдельно, чтобы снизить риск повреждений.'
				)
			);

			$data['product_payment_items'] = array(
				array(
					'icon' => 'fa-file-text-o',
					'title' => 'Счёт на оплату',
					'text' => 'Выставляем счёт для ТОО, ИП и организаций после подтверждения цены, наличия и комплектации товара.'
				),
				array(
					'icon' => 'fa-university',
					'title' => 'Безналичный расчёт',
					'text' => 'Основной формат оплаты для промышленных поставок — безналичный перевод на расчётный счёт компании.'
				),
				array(
					'icon' => 'fa-credit-card',
					'title' => 'Kaspi Pay по согласованию',
					'text' => 'Если такой способ подходит для заказа, менеджер отдельно подтвердит возможность оплаты через Kaspi Pay.'
				),
				array(
					'icon' => 'fa-briefcase',
					'title' => 'Закрывающие документы',
					'text' => 'Подготавливаем документы для бухгалтерии: счёт, договор и отгрузочные документы по согласованной поставке.'
				)
			);

			if ($product_id == 2174) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Какое масло залито в червячный редуктор Motovario NMRV?',
						'answer' => 'Типоразмеры NMRV 030–090 поставляются с завода заправленными высококачественным синтетическим маслом Mobil Glygoyle 30 (ISO VG 320) на весь срок службы и не требуют обслуживания. Крупные габариты NMRV 110–150 поставляются без масла, его необходимо приобретать отдельно и заливать перед запуском в соответствии с монтажным положением редуктора.'
					),
					array(
						'question' => 'В чем ключевое отличие серии NMRV от NMRV-P?',
						'answer' => 'Серия NMRV-P (габариты 063, 075, 090, 110) — это модернизированное поколение редукторов Motovario. Они имеют улучшенную модульную конструкцию корпуса, повышенную жесткость конструкции и способны передавать больший крутящий момент при тех же габаритах по сравнению с базовой серией NMRV.'
					),
					array(
						'question' => 'С какими электродвигателями совместимы мотор-редукторы Motovario?',
						'answer' => 'Редукторы Motovario оснащены входными фланцами стандарта IEC (PAM). Они полностью совместимы с любыми стандартными асинхронными электродвигателями переменного тока (как европейского стандарта DIN, так и отечественного ГОСТ при соответствии размеров вала и фланца).'
					),
					array(
						'question' => 'Какие дополнительные комплектующие можно заказать?',
						'answer' => 'Для мотор-редукторов доступны: односторонние и двухсторонние выходные приводные валы, реактивные кронштейны (моментные рычаги) для гашения вибрации, выходные боковые фланцы (F, FL) для монтажа на оборудование и защитные пластиковые кожухи для выходного вала.'
					),
					array(
						'question' => 'Как правильно определить передаточное отношение (i) редуктора?',
						'answer' => 'Передаточное отношение указано на металлическом шильдике редуктора в поле «i». Если шильдик утерян, передаточное число можно определить вручную: посчитайте количество оборотов входного вала (куда крепится двигатель), необходимых для того, чтобы выходной полый вал сделал ровно один полный оборот.'
					),
					array(
						'question' => 'Каков срок официальной гарантии на продукцию Motovario?',
						'answer' => 'На все оригинальные мотор-редукторы Motovario, приобретаемые в ТОО «Fortune PROM», предоставляется официальная гарантия завода-изготовителя сроком 12 месяцев с момента отгрузки товара при условии соблюдения правил монтажа и эксплуатации.'
					)
				);
			} elseif ($product_id == 400101672) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение NMRV 075-60-1,5/1500-B3?',
						'answer' => 'NMRV — серия червячного мотор-редуктора, 075 — типоразмер редуктора с межосевым расстоянием 75 мм, 60 — передаточное отношение i=60, 1,5 кВт — мощность электродвигателя, 1500 об/мин — синхронная частота вращения двигателя, B3 — горизонтальное монтажное исполнение на лапах.'
					),
					array(
						'question' => 'Какая выходная скорость у мотор-редуктора NMRV 075 с i=60?',
						'answer' => 'При двигателе 1500 об/мин расчетная скорость на выходном валу составляет около 25 об/мин. С учетом фактической скорости асинхронного двигателя под нагрузкой обычно получается примерно 23-24 об/мин.'
					),
					array(
						'question' => 'Какой крутящий момент дает NMRV 075-60 с двигателем 1,5 кВт?',
						'answer' => 'Ориентировочный выходной момент для этой комплектации составляет примерно 380-410 Н·м. Для точного подбора нужно учитывать режим работы, число пусков, ударные нагрузки, длительность работы и требуемый сервис-фактор.'
					),
					array(
						'question' => 'Для какого оборудования подходит эта модель?',
						'answer' => 'NMRV 075-60-1,5/1500-B3 применяют в приводах конвейеров, транспортеров, смесителей, дозаторов, упаковочных линий и другого оборудования, где нужны компактная угловая передача, невысокая выходная скорость и стабильный момент.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Motovario, Bonfiglioli, Lenze или другого NMRV 075?',
						'answer' => 'Да. Для подбора аналога достаточно прислать фото шильдика, передаточное число, мощность двигателя, типоразмер, монтажное положение, размеры выходного вала и фланца. Инженер проверит совместимость по посадочным размерам и нагрузке.'
					),
					array(
						'question' => 'Что важно проверить перед заказом B3 исполнения?',
						'answer' => 'Нужно подтвердить монтажное положение, сторону выходного вала, диаметр полого вала, положение клеммной коробки двигателя, напряжение питания, режим нагрузки и условия эксплуатации. Для червячных редукторов также важно согласовать смазку под фактическое положение установки.'
					),
					array(
						'question' => 'Есть ли доставка по Казахстану и гарантия?',
						'answer' => 'Fortune PROM поставляет мотор-редукторы по Алматы и регионам Казахстана транспортными компаниями. Гарантийные условия и срок поставки менеджер подтверждает перед оплатой с учетом наличия, комплектации и партии оборудования.'
					)
				);
			} elseif ($product_id == 400101680) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение NMRV 090-10-2,2/1500-B5-B3?',
						'answer' => 'NMRV — серия червячного мотор-редуктора, 090 — типоразмер с межосевым расстоянием 90 мм, 10 — передаточное отношение i=10, 2,2 кВт — мощность двигателя, 1500 об/мин — синхронная скорость, B5/B3 — фланцевое присоединение двигателя и горизонтальное монтажное исполнение.'
					),
					array(
						'question' => 'Какая выходная скорость у NMRV 090 с i=10?',
						'answer' => 'При номинальной скорости двигателя около 1420 об/мин выходная частота вращения составляет примерно 142 об/мин. Фактическое значение зависит от двигателя, нагрузки, частоты сети и режима работы оборудования.'
					),
					array(
						'question' => 'Какой крутящий момент у этой комплектации NMRV 090?',
						'answer' => 'Для комплектации NMRV 090 с i=10 и двигателем 2,2 кВт ориентировочный номинальный выходной момент составляет 132 Н·м. Для точного подбора нужно учитывать сервис-фактор, пусковые нагрузки, длительность работы и условия эксплуатации.'
					),
					array(
						'question' => 'Какой выходной вал у мотор-редуктора NMRV 090?',
						'answer' => 'Для NMRV 090 применяются варианты полого выходного вала 35, 38 или 40 мм. Перед заказом нужно подтвердить фактический диаметр, сторону исполнения, наличие фланца или реактивного рычага и размеры посадочного места на оборудовании.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Motovario, Bonfiglioli, Lenze или другого NMRV 090?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, передаточное число, мощность двигателя, типоразмер, монтажное положение, диаметр выходного вала, фланец и условия нагрузки. Инженер проверит совместимость по посадочным размерам и моменту.'
					),
					array(
						'question' => 'Что проверить перед заказом исполнения B5/B3?',
						'answer' => 'Нужно подтвердить фланец двигателя, горизонтальное положение B3, направление вывода вала, напряжение двигателя, положение клеммной коробки, класс защиты, режим работы и фактическую нагрузку. Для червячного редуктора также важно согласовать смазку под монтажное положение.'
					),
					array(
						'question' => 'Есть ли доставка NMRV 090 по Казахстану?',
						'answer' => 'Fortune PROM поставляет мотор-редукторы NMRV 090 по Алматы и регионам Казахстана. Срок поставки, гарантия, комплектация и стоимость доставки подтверждаются менеджером перед выставлением счета.'
					)
				);
			} elseif ($product_id == 400101679) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение NMRV 110-40-70-3?',
						'answer' => 'NMRV — серия червячного мотор-редуктора, 110 — типоразмер редуктора, 40 — передаточное отношение i=40, 70 об/мин — ориентировочная частота выходного вала, 3 — мощность электродвигателя 3 кВт по маркировке.'
					),
					array(
						'question' => 'К какой категории относится эта модель?',
						'answer' => 'Эта карточка относится к ветке Редукторы > Червячные редукторы > Редукторы NMRV > NMRV 110. Такая структура помогает быстрее найти товар по серии и типоразмеру, а также корректно связать карточку с каталогом.'
					),
					array(
						'question' => 'Для какого оборудования подходит NMRV 110-40?',
						'answer' => 'Мотор-редуктор NMRV 110-40 применяют в приводах конвейеров, транспортеров, смесителей, дозаторов и другого промышленного оборудования, где нужна компактная угловая передача, выходная скорость около 70 об/мин и мощность двигателя 3 кВт.'
					),
					array(
						'question' => 'Какой выходной момент у NMRV 110 с i=40?',
						'answer' => 'Точный момент зависит от двигателя, КПД, режима работы и производителя. Для корректного подбора нужно сверить паспортные данные, сервис-фактор, пусковые нагрузки, длительность работы и фактическую нагрузку механизма.'
					),
					array(
						'question' => 'Что проверить перед заказом NMRV 110?',
						'answer' => 'Важно подтвердить передаточное число, мощность двигателя, выходные обороты, диаметр полого вала, фланец, лапы, монтажное положение, сторону выходного вала, положение клеммной коробки и условия эксплуатации.'
					),
					array(
						'question' => 'Можно ли подобрать аналог NMRV 110-40?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, типоразмер, передаточное отношение, мощность двигателя, выходные обороты, размеры вала и фланца. Инженер проверит совместимость по посадочным размерам и нагрузке.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от наличия, производителя, комплектации двигателя, исполнения вала, фланца, монтажного положения и партии. Менеджер подтверждает стоимость после технической сверки.'
					)
				);
			} elseif ($product_id == 400101688) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение FRT 50 70 AC24 IEC71-B14 G514?',
						'answer' => 'FRT — исполнение червячного редуктора серии RT, 50 — типоразмер корпуса, 70 — передаточное отношение i=70, AC24 — вариант исполнения, IEC71-B14 — входной фланец под двигатель стандарта IEC, G514 — код комплектации.'
					),
					array(
						'question' => 'К какой категории относится редуктор FRT 50?',
						'answer' => 'Редуктор FRT 50 относится к червячным редукторам RT/FRT. Для него создана отдельная ветка каталога внутри раздела червячных редукторов, чтобы товар не находился в общей категории мотор-редукторов.'
					),
					array(
						'question' => 'Для каких задач подходит FRT 50 с i=70?',
						'answer' => 'Редуктор применяют в приводах конвейеров, транспортеров, дозаторов, упаковочных узлов и другого оборудования, где требуется компактная червячная передача, снижение оборотов и установка двигателя через фланец IEC71-B14.'
					),
					array(
						'question' => 'Что проверить перед заказом FRT 50?',
						'answer' => 'Нужно подтвердить типоразмер, передаточное отношение, входной фланец IEC71-B14, выходной вал или фланец, монтажное положение, габариты, направление вращения, нагрузку и режим работы оборудования.'
					),
					array(
						'question' => 'Можно ли подобрать аналог FRT 50 70?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, передаточное число, типоразмер, размеры валов и фланцев, монтажное положение, мощность двигателя и описание нагрузки. Инженер проверит совместимость по посадочным размерам.'
					),
					array(
						'question' => 'Поставляется ли редуктор с маслом?',
						'answer' => 'Для редукторов серии RT/FRT часто используется заводская синтетическая смазка, но перед запуском нужно сверить паспорт, монтажное положение и требования производителя к маслу или обслуживанию.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от наличия, исполнения, фланца, вала, дополнительных опций и партии. Fortune PROM подтверждает стоимость после сверки параметров под конкретный привод.'
					)
				);
			} elseif ($product_id == 400101693) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение Ч-125-63-52-КЦ?',
						'answer' => 'Ч — серия червячных редукторов, 125 — межосевое расстояние 125 мм, 63 — передаточное число i=63, 52-КЦ — вариант сборки и конструктивного исполнения. Эти параметры нужно сохранять при замене редуктора.'
					),
					array(
						'question' => 'К какой категории относится редуктор Ч-125?',
						'answer' => 'Карточка относится к ветке Редукторы > Червячные редукторы > Редукторы Ч > Ч-125. Это правильная типоразмерная категория для поиска и подбора аналогов.'
					),
					array(
						'question' => 'Где применяется редуктор Ч-125-63-52?',
						'answer' => 'Редуктор Ч-125 используют в приводах промышленного оборудования, грузоподъемных механизмов, станков, транспортеров и сельскохозяйственной техники, где требуется снижение оборотов и увеличение крутящего момента.'
					),
					array(
						'question' => 'Что проверить перед заказом исполнения 52-КЦ?',
						'answer' => 'Нужно подтвердить вариант сборки, расположение валов, присоединительные размеры, направление вращения, передаточное число, фактическую нагрузку, режим работы и требования к смазке.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Ч-125-63-52-КЦ?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, передаточное число, вариант сборки, размеры валов, крепление и описание нагрузки. Инженер проверит совместимость по габаритам и моменту.'
					),
					array(
						'question' => 'Чем Ч-125 отличается от Ч-100 или Ч-80?',
						'answer' => 'Основное отличие — межосевое расстояние и допустимая нагрузка. Ч-125 крупнее типоразмеров Ч-100 и Ч-80, поэтому подбирается под больший момент, другие габариты и посадочные размеры.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена зависит от наличия, производителя, комплектации, варианта сборки, требований к валам и партии. Срок поставки и гарантия подтверждаются после технической сверки.'
					)
				);
			} elseif ($product_id == 2116) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение Ч-100-31,5-52-2?',
						'answer' => 'Ч — серия червячных редукторов, 100 — межосевое расстояние 100 мм, 31,5 — передаточное число, 52-2 — вариант сборки и исполнения. Эти данные нужны для точного подбора.'
					),
					array(
						'question' => 'К какой категории относится редуктор Ч-100-31,5-52-2?',
						'answer' => 'Карточка относится к ветке «Редукторы > Червячные редукторы > Редукторы Ч > Ч-100». Это правильная подкатегория для червячных редукторов с межосевым расстоянием 100 мм.'
					),
					array(
						'question' => 'Где применяется редуктор Ч-100-31,5-52-2?',
						'answer' => 'Редуктор применяют в приводах транспортеров, станков, подъемных механизмов, дозаторов, мешалок и другого оборудования, где требуется снизить обороты и увеличить крутящий момент.'
					),
					array(
						'question' => 'Что проверить перед заказом исполнения 52-2?',
						'answer' => 'Нужно подтвердить вариант сборки, расположение валов, присоединительные размеры, направление вращения, передаточное число, нагрузку, режим работы и монтажное положение.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Ч-100-31,5-52-2?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, передаточное число, вариант сборки, размеры валов, крепление и описание нагрузки. Инженер проверит совместимость.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от передаточного числа, варианта сборки, исполнения валов, производителя, наличия, партии и доставки по Казахстану. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 400101742) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение Ч-100-15-52?',
						'answer' => 'Ч — серия червячных редукторов, 100 — межосевое расстояние 100 мм, 15 — передаточное число i=15, 52 — вариант сборки. Эти параметры нужны для точного подбора и замены редуктора.'
					),
					array(
						'question' => 'К какой категории относится редуктор Ч-100?',
						'answer' => 'Карточка относится к ветке Редукторы > Червячные редукторы > Редукторы Ч > Ч-100. Это правильная подкатегория для типоразмера с межосевым расстоянием 100 мм.'
					),
					array(
						'question' => 'Где применяется редуктор Ч-100-15-52?',
						'answer' => 'Редуктор применяют в приводах промышленного оборудования, станков, транспортеров, подъемных механизмов и других машин, где требуется снизить обороты и увеличить крутящий момент.'
					),
					array(
						'question' => 'Что проверить перед заказом исполнения 52?',
						'answer' => 'Нужно подтвердить вариант сборки, расположение валов, габаритные и присоединительные размеры, направление вращения, передаточное число, нагрузку, режим работы и требования к смазке.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Ч-100-15-52?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, передаточное число, вариант сборки, размеры валов, крепление и описание нагрузки. Инженер проверит совместимость по посадочным размерам.'
					),
					array(
						'question' => 'Чем Ч-100 отличается от Ч-125?',
						'answer' => 'Главное отличие — межосевое расстояние и допустимая нагрузка. Ч-100 компактнее Ч-125 и подбирается под свои габариты, момент, посадочные размеры и условия работы.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от наличия, производителя, варианта сборки, требований к валам и партии. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 400101810) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение NMRV-S063?',
						'answer' => 'NMRV-S — исполнение червячного мотор-редуктора серии NMRV, 063 — типоразмер редуктора. Для точного заказа дополнительно проверяют передаточное число, мощность двигателя, фланец, вал и монтажное положение.'
					),
					array(
						'question' => 'К какой категории относится NMRV-S063?',
						'answer' => 'Карточка относится к ветке Редукторы > Червячные редукторы > Редукторы NMRV > NMRV 063. Это правильная категория для типоразмера 063.'
					),
					array(
						'question' => 'Для каких задач подходит мотор-редуктор NMRV-S063?',
						'answer' => 'NMRV-S063 применяют в компактных приводах конвейеров, дозаторов, упаковочного оборудования, небольших транспортеров и технологических узлов, где нужна угловая червячная передача.'
					),
					array(
						'question' => 'Что проверить перед заказом NMRV-S063?',
						'answer' => 'Нужно подтвердить передаточное число, мощность и обороты двигателя, диаметр выходного вала, фланец, монтажное положение, сторону выхода вала, напряжение двигателя и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог NMRV-S063?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, типоразмер, передаточное число, мощность двигателя, размеры вала и фланца. Инженер проверит совместимость по посадочным размерам и нагрузке.'
					),
					array(
						'question' => 'Чем NMRV-S063 отличается от NMRV 063?',
						'answer' => 'Различия зависят от производителя и конкретного исполнения корпуса. При замене важно сверять не только типоразмер 063, но и фланец, вал, монтажное положение, габариты и параметры двигателя.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от наличия, передаточного числа, двигателя, исполнения вала, фланца и партии. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 400102003) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение BLY27-87-7,5 кВт?',
						'answer' => 'BLY — серия циклоидального редуктора, 27 и 87 указывают на исполнение и типоразмер, 7,5 кВт — мощность электродвигателя. Для заказа также проверяют передаточное число, выходной вал, фланец и монтаж.'
					),
					array(
						'question' => 'К какой категории относится редуктор BLY27-87?',
						'answer' => 'Карточка относится к ветке Редукторы > Редукторы циклоидальные. Это правильная категория для BLY/BLD, потому что такие редукторы отличаются от червячных NMRV, цилиндрических и конических серий.'
					),
					array(
						'question' => 'Где применяют редуктор BLY27-87 7,5 кВт?',
						'answer' => 'BLY27-87 используют в приводах конвейеров, смесителей, дозаторов, подъемных узлов и технологических линий, где нужна компактная передача с высоким крутящим моментом и устойчивой работой под нагрузкой.'
					),
					array(
						'question' => 'Что проверить перед заказом BLY27-87?',
						'answer' => 'Нужно подтвердить передаточное число, мощность и обороты двигателя, диаметр и исполнение выходного вала, монтажное положение, габаритные размеры, режим нагрузки и требования к фланцу.'
					),
					array(
						'question' => 'Можно ли подобрать аналог BLY27-87?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, мощность, передаточное число, размеры валов, крепление и описание нагрузки. Инженер сверит совместимость по моменту и посадочным размерам.'
					),
					array(
						'question' => 'Чем BLY отличается от BLD?',
						'answer' => 'BLY обычно используют для горизонтальной компоновки, BLD — для вертикальной. При замене важно сверять не только серию, но и фактические размеры, вал, фланец, направление монтажа и передаточное число.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от наличия, мощности, передаточного числа, монтажного исполнения, партии и требований к документации. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 400102004) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение BLD4-87?',
						'answer' => 'BLD — серия циклоидального редуктора, 4 и 87 указывают на исполнение и типоразмер. Для точного заказа дополнительно проверяют мощность двигателя, передаточное число, вал, фланец и монтаж.'
					),
					array(
						'question' => 'К какой категории относится редуктор BLD4-87?',
						'answer' => 'Карточка относится к ветке Редукторы > Редукторы циклоидальные. Так карточка попадает в релевантную посадочную страницу, а не остается в общей категории мотор-редукторов.'
					),
					array(
						'question' => 'Для каких задач подходит BLD4-87?',
						'answer' => 'BLD4-87 применяют в промышленных приводах, конвейерах, мешалках, дозаторах и других механизмах, где нужен компактный циклоидальный редуктор с высоким крутящим моментом.'
					),
					array(
						'question' => 'Что проверить перед заказом BLD4-87?',
						'answer' => 'Нужно подтвердить передаточное число, мощность двигателя, монтажное положение, диаметр выходного вала, присоединительные размеры, направление вращения и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог BLD4-87?',
						'answer' => 'Да. Пришлите фото шильдика, модель, размеры валов, крепление, требуемые обороты и описание нагрузки. Fortune PROM подберет совместимый редуктор или замену по посадочным размерам.'
					),
					array(
						'question' => 'Чем BLD отличается от BLY?',
						'answer' => 'BLD обычно относится к вертикальной компоновке, BLY — к горизонтальной. При подборе аналога это влияет на монтаж, расположение двигателя, габариты и удобство установки в приводе.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от наличия, исполнения, мощности, передаточного числа, вала, фланца и партии. Менеджер подтверждает стоимость после сверки параметров под ваш привод.'
					)
				);
			} elseif ($product_id == 400101931) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение Ц2У-250-31,5-12-КК?',
						'answer' => 'Ц2У — серия двухступенчатого цилиндрического редуктора, 250 — межосевое расстояние в мм, 31,5 — передаточное число, 12 — вариант сборки, КК — конические концы быстроходного и тихоходного валов.'
					),
					array(
						'question' => 'К какой категории относится редуктор Ц2У-250?',
						'answer' => 'Карточка относится к ветке Редукторы > Цилиндрические редукторы > Редукторы Ц2У > Ц2У-250. Это правильная посадочная страница для типоразмера 250.'
					),
					array(
						'question' => 'Где применяется Ц2У-250-31,5-12-КК?',
						'answer' => 'Редуктор применяют в приводах конвейеров, дробилок, смесителей, мельниц, подъемных механизмов и другого промышленного оборудования, где требуется снизить обороты и увеличить крутящий момент.'
					),
					array(
						'question' => 'Что проверить перед заказом исполнения 12-КК?',
						'answer' => 'Нужно подтвердить вариант сборки 12, тип концов валов КК, передаточное число 31,5, монтажное положение, габаритные и присоединительные размеры, нагрузку и режим работы механизма.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Ц2У-250-31,5-12-КК?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, чертеж или размеры валов, передаточное число, вариант сборки, монтажное положение и описание нагрузки. Инженер сверит совместимость по посадочным размерам.'
					),
					array(
						'question' => 'Чем Ц2У отличается от 1Ц2У?',
						'answer' => 'Ц2У — двухступенчатая цилиндрическая серия для большего диапазона передаточных чисел. 1Ц2У — одноступенчатая серия. При замене важно не смешивать серии без проверки габаритов, валов и расчетного момента.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от наличия, исполнения валов, варианта сборки, производителя, партии и требований к паспорту или сертификату. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif (isset($ts2u_reducer_seo[(int)$product_id])) {
				$ts2u = $ts2u_reducer_seo[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'К какой категории относится ' . $ts2u['model'] . '?',
						'answer' => $ts2u['model'] . ' размещается в ветке «' . $ts2u['category'] . '». Такая структура показывает серию Ц2У, типоразмер и точное место карточки в каталоге цилиндрических редукторов.'
					),
					array(
						'question' => 'Что означает обозначение ' . $ts2u['model'] . '?',
						'answer' => 'Ц2У — серия цилиндрических двухступенчатых редукторов, ' . $ts2u['size'] . ' — типоразмер. Остальные части обозначения уточняют передаточное число, вариант сборки, исполнение валов и климатическое исполнение.'
					),
					array(
						'question' => 'Где применяется редуктор ' . $ts2u['model'] . '?',
						'answer' => 'Редукторы Ц2У применяют в приводах конвейеров, транспортеров, дробилок, смесителей, подъемников, питателей, станков и другого промышленного оборудования, где требуется снижение оборотов и высокий момент.'
					),
					array(
						'question' => 'Что проверить перед заказом ' . $ts2u['model'] . '?',
						'answer' => 'Перед заказом нужно подтвердить типоразмер, передаточное число, вариант сборки, исполнение входного и выходного валов, монтажное положение, габаритные размеры, посадочные размеры и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог редуктора ' . $ts2u['model'] . '?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, передаточное число, схему валов, чертеж или размеры посадочных мест. Инженер проверит совместимость по моменту, валам и габаритам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от типоразмера, передаточного числа, исполнения валов, производителя, наличия, партии, паспорта, сертификата и доставки по Казахстану. Fortune PROM подтверждает стоимость после проверки параметров.'
					)
				);
			} elseif (isset($crane_reducer_seo[(int)$product_id])) {
				$crane_reducer = $crane_reducer_seo[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'К какой категории относится ' . $crane_reducer['model'] . '?',
						'answer' => $crane_reducer['model'] . ' размещается в ветке «' . $crane_reducer['category'] . '». Это точнее, чем общая категория крановых редукторов, потому что пользователь видит серию и типоразмер.'
					),
					array(
						'question' => 'Где применяется редуктор ' . $crane_reducer['model'] . '?',
						'answer' => 'Крановые редукторы ' . $crane_reducer['series'] . ' применяют в приводах мостовых кранов, грузовых тележек, механизмов подъема, перемещения, поворота и другого подъемно-транспортного оборудования.'
					),
					array(
						'question' => 'Что проверить перед заказом ' . $crane_reducer['model'] . '?',
						'answer' => 'Перед заказом нужно подтвердить типоразмер, передаточное число, схему сборки, исполнение валов, монтажное положение, межосевые и присоединительные размеры, режим нагрузки и совместимость с существующим приводом.'
					),
					array(
						'question' => 'Можно ли подобрать аналог редуктора ' . $crane_reducer['model'] . '?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, чертеж, размеры валов, передаточное число, схему установки и описание механизма. Инженер сверит совместимость по моменту и посадочным размерам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от серии, типоразмера, передаточного числа, исполнения валов, производителя, наличия и доставки по Казахстану. Fortune PROM подтверждает стоимость после технической проверки.'
					)
				);
			} elseif ($product_id == 2113) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое редуктор ЦДН-630?',
						'answer' => 'ЦДН-630 — тяжелый цилиндрический двухступенчатый редуктор с межосевым расстоянием 630 мм для приводов с высокой нагрузкой и продолжительным режимом работы.'
					),
					array(
						'question' => 'К какой категории относится ЦДН-630?',
						'answer' => 'Карточка относится к ветке «Редукторы > Цилиндрические редукторы > Редукторы ЦДН». Это правильная категория для тяжелых цилиндрических редукторов серии ЦДН.'
					),
					array(
						'question' => 'Где применяют редуктор ЦДН-630?',
						'answer' => 'ЦДН-630 применяют в приводах тяжело нагруженных ленточных конвейеров, дробилок, мельниц, питателей, подъемных механизмов и другого промышленного оборудования.'
					),
					array(
						'question' => 'Что проверить перед заказом ЦДН-630?',
						'answer' => 'Нужно подтвердить передаточное число, вариант сборки, исполнение концов валов, монтажное положение, направление вращения, габариты, момент и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог ЦДН-630?',
						'answer' => 'Да. Аналог подбирают по межосевому расстоянию 630 мм, передаточному числу, моменту, валам, варианту сборки, габаритам и посадочным размерам оборудования.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от исполнения валов, передаточного числа, производителя, наличия, партии, паспорта, сертификата и доставки по Казахстану. Стоимость подтверждается после технической проверки.'
					)
				);
			} elseif ($product_id == 400102062) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение ЦДН-710-31,5-21-ЦЦ?',
						'answer' => 'ЦДН — серия тяжелого цилиндрического редуктора, 710 — межосевое расстояние, 31,5 — передаточное число, 21 — номинальный момент в кН·м, ЦЦ — цилиндрические концы входного и выходного валов.'
					),
					array(
						'question' => 'К какой категории относится ЦДН-710?',
						'answer' => 'Карточка относится к ветке Редукторы > Цилиндрические редукторы > Редукторы ЦДН. Это правильная категория для тяжелых цилиндрических редукторов серии ЦДН.'
					),
					array(
						'question' => 'Где применяется редуктор ЦДН-710-31,5-21-ЦЦ?',
						'answer' => 'Редуктор применяют в приводах тяжело нагруженных ленточных конвейеров, дробилок, мельниц, питателей, подъемных механизмов и других машин, где требуется высокий крутящий момент.'
					),
					array(
						'question' => 'Что проверить перед заказом исполнения ЦЦ?',
						'answer' => 'Нужно подтвердить тип концов валов ЦЦ, передаточное число 31,5, номинальный момент 21 кН·м, вариант сборки, монтажное положение, габариты, направление вращения и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог ЦДН-710?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, чертеж или размеры валов, передаточное число, вариант исполнения валов, монтажное положение и описание нагрузки механизма.'
					),
					array(
						'question' => 'Чем ЦДН отличается от Ц2У?',
						'answer' => 'ЦДН относится к тяжелым цилиндрическим редукторам для более высоких нагрузок и крупных межосевых расстояний. Ц2У обычно применяют в менее тяжелых приводах. При замене обязательно сверяют момент, габариты и валы.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от наличия, исполнения валов, производителя, партии, требований к паспорту и доставке. Стоимость подтверждается после технической проверки параметров.'
					)
				);
			} elseif ($product_id == 400102063) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение 1Ц2У-100-31,5-12-КК-У2?',
						'answer' => '1Ц2У — серия узкого цилиндрического редуктора, 100 — типоразмер с межосевым расстоянием 100 мм, 31,5 — передаточное число, 12 — вариант сборки, КК — конические концы валов, У2 — климатическое исполнение.'
					),
					array(
						'question' => 'К какой категории относится 1Ц2У-100?',
						'answer' => 'Карточка относится к ветке Редукторы > Цилиндрические редукторы > Редукторы 1Ц2У > 1Ц2У-100. Это правильная посадочная страница для типоразмера 100.'
					),
					array(
						'question' => 'Где применяется редуктор 1Ц2У-100-31,5-12-КК-У2?',
						'answer' => 'Редуктор используют в приводах конвейеров, транспортеров, станков, насосов, вспомогательных механизмов и технологического оборудования, где нужны снижение оборотов и стабильный момент.'
					),
					array(
						'question' => 'Что проверить перед заказом исполнения 12-КК-У2?',
						'answer' => 'Нужно подтвердить вариант сборки 12, тип концов валов КК, передаточное число 31,5, климатическое исполнение У2, монтажное положение, габариты и присоединительные размеры.'
					),
					array(
						'question' => 'Можно ли подобрать аналог 1Ц2У-100?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, чертеж или размеры валов, передаточное число, вариант сборки и условия работы. Инженер сверит совместимость по посадочным размерам.'
					),
					array(
						'question' => 'Чем 1Ц2У-100 отличается от 1Ц2У-125?',
						'answer' => 'Главное отличие — типоразмер и межосевое расстояние. 1Ц2У-100 компактнее и рассчитан на меньшие нагрузки, поэтому при замене нельзя выбирать соседний типоразмер без проверки момента, валов и габаритов.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от наличия, исполнения валов, варианта сборки, партии, производителя и требований к документации. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif (isset($one_ts2u_reducer_seo[(int)$product_id])) {
				$one_ts2u = $one_ts2u_reducer_seo[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'К какой категории относится ' . $one_ts2u['model'] . '?',
						'answer' => $one_ts2u['model'] . ' размещается в ветке «' . $one_ts2u['category'] . '». Такая структура показывает серию 1Ц2У, точный типоразмер и место карточки в каталоге редукторов.'
					),
					array(
						'question' => 'Что означает обозначение ' . $one_ts2u['model'] . '?',
						'answer' => '1Ц2У — серия цилиндрических двухступенчатых горизонтальных редукторов, ' . $one_ts2u['size'] . ' — типоразмер. Остальные части обозначения уточняют передаточное число, вариант сборки, исполнение валов и климатическое исполнение.'
					),
					array(
						'question' => 'Где применяется редуктор ' . $one_ts2u['model'] . '?',
						'answer' => 'Редукторы 1Ц2У применяют в приводах конвейеров, транспортеров, подъемников, дробилок, смесителей, станков, насосных агрегатов и другого промышленного оборудования, где нужны снижение оборотов и стабильный выходной момент.'
					),
					array(
						'question' => 'Что проверить перед заказом ' . $one_ts2u['model'] . '?',
						'answer' => 'Перед заказом нужно подтвердить типоразмер, передаточное число, вариант сборки, исполнение входного и выходного валов, монтажное положение, габаритные размеры, посадочные размеры и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог редуктора ' . $one_ts2u['model'] . '?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, передаточное число, схему валов, чертеж или размеры посадочных мест. Инженер сверит совместимость по моменту, валам и габаритам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от типоразмера, передаточного числа, исполнения валов, производителя, наличия, партии, паспорта, сертификата и доставки по Казахстану. Fortune PROM подтверждает стоимость после проверки параметров.'
					)
				);
			} elseif ($product_id == 400102066) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение KA77-16,6-87-11,0-B8?',
						'answer' => 'KA77 — серия и типоразмер коническо-цилиндрического мотор-редуктора, 16,6 — передаточное число, 87 об/мин — выходная скорость, 11,0 — мощность двигателя в кВт, B8 — монтажное положение.'
					),
					array(
						'question' => 'К какой категории относится KA77?',
						'answer' => 'Карточка относится к ветке Редукторы > Мотор-редукторы > Конические мотор-редукторы. Это правильная категория для угловых мотор-редукторов KA с реактивной тягой.'
					),
					array(
						'question' => 'Для чего нужна реактивная тяга?',
						'answer' => 'Реактивная тяга фиксирует корпус насадного мотор-редуктора и воспринимает реактивный момент. Ее наличие важно учитывать при замене, чтобы сохранить монтажную схему привода.'
					),
					array(
						'question' => 'Где применяется мотор-редуктор KA77 11 кВт?',
						'answer' => 'Такие мотор-редукторы применяют в приводах конвейеров, транспортеров, смесителей, дозаторов и технологических линий, где нужен угловой привод с выходной скоростью около 87 об/мин.'
					),
					array(
						'question' => 'Можно ли подобрать аналог KA77?',
						'answer' => 'Да. Для подбора аналога нужны фото шильдика, мощность, передаточное число, выходные обороты, тип вала, монтажное положение, размеры реактивной тяги и описание нагрузки.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от наличия, производителя, исполнения вала, фланца, реактивной тяги, двигателя и партии. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 400102067) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение KA77-16,6-87-7,5-B8 с тормозом?',
						'answer' => 'KA77 — серия и типоразмер коническо-цилиндрического мотор-редуктора, 16,6 — передаточное число, 87 об/мин — выходная скорость, 7,5 — мощность двигателя в кВт, B8 — монтажное положение, тормоз — удерживающий или рабочий тормоз двигателя.'
					),
					array(
						'question' => 'К какой категории относится эта карточка?',
						'answer' => 'Карточка относится к ветке Редукторы > Мотор-редукторы > Конические мотор-редукторы, потому что KA77 — угловой коническо-цилиндрический мотор-редуктор.'
					),
					array(
						'question' => 'Когда нужен мотор-редуктор с тормозом?',
						'answer' => 'Тормоз нужен, когда механизм должен удерживать положение, быстро останавливаться или безопасно работать при остановках. Перед заменой проверяют напряжение тормоза и схему управления.'
					),
					array(
						'question' => 'Что проверить перед заказом KA77 7,5 кВт?',
						'answer' => 'Нужно подтвердить мощность, передаточное число, выходные обороты, тип тормоза и напряжение, вал, монтажное положение B8, реактивную тягу, габариты и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог с тормозом?',
						'answer' => 'Да. Пришлите фото шильдика, параметры тормоза, передаточное число, размеры вала и крепления, монтажное положение и описание нагрузки. Инженер проверит совместимость.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена зависит от наличия, комплектации тормоза, двигателя, исполнения вала, реактивной тяги и партии поставки. Менеджер подтверждает стоимость после проверки параметров.'
					)
				);
			} elseif ($product_id == 400101790) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LC4?',
						'answer' => 'Bonfiglioli A — серия коническо-цилиндрических мотор-редукторов, A 50 2 — типоразмер и двухступенчатое исполнение, UH50 — полый выходной вал 50 мм, 16.6 — передаточное число, S4 — исполнение, B8 — монтажное положение, M4LC4 — 4-полюсный двигатель.'
					),
					array(
						'question' => 'Для какого оборудования подходит эта комплектация?',
						'answer' => 'Мотор-редуктор Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LC4 применяют в приводах конвейеров, транспортеров, смесителей, дозаторов и технологических линий, где нужен полый вал, угловая компоновка 90°, мощность 11 кВт и выходная скорость около 87 об/мин.'
					),
					array(
						'question' => 'Какой выходной момент у Bonfiglioli A 50 2 с двигателем 11 кВт?',
						'answer' => 'Для этой комплектации указан выходной крутящий момент 1 137 Н·м. При подборе нужно проверить сервис-фактор, радиальную нагрузку, режим пусков, длительность работы и фактическую нагрузку механизма.'
					),
					array(
						'question' => 'Что важно проверить по полому валу UH50?',
						'answer' => 'Нужно подтвердить диаметр полого вала 50 мм, шпоночный паз, сторону установки, способ фиксации на валу механизма, наличие реактивного рычага и посадочные размеры оборудования.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Bonfiglioli A 50 2 UH50?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, передаточное число, мощность двигателя, выходные обороты, диаметр полого вала, монтажное положение, фланец, габариты и описание нагрузки. Инженер проверит совместимость по моменту и посадочным размерам.'
					),
					array(
						'question' => 'Что означает монтажное положение B8?',
						'answer' => 'B8 указывает на конкретное пространственное положение редуктора и двигателя. Его нужно сохранять при замене, потому что от положения зависят смазка, сапун, пробки, уровень масла и удобство установки в приводе.'
					),
					array(
						'question' => 'Как формируется цена и срок поставки?',
						'answer' => 'Цена зависит от наличия, партии поставки, комплектации двигателя, исполнения вала, фланцев, тормоза или дополнительных опций. Срок, гарантия и доставка по Казахстану подтверждаются после сверки технических параметров.'
					)
				);
			} elseif ($product_id == 400101792) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LA4 FD R?',
						'answer' => 'Bonfiglioli A — серия коническо-цилиндрических мотор-редукторов, A 50 2 — типоразмер и двухступенчатое исполнение, UH50 — полый выходной вал 50 мм, 16.6 — передаточное число, B8 — монтажное положение, M4LA4 — 4-полюсный двигатель, FD R — исполнение комплектации.'
					),
					array(
						'question' => 'Для каких приводов подходит эта комплектация 7,5 кВт?',
						'answer' => 'Мотор-редуктор Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LA4 FD R используют в конвейерах, транспортерах, смесителях, дозаторах и технологических линиях, где нужен полый вал 50 мм, угловая компоновка 90° и выходная скорость около 87 об/мин.'
					),
					array(
						'question' => 'Какой выходной момент у версии с двигателем 7,5 кВт?',
						'answer' => 'Для этой комплектации ориентировочный выходной момент составляет около 775 Н·м. При подборе нужно учитывать сервис-фактор, радиальную нагрузку, число пусков, режим работы и фактическую нагрузку механизма.'
					),
					array(
						'question' => 'Чем эта версия отличается от Bonfiglioli A 50 2 на 11 кВт?',
						'answer' => 'Ключевое отличие — мощность двигателя и расчетный выходной момент. При одинаковом типоразмере A 50 2, передаточном числе i=16,6 и выходной скорости около 87 об/мин версия 7,5 кВт рассчитана на меньшую нагрузку, чем вариант 11 кВт.'
					),
					array(
						'question' => 'Что проверить по полому валу UH50?',
						'answer' => 'Нужно подтвердить диаметр 50 мм, шпоночный паз, сторону установки, реактивный рычаг, способ фиксации и посадочные размеры оборудования. Эти параметры критичны при замене без переделки привода.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Bonfiglioli A 50 2 UH50?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, передаточное число, мощность двигателя, выходные обороты, диаметр полого вала, монтажное положение, габариты и описание нагрузки.'
					),
					array(
						'question' => 'Как подтверждаются цена и срок поставки?',
						'answer' => 'Цена и срок зависят от наличия, комплектации, двигателя, исполнения вала, фланцев, тормоза и партии поставки. Менеджер подтверждает стоимость после проверки технических параметров.'
					)
				);
			} elseif ($product_id == 400102134) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LLA4 FD R?',
						'answer' => 'Bonfiglioli A — серия коническо-цилиндрических мотор-редукторов, A 50 2 — типоразмер и двухступенчатое исполнение, UH50 — полый выходной вал 50 мм, 16.6 — передаточное число, S4 B8 — исполнение и монтажное положение, M4LLA4 FD R — вариант 4-полюсного двигателя и комплектации.'
					),
					array(
						'question' => 'Для каких приводов подходит эта версия 7,5 кВт?',
						'answer' => 'Мотор-редуктор Bonfiglioli A 50 2 UH50 16.6 S4 B8 M4LLA4 FD R применяют в конвейерах, транспортерах, смесителях, дозаторах, фасовочном и технологическом оборудовании, где требуется угловая компоновка 90°, полый вал 50 мм и выходная скорость около 87 об/мин.'
					),
					array(
						'question' => 'Какой момент и передаточное число у этой комплектации?',
						'answer' => 'Для этой модели указано передаточное число i=16,6, мощность двигателя 7,5 кВт, частота выходного вала около 87 об/мин и ориентировочный выходной момент около 775 Н·м. Итоговую пригодность проверяют по режиму нагрузки и сервис-фактору.'
					),
					array(
						'question' => 'Чем M4LLA4 отличается от похожих исполнений M4LA4?',
						'answer' => 'M4LLA4 и M4LA4 относятся к вариантам комплектации двигателя. При замене важно сверить не только мощность, но и габарит двигателя, присоединение, монтажное положение, шильдик, электрические параметры и фактическую совместимость с редуктором.'
					),
					array(
						'question' => 'Что проверить по полому валу UH50?',
						'answer' => 'Нужно подтвердить диаметр полого вала 50 мм, шпоночный паз, сторону установки, способ фиксации на валу механизма, реактивный рычаг и посадочные размеры оборудования. Это критично для замены без доработки привода.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Bonfiglioli A 50 2 UH50?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, передаточное число, мощность двигателя, выходные обороты, диаметр полого вала, монтажное положение, габариты и описание нагрузки. Инженер проверит совместимость по моменту и размерам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от наличия, двигателя, исполнения вала, фланца, тормоза, партии и требований к документации. Менеджер подтверждает стоимость после сверки технических параметров под ваш привод.'
					)
				);
			} elseif ($product_id == 400102150) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение FAF-S57-24.96-56-1,1?',
						'answer' => 'FAF-S57 указывает на серию и типоразмер плоского цилиндрического мотор-редуктора, 24.96 — передаточное число, 56 — ориентировочная частота выходного вала в об/мин при стандартном двигателе, 1,1 — мощность электродвигателя в кВт.'
					),
					array(
						'question' => 'К какой категории относится мотор-редуктор FAF-S57?',
						'answer' => 'Эта модель относится к плоским цилиндрическим мотор-редукторам с параллельным расположением осей валов. Такие приводы выбирают, когда нужна компактная установка вдоль механизма и надежная цилиндрическая передача.'
					),
					array(
						'question' => 'Для какого оборудования подходит FAF-S57-24.96-56-1,1?',
						'answer' => 'Мотор-редуктор подходит для конвейеров, транспортеров, дозаторов, упаковочного и технологического оборудования, где требуется мощность 1,1 кВт, передаточное число i=24,96 и выходная скорость около 56 об/мин.'
					),
					array(
						'question' => 'Что проверить перед заказом FAF-S57?',
						'answer' => 'Нужно сверить передаточное число, выходные обороты, мощность двигателя, монтажное исполнение, вал, фланец, присоединительные размеры, положение установки и фактическую нагрузку механизма.'
					),
					array(
						'question' => 'Можно ли подобрать аналог FAF-S57-24.96-56-1,1?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, мощность двигателя, выходные обороты, передаточное число, размеры валов, фланец, крепление и описание режима работы оборудования.'
					),
					array(
						'question' => 'Почему важно не путать 56 с фланцем?',
						'answer' => 'В этой маркировке 56 логически соответствует выходной скорости около 56 об/мин: при передаточном числе 24,96 стандартные обороты двигателя снижаются примерно до этого значения. Фланец и присоединительные размеры нужно проверять отдельно.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от наличия, комплектации двигателя, монтажного исполнения, фланца, вала и требований к документации. Стоимость подтверждается после сверки параметров под ваш привод.'
					)
				);
			} elseif ($product_id == 400101999) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение 4МЦ2С-125-35,5-11?',
						'answer' => '4МЦ2С — серия цилиндрических соосных мотор-редукторов, 125 — типоразмер корпуса, 35,5 — передаточное число, 11 — мощность электродвигателя в кВт. Такая маркировка помогает быстро проверить совместимость по моменту, оборотам и габаритам.'
					),
					array(
						'question' => 'Для какого оборудования подходит мотор-редуктор 4МЦ2С-125?',
						'answer' => 'Мотор-редуктор 4МЦ2С-125 применяют в промышленных приводах конвейеров, транспортеров, смесителей, дозаторов, подъемных механизмов и технологических линий, где нужен соосный цилиндрический привод с высоким выходным моментом.'
					),
					array(
						'question' => 'Какое передаточное число и крутящий момент у этой комплектации?',
						'answer' => 'Для модели 4МЦ2С-125-35,5-11 указано передаточное число i=35,5, мощность двигателя 11 кВт и выходной крутящий момент 1340 Н·м. При подборе нужно сверить фактическую нагрузку, режим пусков и требуемые обороты.'
					),
					array(
						'question' => 'Что проверить перед заказом 4МЦ2С-125-35,5-11?',
						'answer' => 'Перед заказом важно подтвердить передаточное число, мощность, выходной момент, частоту вращения, исполнение валов, способ крепления, монтажное положение, габаритные и присоединительные размеры, а также условия работы оборудования.'
					),
					array(
						'question' => 'Можно ли подобрать аналог старого 4МЦ2С?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, мощность двигателя, выходные обороты, передаточное число, размеры валов, крепление и описание нагрузки. Инженер проверит замену по моменту и посадочным размерам.'
					),
					array(
						'question' => 'Чем цилиндрический соосный мотор-редуктор отличается от червячного?',
						'answer' => 'У цилиндрического соосного мотор-редуктора входной и выходной валы расположены по одной оси, а передача обычно имеет более высокий КПД. Червячные редукторы компактны для угловой компоновки, но подбираются по другим ограничениям нагрузки и теплового режима.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от наличия, партии, комплектации двигателя, исполнения валов, монтажного положения и требований к документации. Fortune PROM подтверждает стоимость после проверки параметров под ваш привод.'
					)
				);
			} elseif (in_array((int)$product_id, array(2229, 2231, 2232, 2233, 2234, 2235), true)) {
				$four_mts2s_sizes = array(2229 => '50', 2231 => '80', 2232 => '100', 2233 => '160', 2234 => '125', 2235 => '140');
				$four_mts2s_size = $four_mts2s_sizes[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение 4МЦ2С-' . $four_mts2s_size . '?',
						'answer' => '4МЦ2С — серия цилиндрических соосных мотор-редукторов, ' . $four_mts2s_size . ' — типоразмер корпуса. Передаточное число, выходные обороты, мощность двигателя и момент подбираются под рабочую нагрузку конкретного механизма.'
					),
					array(
						'question' => 'К какой категории относится мотор-редуктор 4МЦ2С-' . $four_mts2s_size . '?',
						'answer' => 'Модель 4МЦ2С-' . $four_mts2s_size . ' относится к цилиндрическим соосным мотор-редукторам. Для правильной навигации и SEO карточка размещается в ветке «Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы».'
					),
					array(
						'question' => 'Для какого оборудования подходит 4МЦ2С-' . $four_mts2s_size . '?',
						'answer' => 'Мотор-редуктор 4МЦ2С-' . $four_mts2s_size . ' используют в приводах конвейеров, транспортеров, дозаторов, смесителей, упаковочного оборудования и технологических линий, где нужна соосная компоновка и стабильный крутящий момент.'
					),
					array(
						'question' => 'Что проверить перед заказом 4МЦ2С-' . $four_mts2s_size . '?',
						'answer' => 'Перед заказом нужно подтвердить выходные обороты, передаточное число, мощность двигателя, крутящий момент, исполнение валов, способ крепления, монтажное положение, габаритные и присоединительные размеры.'
					),
					array(
						'question' => 'Можно ли подобрать аналог 4МЦ2С-' . $four_mts2s_size . '?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, мощность двигателя, требуемые обороты, размеры валов, крепление и описание режима работы. Инженер проверит замену по моменту и посадочным размерам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от исполнения, мощности двигателя, передаточного числа, комплектации, наличия и требований к доставке по Казахстану. Fortune PROM подтверждает стоимость после сверки параметров под ваш привод.'
					)
				);
			} elseif ($product_id == 2230) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение 4МЦ2С-63?',
						'answer' => '4МЦ2С — серия цилиндрических соосных мотор-редукторов, 63 — типоразмер корпуса. Конкретные обороты, передаточное число, мощность двигателя и момент зависят от исполнения и подбираются под рабочую нагрузку механизма.'
					),
					array(
						'question' => 'К какой категории относится мотор-редуктор 4МЦ2С-63?',
						'answer' => '4МЦ2С-63 относится к цилиндрическим соосным мотор-редукторам. Карточка должна находиться в ветке «Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы», а не в планетарных редукторах 3МП.'
					),
					array(
						'question' => 'Для какого оборудования подходит 4МЦ2С-63?',
						'answer' => 'Мотор-редуктор 4МЦ2С-63 применяют в приводах конвейеров, транспортеров, дозаторов, упаковочных машин, смесителей и технологических линий, где нужна соосная компоновка и компактный цилиндрический привод.'
					),
					array(
						'question' => 'Что проверить перед заказом 4МЦ2С-63?',
						'answer' => 'Нужно подтвердить требуемые выходные обороты, передаточное число, мощность двигателя, крутящий момент, исполнение валов, способ крепления, монтажное положение, габаритные и присоединительные размеры.'
					),
					array(
						'question' => 'Можно ли подобрать аналог 4МЦ2С-63?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, мощность, выходные обороты, размеры валов, крепление и описание режима работы. Инженер проверит замену по моменту и посадочным размерам.'
					),
					array(
						'question' => 'Чем 4МЦ2С-63 отличается от 4МЦ2С-125?',
						'answer' => '4МЦ2С-63 — более компактный типоразмер для меньших нагрузок и габаритов. 4МЦ2С-125 применяют в более тяжелых приводах с большей мощностью и выходным моментом, поэтому заменять эти типоразмеры без расчета нельзя.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от исполнения, мощности двигателя, передаточного числа, комплектации, наличия и требований к доставке по Казахстану. Fortune PROM подтверждает стоимость после сверки параметров под ваш привод.'
					)
				);
			} elseif ($product_id == 400102077) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение 4МЦ2С-63-71-1,1-G110?',
						'answer' => '4МЦ2С — серия цилиндрических соосных мотор-редукторов, 63 — типоразмер корпуса, 71 — выходная скорость в об/мин, 1,1 — мощность электродвигателя в кВт, G110 — монтажное исполнение на лапах. Обозначение используют для быстрой проверки совместимости по оборотам, моменту и присоединительным размерам.'
					),
					array(
						'question' => 'К какой категории относится мотор-редуктор 4МЦ2С-63?',
						'answer' => 'Эта модель относится к цилиндрическим соосным мотор-редукторам. Для SEO и навигации карточка должна находиться в ветке «Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы», а не в планетарных редукторах 3МП.'
					),
					array(
						'question' => 'Для какого оборудования подходит 4МЦ2С-63-71-1,1-G110?',
						'answer' => 'Мотор-редуктор применяют в промышленных приводах конвейеров, транспортеров, дозаторов, упаковочного и технологического оборудования, где нужны соосная компоновка, мощность 1,1 кВт, выходная скорость 71 об/мин и момент около 148 Н·м.'
					),
					array(
						'question' => 'Что проверить перед заказом 4МЦ2С-63?',
						'answer' => 'Перед заказом нужно сверить выходные обороты, мощность двигателя, крутящий момент, диаметр выходного вала 28 мм, монтаж G110, габаритные и присоединительные размеры, режим пусков и фактическую нагрузку механизма.'
					),
					array(
						'question' => 'Можно ли подобрать аналог 4МЦ2С-63-71-1,1-G110?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, мощность, выходные обороты, момент, размеры валов, крепление и условия работы. Инженер подберет замену по посадочным размерам и нагрузке.'
					),
					array(
						'question' => 'Чем 4МЦ2С-63 отличается от 4МЦ2С-125?',
						'answer' => '4МЦ2С-63 — более компактный типоразмер для меньшей мощности и момента: в этой комплектации 1,1 кВт и 148 Н·м. 4МЦ2С-125 рассчитан на более тяжелые приводы, например 11 кВт и момент порядка 1340 Н·м.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок поставки зависят от наличия, партии, монтажного исполнения, комплектации двигателя, требований к документам и доставки по Казахстану. Fortune PROM подтверждает стоимость после сверки параметров под ваш привод.'
					)
				);
			} elseif (in_array((int)$product_id, array(2236, 2237, 2238, 2239, 2240, 2241, 2242), true)) {
				$planetary_3mp_sizes = array(2236 => '50', 2237 => '63', 2238 => '80', 2239 => '100', 2240 => '25', 2241 => '125', 2242 => '40');
				$planetary_3mp_size = $planetary_3mp_sizes[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение 3МП-' . $planetary_3mp_size . ' (1МПз-' . $planetary_3mp_size . ')?',
						'answer' => '3МП — серия планетарных мотор-редукторов, ' . $planetary_3mp_size . ' — типоразмер корпуса. Обозначение 1МПз-' . $planetary_3mp_size . ' используют как близкое или альтернативное название этой планетарной серии.'
					),
					array(
						'question' => 'К какой категории относится 3МП-' . $planetary_3mp_size . '?',
						'answer' => 'Модель 3МП-' . $planetary_3mp_size . ' относится к планетарным мотор-редукторам. Карточка размещается в цепочке «Редукторы > Планетарные редукторы > Редукторы 3МП > 3МП-' . $planetary_3mp_size . '», чтобы пользователь и поисковик видели точный типоразмер.'
					),
					array(
						'question' => 'Для какого оборудования подходит мотор-редуктор 3МП-' . $planetary_3mp_size . '?',
						'answer' => 'Планетарные мотор-редукторы 3МП применяют в приводах конвейеров, лебедок, дозаторов, смесителей, элеваторов, мешалок и производственных линий, где нужен компактный соосный привод с высоким крутящим моментом.'
					),
					array(
						'question' => 'Что проверить перед заказом 3МП-' . $planetary_3mp_size . '?',
						'answer' => 'Нужно подтвердить требуемые выходные обороты, передаточное число, мощность двигателя, крутящий момент, исполнение входного и выходного валов, способ крепления, монтажное положение и габариты.'
					),
					array(
						'question' => 'Можно ли подобрать аналог 3МП-' . $planetary_3mp_size . '?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, мощность двигателя, выходные обороты, размеры валов, крепление и описание нагрузки. Инженер проверит совместимость по моменту и посадочным размерам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от типоразмера, передаточного числа, двигателя, комплектации, наличия и доставки по Казахстану. Fortune PROM подтверждает стоимость после сверки параметров под ваш привод.'
					)
				);
			} elseif (in_array((int)$product_id, array(2132, 2133, 2134, 2135, 2136, 2137, 2138, 2139, 2140), true)) {
				$rc_sizes = array(2132 => '17', 2133 => '37', 2134 => '47', 2135 => '57', 2136 => '67', 2137 => '147', 2138 => '137', 2139 => '107', 2140 => '97');
				$rc_size = $rc_sizes[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение RC' . $rc_size . ' / RCF' . $rc_size . '?',
						'answer' => 'RC' . $rc_size . ' и RCF' . $rc_size . ' — варианты мотор-редуктора одного типоразмера. RC и RCF подбирают по монтажному исполнению, передаточному числу, мощности двигателя, выходному моменту и присоединительным размерам.'
					),
					array(
						'question' => 'К какой категории относится мотор-редуктор RC' . $rc_size . '?',
						'answer' => 'Мотор-редуктор RC' . $rc_size . ' / RCF' . $rc_size . ' относится к ветке «Редукторы > Мотор-редукторы > Мотор-редукторы RC». Такая структура помогает быстро найти серию и сравнить соседние типоразмеры.'
					),
					array(
						'question' => 'Где применяют мотор-редукторы RC и RCF?',
						'answer' => 'Их используют в приводах конвейеров, транспортеров, дозаторов, насосов, смесителей, мешалок, упаковочного оборудования и технологических линий, где нужен надежный цилиндрический мотор-редуктор.'
					),
					array(
						'question' => 'Что проверить перед заказом RC' . $rc_size . ' / RCF' . $rc_size . '?',
						'answer' => 'Перед заказом нужно подтвердить выходные обороты, передаточное число, мощность двигателя, крутящий момент, исполнение валов, фланец или лапы, монтажное положение и присоединительные размеры.'
					),
					array(
						'question' => 'Можно ли подобрать аналог RC' . $rc_size . ' / RCF' . $rc_size . '?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, мощность двигателя, выходные обороты, размеры валов, фланца или лап и описание режима работы. Инженер проверит замену по нагрузке и посадочным размерам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от типоразмера, передаточного числа, двигателя, монтажного исполнения, комплектации, наличия и доставки по Казахстану. Fortune PROM подтверждает стоимость после сверки параметров.'
					)
				);
			} elseif (in_array((int)$product_id, array(2143, 2144, 2145, 2146, 2147, 2148, 2149, 2150, 2151), true)) {
				$kts_series = array(2143 => 'КЦ1', 2144 => 'КЦ1', 2145 => 'КЦ1', 2146 => 'КЦ1', 2147 => 'КЦ1', 2148 => 'КЦ2', 2149 => 'КЦ2', 2150 => 'КЦ2', 2151 => 'КЦ2');
				$kts_sizes = array(2143 => '200', 2144 => '250', 2145 => '300', 2146 => '400', 2147 => '500', 2148 => '1000', 2149 => '1300', 2150 => '500', 2151 => '750');
				$kts_series_name = $kts_series[(int)$product_id];
				$kts_size = $kts_sizes[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение ' . $kts_series_name . '-' . $kts_size . '?',
						'answer' => $kts_series_name . ' — серия коническо-цилиндрических редукторов, ' . $kts_size . ' — типоразмер корпуса. Для точного подбора дополнительно сверяют передаточное число, схему валов, момент, крепление и монтажные размеры.'
					),
					array(
						'question' => 'К какой категории относится редуктор ' . $kts_series_name . '-' . $kts_size . '?',
						'answer' => 'Редуктор ' . $kts_series_name . '-' . $kts_size . ' размещается в ветке «Редукторы > Коническо-цилиндрические редукторы > Редукторы ' . $kts_series_name . ' > ' . $kts_series_name . '-' . $kts_size . '», чтобы пользователь и поисковик видели точную серию и типоразмер.'
					),
					array(
						'question' => 'Где применяют редукторы ' . $kts_series_name . '?',
						'answer' => 'Коническо-цилиндрические редукторы применяют в приводах конвейеров, дробилок, смесителей, экструдеров, подъемно-транспортного оборудования, технологических линий и промышленных механизмов с угловой компоновкой валов.'
					),
					array(
						'question' => 'Что проверить перед заказом ' . $kts_series_name . '-' . $kts_size . '?',
						'answer' => 'Перед заказом нужно подтвердить передаточное число, требуемый крутящий момент, частоту вращения валов, схему сборки, направление валов, способ крепления, монтажное положение, габаритные и присоединительные размеры.'
					),
					array(
						'question' => 'Можно ли подобрать аналог редуктора ' . $kts_series_name . '-' . $kts_size . '?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, передаточное число, схему валов, размеры посадочных мест и описание нагрузки. Инженер проверит совместимость по моменту и габаритам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от типоразмера, передаточного числа, схемы сборки, комплектации, наличия и доставки по Казахстану. Fortune PROM подтверждает стоимость после проверки параметров под ваш привод.'
					)
				);
			} elseif (isset($brand_motor_reducer_seo[(int)$product_id]) && (int)$product_id !== 2142) {
				$brand_motor = $brand_motor_reducer_seo[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'К какой категории относится ' . $brand_motor['model'] . '?',
						'answer' => $brand_motor['model'] . ' размещается в ветке «' . $brand_motor['category'] . '». Такая структура помогает пользователю и поисковику видеть тип привода, серию и место карточки в каталоге.'
					),
					array(
						'question' => 'Где применяют ' . $brand_motor['model'] . '?',
						'answer' => $brand_motor['application'] . ' Подбор выполняется по моменту, оборотам, режиму нагрузки, монтажу и условиям эксплуатации.'
					),
					array(
						'question' => 'Что проверить перед заказом ' . $brand_motor['series'] . '?',
						'answer' => $brand_motor['checks']
					),
					array(
						'question' => 'Можно ли подобрать аналог ' . $brand_motor['model'] . '?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, полное обозначение, мощность двигателя, выходные обороты, передаточное число, размеры валов, фланца или лап и описание нагрузки. Инженер проверит совместимость по моменту и посадочным размерам.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от серии, типоразмера, передаточного числа, двигателя, монтажного исполнения, наличия и доставки по Казахстану. Fortune PROM подтверждает стоимость после сверки параметров под ваш привод.'
					)
				);
			} elseif ($product_id == 2142) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что означает обозначение 3МВз?',
						'answer' => '3МВз — серия волновых зубчатых мотор-редукторов. Это приводной агрегат, в котором электродвигатель объединен с волновой зубчатой передачей для получения высокой редукции, компактных габаритов и точного плавного вращения выходного вала.'
					),
					array(
						'question' => 'Какие типоразмеры 3МВз можно подобрать?',
						'answer' => 'В линейке применяются типоразмеры 3МВз-63, 3МВз-80 и 3МВз-160. Подбор зависит от требуемого крутящего момента, выходной скорости, передаточного отношения, мощности двигателя, монтажного исполнения и режима нагрузки.'
					),
					array(
						'question' => 'Какие передаточные отношения и выходные обороты доступны?',
						'answer' => 'Для волновых мотор-редукторов 3МВз обычно применяются передаточные отношения примерно i=78-275. Частота вращения выходного вала подбирается в диапазоне 6,3-35,5 об/мин в зависимости от типоразмера и двигателя.'
					),
					array(
						'question' => 'Где применяются волновые мотор-редукторы 3МВз?',
						'answer' => 'Их используют в станках, механизмах позиционирования, промышленных манипуляторах, транспортных системах, дозаторах, поворотных механизмах и другом оборудовании, где важны компактность, высокая редукция, плавность хода и точность.'
					),
					array(
						'question' => 'Что важно проверить перед заказом 3МВз?',
						'answer' => 'Нужно подтвердить типоразмер, выходные обороты, крутящий момент, передаточное отношение, мощность двигателя, способ крепления, положение выходного вала, климатическое исполнение и фактический режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог старого волнового мотор-редуктора?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, выходные обороты, мощность двигателя, передаточное отношение, монтажные размеры и условия работы. Инженер проверит совместимость по моменту, посадочным размерам и режиму эксплуатации.'
					),
					array(
						'question' => 'Есть ли поставка по Казахстану?',
						'answer' => 'Fortune PROM поставляет волновые мотор-редукторы 3МВз по Алматы и регионам Казахстана. Срок, наличие, комплектация, гарантия и стоимость доставки подтверждаются менеджером перед выставлением счета.'
					)
				);
			} elseif ($product_id == 215) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Какова производительность валковых дробилок Xuanshi?',
						'answer' => 'Производительность зависит от конкретной модели и физико-механических свойств перерабатываемого материала. Например, popularные модели серии 2PG имеют производительность от 5 до 80 тонн в час. Фактический объем регулируется зазором между валками.'
					),
					array(
						'question' => 'Для каких материалов лучше всего подходят валковые дробилки?',
						'answer' => 'Валковые дробилки Xuanshi идеально подходят для измельчения материалов малой и средней прочности, включая каменный уголь, глину, гипс, мел, сланец, а также вязких и влажных пород, которые забивают щековые или конусные дробилки.'
					),
					array(
						'question' => 'Каким образом реализована защита валков от поломки при попадании металла?',
						'answer' => 'Конструкция дробилок Xuanshi включает мощные предохранительные пружинные амортизаторы на подвижном валу. При попадании недробимого предмета (например, куска металла) подвижный вал отжимается, пропуская предмет, после чего пружины возвращают его в исходное рабочее положение.'
					),
					array(
						'question' => 'Где можно заказать износостойкие бандажи (валки) для замены?',
						'answer' => 'ТОО «Fortune PROM» поставляет как дробилки Xuanshi в сборе, так и весь спектр оригинальных запасных частей к ним, включая сменные гладкие и рифленые бандажи валков из марганцовистой стали, шестерни, подшипниковые узлы и предохранительные пружины.'
					)
				);
			} elseif ($product_id == 214) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Какова производительность двухвалковых дробилок серии 2PG?',
						'answer' => 'Производительность двухвалковых дробилок зависит от типоразмера валков и физических свойств измельчаемого сырья. В линейке серии 2PG производительность варьируется от 5 до 80 тонн в час. Регулируя зазор между валками, можно изменять пропускную способность оборудования.'
					),
					array(
						'question' => 'С какими типами материалов наиболее эффективно работают валковые дробилки?',
						'answer' => 'Оборудование предназначено для измельчения мягких материалов и материалов средней прочности (известняк, глина, гипс, мергель, уголь, соль). Они незаменимы при работе с влажным или липким сырьем, которое забивает дробилки других типов (например, конусные или щековые).'
					),
					array(
						'question' => 'Как реализована защита дробящих валков от аварийных поломок?',
						'answer' => 'Один из валков установлен на подвижных подшипниковых опорах, зафиксированных пакетом мощных предохранительных пружин. При прохождении недробимого тела пружины сжимаются, позволяя валку временно отойти назад, а затем автоматически возвращают его в рабочую позицию.'
					),
					array(
						'question' => 'Поставляет ли ТОО «Fortune PROM» запасные части для валковых дробилок?',
						'answer' => 'Да, мы осуществляем прямые поставки оригинальных запасных частей и расходных материалов: сменные валковые бандажи (гладкие, рифленые, зубчатые) из износостойких сплавов марганца, подшипниковые узлы, шестерни, приводные ремни и амортизационные пружины.'
					)
				);
			} elseif ($product_id == 1175) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Какая производительность у дробилки КСД-600?',
						'answer' => 'Производительность конусной дробилки КСД-600 составляет от 19 до 40 кубических метров в час. Фактический объем зависит от ширины разгрузочной щели (настраивается от 12 до 35 мм), прочности и влажности перерабатываемого материала.'
					),
					array(
						'question' => 'Какие материалы подходит для дробления на КСД-600?',
						'answer' => 'Дробилка предназначена для переработки рудных и нерудных материалов средней и высокой прочности. Сюда входят гранит, базальт, кварцит, извесняк, железная руда и другие материалы с пределом прочности при сжатии до 300 МПа.'
					),
					array(
						'question' => 'Каков гарантийный срок на дробилку КСД-600?',
						'answer' => 'ТОО «Fortune PROM» предоставляет официальную заводскую гарантию 12 месяцев на конусные дробилки КСД-600 (ДРО-592). Гарантия распространяется на основные узлы оборудования при условии соблюдения правил эксплуатации и технического обслуживания.'
					),
					array(
						'question' => 'Осуществляется ли доставка дробильного оборудования по Казахстану?',
						'answer' => 'Да, мы осуществляем быструю доставку дробилок и запасных частей во все регионы Республики Казахстан (включая Алматы, Астану, Шымкент, Караганду, Актобе, Усть-Каменогорск). Доставка организуется попутным автотранспортом или железнодорожными платформами.'
					)
				);
			} elseif ($product_id == 2122) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Для каких механизмов подходит редуктор 1Ц2У-200?',
						'answer' => 'Редуктор 1Ц2У-200 применяют в приводах конвейеров, подъемников, транспортеров, дробильного, строительного и другого промышленного оборудования, где нужен высокий выходной момент, снижение оборотов и надежная работа при постоянных или переменных нагрузках.'
					),
					array(
						'question' => 'Какие передаточные числа бывают у редуктора 1Ц2У-200?',
						'answer' => 'Для 1Ц2У-200 применяют стандартный ряд передаточных чисел: 8, 10, 12.5, 16, 20, 25, 31.5 и 40. Передаточное число подбирается по требуемым выходным оборотам, моменту, режиму нагрузки и запасу прочности привода.'
					),
					array(
						'question' => 'Какой выходной момент у редуктора 1Ц2У-200?',
						'answer' => 'Номинальный крутящий момент на выходном валу редуктора 1Ц2У-200 может достигать 2500 Н·м в зависимости от передаточного числа и режима работы. Для точного подбора нужно учитывать мощность двигателя, обороты, характер нагрузки и длительность включения.'
					),
					array(
						'question' => 'Какое масло заливать в редуктор 1Ц2У-200?',
						'answer' => 'В редуктор 1Ц2У-200 заливают специализированные редукторные масла с присадками CLP, например Mobilgear 600 XP 220, Shell Omala S2 G 220 или аналоги по вязкости и назначению. Средний объем заливки составляет около 7 литров, уровень контролируют по смотровому окну или щупу.'
					),
					array(
						'question' => 'Какие размеры валов нужно проверить перед заказом?',
						'answer' => 'Перед заказом важно подтвердить тип выходного вала, диаметр, сторону исполнения, габаритные и присоединительные размеры, передаточное число, монтажное положение и совместимость с существующей рамой или муфтой. Для 1Ц2У-200 часто проверяют выходной вал 65 мм для цилиндрического исполнения и 60 мм для конического.'
					),
					array(
						'question' => 'Можно ли подобрать аналог редуктора Ц2У-200 или заменить старый 1Ц2У-200?',
						'answer' => 'Да. Для подбора аналога достаточно прислать фото шильдика, передаточное число, схему валов, габаритный чертеж или размеры посадочных мест. Инженер проверит совместимость по моменту, оборотам, валам, креплению и условиям эксплуатации.'
					),
					array(
						'question' => 'Каковы условия гарантии и доставки на редуктор 1Ц2У-200?',
						'answer' => 'Fortune PROM поставляет редукторы 1Ц2У-200 по Алматы и регионам Казахстана. Гарантия составляет 12 месяцев, сроки поставки и доставки подтверждаются менеджером с учетом наличия, комплектации, партии и выбранной транспортной компании.'
					)
				);
			} elseif ($product_id == 2167) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Для каких задач подходят конические мотор-редукторы Lenze GKR?',
						'answer' => 'Lenze GKR применяют в приводах конвейеров, упаковочного оборудования, транспортеров, автоматизированных линий и исполнительных механизмов, где нужен компактный мотор-редуктор с высоким моментом, защитой IP55 и подбором двигателя под задачу.'
					),
					array(
						'question' => 'Какая мощность и крутящий момент доступны для Lenze GKR?',
						'answer' => 'Для серии Lenze GKR подбирают электродвигатели мощностью примерно 0,06-5,5 кВт, а диапазон крутящего момента по комплектациям составляет ориентировочно 190-11790 Н·м. Точные значения зависят от передаточного числа, двигателя, режима нагрузки и исполнения.'
					),
					array(
						'question' => 'Что нужно проверить перед заказом Lenze GKR?',
						'answer' => 'Перед заказом важно подтвердить модель с шильдика, передаточное число, мощность двигателя, напряжение, частоту, схему валов, монтажное положение, фланец, габаритные размеры, класс защиты и условия эксплуатации оборудования.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Lenze GKR?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, мощность, передаточное число, размеры валов и посадочных мест, монтажную схему и описание нагрузки. Инженер проверит совместимость по моменту, оборотам, креплению и условиям работы.'
					),
					array(
						'question' => 'Что означает класс защиты IP55 у мотор-редуктора?',
						'answer' => 'IP55 означает защиту корпуса от пыли в опасном количестве и от водяных струй. Для промышленного оборудования это важно при работе в цехах, на линиях и в условиях, где привод должен быть защищен от загрязнений и влаги.'
					),
					array(
						'question' => 'Как формируется цена на конический мотор-редуктор Lenze GKR?',
						'answer' => 'Цена зависит от мощности двигателя, передаточного числа, исполнения валов и фланцев, монтажного положения, наличия, комплектации и партии поставки. Менеджер рассчитывает стоимость после уточнения технических параметров.'
					),
					array(
						'question' => 'Есть ли доставка Lenze GKR по Казахстану?',
						'answer' => 'Fortune PROM поставляет конические мотор-редукторы Lenze GKR по Алматы и регионам Казахстана. Срок поставки, гарантия, комплектация и стоимость доставки подтверждаются перед выставлением счета.'
					)
				);
			} elseif ($product_id == 2168) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое мотор-редукторы Lenze GKS?',
						'answer' => 'Lenze GKS — серия коническо-цилиндрических мотор-редукторов для приводов с угловой компоновкой, где требуется передача момента под 90° и компактная установка двигателя с редуктором.'
					),
					array(
						'question' => 'К какой категории относится Lenze GKS?',
						'answer' => 'Страница относится к ветке «Редукторы > Мотор-редукторы > Конические мотор-редукторы». В этой группе собраны угловые конические и коническо-цилиндрические мотор-редукторы.'
					),
					array(
						'question' => 'Где применяют Lenze GKS?',
						'answer' => 'Lenze GKS применяют в приводах конвейеров, транспортеров, смесителей, упаковочных машин, дозаторов и технологических линий, где нужны угловая компоновка, надежный момент и гибкий монтаж.'
					),
					array(
						'question' => 'Что проверить перед заказом Lenze GKS?',
						'answer' => 'Нужно подтвердить модель, передаточное число, мощность двигателя, выходные обороты, исполнение вала, фланец, монтажное положение, габариты, нагрузку и режим работы оборудования.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Lenze GKS?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, передаточное число, мощность, размеры валов, фланец, крепление и описание нагрузки. Инженер проверит замену по посадочным размерам и моменту.'
					),
					array(
						'question' => 'Чем Lenze GKS отличается от Lenze GKR?',
						'answer' => 'Обе серии относятся к угловым приводам, но отличаются конструктивным исполнением, присоединительными размерами и доступными комплектациями. При замене нужно сверять шильдик, вал, фланец, передаточное число и монтаж.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от типоразмера, передаточного числа, двигателя, исполнения валов, фланца, партии, наличия и доставки по Казахстану. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 2186) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое мотор-редукторы Siemens Motox B/K?',
						'answer' => 'Siemens Motox B/K — серия коническо-цилиндрических мотор-редукторов для промышленных приводов с угловой компоновкой, где двигатель и выходной вал удобно размещаются под 90°.'
					),
					array(
						'question' => 'К какой категории относится Siemens Motox B/K?',
						'answer' => 'Карточка относится к ветке «Редукторы > Мотор-редукторы > Конические мотор-редукторы». Это правильная категория для угловых коническо-цилиндрических мотор-редукторов Siemens.'
					),
					array(
						'question' => 'Где применяются коническо-цилиндрические мотор-редукторы Siemens?',
						'answer' => 'Их применяют на конвейерах, транспортерах, смесителях, упаковочных машинах, дозаторах и технологических линиях, где нужны компактная угловая компоновка, стабильный момент и промышленная надежность.'
					),
					array(
						'question' => 'Какие параметры нужны для подбора Siemens Motox B/K?',
						'answer' => 'Для подбора нужны модель с шильдика, передаточное число, мощность двигателя, выходные обороты, момент, тип выходного вала, фланец, монтажное положение, габариты и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Siemens Motox B/K?',
						'answer' => 'Да. Инженер подберет Siemens Motox B/K или технически совместимый аналог по посадочным размерам, моменту, передаточному числу, исполнению вала, фланцу, креплению и условиям работы.'
					),
					array(
						'question' => 'Как формируется цена на мотор-редуктор Siemens?',
						'answer' => 'Цена зависит от типоразмера, передаточного числа, мощности двигателя, исполнения валов и фланцев, комплектации, наличия, партии и доставки по Казахстану. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 2187) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое мотор-редукторы Siti серии MBH?',
						'answer' => 'Siti MBH — серия коническо-цилиндрических мотор-редукторов для приводов с угловой компоновкой, где нужно компактно передать момент под 90° и сохранить удобный монтаж.'
					),
					array(
						'question' => 'В какой категории должна быть карточка Siti MBH?',
						'answer' => 'Карточка относится к ветке «Редукторы > Мотор-редукторы > Конические мотор-редукторы», потому что это угловой коническо-цилиндрический мотор-редуктор, а не обычный цилиндрический редуктор.'
					),
					array(
						'question' => 'Где применяются коническо-цилиндрические мотор-редукторы Siti MBH?',
						'answer' => 'Siti MBH применяют в приводах конвейеров, транспортеров, смесителей, дозаторов, упаковочного оборудования и производственных линий с ограниченным местом под привод.'
					),
					array(
						'question' => 'Какие данные нужны для подбора Siti MBH?',
						'answer' => 'Нужны серия или модель с шильдика, передаточное число, мощность двигателя, выходные обороты, расчетный момент, тип вала, фланец, монтажное положение, габариты и режим работы.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Siti MBH?',
						'answer' => 'Да. По шильдику и посадочным размерам можно подобрать Siti MBH или совместимый аналог с учетом момента, передаточного числа, мощности, крепления, вала и условий эксплуатации.'
					),
					array(
						'question' => 'Как узнать цену и срок поставки Siti MBH?',
						'answer' => 'Цена и срок зависят от типоразмера, передаточного числа, мощности, исполнения валов и фланцев, комплектации, наличия, партии и доставки. Стоимость подтверждается после технической проверки.'
					)
				);
			} elseif ($product_id == 2188) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое маятниковый редуктор Siti RP2?',
						'answer' => 'Siti RP2 — насадной маятниковый редуктор для приводов, где корпус фиксируется реактивной тягой, а редуктор устанавливается непосредственно на вал рабочего механизма.'
					),
					array(
						'question' => 'В какой категории должна быть карточка Siti RP2?',
						'answer' => 'Карточка должна находиться в ветке «Редукторы > Редукторы насадные», потому что RP2 относится к маятниковым насадным редукторам, а не к общей категории мотор-редукторов.'
					),
					array(
						'question' => 'Где применяют редукторы Siti RP2?',
						'answer' => 'Siti RP2 применяют на ленточных конвейерах, транспортерах, дробильных установках, дозаторах и производственных линиях, где нужна компактная установка привода на вал механизма.'
					),
					array(
						'question' => 'Какие параметры нужны для подбора Siti RP2?',
						'answer' => 'Для подбора нужны серия и типоразмер, передаточное число, расчетный момент, скорость выходного вала, диаметр полого вала, вариант крепления, реактивная тяга и условия эксплуатации.'
					),
					array(
						'question' => 'Можно ли подобрать аналог маятникового редуктора Siti RP2?',
						'answer' => 'Да. Аналог подбирают по моменту, передаточному числу, посадочному диаметру полого вала, габаритам, креплению реактивной тяги и режиму нагрузки оборудования.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки Siti RP2?',
						'answer' => 'Цена зависит от типоразмера, передаточного числа, исполнения вала, комплектации, наличия, партии и доставки по Казахстану. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 2157) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Для каких задач подходит конический мотор-редуктор Bauer BK?',
						'answer' => 'Bauer BK применяют в промышленных приводах, конвейерах, упаковочном оборудовании, пищевой промышленности и других механизмах, где нужен компактный конический мотор-редуктор с передачей под 90°, защитой IP65 и гибкими вариантами монтажа.'
					),
					array(
						'question' => 'Какие варианты монтажа доступны для Bauer серии BK?',
						'answer' => 'Для Bauer BK применяют монтаж на валу, на лапах, на фланец или на лицевую поверхность. Перед заказом нужно подтвердить монтажное положение, сторону исполнения, тип выходного вала и присоединительные размеры оборудования.'
					),
					array(
						'question' => 'Что означает класс защиты IP65 у мотор-редуктора Bauer BK?',
						'answer' => 'IP65 означает защиту корпуса от пыли и водяных струй. Это важно для приводов, которые работают в производственных цехах, на линиях, в условиях загрязнений, влажности или мойки оборудования.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Bauer BK?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, передаточное число, мощность двигателя, напряжение, схему валов, фланец, монтажные размеры и описание нагрузки. Инженер проверит совместимость по моменту, оборотам, креплению и условиям эксплуатации.'
					),
					array(
						'question' => 'Что проверить перед заказом Bauer BK?',
						'answer' => 'Нужно подтвердить модель и серию, передаточное число, мощность и обороты двигателя, тип вала, монтаж, фланец, положение клеммной коробки, наличие частотного привода, класс защиты и режим работы оборудования.'
					),
					array(
						'question' => 'Как формируется цена на мотор-редуктор Bauer серии BK?',
						'answer' => 'Цена зависит от типоразмера, передаточного числа, двигателя, исполнения валов, способа монтажа, комплектации, наличия и партии поставки. Менеджер рассчитывает стоимость после уточнения технических параметров.'
					),
					array(
						'question' => 'Есть ли доставка Bauer BK по Казахстану?',
						'answer' => 'Fortune PROM поставляет конические мотор-редукторы Bauer BK по Алматы и регионам Казахстана. Срок поставки, гарантия, комплектация и стоимость доставки подтверждаются перед выставлением счета.'
					)
				);
			} elseif ($product_id == 2152) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое редукторы Yilmaz серии K?',
						'answer' => 'Yilmaz K — серия коническо-цилиндрических редукторов для приводов, где нужно изменить направление передачи под углом 90° и получить высокий крутящий момент в компактной компоновке.'
					),
					array(
						'question' => 'К какой категории относится Yilmaz K?',
						'answer' => 'Страница относится к ветке «Редукторы > Коническо-цилиндрические редукторы > Редукторы серии K». Это правильная структура для серии, а не раздел обычных цилиндрических редукторов.'
					),
					array(
						'question' => 'Где применяют коническо-цилиндрические редукторы Yilmaz K?',
						'answer' => 'Их применяют в приводах конвейеров, смесителей, дозаторов, упаковочного оборудования, транспортеров и технологических линий, где требуется угловая компоновка, надежная передача момента и удобный монтаж.'
					),
					array(
						'question' => 'Что проверить перед заказом редуктора Yilmaz K?',
						'answer' => 'Нужно подтвердить типоразмер, передаточное число, расчетный момент, исполнение вала, фланец, монтажное положение, габаритные размеры, режим нагрузки и требования к двигателю или входному фланцу.'
					),
					array(
						'question' => 'Можно ли подобрать аналог Yilmaz K?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, передаточное число, момент, мощность двигателя, размеры валов, фланец и схему крепления. Инженер сверит совместимость по посадочным размерам.'
					),
					array(
						'question' => 'Чем серия K отличается от соосных цилиндрических редукторов?',
						'answer' => 'Серия K передает вращение под углом 90° и удобна для угловой компоновки. Соосные цилиндрические редукторы ставят там, где входной и выходной вал должны располагаться по одной оси.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от типоразмера, передаточного числа, исполнения вала, фланца, партии, наличия и доставки по Казахстану. Fortune PROM подтверждает стоимость после технической сверки.'
					)
				);
			} elseif ($product_id == 2153) {
				$data['product_faq_items'] = array(
					array('question' => 'Что такое редукторы Yilmaz серии D?', 'answer' => 'Yilmaz D — плоско-цилиндрические редукторы с параллельными валами для компактных промышленных приводов, где важно установить редуктор на вал или в ограниченном пространстве.'),
					array('question' => 'К какой категории относится Yilmaz D?', 'answer' => 'Карточка относится к ветке «Редукторы > Цилиндрические редукторы > Плоско-цилиндрические редукторы», потому что серия D относится к цилиндрическим редукторам с параллельными валами.'),
					array('question' => 'Какие параметры нужны для подбора Yilmaz D?', 'answer' => 'Нужны типоразмер, передаточное число, расчетный момент, исполнение выходного вала, фланец, монтажное положение, габариты и режим нагрузки.'),
					array('question' => 'Можно ли подобрать аналог Yilmaz D?', 'answer' => 'Да. Аналог подбирают по серии D, моменту, передаточному числу, валам, фланцу, креплению и посадочным размерам оборудования.')
				);
			} elseif ($product_id == 2154) {
				$data['product_faq_items'] = array(
					array('question' => 'Что такое редукторы Yilmaz серии M?', 'answer' => 'Yilmaz M — соосно-цилиндрические редукторы, где входной и выходной вал расположены по одной оси, что удобно для прямолинейной компоновки промышленного привода.'),
					array('question' => 'К какой категории относится Yilmaz M?', 'answer' => 'Карточка относится к ветке «Редукторы > Цилиндрические редукторы > Соосно-цилиндрические редукторы». Это точная категория для серии M.'),
					array('question' => 'Где применяют Yilmaz M?', 'answer' => 'Редукторы серии M применяют в конвейерах, смесителях, дозаторах, транспортерах и технологических линиях, где нужна соосная компоновка и стабильный момент.'),
					array('question' => 'Что проверить перед заказом Yilmaz M?', 'answer' => 'Нужно подтвердить типоразмер, передаточное число, момент, исполнение валов, монтаж, фланец, габариты и режим нагрузки оборудования.')
				);
			} elseif ($product_id == 2155) {
				$data['product_faq_items'] = array(
					array('question' => 'Что такое редукторы Yilmaz серии N?', 'answer' => 'Yilmaz N — соосно-цилиндрические редукторы для промышленных приводов с прямым расположением входного и выходного валов.'),
					array('question' => 'К какой категории относится Yilmaz N?', 'answer' => 'Карточка относится к ветке «Редукторы > Цилиндрические редукторы > Соосно-цилиндрические редукторы», где собраны соосные цилиндрические серии.'),
					array('question' => 'Где применяют Yilmaz N?', 'answer' => 'Серию N применяют в приводах конвейеров, смесителей, насосных и технологических механизмов, где нужна соосная передача момента.'),
					array('question' => 'Можно ли подобрать аналог Yilmaz N?', 'answer' => 'Да. Аналог подбирают по типоразмеру, передаточному числу, моменту, валам, монтажным размерам и условиям работы.')
				);
			} elseif ($product_id == 2156) {
				$data['product_faq_items'] = array(
					array('question' => 'Что такое редукторы Yilmaz серии E?', 'answer' => 'Yilmaz E — червячные редукторы для компактных приводов, где нужно снизить обороты, увеличить момент и получить угловую компоновку передачи.'),
					array('question' => 'К какой категории относится Yilmaz E?', 'answer' => 'Карточка относится к ветке «Редукторы > Червячные редукторы > Червячные редукторы Yilmaz». Это правильная структура для серии E.'),
					array('question' => 'Где применяют Yilmaz E?', 'answer' => 'Серию E используют в конвейерах, дозаторах, упаковочном оборудовании, мешалках и других механизмах с компактным приводом.'),
					array('question' => 'Что проверить перед заказом Yilmaz E?', 'answer' => 'Нужно подтвердить типоразмер, передаточное число, исполнение валов, монтажное положение, фланец, габариты и режим нагрузки.')
				);
			} elseif ($product_id == 2160) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Для чего нужен монорельсовый мотор-редуктор Bauer BM?',
						'answer' => 'Bauer BM применяют в приводах монорельсовых тележек, тельферов, крановых механизмов и подвесных транспортных систем, где нужна надежная передача момента при движении груза по балке или направляющей.'
					),
					array(
						'question' => 'К какой категории относится Bauer BM?',
						'answer' => 'Эта страница относится к ветке «Редукторы > Мотор-редукторы > Крановые мотор-редукторы». Для такого оборудования это точнее, чем общий раздел мотор-редукторов.'
					),
					array(
						'question' => 'Что проверить перед заказом Bauer BM?',
						'answer' => 'Нужно подтвердить модель с шильдика, грузоподъемность, скорость перемещения, передаточное число, мощность двигателя, наличие тормоза, напряжение, монтаж, присоединительные размеры и режим работы крана.'
					),
					array(
						'question' => 'Можно ли подобрать аналог монорельсового Bauer BM?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, параметры двигателя, тормоза, скорость, нагрузку, схему крепления и размеры посадочных мест. Инженер проверит совместимость по моменту и монтажу.'
					),
					array(
						'question' => 'Чем монорельсовый мотор-редуктор отличается от обычного?',
						'answer' => 'Монорельсовый мотор-редуктор подбирают под перемещение тележки или тельфера, поэтому важны скорость хода, частые пуски, тормоз, крепление, нагрузка на привод и требования безопасности кранового оборудования.'
					),
					array(
						'question' => 'Как уточнить цену и срок поставки?',
						'answer' => 'Цена и срок зависят от модели, комплектации двигателя и тормоза, нагрузки, партии, наличия и доставки по Казахстану. Fortune PROM подтверждает стоимость после технической сверки.'
					)
				);
			} elseif ($product_id == 2128) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое мотор-редуктор F37?',
						'answer' => 'F37 — компактный плоский цилиндрический мотор-редуктор с параллельными валами для приводов, где важно экономить место и сохранить удобную установку двигателя.'
					),
					array(
						'question' => 'К какой категории относится F37?',
						'answer' => 'Карточка относится к ветке «Редукторы > Мотор-редукторы > Плоские цилиндрические мотор-редукторы». Это правильная категория для мотор-редукторов серии F.'
					),
					array(
						'question' => 'Где применяют плоский мотор-редуктор F37?',
						'answer' => 'F37 применяют на конвейерах, транспортерах, дозаторах, упаковочном оборудовании и компактных технологических линиях с ограниченным пространством под привод.'
					),
					array(
						'question' => 'Какие параметры нужны для подбора F37?',
						'answer' => 'Нужны типоразмер, передаточное число, мощность двигателя, выходные обороты, момент, исполнение вала, фланец, монтажное положение, габариты и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог F37?',
						'answer' => 'Да. Аналог подбирают по моменту, передаточному числу, мощности, валам, присоединительным размерам, креплению и условиям работы оборудования.'
					)
				);
			} elseif ($product_id == 2129) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое плоский цилиндрический мотор-редуктор F47?',
						'answer' => 'F47 — плоский цилиндрический мотор-редуктор с параллельными валами для компактных приводов конвейеров, транспортеров и промышленного оборудования.'
					),
					array(
						'question' => 'Почему F47 размещен в плоских цилиндрических мотор-редукторах?',
						'answer' => 'Серия F относится к плоским цилиндрическим мотор-редукторам, поэтому правильная ветка для карточки — «Редукторы > Мотор-редукторы > Плоские цилиндрические мотор-редукторы».'
					),
					array(
						'question' => 'Где применяется мотор-редуктор F47?',
						'answer' => 'F47 применяют в приводах конвейеров, транспортеров, смесителей, дозаторов и производственных линий, где нужны параллельные валы и компактная компоновка.'
					),
					array(
						'question' => 'Какие данные нужны для подбора F47?',
						'answer' => 'Для подбора нужны передаточное число, мощность двигателя, выходные обороты, момент, тип вала, фланец, монтажное положение, габариты и описание нагрузки.'
					),
					array(
						'question' => 'Что делать с дублем страницы F47?',
						'answer' => 'Для SEO основным выбран более точный URL с названием «плоский цилиндрический мотор-редуктор F47», а дубль должен вести на него через 301-редирект.'
					)
				);
			} elseif ($product_id == 2130) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое мотор-редуктор F157?',
						'answer' => 'F157 — плоский цилиндрический мотор-редуктор крупного типоразмера для приводов с повышенным моментом и компактной параллельной компоновкой валов.'
					),
					array(
						'question' => 'К какой категории относится F157?',
						'answer' => 'Карточка относится к ветке «Редукторы > Мотор-редукторы > Плоские цилиндрические мотор-редукторы». Это точная категория для серии F с параллельными валами.'
					),
					array(
						'question' => 'Где применяют плоский мотор-редуктор F157?',
						'answer' => 'F157 применяют на тяжелых конвейерах, транспортерах, смесителях, питателях и производственных линиях, где требуется повышенный момент и компактный монтаж.'
					),
					array(
						'question' => 'Что проверить перед заказом F157?',
						'answer' => 'Нужно подтвердить передаточное число, мощность двигателя, выходные обороты, момент, исполнение вала, фланец, монтажное положение, габариты и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог F157?',
						'answer' => 'Да. Аналог подбирают по типоразмеру F157, моменту, передаточному числу, валам, креплению, габаритам и условиям работы оборудования.'
					)
				);
			} elseif ($product_id == 2141) {
				$data['product_faq_items'] = array(
					array('question' => 'Что такое мотор-редуктор 5МПО1М-10ВК?', 'answer' => '5МПО1М-10ВК — цилиндрический соосный мотор-редуктор общепромышленного назначения для снижения оборотов и передачи крутящего момента в приводах оборудования.'),
					array('question' => 'К какой категории относится 5МПО1М-10ВК?', 'answer' => 'Карточка относится к ветке «Редукторы > Мотор-редукторы > Цилиндрические соосные мотор-редукторы», потому что серия 5МПО1М имеет соосную цилиндрическую компоновку.'),
					array('question' => 'Где применяют 5МПО1М-10ВК?', 'answer' => 'Мотор-редуктор применяют в приводах конвейеров, транспортеров, дозаторов, мешалок и другого технологического оборудования с общепромышленным режимом работы.'),
					array('question' => 'Что проверить перед заказом 5МПО1М-10ВК?', 'answer' => 'Нужно подтвердить передаточное число, мощность двигателя, выходные обороты, исполнение валов, монтажное положение, габариты и режим нагрузки.')
				);
			} elseif ($product_id == 2212) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Что такое мотор-редуктор 5Ч-125А?',
						'answer' => '5Ч-125А — червячный мотор-редуктор с межосевым расстоянием 125 мм, который снижает обороты и увеличивает крутящий момент в промышленном приводе.'
					),
					array(
						'question' => 'К какой категории относится 5Ч-125А?',
						'answer' => 'Карточка относится к ветке «Редукторы > Мотор-редукторы > Червячные мотор-редукторы». Это правильная категория для мотор-редуктора серии 5Ч.'
					),
					array(
						'question' => 'Где применяют мотор-редуктор 5Ч-125А?',
						'answer' => '5Ч-125А применяют в приводах конвейеров, транспортеров, мешалок, дозаторов, подъемных и технологических механизмов, где нужны компактность, надежный момент и червячная передача.'
					),
					array(
						'question' => 'Какие параметры нужны для подбора 5Ч-125А?',
						'answer' => 'Нужно подтвердить передаточное число, мощность двигателя, входные и выходные обороты, исполнение валов, монтажное положение, габариты, направление вращения и режим нагрузки.'
					),
					array(
						'question' => 'Можно ли подобрать аналог 5Ч-125А?',
						'answer' => 'Да. Аналог подбирают по межосевому расстоянию 125 мм, передаточному числу, моменту, мощности двигателя, валам, креплению и посадочным размерам оборудования.'
					),
					array(
						'question' => 'Как узнать цену и срок поставки 5Ч-125А?',
						'answer' => 'Цена зависит от передаточного числа, мощности двигателя, исполнения валов, монтажного положения, комплектации, наличия и доставки по Казахстану. Стоимость подтверждается после технической сверки.'
					)
				);
			} elseif ($product_id == 2213) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Для каких задач подходит мотор-редуктор 5Ч-80?',
						'answer' => 'Мотор-редуктор 5Ч-80 применяют в приводах конвейеров, транспортеров, дозаторов, смесителей и другого промышленного оборудования, где нужно снизить обороты и получить более высокий крутящий момент в компактном червячном приводе.'
					),
					array(
						'question' => 'Что означает обозначение 5Ч-80?',
						'answer' => '5Ч — серия червячных редукторов и мотор-редукторов, а 80 указывает на межосевое расстояние 80 мм. При подборе также важно знать передаточное число, мощность двигателя, исполнение валов и монтажное положение.'
					),
					array(
						'question' => 'Какие параметры проверить перед заказом 5Ч-80?',
						'answer' => 'Нужно подтвердить передаточное число, мощность и обороты двигателя, диаметр и исполнение валов, монтаж, направление вращения, режим нагрузки, условия эксплуатации и габаритные присоединительные размеры оборудования.'
					),
					array(
						'question' => 'Можно ли подобрать аналог мотор-редуктора 5Ч-80?',
						'answer' => 'Да. Для подбора аналога пришлите фото шильдика, модель, передаточное число, мощность двигателя, монтажную схему, размеры валов и посадочных мест. Инженер проверит совместимость по моменту, оборотам и креплению.'
					),
					array(
						'question' => 'Чем червячный мотор-редуктор отличается от цилиндрического?',
						'answer' => 'Червячный мотор-редуктор дает компактную угловую передачу и высокий коэффициент редукции в одном корпусе. Цилиндрические редукторы обычно выбирают для более высокого КПД и тяжелых режимов, когда компоновка оборудования позволяет прямую передачу.'
					),
					array(
						'question' => 'Как формируется цена на мотор-редуктор 5Ч-80?',
						'answer' => 'Цена зависит от передаточного числа, мощности и типа двигателя, исполнения валов, монтажного положения, комплектации, наличия и партии поставки. Менеджер рассчитывает стоимость после уточнения технических параметров.'
					),
					array(
						'question' => 'Есть ли доставка 5Ч-80 по Казахстану?',
						'answer' => 'Fortune PROM поставляет червячные мотор-редукторы 5Ч-80 по Алматы и регионам Казахстана. Срок поставки, гарантия, комплектация и стоимость доставки подтверждаются перед выставлением счета.'
					)
				);
			} elseif ($product_id == 213) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Каковы ключевые отличия конусных дробилок серии CY от пружинных серии PY?',
						'answer' => 'Серия CY оснащена современной комбинированной гидравлической системой (регулировки, фиксации и защиты от перегрузок), в то время как серия PY использует для защиты традиционные механические пружины. Гидравлика CY позволяет настраивать выходную щель удаленно с пульта управления и быстрее освобождать камеру дробления при заклинивании.'
					),
					array(
						'question' => 'Для каких типов пород подходит конусная дробилка серии CY?',
						'answer' => 'Дробилки CY оптимальны для работы со среднетвердыми и сверхтвердыми абразивными материалами, имеющими прочность на сжатие до 300 МПа. Она отлично справляется с дроблением гранита, базальта, диабаза, кварцита, железной и медной руды, а также речного гравия.'
					),
					array(
						'question' => 'Как правильно выбрать тип полости дробления (камеры)?',
						'answer' => 'Для первой стадии вторичного дробления и крупных фракций на входе выбирают «Стандартный» тип камеры (крупная, средняя или особо крупная). Для третичного дробления (получение мелкого щебня 5-20 мм) применяется камера типа «Низкая головка» (Short Head) с тонким или среднего типа исполнением.'
					),
					array(
						'question' => 'Что входит в систему гидравлической защиты дробилки CY?',
						'answer' => 'В систему входят гидроцилиндры защиты от перегрузок, гидроаккумуляторы и насосная станция. При попадании в камеру недробимого тела (например, зуба экскаватора), гидравлическое давление падает, чаша приподнимается, пропуская объект, и автоматически возвращается в исходное положение без остановки процесса.'
					),
					array(
						'question' => 'Предоставляет ли Fortune PROM запчасти и расходные материалы для дробилок CY?',
						'answer' => 'Да, мы поддерживаем постоянный складской запас изнашиваемых деталей (подвижная броня конуса, неподвижная броня чаши, латунные эксцентриковые втулки, фильтрующие элементы) в Алматы, что минимизирует время простоя вашего оборудования.'
					)
				);
			} elseif ($product_id == 1176) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'В чем разница между модификациями PYB, PYZ и PYD в дробилке PY-600?',
						'answer' => 'Они отличаются профилем камеры дробления и назначением. PYB-600 — стандартная модель для крупного/среднего дробления (принимает куски до 65 мм, выход 12–25 мм). PYZ-600 — средний профиль камеры для среднего дробления (вход до 35 мм, выход 5–15 мм). PYD-600 — короткоконусная модель с низкой головкой для мелкого дробления (вход до 15 мм, выход 3–10 мм).'
					),
					array(
						'question' => 'Как работает пружинная защита от перегрузок в дробилке PY-600?',
						'answer' => 'Кожух дробилки соединен с рамой мощными витыми стальными пружинами. При попадании в камеру недробимого предмета (например, куска металла), усилие преодолевает сопротивление пружин, чаша приподнимается, выпуская предмет, после чего пружины мгновенно возвращают чашу в исходное положение без поломки деталей.'
					),
					array(
						'question' => 'Какие требования предъявляются к смазочным материалам для конусной дробилки PY-600?',
						'answer' => 'Для бесперебойной работы эксцентриковой втулки и подшипников требуется непрерывная циркуляционная смазка. Рекомендуется использовать качественные индустриальные масла с противозадирными присадками (класс вязкости ISO VG 100 или 150), например Mobil Gear 600 XP 150 или отечественные аналоги.'
					),
					array(
						'question' => 'Какие изнашиваемые детали конусной дробилки PY-600 подвержены наибольшему износу?',
						'answer' => 'Основными расходными материалами являются броня подвижного конуса и броня неподвижной чаши (изготавливаются из износостойкой высокомарганцовистой стали 110Г13Л / Mn13Cr2), а также бронзовая эксцентриковая втулка и подпятник. ТОО «Fortune PROM» поддерживает их постоянное наличие на складе в Алматы.'
					),
					array(
						'question' => 'Каковы габаритные размеры и вес PY-600 для транспортировки?',
						'answer' => 'Масса дробилки в сборе составляет 5.0 тонн. Габаритные размеры — 2250 × 1370 × 1700 мм. Оборудование является негабаритным по высоте и массе для стандартных малотоннажных грузовиков, поэтому доставка осуществляется автотранспортом соответствующей грузоподъемности или манипулятором.'
					)
				);
			} elseif ($product_id == 1412) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Для каких целей в основном используется конусная дробилка PYD-1750?',
						'answer' => 'Модель PYD-1750 — это тяжелая короткоконусная дробилка (Short Head), предназначенная специально для третьей (финишной) стадии мелкого дробления высокопрочных и абразивных материалов. Она оптимальна для производства кубовидного щебня мелких фракций (5–10 мм, 5–20 мм) и отсева, а также для подготовки рудного сырья к измельчению на обогатительных фабриках.'
					),
					array(
						'question' => 'Как реализована защита от попадания недробимых тел в дробилке PYD-1750?',
						'answer' => 'Защита осуществляется за счет блока тяжелых пружин, установленных по периметру опорного кольца. При попадании в камеру недробимого куска (например, элемента ковша экскаватора), пружины сжимаются, позволяя регулирующему кольцу вместе с неподвижным конусом приподняться и выпустить объект. При систематических перегрузках срабатывает электрическая защита, отключающая главный привод.'
					),
					array(
						'question' => 'Каковы особенности монтажа дробилки PYD-1750 из-за её веса в 50 тонн?',
						'answer' => 'Из-за внушительного веса в 50.2 тонн и мощных динамических нагрузок при работе, дробилка PYD-1750 требует монтажа на массивный железобетонный фундамент, рассчитанный проектной организацией. Для снижения вибрационных воздействий на строительные конструкции применяются специальные резиновые или пружинные виброизоляторы.'
					),
					array(
						'question' => 'Какие требования к смазке у конусной дробилки PYD-1750?',
						'answer' => 'Дробилка снабжена централизованной автоматической системой жидкой смазки под давлением с подогревом и охлаждением масла. Масло подается к опорному подпятнику, приводным шестерням и эксцентриковой втулке. Используются специализированные редукторные масла класса вязкости ISO VG 150 с противозадирными присадками, требующие регулярной фильтрации.'
					),
					array(
						'question' => 'Предоставляет ли ТОО «Fortune PROM» услуги по запуску дробилок PYD-1750?',
						'answer' => 'Да, наши инженеры осуществляют полный комплекс шеф-монтажных и пусконаладочных работ непосредственно на объекте заказчика в любой точке Казахстана, проводят проверку работоспособности систем смазки и гидравлики под нагрузкой, а также осуществляют обучение обслуживающего персонала.'
					)
				);
			} elseif ($product_id == 2190) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Каковы главные преимущества насадного (плоского) исполнения мотор-редукторов MPD?',
						'answer' => 'Конструкция с полым валом монтируется непосредственно на приводной вал рабочего механизма (например, конвейера), фиксируясь с помощью реактивной штанги (моментного рычага). Это полностью исключает необходимость в соединительных муфтах, переходных рамах и трудоемкой лазерной центровке валов, экономя пространство и исключая несоосность.'
					),
					array(
						'question' => 'Какое масло заливается в мотор-редукторы SITI MPD?',
						'answer' => 'Младший габарит MPD 80 поставляется с завода заправленным синтетическим маслом на весь срок службы и не требует обслуживания. Габариты от MPD 90 до MPD 150 поставляются без смазки. В них необходимо залить качественное минеральное или синтетеческое редукторное масло класса вязкости ISO VG 220 (например, Mobilgear 600 XP 220) в объеме, соответствующем выбранному монтажному положению (M1-M6).'
					),
					array(
						'question' => 'Можно ли использовать редукторы MPD во влажных или пыльных условиях?',
						'answer' => 'Да. Стандартный класс защиты двигателя составляет IP55, а сам редуктор оснащен высококачественными сальниками из бутадиен-нитрильного каучука (NBR) или витона (FKM). Для особо тяжелых сред мы можем укомплектовать редукторы защитными крышками для полого вала, сальниками с дополнительной пылезащитной кромкой и специальной антикоррозийной окраской.'
					),
					array(
						'question' => 'С какими двигателями поставляются мотор-редукторы MPD?',
						'answer' => 'Агрегаты комплектуются трехфазными асинхронными электродвигателями переменного тока (0.09 – 22 кВт) европейского стандарта IEC с классом энергоэффективности IE1, IE2 или IE3. Доступны версии с тормозом, независимой вентиляцией (для работы с частотным преобразователем) и взрывозащищенным исполнением (ATEX).'
					),
					array(
						'question' => 'Совместимы ли запчасти SITI с другими европейскими брендами?',
						'answer' => 'Серия SITI MPD имеет стандартные присоединительные размеры по фланцам IEC и диаметрам полого вала, что позволяет заменять аналогичные плоские мотор-редукторы других европейских марок (например, SEW-Eurodrive серии F или Flender/Siemens) без изменения конструкции приводной станции.'
					)
				);
			} elseif ($product_id == 2191) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'В чем разница между сериями редукторов RFV и MPD бренда SITI?',
						'answer' => 'Обе серии представляют собой насадные цилиндрические редукторы с параллельными валами. Однако серия RFV поставляется как самостоятельный редуктор с входным полым валом и фланцем под электродвигатель стандарта IEC (PAM) для быстрой сборки с любым сторонним мотором. Серия MPD представляет собой готовый мотор-редуктор, оптимизированный под непосредственную интеграцию электродвигателя.'
					),
					array(
						'question' => 'Как правильно смонтировать редуктор RFV на вал приводного механизма?',
						'answer' => 'Монтаж выполняется путем сопряжения полого вала редуктора со сплошным валом механизма по скользящей посадке с использованием шпоночного соединения. Для облегчения последующего демонтажа рекомендуется обработать вал противозадирной пастой. Фиксация редуктора от проворачивания осуществляется через реактивный кронштейн (моментный рычаг) с резиновым буфером.'
					),
					array(
						'question' => 'Какие монтажные положения возможны для редукторов SITI RFV?',
						'answer' => 'Редукторы RFV могут монтироваться в любом из 6 стандартных пространственных положений (H1, H2, H3, H4, V5, V6). При заказе важно указать монтажное положение, так как от него зависит уровень заливки масла, а также расположение заливных, контрольных и сливных пробок.'
					),
					array(
						'question' => 'Каковы сроки поставки редукторов RFV и запчастей в Казахстан?',
						'answer' => 'ТОО «Fortune PROM» поддерживает ходовые габариты редукторов RFV (80, 90, 100) на складе в Алматы. Срок сборки под требуемое передаточное число составляет 1–2 рабочих дня. Редкие или крупные типоразмеры поставляются под заказ напрямую из Италии в течение 4–6 недель.'
					),
					array(
						'question' => 'Каков ресурс работы цилиндрических шестерен редукторов RFV?',
						'answer' => 'Все шестерни редукторов SITI изготавливаются из легированной стали с последующей цементацией, закалкой и точным шлифованием профиля зубьев. При соблюдении паспортных режимов нагрузок, правильном выборе сервис-фактора и своевременной замене масла ресурс зубчатых передач составляет не менее 15 000 – 20 000 рабочих часов.'
					)
				);
			} elseif ($product_id == 2192) {
				$data['product_faq_items'] = array(
					array('question' => 'Что такое редуктор Siti серии M?', 'answer' => 'Siti M — цилиндрический редуктор с параллельными валами для промышленных приводов, где нужна компактная компоновка, высокий КПД и стабильная передача момента.'),
					array('question' => 'К какой категории относится Siti M?', 'answer' => 'Карточка размещена в ветке «Редукторы > Цилиндрические редукторы > Плоско-цилиндрические редукторы», потому что серия M относится к цилиндрическим редукторам с параллельными валами.'),
					array('question' => 'Какие параметры нужны для подбора Siti M?', 'answer' => 'Нужны типоразмер, передаточное число, расчетный момент, исполнение валов, фланец, монтажное положение, габариты и режим нагрузки.'),
					array('question' => 'Можно ли подобрать аналог Siti M?', 'answer' => 'Да. Аналог подбирают по моменту, передаточному числу, валам, фланцу, креплению и посадочным размерам оборудования.')
				);
			} elseif ($product_id == 2193) {
				$data['product_faq_items'] = array(
					array('question' => 'Что такое редуктор Siti серии Z?', 'answer' => 'Siti Z — цилиндрический редуктор с параллельными осями для приводов, где важно сохранить компактный корпус и надежную передачу момента.'),
					array('question' => 'К какой категории относится Siti Z?', 'answer' => 'Карточка относится к разделу «Редукторы > Цилиндрические редукторы > Плоско-цилиндрические редукторы». Это правильная ветка для редукторов с параллельной компоновкой валов.'),
					array('question' => 'Где применяют Siti Z?', 'answer' => 'Серию Z применяют в конвейерах, транспортерах, дозаторах, упаковочных линиях и других механизмах с компактным цилиндрическим редуктором.'),
					array('question' => 'Что проверить перед заказом Siti Z?', 'answer' => 'Нужно подтвердить типоразмер, передаточное число, момент, исполнение валов, монтаж, фланец, габариты и режим нагрузки оборудования.')
				);
			} elseif ($product_id == 400101995) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Каковы особенности расшифровки маркировки мотор-редуктора NMRW 030-270-0.25-B8?',
						'answer' => 'В маркировке указано: NMRW (серия червячного мотор-редуктора с круглым однокомпонентным корпусом), 030 (межосевое расстояние редуктора в мм, самый компактный габарит), 270 (общее передаточное число, обеспечивающее на выходе около 5.2 об/мин при работе с двигателем 1400 об/мин), 0.25 (мощность встроенного электродвигателя в кВт), B8 (монтажное исполнение: редуктор крепится на вертикальную плоскость выходным валом вверх).'
					),
					array(
						'question' => 'Нужно ли заправлять маслом мотор-редуктор NMRW 030 перед пуском?',
						'answer' => 'Нет, малогабаритный червячный редуктор NMRW 030 поставляется с завода заправленным качественным синтетическим маслом на весь срок службы и является полностью необслуживаемым. Оно рассчитано на эксплуатацию в широком температурном диапазоне (от -25°C до +50°C) и не требует замены или доливки.'
					),
					array(
						'question' => 'Где чаще всего применяется мотор-редуктор NMRW 030 с передаточным числом 270?',
						'answer' => 'За счет высокого передаточного отношения (i=270) и компактных размеров, данная модель незаменима в приводах с низкой скоростью вращения и небольшим крутящим моментом. Он применяется в легких ленточных конвейерах, лабораторных мешалках, дозаторах сыпучих материалов, механизмах открытия ворот, рекламных стендах и пищевом оборудовании.'
					),
					array(
						'question' => 'Что означает монтажное исполнение B8 и как оно влияет на эксплуатацию?',
						'answer' => 'Монтажное исполнение B8 определяет пространственное положение редуктора при установке (корпус закреплен так, что полый выходной вал ориентирован вертикально). Поскольку габарит 030 заправлен синтетическим маслом и герметичен, исполнение B8 не требует установки сапуна и полностью исключает риск утечки смазочных материалов.'
					),
					array(
						'question' => 'Каков КПД у червячного мотор-редуктора с передаточным числом 270?',
						'answer' => 'Из-за физических особенностей червячной передачи, при высоких передаточных числах (i=270, которое обычно реализуется как двухступенчатый комбинированный червячный редуктор PCRV/DRV или комбинация редуктора 030 с предварительной цилиндрической ступенью), КПД составляет около 50-60%. Преимуществом является эффект самоторможения (отсутствие обратного вращения при выключенном двигателе).'
					)
				);
			} elseif ($product_id == 400101996) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Каковы особенности электродвигателя серии 5АИСЕ1 в мотор-редукторе NMRW 030?',
						'answer' => 'Маркировка 5АИСЕ1 обозначает специализированный однофазный асинхронный электродвигатель (с питанием от сети 220 В) с повышенным пусковым моментом и встроенным рабочим конденсатором. Двигатели серии АИС выполнены по европейскому стандарту DIN/CENELEC (привязка мощностей к габаритам), что делает мотор-редуктор идеальным выбором для подключения к обычной бытовой розетке 220В на производствах или в частных мастерских.'
					),
					array(
						'question' => 'Как осуществляется пуск и подключение однофазного двигателя 5АИСЕ1?',
						'answer' => 'Двигатель поставляется с конденсаторной коробкой, в которой уже смонтированы рабочие конденсаторы. Для запуска требуется стандартная однофазная сеть переменного тока 220 В (50 Гц). Реверс направления вращения осуществляется перекоммутацией перемычек в клеммной коробке (борно) согласно схеме, приведенной на внутренней стороне крышки.'
					),
					array(
						'question' => 'Какое обслуживание требуется мотор-редуктору NMRW 030-270-0.25-B8 (5АИСЕ1)?',
						'answer' => 'Червячная часть редуктора 030-го габарита является полностью необслуживаемой и заправлена синтетическим маслом на заводе на весь срок службы. Электродвигатель 5АИСЕ1 оснащен закрытыми подшипниками, не требующими пополнения смазки. Обслуживание сводится к периодической очистке корпуса от пыли для обеспечения нормального охлаждения.'
					),
					array(
						'question' => 'Какие защитные устройства рекомендуется установить для однофазного мотора 220В?',
						'answer' => 'Из-за возможных колебаний напряжения в однофазной сети и риска перегрузок рекомендуется подключать мотор через автоматический выключатель защиты двигателя (тепловое реле) соответствующего номинала (рабочий ток двигателя мощностью 0.25 кВт составляет около 1.8–2.0 А). Также двигатель оснащен встроенным термореле для защиты обмоток от перегрева.'
					),
					array(
						'question' => 'Влияет ли монтажное положение B8 на работу двигателя и редуктора?',
						'answer' => 'При монтаже в исполнении B8 (редуктор закреплен вертикально на плоскости) однофазный двигатель располагается горизонтально, что является стандартным и безопасным для него режимом работы. Редуктор 030 герметичен, поэтому масло не перетекает в клеммную коробку мотора.'
					)
				);
			} elseif ($product_id == 2196) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Каково основное назначение цилиндрических крановых редукторов РК-450?',
						'answer' => 'Редукторы серии РК-450 — это специальные двухступенчатые цилиндрические редукторы узкого типа, предназначенные в первую очередь для приводов механизмов передвижения грузоподъемных кранов (мостовых, козловых, башенных) и крановых тележек. Они обеспечивают изменение крутящих моментов и частоты вращения в широком диапазоне передаточных чисел при повторно-кратковременных режимах работы.'
					),
					array(
						'question' => 'В чем особенности конструкции редукторов РК-450?',
						'answer' => 'Редуктор имеет вертикальный или горизонтальный разъемный корпус из прочного серого чугуна марки СЧ20. Шестерни редуктора изготавливаются из легированных сталей с эвольвентным зацеплением, подвергаются термической обработке (улучшению, закалке ТВЧ) для повышения износостойкости. Валы установлены на надежные подшипники качения. Выходной вал может быть выполнен в виде зубчатой муфты, полым или цилиндрическим.'
					),
					array(
						'question' => 'Какие требования к смазке у редукторов РК-450?',
						'answer' => 'Смазка зубчатых зацеплений и подшипников осуществляется методом окунания колес в масляную ванну картера (картерная система смазки). Используются специализированные индустриальные редукторные масла повышенной вязкости (например, ИТД-150, ИТД-220 или зарубежные аналоги класса ISO VG 150/220). Необходим регулярный контроль уровня масла по маслоуказателю и его полная замена каждые 500–1000 часов наработки.'
					),
					array(
						'question' => 'В каких климатических и температурных условиях могут эксплуатироваться редукторы РК-450?',
						'answer' => 'В соответствии с ГОСТ 15150 редукторы РК-450 выпускаются в климатических исполнениях У (умеренный климат) и Т (тропический климат) категорий размещения 1–4. Они стабильно работают на открытом воздухе или под навесом в температурном диапазоне от -40°C до +40°C при неагрессивной запыленной среде.'
					),
					array(
						'question' => 'Предоставляет ли ТОО «Fortune PROM» гарантийное и сервисное обслуживание на редукторы РК-450?',
						'answer' => 'Да, мы предоставляем официальную заводскую гарантию 12 месяцев со дня отгрузки. ТОО «Fortune PROM» поставляет как редукторы РК-450 в сборе (любых вариантов сборки и передаточных чисел), так и отдельные комплектующие к ним (шестерни, промежуточные валы, зубчатые пары и колеса), а также выполняет консультации по монтажу и вводу в эксплуатацию.'
					)
				);
			} elseif ($product_id == 2197) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Каковы отличия кранового редуктора РК-500 от младшей модели РК-450?',
						'answer' => 'Основное отличие заключается в габаритных размерах и межосевом расстоянии тихоходной ступени, которое у редуктора РК-500 составляет 500 мм (против 450 мм у РК-450). За счет увеличенных габаритов и более массивных косозубых передач модель РК-500 способна передавать крутящий момент до 20 000 Н·м и выдерживать значительно большие радиальные и консольные нагрузки на валах.'
					),
					array(
						'question' => 'Какова масса и габариты цилиндрического редуктора РК-500?',
						'answer' => 'Редуктор РК-500 относится к классу тяжелого приводного оборудования. Его чистый вес (без учета заправленного масла) составляет порядка 1140 кг. Для монтажа и транспортировки чугунный корпус снабжен специальными грузовыми проушинами. Высота редуктора превышает 1 метр, поэтому его монтаж требует применения подъемной техники.'
					),
					array(
						'question' => 'Какие варианты сборки и конфигурации валов доступны для РК-500?',
						'answer' => 'В соответствии с требованиями заказчика редуктор собирается по различным схемам (варианты сборки 11, 12, 13, 21, 22, 23, 31, 32, 33). Быстроходный вал может иметь конический или цилиндрический конец. Тихоходный вал поставляется в виде сплошного цилиндрического вала, конического, полого вала или со специальным зубчатым концом (в виде полумуфты) для прямого соединения с барабаном лебедки крана.'
					),
					array(
						'question' => 'Каковы особенности смазочной системы редуктора РК-500?',
						'answer' => 'РК-500 использует картерный метод смазки методом разбрызгивания (жидкое редукторное масло). Объем масляной ванны в картере составляет от 30 до 40 литров. Для обеспечения надежного масляного клина в зацеплениях тяжелых шестерен рекомендуется применять индустриальные редукторные масла класса вязкости ISO VG 220 (например, ИТД-220, Mobilgear 600 XP 220, Shell Omala S2 G 220).'
					),
					array(
						'question' => 'Какие запчасти для редуктора РК-500 можно приобрести в ТОО «Fortune PROM»?',
						'answer' => 'ТОО «Fortune PROM» поставляет весь перечень оригинальных запасных частей для ремонта крановых редукторов РК-500. Вы можете заказать отдельно: комплект вал-шестерен быстроходной и промежуточной ступеней, тихоходное колесо (зубчатый венец), валы в сборе с шестернями и подшипниками, регулировочные прокладки и уплотнительные манжеты.'
					)
				);
			} elseif ($product_id == 2198) {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Каковы ключевые технические отличия редуктора РК-600 от младших моделей серии РК?',
						'answer' => 'Редуктор РК-600 является самым крупным и мощным представителем линейки крановых двухступенчатых редукторов РК. Межосевое расстояние его тихоходной ступени составляет 600 мм. Данный габарит способен передавать крутящий момент до 32 000 Н·м (что более чем в 1.5 раза превышает показатели РК-500) и выдерживать колоссальные консольные нагрузки на выходном валу до 130 000 Н.'
					),
					array(
						'question' => 'Каков вес редуктора РК-600 и особенности его монтажа?',
						'answer' => 'Ввиду своих огромных размеров и массивности чугунного корпуса, чистый вес редуктора РК-600 в сборе без залитого масла составляет около 1720 кг. Из-за веса почти в 2 тонны монтаж, центровка валов и установка на фундаментную раму крановой тележки производятся строго с использованием мостовых или автокранов достаточной грузоподъемности через предусмотренные в корпусе такелажные проушины.'
					),
					array(
						'question' => 'Какой объем масла требуется для заправки редуктора РК-600 и как часто его менять?',
						'answer' => 'Для нормальной работы косозубых передач картерная масляная ванна редуктора РК-600 должна быть заправлена жидким маслом в объеме от 45 до 55 литров. Рекомендуется использовать специализированные трансмиссионные и редукторные масла класса вязкости ISO VG 220 или 320 (например, ИТД-220, ИТД-320, Mobilgear 600 XP 220). Первую замену масла проводят после 100 часов обкатки, последующие — каждые 1000 часов работы прибора.'
					),
					array(
						'question' => 'Какие схемы сборки и виды валов доступны для тяжелого редуктора РК-600?',
						'answer' => 'Мы предлагаем редукторы РК-600 во всех стандартных вариантах сборки (схемы 11, 12, 13, 21, 22, 23, 31, 32, 33). Быстроходный вал может иметь конический или цилиндрический конец. Выходной тихоходный вал выполняется в виде сплошного цилиндра, конуса или с зубчатым венцом (зубчатый вал-муфта) для непосредственного соединения с грузовым барабаном лебедки.'
					),
					array(
						'question' => 'Предоставляет ли ТОО «Fortune PROM» техническую документацию на РК-600?',
						'answer' => 'Да, ТОО «Fortune PROM» поставляет редукторы РК-600 с полным пакетом технической документации на русском языке: паспорт изделия, руководство по монтажу и эксплуатации, чертежи присоединительных и габаритных размеров, а также сертификаты соответствия стандартам и ГОСТ.'
					)
				);
			} elseif (isset($remaining_reducer_seo[(int)$product_id])) {
				$remaining_reducer = $remaining_reducer_seo[(int)$product_id];
				$data['product_faq_items'] = array(
					array(
						'question' => 'К какой категории относится ' . $remaining_reducer['model'] . '?',
						'answer' => 'Карточка размещена в ветке «' . $remaining_reducer['category'] . '». Такая структура помогает искать товар по серии, типоразмеру и назначению, а также связывает страницу с правильным разделом каталога.'
					),
					array(
						'question' => 'Где применяют ' . $remaining_reducer['model'] . '?',
						'answer' => $remaining_reducer['model'] . ' применяют в таких задачах: ' . $remaining_reducer['application'] . '. Перед заказом важно сверить фактическую нагрузку и режим работы оборудования.'
					),
					array(
						'question' => 'Какие параметры нужны для подбора?',
						'answer' => $remaining_reducer['checks']
					),
					array(
						'question' => 'Можно ли подобрать аналог?',
						'answer' => 'Да. Аналог подбирается ' . $remaining_reducer['selection'] . '. Для проверки совместимости лучше отправить фото шильдика, размеры валов и посадочных мест, монтажную схему и описание нагрузки.'
					),
					array(
						'question' => 'Как формируется цена и срок поставки?',
						'answer' => 'Цена зависит от производителя, исполнения, передаточного числа, мощности или момента, комплектации, наличия и партии поставки. Fortune PROM подтверждает стоимость, срок и гарантию после технической сверки.'
					)
				);
			} else {
				$data['product_faq_items'] = array(
					array(
						'question' => 'Как узнать точную цену?',
						'answer' => 'Нажмите «Запросить цену» или напишите в WhatsApp. Менеджер уточнит наличие, комплектацию и рассчитает актуальную стоимость.'
					),
					array(
						'question' => 'Можно ли подобрать аналог?',
						'answer' => 'Да. Для подбора аналога пришлите модель, фото шильдика, мощность, передаточное число, исполнение и условия работы оборудования.'
					),
					array(
						'question' => 'Работаете ли вы с регионами Казахстана?',
						'answer' => 'Да, отправляем заказы по Казахстану транспортными компаниями. Срок и стоимость доставки согласовываются перед оплатой.'
					),
					array(
						'question' => 'Можно ли получить счёт и документы?',
						'answer' => 'Да. После подтверждения заказа подготовим счёт на оплату и необходимые закрывающие документы для компании.'
					),
					array(
						'question' => 'Есть ли гарантия на товар?',
						'answer' => 'Гарантийные условия зависят от типа оборудования и поставки. Менеджер подтвердит срок гарантии перед оформлением заказа.'
					)
				);
			}

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
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
					'currency'    => $this->session->data['currency'],
					'availability' => $result['quantity'] > 0,
					'has_price_schema' => false,
					'schema_price' => '',
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			// JSON-LD BreadcrumbList
			$schema_bc = array('@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => array());
			$schema_bc_position = 1;
			foreach ($data['breadcrumbs'] as $i => $crumb) {
				$crumb_name = trim(strip_tags(html_entity_decode($crumb['text'], ENT_QUOTES, 'UTF-8')));
				$crumb_href = html_entity_decode($crumb['href'], ENT_QUOTES, 'UTF-8');
				$is_last_crumb = ($i + 1) === count($data['breadcrumbs']);
				$is_search_crumb = (strpos($crumb_href, 'route=product/search') !== false) || preg_match('/[?&](search|tag)=/', $crumb_href);

				if ($crumb_name === '') {
					$crumb_name = ($i === 0) ? 'FortunePROM.kz' : '';
				}

				if ($crumb_name === '' || ($is_search_crumb && !$is_last_crumb)) {
					continue;
				}

				$schema_bc['itemListElement'][] = array(
					'@type' => 'ListItem',
					'position' => $schema_bc_position++,
					'name' => $crumb_name,
					'item' => $is_last_crumb ? $product_url : $crumb['href']
				);
			}

			// JSON-LD Product Schema
			$product_description_source = !empty($seo_meta['description']) ? $seo_meta['description'] : ($product_info['meta_description'] ?: $product_info['description']);
			$product_description = trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode($product_description_source, ENT_QUOTES, 'UTF-8'))));
			$product_images = array();

			if (!empty($data['popup'])) {
				$product_images[] = $data['popup'];
			}

			if (!empty($data['images'])) {
				foreach ($data['images'] as $image_data) {
					if (!empty($image_data['popup'])) {
						$product_images[] = $image_data['popup'];
					}
				}
			}

			$schema_p = array(
				'@context' => 'https://schema.org', '@type' => 'Product',
				'@id' => $product_url . '#product',
				'name' => $product_info['name'],
				'url' => $product_url,
				'description' => $product_description
			);

			// Fallback image for products without a picture (Google requires at least one image)
			if (empty($product_images)) {
				$product_images[] = HTTPS_SERVER . 'image/catalog/fortune-prom-logo-tight-bottom-transparent.png';
			}
			$schema_p['image'] = array_values(array_unique($product_images));

			if (!empty($product_info['sku'])) {
				$schema_p['sku'] = $product_info['sku'];
			}

			if (!empty($product_info['model'])) {
				$schema_p['mpn'] = $product_info['model'];
				$schema_p['model'] = $product_info['model'];
			}

			if (!empty($product_info['manufacturer'])) {
				$schema_p['brand'] = array('@type' => 'Brand', 'name' => $product_info['manufacturer']);
			}

			if ($schema_price_value > 0) {
				$schema_p['offers'] = array(
					'@type' => 'Offer',
					'url' => $product_url,
					'priceCurrency' => 'KZT',
					'price' => number_format((float)$schema_price_value, 2, '.', ''),
					'availability' => $product_info['quantity'] > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
					'hasMerchantReturnPolicy' => array(
						'@type' => 'MerchantReturnPolicy',
						'applicableCountry' => 'KZ',
						'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
						'merchantReturnDays' => 14,
						'returnMethod' => 'https://schema.org/ReturnByMail'
					)
				);
			}

			if ($product_id == 214 || $product_id == 215 || $product_id == 1175 || $product_id == 2122) {
				$schema_p['brand'] = array('@type' => 'Brand', 'name' => 'Fortune PROM');
				$schema_p['offers'] = array(
					'@type' => 'Offer',
					'url' => $product_url,
					'priceCurrency' => 'KZT',
					'price' => '0.00',
					'availability' => 'https://schema.org/InStock',
					'hasMerchantReturnPolicy' => array(
						'@type' => 'MerchantReturnPolicy',
						'applicableCountry' => 'KZ',
						'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
						'merchantReturnDays' => 14,
						'returnMethod' => 'https://schema.org/ReturnByMail'
					)
				);
			}

			$schema_properties = array();

			foreach ($data['product_spec_rows'] as $spec) {
				if (!empty($spec['name']) && (string)$spec['value'] !== '') {
					$schema_properties[] = array('@type' => 'PropertyValue', 'name' => $spec['name'], 'value' => strip_tags((string)$spec['value']));
				}
			}

			foreach ($data['attribute_groups'] as $attribute_group) {
				if (!empty($attribute_group['attribute'])) {
					foreach ($attribute_group['attribute'] as $attribute) {
						if (!empty($attribute['name']) && (string)$attribute['text'] !== '') {
							$schema_properties[] = array('@type' => 'PropertyValue', 'name' => $attribute['name'], 'value' => strip_tags((string)$attribute['text']));
						}
					}
				}
			}

			if ($schema_properties) {
				$schema_p['additionalProperty'] = $schema_properties;
			}

			if ($product_info['rating'] > 0 && $product_info['reviews'] > 0) {
				$schema_p['aggregateRating'] = array('@type' => 'AggregateRating', 'ratingValue' => $product_info['rating'], 'reviewCount' => $product_info['reviews']);
			}

			$schema_faq = array('@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array());

			foreach ($data['product_faq_items'] as $faq_item) {
				if (!empty($faq_item['question']) && !empty($faq_item['answer'])) {
					$schema_faq['mainEntity'][] = array(
						'@type' => 'Question',
						'name' => strip_tags($faq_item['question']),
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text' => strip_tags($faq_item['answer'])
						)
					);
				}
			}

			$json_ld = '<script type="application/ld+json">' . json_encode($schema_bc, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';

			$json_ld .= '<script type="application/ld+json">' . json_encode($schema_p, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';



			if (!empty($schema_faq['mainEntity'])) {
				$json_ld .= '<script type="application/ld+json">' . json_encode($schema_faq, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
			}

			$data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if ($product_id == 215) {
				$data['heading_title'] = 'Валковая дробилка Xuanshi';
			} elseif ($product_id == 214) {
				$data['heading_title'] = 'Валковая дробилка';
			} elseif ($product_id == 1175) {
				$data['heading_title'] = 'Конусная дробилка КСД-600 (ДРО-592)';
			}

			$data['json_ld'] = $json_ld ?? '';
			$this->response->setOutput($this->load->view('product/product', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
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

	public function review() {
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('product/review', $data));
	}

	public function write() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function getPriorityReducerMetaMap() {
		return array(
			400101672 => array(
				'title' => 'NMRV 075-60-1,5/1500-B3 мотор-редуктор | Fortune PROM',
				'description' => 'Мотор-редуктор NMRV 075-60-1,5/1500-B3 с i=60 и двигателем 1,5 кВт. Подбор по валу, монтажу B3 и нагрузке, доставка по Казахстану.'
			),
			215 => array(
				'title' => 'Валковая дробилка Xuanshi купить в Алматы: цена, характеристики, чертежи | Fortune PROM',
				'description' => 'Купить валковую дробилку Xuanshi по выгодной цене в Алматы и Казахстане от Fortune PROM. Технические характеристики, габаритные чертежи, профессиональный подбор и доставка по регионам РК.'
			),
			214 => array(
				'title' => 'Валковая дробилка купить в Алматы: цена, характеристики, чертежи | Fortune PROM',
				'description' => 'Купить валковую дробилку по выгодной цене в Алматы и Казахстане от Fortune PROM. Технические характеристики, габаритные чертежи, профессиональный подбор и доставка по регионам РК.'
			),
			1175 => array(
				'title' => 'Конусная дробилка КСД-600 (ДРО-592) купить в Алматы: цена, характеристики, чертежи | Fortune PROM',
				'description' => 'Купить конусную дробилку КСД-600 (ДРО-592) по цене производителя в Алматы и Казахстане от Fortune PROM. Технические характеристики, габаритные чертежи, помощь в подборе и быстрая доставка по регионам РК.'
			),
			2122 => array(
				'title' => '1Ц2У-200 редуктор цилиндрический | Fortune PROM',
				'description' => 'Редуктор 1Ц2У-200 цилиндрический двухступенчатый для промышленных приводов. Подбор по передаточному числу, валам, сборке и нагрузке.'
			),
			2142 => array(
				'title' => '3МВз волновые мотор-редукторы | Fortune PROM',
				'description' => 'Волновые мотор-редукторы 3МВз для точных промышленных приводов. Подбор по типоразмеру, моменту, передаточному числу и монтажу.'
			),
			2167 => array(
				'title' => 'Lenze GKR конические мотор-редукторы | Fortune PROM',
				'description' => 'Конические мотор-редукторы Lenze GKR для промышленных приводов. Подбор аналога по серии, моменту, передаточному числу, валу и монтажу.'
			),
			2168 => array(
				'title' => 'Lenze GKS коническо-цилиндрические мотор-редукторы | Fortune PROM',
				'description' => 'Коническо-цилиндрические мотор-редукторы Lenze GKS. Подбор по типоразмеру, моменту, валу, фланцу, передаточному числу и монтажу.'
			),
			2157 => array(
				'title' => 'Bauer BK конический мотор-редуктор | Fortune PROM',
				'description' => 'Конический мотор-редуктор Bauer серии BK для промышленных приводов. Подбор по шильдику, моменту, передаточному числу, валу и монтажу.'
			)
		);
	}

	private function getRemainingReducerSeoMap() {
		$worm_category = 'Редукторы > Червячные редукторы > ';
		$cyl_category = 'Редукторы > Цилиндрические редукторы > ';
		$kcc_category = 'Редукторы > Коническо-цилиндрические редукторы > ';
		$motor_category = 'Редукторы > Мотор-редукторы > ';
		$crane_category = 'Редукторы > Крановые редукторы > ';

		$items = array(
			2123 => array('path' => '236_2442_2450_2642', 'h1' => 'Редуктор NRV 030 червячный', 'series' => 'NRV', 'model' => 'NRV 030', 'type' => 'червячный редуктор', 'layout' => 'угловая червячная передача без встроенного электродвигателя', 'application' => 'компактные конвейеры, дозаторы, небольшие приводы, упаковочные узлы и вспомогательные механизмы', 'selection' => 'по типоразмеру 030, передаточному числу, валу, фланцу PAM и монтажному положению', 'checks' => 'Перед заказом NRV 030 подтвердите передаточное число, диаметр выходного вала, входной фланец под двигатель, монтажное положение, нагрузку и режим работы.', 'category' => $worm_category . 'Редукторы NRV > NRV 030'),
			2124 => array('path' => '236_2442_2450_2643', 'h1' => 'Редуктор NRV 040 червячный', 'series' => 'NRV', 'model' => 'NRV 040', 'type' => 'червячный редуктор', 'layout' => 'угловая червячная передача с компактным корпусом', 'application' => 'конвейеры, транспортеры, дозаторы, мешалки и небольшие промышленные приводы', 'selection' => 'по типоразмеру 040, передаточному числу, выходному валу, фланцу двигателя и монтажу', 'checks' => 'Перед заказом NRV 040 нужно сверить передаточное число, размеры валов и фланцев, монтажное положение, фактическую нагрузку и условия эксплуатации.', 'category' => $worm_category . 'Редукторы NRV > NRV 040'),
			2125 => array('path' => '236_2442_2452_2644', 'h1' => 'Редуктор 2Ч-40 червячный одноступенчатый', 'series' => '2Ч', 'model' => '2Ч-40', 'type' => 'червячный одноступенчатый редуктор', 'layout' => 'червячная передача с межосевым расстоянием 40 мм', 'application' => 'легкие промышленные приводы, дозаторы, небольшие транспортеры, заслонки и вспомогательные механизмы', 'selection' => 'по типоразмеру 2Ч-40, передаточному числу, варианту сборки, валам и креплению', 'checks' => 'Для подбора 2Ч-40 нужны передаточное число, вариант сборки, размеры валов, монтажное положение, нагрузка и габаритные ограничения.', 'category' => $worm_category . 'Редукторы 2Ч > 2Ч-40'),
			2126 => array('path' => '236_2442_2452_2645', 'h1' => 'Редуктор 2Ч-63 червячный', 'series' => '2Ч', 'model' => '2Ч-63', 'type' => 'червячный редуктор', 'layout' => 'червячная передача с межосевым расстоянием 63 мм', 'application' => 'приводы транспортеров, дозаторов, мешалок, подъемных и технологических механизмов', 'selection' => 'по типоразмеру 2Ч-63, передаточному числу, сборке, валам, креплению и нагрузке', 'checks' => 'Перед заказом 2Ч-63 подтвердите передаточное число, сборку, исполнение валов, крепление, монтажное положение и режим нагрузки.', 'category' => $worm_category . 'Редукторы 2Ч > 2Ч-63'),
			2127 => array('path' => '236_2442_2452_2646', 'h1' => 'Редуктор 2Ч-80 червячный', 'series' => '2Ч', 'model' => '2Ч-80', 'type' => 'червячный редуктор', 'layout' => 'червячная передача с межосевым расстоянием 80 мм', 'application' => 'приводы конвейеров, транспортеров, смесителей, станков и подъемно-транспортного оборудования', 'selection' => 'по типоразмеру 2Ч-80, передаточному числу, сборке, валам, креплению и монтажу', 'checks' => 'Для подбора 2Ч-80 нужны передаточное число, вариант сборки, диаметр и исполнение валов, габариты, нагрузка и условия эксплуатации.', 'category' => $worm_category . 'Редукторы 2Ч > 2Ч-80'),
			2189 => array('path' => '236_2446_2653', 'h1' => 'Редуктор Siti GHA для пищевого производства', 'series' => 'Siti GHA', 'model' => 'Siti GHA', 'type' => 'специальный промышленный редуктор', 'layout' => 'исполнение для технологических линий пищевой промышленности', 'application' => 'пищевые линии, конвейеры, упаковочные машины, дозаторы и моечные зоны с повышенными требованиями к приводу', 'selection' => 'по серии GHA, передаточному числу, моменту, валу, фланцу, материалам и санитарным требованиям', 'checks' => 'Перед заказом Siti GHA нужно подтвердить модель, передаточное число, момент, вал, фланец, монтаж, класс защиты и условия мойки или влажности.', 'category' => $motor_category . 'Специальные мотор-редукторы'),
			2199 => array('path' => '236_2443_2652', 'h1' => 'Редуктор Ц3ВК-100Ф цилиндрический', 'series' => 'Ц3ВК', 'model' => 'Ц3ВК-100Ф', 'type' => 'цилиндрический редуктор', 'layout' => 'цилиндрическая передача для промышленного привода', 'application' => 'конвейеры, подъемные механизмы, технологические линии и промышленное оборудование, где требуется надежное снижение оборотов', 'selection' => 'по модели Ц3ВК-100Ф, передаточному числу, валам, креплению, габаритам и нагрузке', 'checks' => 'Для подбора Ц3ВК-100Ф нужны передаточное число, исполнение валов, монтажные размеры, направление вращения, фактическая нагрузка и режим работы.', 'category' => $cyl_category . 'Редукторы Ц3ВК'),
			2210 => array('path' => '236_2442_2489_2647', 'h1' => 'Мотор-редуктор МЧ-40 червячный', 'series' => 'МЧ', 'model' => 'МЧ-40', 'type' => 'червячный мотор-редуктор', 'layout' => 'червячный редуктор с электродвигателем в компактной компоновке', 'application' => 'небольшие конвейеры, дозаторы, заслонки, смесители и вспомогательные приводы', 'selection' => 'по модели МЧ-40, передаточному числу, мощности двигателя, валам и монтажным размерам', 'checks' => 'Перед заказом МЧ-40 подтвердите передаточное число, мощность двигателя, обороты, вал, крепление, монтажное положение и фактическую нагрузку.', 'category' => $worm_category . 'Редукторы червячные Ч > Мотор-редукторы МЧ/2МЧ'),
			2211 => array('path' => '236_2442_2489_2647', 'h1' => 'Мотор-редуктор Ч2-80/160 червячный', 'series' => 'Ч2', 'model' => 'Ч2-80/160', 'type' => 'червячный мотор-редуктор', 'layout' => 'червячная передача с электродвигателем для промышленного привода', 'application' => 'транспортеры, смесители, подъемные узлы, дозаторы и технологические механизмы', 'selection' => 'по модели Ч2-80/160, передаточному числу, мощности двигателя, валам, креплению и габаритам', 'checks' => 'Для подбора Ч2-80/160 нужны шильдик, передаточное число, мощность и обороты двигателя, размеры валов, монтажная схема и нагрузка.', 'category' => $worm_category . 'Редукторы червячные Ч > Мотор-редукторы МЧ/2МЧ'),
			2214 => array('path' => '236_2446_2649', 'h1' => 'Мотор-редуктор РГ-240 промышленный', 'series' => 'РГ', 'model' => 'РГ-240', 'type' => 'промышленный мотор-редуктор', 'layout' => 'редуктор и электродвигатель в составе приводного узла', 'application' => 'конвейеры, транспортеры, производственные линии, смесители и вспомогательные промышленные механизмы', 'selection' => 'по модели РГ-240, передаточному числу, мощности двигателя, моменту, валам и монтажу', 'checks' => 'Перед заказом РГ-240 подтвердите шильдик, передаточное число, мощность и обороты двигателя, выходной вал, крепление, габариты и режим нагрузки.', 'category' => $motor_category . 'Мотор-редукторы РГ'),
			400101703 => array('path' => '236_2442_2449_2463', 'h1' => 'Мотор-редуктор NMRV 050-40-0,37/1500-B5-B3', 'title' => 'NMRV 050-40-0,37/1500-B5-B3 мотор-редуктор | Fortune PROM', 'description' => 'Мотор-редуктор NMRV 050-40-0,37/1500-B5-B3 для промышленных приводов. Подбор по валу, фланцу, монтажу и нагрузке, доставка по Казахстану.', 'series' => 'NMRV', 'model' => 'NMRV 050-40-0,37/1500-B5-B3', 'type' => 'червячный мотор-редуктор', 'layout' => 'угловая червячная передача NMRV 050 с фланцем B5 и монтажом B3', 'application' => 'конвейеры, дозаторы, упаковочные линии, мешалки, заслонки и компактные промышленные приводы малой мощности', 'selection' => 'по типоразмеру 050, i=40, мощности 0,37 кВт, выходному валу, фланцу B5, монтажу B3 и фактической нагрузке', 'checks' => 'Перед заказом NMRV 050-40 нужно подтвердить передаточное число, мощность 0,37 кВт, обороты 1500 об/мин, выходной вал, фланец B5, монтаж B3, напряжение двигателя и режим работы.', 'category' => $worm_category . 'Редукторы NMRV > NMRV 050'),
			400101672 => array('path' => '236_2442_2449_2465', 'h1' => 'Мотор-редуктор NMRV 075-60-1,5/1500-B3', 'series' => 'NMRV', 'model' => 'NMRV 075-60-1,5/1500-B3', 'type' => 'червячный мотор-редуктор', 'layout' => 'угловая червячная передача NMRV 075 с монтажом B3', 'application' => 'конвейеры, транспортеры, смесители, дозаторы, упаковочные линии и компактные промышленные приводы', 'selection' => 'по типоразмеру 075, i=60, мощности 1,5 кВт, валу, фланцу, монтажу B3 и нагрузке', 'checks' => 'Перед заказом NMRV 075-60 нужно подтвердить передаточное число, мощность двигателя, выходной вал, монтаж B3, напряжение, положение клеммной коробки и режим работы.', 'category' => $worm_category . 'Редукторы NMRV > NMRV 075'),
			400101681 => array('path' => '236_2442_2449_2466', 'h1' => 'Мотор-редуктор NMRV 090-10-2,2/1500-B5-B3', 'series' => 'NMRV', 'model' => 'NMRV 090-10-2,2/1500-B5-B3', 'type' => 'червячный мотор-редуктор', 'layout' => 'угловая червячная передача NMRV 090 с фланцем B5 и монтажом B3', 'application' => 'конвейеры, транспортеры, смесители, дозаторы и линии, где нужна компактная угловая передача', 'selection' => 'по типоразмеру 090, i=10, мощности 2,2 кВт, валу, фланцу B5, монтажу B3 и нагрузке', 'checks' => 'Перед заказом NMRV 090 подтвердите передаточное число, мощность двигателя, вал 35/38/40 мм, фланец, монтаж, напряжение и фактический режим работы.', 'category' => $worm_category . 'Редукторы NMRV > NMRV 090'),
			400101709 => array('path' => '236_2442_2449_2466', 'h1' => 'Мотор-редуктор NMRV 090-15-4/1500-B5-B3', 'series' => 'NMRV', 'model' => 'NMRV 090-15-4/1500-B5-B3', 'type' => 'червячный мотор-редуктор', 'layout' => 'угловая червячная передача NMRV 090 с фланцевым присоединением', 'application' => 'приводы конвейеров, транспортеров, смесителей, дозаторов и производственных линий', 'selection' => 'по типоразмеру 090, i=15, мощности 4 кВт, валу, фланцу, монтажу и сервис-фактору', 'checks' => 'Для подбора NMRV 090-15 нужны передаточное число, мощность 4 кВт, вал, фланец B5, монтаж B3, выходные обороты и режим нагрузки.', 'category' => $worm_category . 'Редукторы NMRV > NMRV 090'),
			400101863 => array('path' => '236_2446_2654', 'h1' => 'Поворотный редуктор FO/23 B для крана', 'series' => 'FO', 'model' => 'FO/23 B', 'type' => 'поворотный редуктор', 'layout' => 'редуктор поворотного механизма для кранового оборудования', 'application' => 'механизмы поворота кранов, подъемно-транспортное оборудование и спецтехника', 'selection' => 'по модели FO/23 B, моменту, передаточному числу, валам, креплению и габаритам', 'checks' => 'Перед заказом FO/23 B нужно подтвердить шильдик, передаточное число, момент, размеры валов, крепление, направление вращения и условия работы крана.', 'category' => $motor_category . 'Поворотные редукторы'),
			400101867 => array('path' => '236_2442_2489_2647', 'h1' => 'Мотор-редуктор 2МЧ-40-35,5-1,1 червячный', 'series' => '2МЧ', 'model' => '2МЧ-40-35,5-1,1', 'type' => 'червячный мотор-редуктор', 'layout' => 'червячный мотор-редуктор с передаточным числом 35,5 и двигателем 1,1 кВт', 'application' => 'приводы транспортеров, дозаторов, мешалок, заслонок и небольших технологических механизмов', 'selection' => 'по модели 2МЧ-40, i=35,5, мощности 1,1 кВт, валам, креплению и монтажу', 'checks' => 'Для подбора 2МЧ-40 нужны передаточное число, мощность двигателя, выходные обороты, вал, крепление, монтажное положение и нагрузка.', 'category' => $worm_category . 'Редукторы червячные Ч > Мотор-редукторы МЧ/2МЧ'),
			400101900 => array('path' => '236_2442_2449_2465', 'h1' => 'Мотор-редуктор NMRV 075-100-0,55/1500', 'series' => 'NMRV', 'model' => 'NMRV 075-100-0,55/1500', 'type' => 'червячный мотор-редуктор', 'layout' => 'угловая червячная передача NMRV 075 с высоким передаточным отношением', 'application' => 'медленные конвейеры, дозаторы, поворотные узлы, заслонки и механизмы с невысокой выходной скоростью', 'selection' => 'по типоразмеру 075, i=100, мощности 0,55 кВт, валу, фланцу, монтажу и нагрузке', 'checks' => 'Перед заказом NMRV 075-100 нужно подтвердить выходные обороты, вал, фланец, монтажное положение, напряжение двигателя и фактическую нагрузку.', 'category' => $worm_category . 'Редукторы NMRV > NMRV 075'),
			400101919 => array('path' => '236_2443_2655', 'h1' => 'Редуктор 1ЦУ-200-5-21-Ц-У3 цилиндрический', 'series' => '1ЦУ', 'model' => '1ЦУ-200-5-21-Ц-У3', 'type' => 'цилиндрический редуктор', 'layout' => 'цилиндрическая передача с межосевым расстоянием 200 мм', 'application' => 'конвейеры, технологические линии, подъемные узлы и промышленные механизмы с прямолинейной компоновкой привода', 'selection' => 'по модели 1ЦУ-200, передаточному числу i=5, сборке 21, валам, креплению и климатическому исполнению', 'checks' => 'Перед заказом 1ЦУ-200 нужно подтвердить передаточное число, сборку, исполнение валов, монтаж, габариты, нагрузку и климатическое исполнение У3.', 'category' => $cyl_category . 'Редукторы 1ЦУ'),
			400101997 => array('path' => '236_2442_2449_2464', 'h1' => 'Мотор-редуктор NMRV 063-25-1,5/1500-B5-B3', 'series' => 'NMRV', 'model' => 'NMRV 063-25-1,5/1500-B5-B3', 'type' => 'червячный мотор-редуктор', 'layout' => 'угловая червячная передача NMRV 063 с фланцем B5 и монтажом B3', 'application' => 'конвейеры, транспортеры, упаковочные линии, дозаторы и компактные промышленные приводы', 'selection' => 'по типоразмеру 063, i=25, мощности 1,5 кВт, валу, фланцу, монтажу и нагрузке', 'checks' => 'Для подбора NMRV 063-25 нужны передаточное число, мощность, выходные обороты, вал, фланец B5, монтаж B3, напряжение и сервис-фактор.', 'category' => $worm_category . 'Редукторы NMRV > NMRV 063'),
			400102000 => array('path' => '236_2442_2489_2491', 'h1' => 'Редуктор Ч-80-31,5-52-1-1 червячный', 'series' => 'Ч', 'model' => 'Ч-80-31,5-52-1-1', 'type' => 'червячный редуктор', 'layout' => 'червячная передача с межосевым расстоянием 80 мм', 'application' => 'конвейеры, дозаторы, смесители, станки и технологические механизмы с компактным приводом', 'selection' => 'по типоразмеру Ч-80, i=31,5, сборке 52-1-1, валам, креплению и нагрузке', 'checks' => 'Перед заказом Ч-80 подтвердите передаточное число, вариант сборки, размеры валов, крепление, монтажное положение и режим нагрузки.', 'category' => $worm_category . 'Редукторы червячные Ч > Ч-80'),
			400102001 => array('path' => '236_2442_2489_2491', 'h1' => 'Редуктор Ч-80-31,5-52-1-1 с электродвигателем 2,2 кВт', 'series' => 'Ч', 'model' => 'Ч-80-31,5-52-1-1 с двигателем 2,2 кВт', 'type' => 'червячный редуктор в сборе с электродвигателем', 'layout' => 'червячный редуктор Ч-80 с электродвигателем через муфту', 'application' => 'готовые приводы конвейеров, транспортеров, дозаторов, смесителей и промышленного оборудования', 'selection' => 'по модели Ч-80, i=31,5, двигателю 2,2 кВт, муфте, валам, креплению и габаритам', 'checks' => 'Нужно подтвердить редуктор, двигатель 2,2 кВт, обороты, муфту, вал, монтаж, направление вращения, питание и фактическую нагрузку.', 'category' => $worm_category . 'Редукторы червячные Ч > Ч-80'),
			400102144 => array('path' => '236_2442_2489_2492', 'h1' => 'Редуктор Ч-100-80-52 червячный', 'series' => 'Ч', 'model' => 'Ч-100-80-52', 'type' => 'червячный редуктор', 'layout' => 'червячная передача с межосевым расстоянием 100 мм', 'application' => 'транспортеры, станки, подъемные узлы, дозаторы и промышленные приводы с большим передаточным отношением', 'selection' => 'по типоразмеру Ч-100, i=80, сборке 52, валам, креплению и нагрузке', 'checks' => 'Перед заказом Ч-100-80-52 подтвердите передаточное число, сборку, вал, крепление, монтажное положение, габариты и условия работы.', 'category' => $worm_category . 'Редукторы червячные Ч > Ч-100'),
			400102149 => array('path' => '236_2446_2508', 'h1' => 'Мотор-редуктор FAF 67 i=25,13 1,5 кВт', 'series' => 'FAF', 'model' => 'FAF 67 i=25,13', 'type' => 'плоский цилиндрический мотор-редуктор', 'layout' => 'параллельные валы, боковой фланец и двигатель 1,5 кВт', 'application' => 'конвейеры, транспортеры, упаковочные линии, дозаторы и механизмы с ограниченной монтажной высотой', 'selection' => 'по серии FAF, типоразмеру 67, передаточному числу 25,13, валу 40 мм, фланцу и двигателю', 'checks' => 'Для подбора FAF 67 нужны передаточное число, выходные обороты, мощность 1,5 кВт, вал 40 мм, боковой фланец, монтаж и нагрузка.', 'category' => $motor_category . 'Плоские цилиндрические мотор-редукторы'),
			400102203 => array('path' => '236_2442_2657', 'h1' => 'Червячный редуктор i=39 с электродвигателем 0,55 кВт', 'series' => 'Червячный привод', 'model' => 'i=39, 0,55 кВт, 1390 об/мин', 'type' => 'червячный редуктор с электродвигателем', 'layout' => 'червячная передача с электродвигателем 0,55 кВт', 'application' => 'флотационные машины, дозаторы, мешалки, вспомогательные технологические узлы и компактные приводы', 'selection' => 'по передаточному числу i=39, мощности 0,55 кВт, оборотам 1390 об/мин, валам и монтажу', 'checks' => 'Перед заказом нужно подтвердить передаточное число, мощность и обороты двигателя, вал, фланец, габариты, монтажное положение и режим нагрузки.', 'category' => $worm_category . 'Червячные редукторы с электродвигателем'),
			400102336 => array('path' => '236_2446_2508', 'h1' => 'Мотор-редуктор FAF-S57-24.96-56-1,1', 'series' => 'FAF', 'model' => 'FAF-S57-24.96-56-1,1', 'type' => 'плоский цилиндрический мотор-редуктор', 'layout' => 'параллельные валы и компактная фланцевая компоновка', 'application' => 'конвейеры, транспортеры, упаковочные линии, дозаторы и промышленные механизмы с параллельными валами', 'selection' => 'по модели FAF-S57, передаточному числу 24.96, выходным оборотам, мощности, валам, фланцу и монтажу', 'checks' => 'Перед заказом FAF-S57 нужно подтвердить передаточное число, выходные обороты, мощность, диаметр вала, фланец, монтажное положение и нагрузку.', 'category' => $motor_category . 'Плоские цилиндрические мотор-редукторы'),
			400102438 => array('path' => '236_2442_2489_2492', 'h1' => 'Редуктор Ч-100-63-51 червячный', 'series' => 'Ч', 'model' => 'Ч-100-63-51', 'type' => 'червячный редуктор', 'layout' => 'червячная передача с межосевым расстоянием 100 мм', 'application' => 'транспортеры, дозаторы, мешалки, станки и технологические линии', 'selection' => 'по типоразмеру Ч-100, i=63, сборке 51, валам, креплению и нагрузке', 'checks' => 'Перед заказом Ч-100-63-51 подтвердите передаточное число, сборку, вал, крепление, габариты, монтажное положение и режим работы.', 'category' => $worm_category . 'Редукторы червячные Ч > Ч-100'),
			400103274 => array('path' => '236_2447_2640', 'h1' => 'Редуктор крановый ВК-550-18-22-Ц-У-1 вертикальный трехступенчатый', 'series' => 'ВК', 'model' => 'ВК-550-18-22-Ц-У-1', 'type' => 'крановый цилиндрический трехступенчатый редуктор', 'layout' => 'вертикальное исполнение для кранового привода', 'application' => 'механизмы передвижения и подъема кранов, крановые тележки и подъемно-транспортное оборудование', 'selection' => 'по серии ВК, типоразмеру 550, передаточному числу, валам, креплению, вертикальному исполнению и нагрузке', 'checks' => 'Для подбора ВК-550 нужно подтвердить шильдик, передаточное число, схему валов, исполнение Ц-У-1, монтаж, нагрузку и режим работы крана.', 'category' => $crane_category . 'Редукторы ВК')
		);

		foreach (array(
			2194 => 'ГПШ-400',
			2195 => 'ГПШ-500',
			400102294 => 'ГПШ-400 (20-27-У1)',
			400102295 => 'ГПШ-400 (20-18-У1)'
		) as $product_id => $model) {
			$items[$product_id] = array(
				'path' => '236_2443_2650',
				'h1' => 'Редуктор ' . $model . ' цилиндрический',
				'series' => 'ГПШ',
				'model' => $model,
				'type' => 'цилиндрический редуктор',
				'layout' => 'промышленная цилиндрическая передача для тяжелого привода',
				'application' => 'конвейеры, технологические линии, подъемно-транспортное оборудование, дробильные и производственные механизмы',
				'selection' => 'по модели ' . $model . ', передаточному числу, сборке, валам, креплению и фактической нагрузке',
				'checks' => 'Перед заказом ' . $model . ' нужно подтвердить передаточное число, вариант сборки, размеры валов, монтаж, габариты, направление вращения и режим работы.',
				'category' => $cyl_category . 'Редукторы ГПШ'
			);
		}

		foreach (array(
			2209 => 'ПР-4110',
			2215 => 'ПР-2110',
			2216 => 'ПР-219',
			2217 => 'ПР-3110',
			2218 => 'ПР-3112',
			2219 => 'ПР-3115',
			2220 => 'ПР-3116',
			2221 => 'ПР-3118',
			2222 => 'ПР-313',
			2223 => 'ПР-314',
			2224 => 'ПР-315',
			2225 => 'ПР-316',
			2226 => 'ПР-317',
			2227 => 'ПР-318',
			2228 => 'ПР-319'
		) as $product_id => $model) {
			$items[$product_id] = array(
				'path' => '236_2446_2648',
				'h1' => 'Мотор-редуктор ' . $model . ' промышленный',
				'series' => 'ПР',
				'model' => $model,
				'type' => 'промышленный мотор-редуктор',
				'layout' => 'электродвигатель и редуктор в составе готового приводного узла',
				'application' => 'конвейеры, транспортеры, технологические линии, подъемные узлы, дозаторы и производственные механизмы',
				'selection' => 'по модели ' . $model . ', мощности двигателя, выходным оборотам, моменту, валам и монтажным размерам',
				'checks' => 'Для подбора ' . $model . ' нужны модель с шильдика, передаточное число, мощность двигателя, выходные обороты, вал, крепление, питание и режим нагрузки.',
				'category' => $motor_category . 'Мотор-редукторы ПР'
			);
		}

		foreach (array(
			400101787 => array('КЦ2-1300-45', '236_2444_2457_2483', 'КЦ2-1300'),
			400102140 => array('КЦ2-1300-45', '236_2444_2457_2483', 'КЦ2-1300'),
			400102206 => array('КЦ2-1300-45', '236_2444_2457_2483', 'КЦ2-1300'),
			400102429 => array('КЦ2-750-45-41', '236_2444_2457_2481', 'КЦ2-750'),
			400102430 => array('КЦ2-500-45-41', '236_2444_2457_2480', 'КЦ2-500')
		) as $product_id => $kts2) {
			$model = $kts2[0];
			$items[$product_id] = array(
				'path' => $kts2[1],
				'h1' => 'Редуктор ' . $model . ' коническо-цилиндрический',
				'series' => 'КЦ2',
				'model' => $model,
				'type' => 'коническо-цилиндрический редуктор',
				'layout' => 'трехступенчатая коническо-цилиндрическая передача для тяжелого привода',
				'application' => 'конвейеры, дробилки, смесители, подъемно-транспортное оборудование и тяжелые технологические линии',
				'selection' => 'по модели ' . $model . ', передаточному числу, валам, креплению, монтажу и расчетному моменту',
				'checks' => 'Перед заказом ' . $model . ' нужно подтвердить передаточное число, вариант сборки, исполнение валов, габариты, крепление и фактическую нагрузку.',
				'category' => $kcc_category . 'Редукторы КЦ2 > ' . $kts2[2]
			);
		}

		foreach (array(
			400102210 => 'ЦТНД-315-125-22У2',
			400102431 => 'ЦТНД-400-63-22'
		) as $product_id => $model) {
			$items[$product_id] = array(
				'path' => '236_2443_2651',
				'h1' => 'Редуктор ' . $model . ' цилиндрический',
				'series' => 'ЦТНД',
				'model' => $model,
				'type' => 'цилиндрический редуктор',
				'layout' => 'цилиндрическая передача для тяжелого промышленного привода',
				'application' => 'конвейеры, подъемные механизмы, дробильные агрегаты, смесители и технологические линии с высокой нагрузкой',
				'selection' => 'по модели ' . $model . ', передаточному числу, валам, креплению, монтажным размерам и нагрузке',
				'checks' => 'Перед заказом ' . $model . ' подтвердите передаточное число, исполнение валов, габариты, крепление, направление вращения и режим работы.',
				'category' => $cyl_category . 'Редукторы ЦТНД'
			);
		}

		return $items;
	}

	private function getCraneReducerSeoMap() {
		$category = 'Редукторы > Крановые редукторы > ';
		$vk_category = $category . 'Редукторы ВК';
		$vku_category = $category . 'Редукторы ВКУ';

		return array(
			2200 => array('path' => '236_2447_2640', 'h1' => 'Редуктор крановый ВК-350 цилиндрический', 'series' => 'ВК', 'model' => 'ВК-350', 'size' => '350', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'цилиндрическая передача для крановых механизмов', 'category' => $vk_category),
			2201 => array('path' => '236_2447_2640', 'h1' => 'Редуктор крановый ВК-475 цилиндрический', 'series' => 'ВК', 'model' => 'ВК-475', 'size' => '475', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'цилиндрическая передача для крановых механизмов', 'category' => $vk_category),
			2202 => array('path' => '236_2447_2640', 'h1' => 'Редуктор крановый ВК-550 цилиндрический', 'series' => 'ВК', 'model' => 'ВК-550', 'size' => '550', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'цилиндрическая передача для крановых механизмов', 'category' => $vk_category),
			400103275 => array('path' => '236_2447_2640', 'h1' => 'Редуктор крановый ВК-550-18-22-Ц-У-1 вертикальный трехступенчатый', 'series' => 'ВК', 'model' => 'ВК-550-18-22-Ц-У-1', 'size' => '550', 'type' => 'крановый цилиндрический трехступенчатый редуктор', 'layout' => 'вертикальное исполнение для кранового привода', 'category' => $vk_category),
			2203 => array('path' => '236_2447_2641', 'h1' => 'Редуктор крановый ВКУ-500 цилиндрический', 'series' => 'ВКУ', 'model' => 'ВКУ-500', 'size' => '500', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'усиленная цилиндрическая передача для крановых механизмов', 'category' => $vku_category),
			2204 => array('path' => '236_2447_2641', 'h1' => 'Редуктор крановый ВКУ-610 цилиндрический', 'series' => 'ВКУ', 'model' => 'ВКУ-610', 'size' => '610', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'усиленная цилиндрическая передача для крановых механизмов', 'category' => $vku_category),
			2205 => array('path' => '236_2447_2641', 'h1' => 'Редуктор крановый ВКУ-750 цилиндрический', 'series' => 'ВКУ', 'model' => 'ВКУ-750', 'size' => '750', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'усиленная цилиндрическая передача для крановых механизмов', 'category' => $vku_category),
			2206 => array('path' => '236_2447_2641', 'h1' => 'Редуктор крановый ВКУ-765 цилиндрический', 'series' => 'ВКУ', 'model' => 'ВКУ-765', 'size' => '765', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'усиленная цилиндрическая передача для крановых механизмов', 'category' => $vku_category),
			2207 => array('path' => '236_2447_2641', 'h1' => 'Редуктор крановый ВКУ-950 цилиндрический', 'series' => 'ВКУ', 'model' => 'ВКУ-950', 'size' => '950', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'усиленная цилиндрическая передача для тяжелых крановых механизмов', 'category' => $vku_category),
			2208 => array('path' => '236_2447_2641', 'h1' => 'Редуктор крановый ВКУ-965 цилиндрический', 'series' => 'ВКУ', 'model' => 'ВКУ-965', 'size' => '965', 'type' => 'крановый цилиндрический редуктор', 'layout' => 'усиленная цилиндрическая передача для тяжелых крановых механизмов', 'category' => $vku_category)
		);
	}

	private function getTs2uReducerSeoMap() {
		$category = 'Редукторы > Цилиндрические редукторы > Редукторы Ц2У > ';

		return array(
			400102435 => array(
				'path' => '236_2443_2454_2633',
				'h1' => 'Редуктор Ц2У-100 цилиндрический двухступенчатый',
				'model' => 'Ц2У-100',
				'size' => '100',
				'axis' => '100 мм',
				'ratio' => 'уточняется по требуемым оборотам и моменту',
				'assembly' => 'уточняется по обозначению или чертежу',
				'shaft' => 'цилиндрические или конические концы валов, по исполнению',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-100'
			),
			400102334 => array(
				'path' => '236_2443_2454_2634',
				'h1' => 'Редуктор Ц2У-125-20-12 цилиндрический двухступенчатый',
				'model' => 'Ц2У-125-20-12',
				'size' => '125',
				'axis' => '125 мм',
				'ratio' => 'i = 20',
				'assembly' => '12',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-125'
			),
			400101711 => array(
				'path' => '236_2443_2454_2635',
				'h1' => 'Редуктор Ц2У-160-40-21-У2 цилиндрический двухступенчатый',
				'model' => 'Ц2У-160-40-21-У2',
				'size' => '160',
				'axis' => '160 мм',
				'ratio' => 'i = 40',
				'assembly' => '21',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'У2',
				'category' => $category . 'Ц2У-160'
			),
			400101713 => array(
				'path' => '236_2443_2454_2635',
				'h1' => 'Редуктор Ц2У-160-40-21-У2 цилиндрический двухступенчатый',
				'model' => 'Ц2У-160-40-21-У2',
				'size' => '160',
				'axis' => '160 мм',
				'ratio' => 'i = 40',
				'assembly' => '21',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'У2',
				'category' => $category . 'Ц2У-160'
			),
			400102432 => array(
				'path' => '236_2443_2454_2635',
				'h1' => 'Редуктор Ц2У-160-40-12 цилиндрический двухступенчатый',
				'model' => 'Ц2У-160-40-12',
				'size' => '160',
				'axis' => '160 мм',
				'ratio' => 'i = 40',
				'assembly' => '12',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-160'
			),
			2115 => array(
				'path' => '236_2443_2454_2636',
				'h1' => 'Редуктор Ц2У-200-40-21 цилиндрический двухступенчатый',
				'model' => 'Ц2У-200-40-21',
				'size' => '200',
				'axis' => '200 мм',
				'ratio' => 'i = 40',
				'assembly' => '21',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-200'
			),
			400102333 => array(
				'path' => '236_2443_2454_2636',
				'h1' => 'Редуктор Ц2У-200-40-21 цилиндрический двухступенчатый',
				'model' => 'Ц2У-200-40-21',
				'size' => '200',
				'axis' => '200 мм',
				'ratio' => 'i = 40',
				'assembly' => '21',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-200'
			),
			2117 => array(
				'path' => '236_2443_2454_2636',
				'h1' => 'Редуктор Ц2У-200-40-12 цилиндрический двухступенчатый',
				'model' => 'Ц2У-200-40-12',
				'size' => '200',
				'axis' => '200 мм',
				'ratio' => 'i = 40',
				'assembly' => '12',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-200'
			),
			400102332 => array(
				'path' => '236_2443_2454_2636',
				'h1' => 'Редуктор Ц2У-200-40-12 цилиндрический двухступенчатый',
				'model' => 'Ц2У-200-40-12',
				'size' => '200',
				'axis' => '200 мм',
				'ratio' => 'i = 40',
				'assembly' => '12',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-200'
			),
			2118 => array(
				'path' => '236_2443_2454_2636',
				'h1' => 'Редуктор Ц2У-200-16-21-У2 цилиндрический двухступенчатый',
				'model' => 'Ц2У-200-16-21-У2',
				'size' => '200',
				'axis' => '200 мм',
				'ratio' => 'i = 16',
				'assembly' => '21',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'У2',
				'category' => $category . 'Ц2У-200'
			),
			400101932 => array(
				'path' => '236_2443_2454_2637',
				'h1' => 'Редуктор Ц2У-250-40-12-КК цилиндрический двухступенчатый',
				'model' => 'Ц2У-250-40-12-КК',
				'size' => '250',
				'axis' => '250 мм',
				'ratio' => 'i = 40',
				'assembly' => '12',
				'shaft' => 'КК, конические концы валов',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-250'
			),
			400102436 => array(
				'path' => '236_2443_2454_2637',
				'h1' => 'Редуктор Ц2У-250-31,5-21 цилиндрический двухступенчатый',
				'model' => 'Ц2У-250-31,5-21',
				'size' => '250',
				'axis' => '250 мм',
				'ratio' => 'i = 31,5',
				'assembly' => '21',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-250'
			),
			400101933 => array(
				'path' => '236_2443_2454_2638',
				'h1' => 'Редуктор Ц2У-315-25-12-ЦЦ цилиндрический двухступенчатый',
				'model' => 'Ц2У-315-25-12-ЦЦ',
				'size' => '315',
				'axis' => '315 мм',
				'ratio' => 'i = 25',
				'assembly' => '12',
				'shaft' => 'ЦЦ, цилиндрические концы валов',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-315'
			),
			400102434 => array(
				'path' => '236_2443_2454_2638',
				'h1' => 'Редуктор Ц2У-315-40-12 цилиндрический двухступенчатый',
				'model' => 'Ц2У-315-40-12',
				'size' => '315',
				'axis' => '315 мм',
				'ratio' => 'i = 40',
				'assembly' => '12',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-315'
			),
			400102433 => array(
				'path' => '236_2443_2454_2639',
				'h1' => 'Редуктор Ц2У-400-40-12 цилиндрический двухступенчатый',
				'model' => 'Ц2У-400-40-12',
				'size' => '400',
				'axis' => '400 мм',
				'ratio' => 'i = 40',
				'assembly' => '12',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-400'
			),
			400102445 => array(
				'path' => '236_2443_2454_2639',
				'h1' => 'Редуктор Ц2У-400Н-40-11-ЦЦ-92 цилиндрический двухступенчатый',
				'model' => 'Ц2У-400Н-40-11-ЦЦ-92',
				'size' => '400Н',
				'axis' => '400 мм',
				'ratio' => 'i = 40',
				'assembly' => '11',
				'shaft' => 'ЦЦ, цилиндрические концы валов',
				'climate' => 'уточняется при заказе',
				'category' => $category . 'Ц2У-400'
			)
		);
	}

	private function getOneTs2uReducerSeoMap() {
		$category = 'Редукторы > Цилиндрические редукторы > Редукторы 1Ц2У > ';

		return array(
			2119 => array(
				'path' => '236_2443_2453_2470',
				'h1' => 'Редуктор 1Ц2У-100 цилиндрический двухступенчатый',
				'model' => '1Ц2У-100',
				'size' => '100',
				'axis' => '100 мм',
				'ratio' => 'подбирается по требуемым оборотам и моменту',
				'assembly' => 'уточняется по обозначению или чертежу',
				'shaft' => 'цилиндрические или конические концы валов, по исполнению',
				'climate' => 'уточняется при заказе',
				'category' => $category . '1Ц2У-100'
			),
			2120 => array(
				'path' => '236_2443_2453_2629',
				'h1' => 'Редуктор 1Ц2У-125 цилиндрический двухступенчатый',
				'model' => '1Ц2У-125',
				'size' => '125',
				'axis' => '125 мм',
				'ratio' => 'подбирается по требуемым оборотам и моменту',
				'assembly' => 'уточняется по обозначению или чертежу',
				'shaft' => 'цилиндрические или конические концы валов, по исполнению',
				'climate' => 'уточняется при заказе',
				'category' => $category . '1Ц2У-125'
			),
			400102146 => array(
				'path' => '236_2443_2453_2629',
				'h1' => 'Редуктор 1Ц2У-125-20-12 цилиндрический двухступенчатый',
				'model' => '1Ц2У-125-20-12',
				'size' => '125',
				'axis' => '125 мм',
				'ratio' => 'i = 20',
				'assembly' => '12',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . '1Ц2У-125'
			),
			2121 => array(
				'path' => '236_2443_2453_2630',
				'h1' => 'Редуктор 1Ц2У-160 цилиндрический двухступенчатый',
				'model' => '1Ц2У-160',
				'size' => '160',
				'axis' => '160 мм',
				'ratio' => 'подбирается по требуемым оборотам и моменту',
				'assembly' => 'уточняется по обозначению или чертежу',
				'shaft' => 'цилиндрические или конические концы валов, по исполнению',
				'climate' => 'уточняется при заказе',
				'category' => $category . '1Ц2У-160'
			),
			400102002 => array(
				'path' => '236_2443_2453_2630',
				'h1' => 'Редуктор 1Ц2У-160-40-12-КК-У2 цилиндрический двухступенчатый',
				'model' => '1Ц2У-160-40-12-КК-У2',
				'size' => '160',
				'axis' => '160 мм',
				'ratio' => 'i = 40',
				'assembly' => '12',
				'shaft' => 'КК, конические концы валов',
				'climate' => 'У2',
				'category' => $category . '1Ц2У-160'
			),
			400102145 => array(
				'path' => '236_2443_2453_2473',
				'h1' => 'Редуктор 1Ц2У-200-20-12 цилиндрический двухступенчатый',
				'model' => '1Ц2У-200-20-12',
				'size' => '200',
				'axis' => '200 мм',
				'ratio' => 'i = 20',
				'assembly' => '12',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . '1Ц2У-200'
			),
			2111 => array(
				'path' => '236_2443_2453_2631',
				'h1' => 'Редуктор 1Ц2У-250 горизонтальный цилиндрический двухступенчатый',
				'model' => '1Ц2У-250',
				'size' => '250',
				'axis' => '250 мм',
				'ratio' => 'подбирается по требуемым оборотам и моменту',
				'assembly' => 'горизонтальное исполнение',
				'shaft' => 'цилиндрические или конические концы валов, по исполнению',
				'climate' => 'уточняется при заказе',
				'category' => $category . '1Ц2У-250'
			),
			400101646 => array(
				'path' => '236_2443_2453_2631',
				'h1' => 'Редуктор 1Ц2У-250 цилиндрический двухступенчатый',
				'model' => '1Ц2У-250',
				'size' => '250',
				'axis' => '250 мм',
				'ratio' => 'подбирается по требуемым оборотам и моменту',
				'assembly' => 'уточняется по обозначению или чертежу',
				'shaft' => 'цилиндрические или конические концы валов, по исполнению',
				'climate' => 'уточняется при заказе',
				'category' => $category . '1Ц2У-250'
			),
			2112 => array(
				'path' => '236_2443_2453_2632',
				'h1' => 'Редуктор 1Ц2У-315Н-40-12-Ц-Ц-С-У2 цилиндрический двухступенчатый',
				'model' => '1Ц2У-315Н-40-12-Ц-Ц-С-У2',
				'size' => '315Н',
				'axis' => '315 мм',
				'ratio' => 'i = 40',
				'assembly' => '12',
				'shaft' => 'Ц-Ц, цилиндрические концы валов',
				'climate' => 'У2',
				'category' => $category . '1Ц2У-315'
			),
			2114 => array(
				'path' => '236_2443_2453_2632',
				'h1' => 'Редуктор 1Ц2У-315Н-31-12,5 цилиндрический двухступенчатый',
				'model' => '1Ц2У-315Н-31-12,5',
				'size' => '315Н',
				'axis' => '315 мм',
				'ratio' => 'i = 31,5',
				'assembly' => 'уточняется по обозначению или чертежу',
				'shaft' => 'уточняется по чертежу или шильдику',
				'climate' => 'уточняется при заказе',
				'category' => $category . '1Ц2У-315'
			)
		);
	}

	private function getBrandMotorReducerSeoMap() {
		$category_motor = 'Редукторы > Мотор-редукторы > ';

		return array(
			2142 => array(
				'path' => '236_2446_2511',
				'h1' => 'Волновые мотор-редукторы 3МВз',
				'brand' => '3МВз',
				'series' => '3МВз',
				'model' => 'Волновые мотор-редукторы 3МВз',
				'type' => 'волновой мотор-редуктор',
				'layout' => 'волновая зубчатая передача с высокой редукцией',
				'application' => 'механизмы позиционирования, станки, манипуляторы, дозаторы и поворотные узлы',
				'selection' => 'по типоразмеру 3МВз, моменту, передаточному числу, оборотам, двигателю и монтажу',
				'checks' => 'Нужно подтвердить типоразмер, выходные обороты, крутящий момент, передаточное отношение, мощность двигателя, способ крепления, положение выходного вала и режим нагрузки.',
				'category' => $category_motor . 'Волновые мотор-редукторы'
			),
			2158 => array(
				'path' => '236_2446_2507',
				'h1' => 'Цилиндрический соосный мотор-редуктор Bauer серии BG',
				'brand' => 'Bauer',
				'series' => 'BG',
				'model' => 'Bauer BG',
				'type' => 'цилиндрический соосный мотор-редуктор',
				'layout' => 'соосная цилиндрическая передача для компактного линейного привода',
				'application' => 'конвейеры, транспортеры, упаковочные линии, дозаторы, насосы и промышленные агрегаты с соосной компоновкой',
				'selection' => 'по серии Bauer BG, типоразмеру, оборотам, моменту, двигателю, валу и монтажному положению',
				'checks' => 'Перед заказом Bauer BG нужно подтвердить типоразмер, передаточное число, выходные обороты, мощность двигателя, крутящий момент, вариант крепления, положение клеммной коробки и габаритные размеры.',
				'category' => $category_motor . 'Цилиндрические соосные мотор-редукторы'
			),
			2159 => array(
				'path' => '236_2446_2508',
				'h1' => 'Плоский цилиндрический мотор-редуктор Bauer серии BF',
				'brand' => 'Bauer',
				'series' => 'BF',
				'model' => 'Bauer BF',
				'type' => 'плоский цилиндрический мотор-редуктор',
				'layout' => 'параллельные валы и компактная плоская компоновка',
				'application' => 'ленточные конвейеры, транспортеры, смесители, дозаторы и линии, где важны компактная высота и параллельные валы',
				'selection' => 'по серии Bauer BF, типоразмеру, передаточному числу, выходному валу, моменту и монтажу',
				'checks' => 'Перед заказом Bauer BF нужно проверить обороты, момент, диаметр и исполнение выходного вала, способ крепления, монтажное положение, двигатель и присоединительные размеры.',
				'category' => $category_motor . 'Плоские цилиндрические мотор-редукторы'
			),
			2161 => array(
				'path' => '236_2446_2510',
				'h1' => 'Планетарные мотор-редукторы Bonfiglioli серии 300',
				'brand' => 'Bonfiglioli',
				'series' => '300',
				'model' => 'Bonfiglioli 300',
				'type' => 'планетарный мотор-редуктор',
				'layout' => 'соосная планетарная передача с высоким крутящим моментом',
				'application' => 'приводы с высоким моментом, подъемные механизмы, смесители, конвейеры, тяжелые технологические линии и поворотные узлы',
				'selection' => 'по серии Bonfiglioli 300, типоразмеру, моменту, передаточному числу, двигателю и присоединению',
				'checks' => 'Перед заказом Bonfiglioli 300 нужно подтвердить расчетный момент, сервис-фактор, передаточное число, обороты, тип входа, выходной вал, монтаж и рабочий цикл.',
				'category' => $category_motor . 'Планетарные мотор-редукторы'
			),
			2162 => array(
				'path' => '236_2446_2507',
				'h1' => 'Соосно-цилиндрические мотор-редукторы Bonfiglioli AS',
				'brand' => 'Bonfiglioli',
				'series' => 'AS',
				'model' => 'Bonfiglioli AS',
				'type' => 'соосно-цилиндрический мотор-редуктор',
				'layout' => 'соосные входной и выходной валы для прямолинейного привода',
				'application' => 'конвейеры, транспортеры, насосы, мешалки, дозаторы и производственные линии с соосной схемой привода',
				'selection' => 'по серии Bonfiglioli AS, типоразмеру, оборотам, моменту, двигателю, валу и креплению',
				'checks' => 'Перед заказом Bonfiglioli AS нужно проверить передаточное число, выходные обороты, крутящий момент, мощность двигателя, способ крепления, вал, фланец и монтажные размеры.',
				'category' => $category_motor . 'Цилиндрические соосные мотор-редукторы'
			),
			2163 => array(
				'path' => '236_2446_2507',
				'h1' => 'Соосно-цилиндрические мотор-редукторы Bonfiglioli C',
				'brand' => 'Bonfiglioli',
				'series' => 'C',
				'model' => 'Bonfiglioli C',
				'type' => 'соосно-цилиндрический мотор-редуктор',
				'layout' => 'соосная цилиндрическая передача для универсального промышленного привода',
				'application' => 'конвейеры, насосные агрегаты, смесители, мешалки, дозаторы и технологические линии непрерывного действия',
				'selection' => 'по серии Bonfiglioli C, типоразмеру, передаточному числу, мощности двигателя, моменту и монтажу',
				'checks' => 'Перед заказом Bonfiglioli C нужно подтвердить типоразмер, передаточное число, обороты, момент, двигатель, исполнение валов, крепление и рабочий режим.',
				'category' => $category_motor . 'Цилиндрические соосные мотор-редукторы'
			),
			2164 => array(
				'path' => '236_2446_2508',
				'h1' => 'Плоско-цилиндрические мотор-редукторы Bonfiglioli F',
				'brand' => 'Bonfiglioli',
				'series' => 'F',
				'model' => 'Bonfiglioli F',
				'type' => 'плоско-цилиндрический мотор-редуктор',
				'layout' => 'параллельные валы, полый или сплошной выходной вал по исполнению',
				'application' => 'конвейеры, транспортеры, элеваторы, дозаторы, упаковочные и технологические линии с ограниченной монтажной высотой',
				'selection' => 'по серии Bonfiglioli F, типоразмеру, валу, моменту, передаточному числу, двигателю и креплению',
				'checks' => 'Перед заказом Bonfiglioli F нужно проверить исполнение выходного вала, передаточное число, обороты, момент, двигатель, реактивную тягу или лапы и монтажные размеры.',
				'category' => $category_motor . 'Плоские цилиндрические мотор-редукторы'
			),
			2165 => array(
				'path' => '236_2446_2506',
				'h1' => 'Червячные мотор-редукторы Bonfiglioli VF и W',
				'brand' => 'Bonfiglioli',
				'series' => 'VF / W',
				'model' => 'Bonfiglioli VF / W',
				'type' => 'червячный мотор-редуктор',
				'layout' => 'угловая червячная передача с компактным корпусом',
				'application' => 'конвейеры, дозаторы, упаковочные машины, пищевое и технологическое оборудование, где нужна компактная угловая передача',
				'selection' => 'по серии Bonfiglioli VF/W, межосевому расстоянию, передаточному числу, валу, фланцу и двигателю',
				'checks' => 'Перед заказом Bonfiglioli VF/W нужно подтвердить межосевое расстояние, передаточное число, выходные обороты, мощность двигателя, положение вала, фланец, лапы и монтаж.',
				'category' => $category_motor . 'Червячные мотор-редукторы'
			),
			2166 => array(
				'path' => '236_2446_2506',
				'h1' => 'Червячные мотор-редукторы Bonfiglioli W/VF-EP для агрессивной среды',
				'brand' => 'Bonfiglioli',
				'series' => 'W/VF-EP',
				'model' => 'Bonfiglioli W/VF-EP',
				'type' => 'червячный мотор-редуктор для агрессивной среды',
				'layout' => 'угловая червячная передача с исполнением для сложных условий эксплуатации',
				'application' => 'мойки, пищевые линии, химические участки, влажные зоны, упаковочное и технологическое оборудование с повышенными требованиями к защите',
				'selection' => 'по серии Bonfiglioli W/VF-EP, степени защиты, материалу корпуса, передаточному числу, валу, двигателю и монтажу',
				'checks' => 'Перед заказом Bonfiglioli W/VF-EP нужно подтвердить среду эксплуатации, степень защиты, передаточное число, обороты, момент, двигатель, исполнение вала и требования к покрытию.',
				'category' => $category_motor . 'Червячные мотор-редукторы'
			),
			2169 => array(
				'path' => '236_2446_2508',
				'h1' => 'Плоские цилиндрические мотор-редукторы Lenze GFL',
				'brand' => 'Lenze',
				'series' => 'GFL',
				'model' => 'Lenze GFL',
				'type' => 'плоский цилиндрический мотор-редуктор',
				'layout' => 'параллельные валы и компактная плоская компоновка',
				'application' => 'конвейеры, транспортеры, упаковочные линии, складская техника и производственные механизмы с параллельными валами',
				'selection' => 'по серии Lenze GFL, типоразмеру, передаточному числу, валу, фланцу, двигателю и монтажу',
				'checks' => 'Перед заказом Lenze GFL нужно проверить типоразмер, передаточное число, выходные обороты, момент, исполнение вала, двигатель, способ крепления и присоединительные размеры.',
				'category' => $category_motor . 'Плоские цилиндрические мотор-редукторы'
			),
			2170 => array(
				'path' => '236_2446_2509',
				'h1' => 'Цилиндро-конические мотор-редукторы Motovario B',
				'brand' => 'Motovario',
				'series' => 'B',
				'model' => 'Motovario B',
				'type' => 'цилиндро-конический мотор-редуктор',
				'layout' => 'угловая коническо-цилиндрическая передача для привода под 90 градусов',
				'application' => 'конвейеры, транспортеры, смесители, упаковочные линии и технологические приводы с угловой компоновкой валов',
				'selection' => 'по серии Motovario B, типоразмеру, передаточному числу, моменту, валу, фланцу и двигателю',
				'checks' => 'Перед заказом Motovario B нужно подтвердить типоразмер, передаточное число, выходные обороты, момент, вал, фланец, монтажное положение и мощность двигателя.',
				'category' => $category_motor . 'Конические мотор-редукторы'
			),
			2171 => array(
				'path' => '236_2446_2509',
				'h1' => 'Цилиндро-конические мотор-редукторы Motovario BA',
				'brand' => 'Motovario',
				'series' => 'BA',
				'model' => 'Motovario BA',
				'type' => 'цилиндро-конический мотор-редуктор',
				'layout' => 'угловая коническо-цилиндрическая передача с промышленным корпусом',
				'application' => 'конвейерные линии, смесители, дозаторы, упаковочное оборудование и механизмы с поворотом направления передачи момента',
				'selection' => 'по серии Motovario BA, типоразмеру, моменту, передаточному числу, двигателю, валу и монтажу',
				'checks' => 'Перед заказом Motovario BA нужно сверить передаточное число, обороты, момент, тип выходного вала, способ крепления, монтажное положение и мощность двигателя.',
				'category' => $category_motor . 'Конические мотор-редукторы'
			),
			2172 => array(
				'path' => '236_2446_2507',
				'h1' => 'Соосно-цилиндрические мотор-редукторы Motovario HA',
				'brand' => 'Motovario',
				'series' => 'HA',
				'model' => 'Motovario HA',
				'type' => 'соосно-цилиндрический мотор-редуктор',
				'layout' => 'соосная цилиндрическая передача для прямого промышленного привода',
				'application' => 'конвейеры, насосы, мешалки, дозаторы, упаковочные машины и технологические линии с соосным приводом',
				'selection' => 'по серии Motovario HA, типоразмеру, передаточному числу, двигателю, моменту, валу и креплению',
				'checks' => 'Перед заказом Motovario HA нужно подтвердить типоразмер, передаточное число, выходные обороты, момент, мощность двигателя, вал, фланец и монтажные размеры.',
				'category' => $category_motor . 'Цилиндрические соосные мотор-редукторы'
			),
			2173 => array(
				'path' => '236_2446_2507',
				'h1' => 'Соосно-цилиндрические мотор-редукторы Motovario R',
				'brand' => 'Motovario',
				'series' => 'R',
				'model' => 'Motovario R',
				'type' => 'соосно-цилиндрический мотор-редуктор',
				'layout' => 'соосные входной и выходной валы с цилиндрической передачей',
				'application' => 'приводы конвейеров, транспортеров, насосов, мешалок, дозаторов и другого промышленного оборудования',
				'selection' => 'по серии Motovario R, типоразмеру, оборотам, моменту, передаточному числу, двигателю и монтажу',
				'checks' => 'Перед заказом Motovario R нужно проверить передаточное число, выходные обороты, момент, мощность двигателя, исполнение валов, фланец, лапы и монтаж.',
				'category' => $category_motor . 'Цилиндрические соосные мотор-редукторы'
			),
			2175 => array(
				'path' => '236_2446_2509',
				'h1' => 'Цилиндро-конические мотор-редукторы NORD SK',
				'brand' => 'NORD',
				'series' => 'SK',
				'model' => 'NORD SK',
				'type' => 'цилиндро-конический мотор-редуктор',
				'layout' => 'угловая коническо-цилиндрическая передача для тяжелых промышленных приводов',
				'application' => 'конвейеры, элеваторы, транспортеры, смесители, подъемно-транспортное оборудование и технологические линии',
				'selection' => 'по серии NORD SK, типоразмеру, передаточному числу, моменту, двигателю, валу и монтажному исполнению',
				'checks' => 'Перед заказом NORD SK нужно подтвердить типоразмер, передаточное число, выходные обороты, момент, двигатель, вал, фланец, монтажное положение и сервис-фактор.',
				'category' => $category_motor . 'Конические мотор-редукторы'
			),
			2176 => array(
				'path' => '236_2446_2508',
				'h1' => 'Мотор-редукторы NORD SK с параллельными валами',
				'brand' => 'NORD',
				'series' => 'SK',
				'model' => 'NORD SK с параллельными валами',
				'type' => 'мотор-редуктор с параллельными валами',
				'layout' => 'параллельные валы и плоская цилиндрическая компоновка',
				'application' => 'ленточные конвейеры, транспортеры, элеваторы, складская техника и производственные линии с компактным боковым монтажом',
				'selection' => 'по серии NORD SK, типоразмеру, выходному валу, моменту, передаточному числу, двигателю и креплению',
				'checks' => 'Перед заказом NORD SK с параллельными валами нужно проверить тип вала, передаточное число, обороты, момент, двигатель, реактивную тягу или лапы и монтажные размеры.',
				'category' => $category_motor . 'Плоские цилиндрические мотор-редукторы'
			),
			2177 => array(
				'path' => '236_2446_2508',
				'h1' => 'Мотор-редукторы NORD UNICASE с параллельными валами',
				'brand' => 'NORD',
				'series' => 'UNICASE',
				'model' => 'NORD UNICASE с параллельными валами',
				'type' => 'мотор-редуктор с параллельными валами',
				'layout' => 'моноблочный корпус UNICASE и параллельная цилиндрическая передача',
				'application' => 'конвейеры, транспортеры, элеваторы, смесители, дозаторы и непрерывные производственные линии',
				'selection' => 'по серии NORD UNICASE, типоразмеру, моменту, передаточному числу, валу, двигателю и монтажу',
				'checks' => 'Перед заказом NORD UNICASE нужно подтвердить типоразмер, передаточное число, выходные обороты, момент, тип вала, двигатель, монтаж и рабочий режим.',
				'category' => $category_motor . 'Плоские цилиндрические мотор-редукторы'
			),
			2178 => array(
				'path' => '236_2446_2506',
				'h1' => 'Червячные мотор-редукторы NORD Universal SI',
				'brand' => 'NORD',
				'series' => 'Universal SI',
				'model' => 'NORD Universal SI',
				'type' => 'червячный мотор-редуктор',
				'layout' => 'угловая червячная передача серии Universal SI',
				'application' => 'компактные конвейеры, дозаторы, упаковочные машины, пищевое оборудование и вспомогательные приводы с угловой передачей',
				'selection' => 'по серии NORD Universal SI, типоразмеру, межосевому расстоянию, передаточному числу, валу и двигателю',
				'checks' => 'Перед заказом NORD Universal SI нужно подтвердить типоразмер, передаточное число, обороты, момент, двигатель, исполнение вала, фланец, лапы и монтаж.',
				'category' => $category_motor . 'Червячные мотор-редукторы'
			),
			2179 => array(
				'path' => '236_2446_2506',
				'h1' => 'Червячные мотор-редукторы NORD Universal SMI',
				'brand' => 'NORD',
				'series' => 'Universal SMI',
				'model' => 'NORD Universal SMI',
				'type' => 'червячный мотор-редуктор',
				'layout' => 'угловая червячная передача серии Universal SMI',
				'application' => 'конвейеры, транспортеры, упаковочные линии, дозаторы и технологическое оборудование с компактным угловым приводом',
				'selection' => 'по серии NORD Universal SMI, типоразмеру, передаточному числу, оборотам, моменту, валу и монтажу',
				'checks' => 'Перед заказом NORD Universal SMI нужно проверить передаточное число, выходные обороты, момент, мощность двигателя, вал, фланец, монтажное положение и размеры.',
				'category' => $category_motor . 'Червячные мотор-редукторы'
			),
			2180 => array(
				'path' => '236_2446_2506',
				'h1' => 'Червячные мотор-редукторы NORD Universal',
				'brand' => 'NORD',
				'series' => 'Universal',
				'model' => 'NORD Universal',
				'type' => 'червячный мотор-редуктор',
				'layout' => 'универсальная угловая червячная передача',
				'application' => 'конвейеры, дозаторы, упаковочные машины, легкие транспортеры и вспомогательные промышленные механизмы',
				'selection' => 'по серии NORD Universal, типоразмеру, передаточному числу, выходному валу, двигателю и монтажу',
				'checks' => 'Перед заказом NORD Universal нужно подтвердить типоразмер, передаточное число, выходные обороты, момент, мощность двигателя, исполнение валов и крепление.',
				'category' => $category_motor . 'Червячные мотор-редукторы'
			),
			2181 => array(
				'path' => '236_2446_2506',
				'h1' => 'Червячные мотор-редукторы NORD Universal с электродвигателем',
				'brand' => 'NORD',
				'series' => 'Universal',
				'model' => 'NORD Universal с электродвигателем',
				'type' => 'червячный мотор-редуктор с электродвигателем',
				'layout' => 'готовый угловой привод на базе червячного редуктора и электродвигателя',
				'application' => 'конвейеры, дозаторы, упаковочные линии, мешалки и вспомогательные приводы, где нужен комплектный мотор-редуктор',
				'selection' => 'по серии NORD Universal, мощности двигателя, передаточному числу, выходным оборотам, валу и монтажу',
				'checks' => 'Перед заказом NORD Universal с двигателем нужно проверить мощность, обороты, напряжение, передаточное число, момент, вал, фланец и монтажное положение.',
				'category' => $category_motor . 'Червячные мотор-редукторы'
			),
			2182 => array(
				'path' => '236_2446_2509',
				'h1' => 'Цилиндро-конические мотор-редукторы Siemens Motox B/K',
				'brand' => 'Siemens',
				'series' => 'Motox B/K',
				'model' => 'Siemens Motox B/K',
				'type' => 'цилиндро-конический мотор-редуктор',
				'layout' => 'угловая коническо-цилиндрическая передача Siemens Motox',
				'application' => 'конвейерные линии, транспортеры, смесители, подъемно-транспортное оборудование и промышленные механизмы с угловой передачей',
				'selection' => 'по серии Siemens Motox B/K, типоразмеру, моменту, передаточному числу, двигателю, валу и монтажу',
				'checks' => 'Перед заказом Siemens Motox B/K нужно подтвердить типоразмер, передаточное число, выходные обороты, момент, двигатель, вал, фланец, монтаж и сервис-фактор.',
				'category' => $category_motor . 'Конические мотор-редукторы'
			),
			2183 => array(
				'path' => '236_2446_2508',
				'h1' => 'Плоско-цилиндрические мотор-редукторы Siemens Motox FZ/FD',
				'brand' => 'Siemens',
				'series' => 'Motox FZ/FD',
				'model' => 'Siemens Motox FZ/FD',
				'type' => 'плоско-цилиндрический мотор-редуктор',
				'layout' => 'параллельные валы и плоская цилиндрическая компоновка Siemens Motox',
				'application' => 'конвейеры, транспортеры, элеваторы, упаковочные линии и промышленные механизмы с параллельными валами',
				'selection' => 'по серии Siemens Motox FZ/FD, типоразмеру, валу, моменту, передаточному числу, двигателю и монтажу',
				'checks' => 'Перед заказом Siemens Motox FZ/FD нужно проверить типоразмер, передаточное число, обороты, момент, исполнение вала, двигатель, способ крепления и размеры.',
				'category' => $category_motor . 'Плоские цилиндрические мотор-редукторы'
			),
			2184 => array(
				'path' => '236_2446_2506',
				'h1' => 'Червячные мотор-редукторы Siemens Motox C/CA',
				'brand' => 'Siemens',
				'series' => 'Motox C/CA',
				'model' => 'Siemens Motox C/CA',
				'type' => 'червячный мотор-редуктор',
				'layout' => 'угловая червячная передача Siemens Motox',
				'application' => 'дозаторы, конвейеры, упаковочные машины, пищевое и технологическое оборудование с компактной угловой передачей',
				'selection' => 'по серии Siemens Motox C/CA, типоразмеру, передаточному числу, двигателю, валу, фланцу и монтажу',
				'checks' => 'Перед заказом Siemens Motox C/CA нужно подтвердить типоразмер, передаточное число, выходные обороты, момент, двигатель, исполнение вала, фланец и монтаж.',
				'category' => $category_motor . 'Червячные мотор-редукторы'
			),
			2185 => array(
				'path' => '236_2446_2507',
				'h1' => 'Соосно-цилиндрические мотор-редукторы Siemens Motox E/Z/D',
				'brand' => 'Siemens',
				'series' => 'Motox E/Z/D',
				'model' => 'Siemens Motox E/Z/D',
				'type' => 'соосно-цилиндрический мотор-редуктор',
				'layout' => 'соосная цилиндрическая передача Siemens Motox для линейного привода',
				'application' => 'конвейеры, насосы, мешалки, дозаторы, упаковочные линии и промышленные агрегаты с соосной схемой',
				'selection' => 'по серии Siemens Motox E/Z/D, типоразмеру, передаточному числу, мощности, моменту, валу и монтажу',
				'checks' => 'Перед заказом Siemens Motox E/Z/D нужно проверить передаточное число, выходные обороты, момент, мощность двигателя, вал, фланец или лапы, монтаж и размеры.',
				'category' => $category_motor . 'Цилиндрические соосные мотор-редукторы'
			)
		);
	}

	private function getPrimaryCategoryBreadcrumbs($product_id) {
		$breadcrumbs = array();

		$category_query = $this->db->query("SELECT p2c.category_id, COUNT(cp.path_id) AS depth, c.sort_order FROM " . DB_PREFIX . "product_to_category p2c INNER JOIN " . DB_PREFIX . "category c ON (c.category_id = p2c.category_id) INNER JOIN " . DB_PREFIX . "category_to_store c2s ON (c2s.category_id = p2c.category_id) INNER JOIN " . DB_PREFIX . "category_path cp ON (cp.category_id = p2c.category_id) INNER JOIN " . DB_PREFIX . "category path_c ON (path_c.category_id = cp.path_id) INNER JOIN " . DB_PREFIX . "category_to_store path_c2s ON (path_c2s.category_id = cp.path_id) WHERE p2c.product_id = '" . (int)$product_id . "' AND c.status = '1' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND path_c.status = '1' AND path_c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY p2c.category_id ORDER BY depth DESC, c.sort_order ASC, p2c.category_id DESC LIMIT 1");

		if (!$category_query->num_rows) {
			return $breadcrumbs;
		}

		$path_query = $this->db->query("SELECT cp.path_id AS category_id, cd.name FROM " . DB_PREFIX . "category_path cp INNER JOIN " . DB_PREFIX . "category c ON (c.category_id = cp.path_id) INNER JOIN " . DB_PREFIX . "category_to_store c2s ON (c2s.category_id = cp.path_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = cp.path_id) WHERE cp.category_id = '" . (int)$category_query->row['category_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status = '1' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY cp.level ASC");

		$path = '';

		foreach ($path_query->rows as $category) {
			$path = $path ? $path . '_' . (int)$category['category_id'] : (string)(int)$category['category_id'];

			$breadcrumbs[] = array(
				'text' => $category['name'],
				'href' => $this->url->link('product/category', 'path=' . $path)
			);
		}

		return $breadcrumbs;
	}

	public function getRecurringDescription() {
		$this->load->language('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
