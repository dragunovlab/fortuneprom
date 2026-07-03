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

			// Render
			$schemaHtml = '';
			foreach ($schemas as $s) {
				$schemaHtml .= '<script type="application/ld+json">' . json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
			}
			$this->output = str_replace('</body>', $schemaHtml . '</body>', $this->output);

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