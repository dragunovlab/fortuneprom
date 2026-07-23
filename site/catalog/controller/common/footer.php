<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');

		// JSON-LD Organization (обогащённый: LocalBusiness + sameAs + полный адрес)
		$schema_org = array(
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => $this->config->get('config_name'),
			'alternateName' => 'Fortune PROM',
			'url' => HTTPS_SERVER,
			'logo' => HTTPS_SERVER . 'image/' . $this->config->get('config_logo'),
			'telephone' => '+7 778 970 71 40',
			'email' => $this->config->get('config_email'),
			'description' => 'Комплексные поставки промышленного оборудования в Казахстане с 2015 года',
			'foundingDate' => '2015',
			'address' => array(
				'@type' => 'PostalAddress',
				'addressLocality' => 'Алматы',
				'streetAddress' => 'улица Фаворского, 21',
				'addressRegion' => 'Алматы',
				'addressCountry' => 'KZ'
			),
			'contactPoint' => array(
				'@type' => 'ContactPoint',
				'telephone' => '+7 778 970 71 40',
				'contactType' => 'sales',
				'availableLanguage' => array('Russian', 'Kazakh')
			),
			'sameAs' => array(
				'https://wa.me/77789707140',
				'https://t.me/fortuneprom',
				'https://www.instagram.com/fortuneprom.kz/'
			)
		);

		// JSON-LD LocalBusiness (для карты и локального SEO)
		$schema_lb = array(
			'@context' => 'https://schema.org',
			'@type' => 'LocalBusiness',
			'name' => $this->config->get('config_name'),
			'url' => HTTPS_SERVER,
			'telephone' => '+7 778 970 71 40',
			'email' => $this->config->get('config_email'),
			'address' => array(
				'@type' => 'PostalAddress',
				'addressLocality' => 'Алматы',
				'streetAddress' => 'улица Фаворского, 21',
				'addressRegion' => 'Алматы',
				'addressCountry' => 'KZ'
			),
			'openingHoursSpecification' => array(
				array(
					'@type' => 'OpeningHoursSpecification',
					'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
					'opens' => '09:00',
					'closes' => '18:00'
				)
			),
			'image' => HTTPS_SERVER . 'image/' . $this->config->get('config_logo'),
			'priceRange' => '₸₸₸'
		);

		// JSON-LD WebSite Schema with SearchAction
		$schema_ws = array(
			'@context' => 'https://schema.org',
			'@type' => 'WebSite',
			'name' => $this->config->get('config_name'),
			'url' => HTTPS_SERVER,
			'potentialAction' => array(
				'@type' => 'SearchAction',
				'target' => array('@type' => 'EntryPoint', 'urlTemplate' => HTTPS_SERVER . 'index.php?route=product/search&search={search_term_string}'),
				'query-input' => 'required name=search_term_string'
			)
		);

		$schema_html = '<script type="application/ld+json">' . json_encode($schema_org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
		$schema_html .= '<script type="application/ld+json">' . json_encode($schema_lb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
		$schema_html .= '<script type="application/ld+json">' . json_encode($schema_ws, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
		
		return $schema_html . $this->load->view('common/footer', $data);
	}
}
