<?php
/**
 * ULTIMATE SCHEMA FIX for fortuneprom.kz
 * 
 * Upload to httpdocs/ and access via browser.
 * Modifies system/library/response.php to inject JSON-LD schema.
 * 100% reliable: output() is called for EVERY page.
 */

$httpdocs = '/var/www/vhosts/fortuneprom.kz/httpdocs';
$storage  = '/var/www/vhosts/fortuneprom.kz/storage';

// ===== 1. Find and modify response.php =====
$targets = [
    $storage . '/modification/system/library/response.php',
    $httpdocs . '/system/library/response.php'
];

$respFile = null;
foreach ($targets as $f) {
    if (file_exists($f)) { $respFile = $f; break; }
}

if (!$respFile) die("ERROR: response.php not found\n");
echo "Target: $respFile\n";

$content = file_get_contents($respFile);
if (strpos($content, 'SCHEMA_INJECTION') !== false) {
    echo "Already modified\n";
} else {
    $backup = $respFile . '.bak.' . date('Ymd_His');
    file_put_contents($backup, $content);
    echo "Backup: $backup\n";

    // Add appendOutput() if missing
    if (strpos($content, 'function appendOutput') === false) {
        $content = str_replace(
            'public function setOutput($output) {
		$this->output = $output;
	}',
            'public function setOutput($output) {
		$this->output = $output;
	}

	public function appendOutput($output) {
		$this->output .= $output;
	}',
            $content
        );
        echo "Added appendOutput()\n";
    }

    // Insert schema injection into output()
    $code = <<<'PHP'
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
PHP;

    // Insert code before the closing brace of output()
    $outPos = strpos($content, 'public function output() {');
    if ($outPos === false) die("Cannot find output()\n");

    $bracePos = strpos($content, '{', $outPos);
    $depth = 1; $i = $bracePos + 1;
    while ($depth > 0 && $i < strlen($content)) {
        if ($content[$i] == '{') $depth++;
        if ($content[$i] == '}') $depth--;
        $i++;
    }

    $content = substr($content, 0, $i - 1) . "\n" . $code . "\n" . substr($content, $i - 1);
    file_put_contents($respFile, $content);
    echo "response.php updated\n";
}

// ===== 2. Remove duplicate schema from footer =====
echo "\n--- Removing duplicate from footer ---\n";
$footerFiles = [
    $storage . '/modification/catalog/controller/common/footer.php',
    $httpdocs . '/catalog/controller/common/footer.php'
];
foreach ($footerFiles as $ff) {
    if (file_exists($ff)) {
        $fc = file_get_contents($ff);
        if (strpos($fc, 'ld+json') !== false || strpos($fc, 'schema.org') !== false) {
            $bk = $ff . '.bak.' . date('Ymd_His');
            file_put_contents($bk, $fc);
            $fc = preg_replace('/\$schema[^;]+;\s*/', '', $fc);
            $fc = preg_replace('/\$schema0[^;]+;\s*/', '', $fc);
            $fc = preg_replace('/\$schemaWs[^;]+;\s*/', '', $fc);
            $fc = preg_replace('/echo [^;]+;\s*/', '', $fc);
            $fc = preg_replace('/\$this->response->appendOutput[^;]+;\s*/', '', $fc);
            file_put_contents($ff, $fc);
            echo "Cleaned footer: $ff\n";
        } else {
            echo "No schema in footer: $ff\n";
        }
    }
}

// ===== 3. Clear caches =====
echo "\n--- Clearing caches ---\n";
foreach (glob($storage . '/cache/*') as $cf) { if (is_file($cf)) unlink($cf); }
echo "Cache cleared\n";
if (function_exists('opcache_reset')) { opcache_reset(); echo "Opcache reset\n"; }

// ===== 4. Test =====
echo "\n--- Testing ---\n";
$tests = [
    'Home'     => 'https://fortuneprom.kz/',
    'Product'  => 'https://fortuneprom.kz/elektrodvigatel-air-56-a2',
    'Category' => 'https://fortuneprom.kz/ehlektrodvigateli'
];

foreach ($tests as $name => $url) {
    $html = @file_get_contents($url, false, stream_context_create(['http' => ['timeout' => 10]]));
    if ($html) {
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m);
        echo "$name: " . count($m[1]) . " blocks\n";
        foreach ($m[1] as $j) {
            $d = json_decode($j, true);
            if ($d && isset($d['@type'])) echo "  - {$d['@type']}" . (isset($d['name']) ? ": {$d['name']}" : '') . "\n";
        }
    } else {
        echo "$name: FAILED\n";
    }
}

// ===== 5. Self-delete =====
echo "\n--- Cleaning up ---\n";
@unlink(__FILE__);
echo "Deleted self\n";

echo "\nDONE\n";
