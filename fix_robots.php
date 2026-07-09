<?php
/**
 * Update robots.txt with Sitemap directive
 */
$path = '/var/www/vhosts/fortuneprom.kz/httpdocs/robots.txt';
$content = @file_get_contents($path);
if ($content === false) die("Cannot read robots.txt\n");

// Check if Sitemap already exists
if (strpos($content, 'Sitemap:') !== false) {
    echo "Sitemap already in robots.txt\n";
} else {
    $content .= "\nSitemap: https://fortuneprom.kz/sitemap.xml\n";
    file_put_contents($path, $content);
    echo "Added Sitemap to robots.txt\n";
}

echo "Done\n";
