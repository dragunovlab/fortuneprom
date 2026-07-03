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

		// JSON-LD Organization Schema
		$schema_org = array(
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => $this->config->get('config_name'),
			'url' => HTTPS_SERVER,
			'logo' => HTTPS_SERVER . 'image/' . $this->config->get('config_logo'),
			'telephone' => $this->config->get('config_telephone'),
			'email' => $this->config->get('config_email'),
			'address' => array('@type' => 'PostalAddress', 'addressCountry' => 'KZ'),
			'contactPoint' => array('@type' => 'ContactPoint', 'telephone' => $this->config->get('config_telephone'), 'contactType' => 'sales')
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
		$schema_html .= '<script type="application/ld+json">' . json_encode($schema_ws, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
		
		return $schema_html . $this->load->view('common/footer', $data);
	}
}
