<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://opencart.com
 */

/**
 * Response class
 */
class Response {
	private $headers = array();
	private $level = 0;
	private $output;

	/**
	 * Constructor
	 *
	 * @param	string	$header
	 */
	public function addHeader($header) {
		$this->headers[] = $header;
	}
	
	/**
	 * 
	 *
	 * @param	string	$url
	 * @param	int		$status
	 */
	public function redirect($url, $status = 302) {
		header('Location: ' . str_replace(array('&', "\n", "\r"), array('&', '', ''), $url), true, $status);
		exit();
	}
	
	/**
	 * 
	 *
	 * @param	int		$level
	 */
	public function setCompression($level) {
		$this->level = $level;
	}
	
	/**
	 * 
	 *
	 * @return	array
	 */
	public function getOutput() {
		return $this->output;
	}
	
	/**
	 * 
	 *
	 * @param	string	$output
	 */	
	public function setOutput($output) {
		$this->output = $output;
	}

	public function appendOutput($output) {
		$this->output .= $output;
	}
	
	/**
	 * 
	 *
	 * @param	string	$data
	 * @param	int		$level
	 * 
	 * @return	string
	 */
	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding) || ($level < -1 || $level > 9)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) {
			return $data;
		}

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, (int)$level);
	}
	
	/**
	 * 
	 */
	public function output() {
		if ($this->output) {
			// SCHEMA_INJECTION: JSON-LD structured data
			$route = isset($_GET['route']) ? $_GET['route'] : '';
			$isProduct = ($route === 'product/product');
			$isCategory = ($route === 'product/category');
			$currUrl = 'https://fortuneprom.kz/' . (isset($_SERVER['REQUEST_URI']) ? ltrim($_SERVER['REQUEST_URI'], '/') : '');

			$schemas = array();

			// Organization
			$schemas[] = array(
				'@context' => 'https://schema.org',
				'@type' => 'Organization',
				'name' => 'FortunePROM.kz',
				'url' => 'https://fortuneprom.kz/',
				'telephone' => '+77786245665',
				'contactPoint' => array(
					'@type' => 'ContactPoint',
					'telephone' => '+77786245665',
					'contactType' => 'sales'
				)
			);

			// WebSite
			$schemas[] = array(
				'@context' => 'https://schema.org',
				'@type' => 'WebSite',
				'name' => 'FortunePROM.kz',
				'url' => 'https://fortuneprom.kz/',
				'potentialAction' => array(
					'@type' => 'SearchAction',
					'target' => array(
						'@type' => 'EntryPoint',
						'urlTemplate' => 'https://fortuneprom.kz/index.php?route=product/search&search={search_term_string}'
					),
					'query-input' => 'required name=search_term_string'
				)
			);

		// LocalBusiness (Kazakhstan)
		$schemas[] = array(
			'@context' => 'https://schema.org',
			'@type' => 'LocalBusiness',
			'name' => 'FortunePROM.kz',
			'description' => 'Комплексные поставки промышленного оборудования в Казахстане',
			'url' => 'https://fortuneprom.kz/',
			'telephone' => '+77786245665',
			'email' => 'info@fortuneprom.kz',
			'address' => array(
				'@type' => 'PostalAddress',
				'addressLocality' => 'Алматы',
				'addressRegion' => 'Алматы',
				'addressCountry' => 'KZ'
			),
			'openingHours' => 'Mo-Fr 09:00-18:00'
		);

			// Product page
			if ($isProduct) {
				$prodName = '';
				if (preg_match('/<h1[^>]*>([^<]+)<\/h1>/i', $this->output, $m)) {
					$prodName = trim(strip_tags($m[1]));
				}
				if (!$prodName && preg_match('/<title>([^<]+)<\/title>/i', $this->output, $m)) {
					$prodName = trim($m[1]);
				}

				$price = '';
				if (preg_match('/itemprop="price"[^>]*content="([^"]+)"/i', $this->output, $m)) {
					$price = $m[1];
				}

				$image = '';
				if (preg_match('/<meta[^>]+property="og:image"[^>]+content="([^"]+)"/i', $this->output, $m)) {
					$image = $m[1];
				}

				$product = array(
					'@context' => 'https://schema.org',
					'@type' => 'Product',
					'name' => $prodName ?: 'Товар',
					'url' => $currUrl,
					'image' => $image ?: 'https://fortuneprom.kz/image/catalog/logo.png'
				);
				if ($price) {
					$cp = preg_replace('/[^0-9.,]/', '', $price);
					$cp = str_replace(',', '.', $cp);
					$product['offers'] = array(
						'@type' => 'Offer',
						'price' => $cp,
						'priceCurrency' => 'KZT',
						'availability' => 'https://schema.org/InStock',
						'url' => $currUrl
					);
				}
				$schemas[] = $product;

				$schemas[] = array(
					'@context' => 'https://schema.org',
					'@type' => 'BreadcrumbList',
					'itemListElement' => array(
						array('@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => 'https://fortuneprom.kz/'),
						array('@type' => 'ListItem', 'position' => 2, 'name' => $prodName ?: 'Товар', 'item' => $currUrl)
					)
				);
			}

			// Category page
			if ($isCategory) {
				$catName = '';
				if (preg_match('/<h1[^>]*>([^<]+)<\/h1>/i', $this->output, $m)) {
					$catName = trim(strip_tags($m[1]));
				}
				$schemas[] = array(
					'@context' => 'https://schema.org',
					'@type' => 'BreadcrumbList',
					'itemListElement' => array(
						array('@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => 'https://fortuneprom.kz/'),
						array('@type' => 'ListItem', 'position' => 2, 'name' => $catName ?: 'Категория', 'item' => $currUrl)
					)
				);
			}
		
		// Homepage SEO cleanup
		if ($route === 'common/home' || $route === '') {
			// Convert title-module divs to H2
			$this->output = preg_replace_callback('/<div[^>]*class="title-module"[^>]*>(.+?)<\/div>/isu', function($m) {
				$inner = preg_replace('/<\/?span[^>]*>/iu', '', $m[1]);
				return '<h2 class="title-module">' . trim($inner) . '</h2>';
			}, $this->output);
			// Add H1 before first container-module (main content area)
			if (stripos($this->output, '<h1') === false) {
				$h1Text = html_entity_decode('&#1055;&#1088;&#1086;&#1084;&#1099;&#1096;&#1083;&#1077;&#1085;&#1085;&#1086;&#1077; &#1086;&#1073;&#1086;&#1088;&#1091;&#1076;&#1086;&#1074;&#1072;&#1085;&#1080;&#1077; FORTUNE PROM &#1074; &#1050;&#1072;&#1079;&#1072;&#1093;&#1089;&#1090;&#1072;&#1085;&#1077;', ENT_QUOTES, 'UTF-8');
				$h1 = '<div class="container"><h1 class="seo-homepage-h1" style="font-size:22px;margin:20px 0 15px;color:#333;">' . $h1Text . '</h1></div>';
				$this->output = preg_replace('/(<div[^>]*class="container-module"[^>]*>)/i', $h1 . '$1', $this->output, 1);
			}
		}
		// end Homepage SEO cleanup
		
		// Homepage SEO cleanup
		if ($route === 'common/home' || $route === '') {
			// Convert title-module divs to H2
			$this->output = preg_replace_callback('/<div[^>]*class="title-module"[^>]*>(.+?)<\/div>/isu', function($m) {
				$inner = preg_replace('/<\/?span[^>]*>/iu', '', $m[1]);
				return '<h2 class="title-module">' . trim($inner) . '</h2>';
			}, $this->output);
			// Add H1 if missing - place before first container-module
			if (stripos($this->output, '<h1') === false) {
				$h1Text = html_entity_decode('&#1055;&#1088;&#1086;&#1084;&#1099;&#1096;&#1083;&#1077;&#1085;&#1085;&#1086;&#1077; &#1086;&#1073;&#1086;&#1088;&#1091;&#1076;&#1086;&#1074;&#1072;&#1085;&#1080;&#1077; FORTUNE PROM &#1074; &#1050;&#1072;&#1079;&#1072;&#1093;&#1089;&#1090;&#1072;&#1085;&#1077;', ENT_QUOTES, 'UTF-8');
				$h1 = '<div class="container"><h1 class="seo-homepage-h1" style="font-size:22px;margin:20px 0 15px;color:#333;">' . $h1Text . '</h1></div>';
				$this->output = preg_replace('/(<div[^>]*class="container-module"[^>]*>)/i', $h1 . '$1', $this->output, 1);
			}
		}
		// end Homepage SEO cleanup
		// Render
			$schemaHtml = '';
			foreach ($schemas as $s) {
				$schemaHtml .= '<script type="application/ld+json">' . json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
			}
			$this->output = str_replace('</head>', $schemaHtml . '</head>', $this->output);

			$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;
			
			if (!headers_sent()) {
				foreach ($this->headers as $header) {
					header($header, true);
				}
			}
			
			echo $output;
		}
	}
}