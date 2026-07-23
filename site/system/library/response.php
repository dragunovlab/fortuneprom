<?php
class Response {
    private $headers = array();
    private $level = 0;
    private $output;

    public function addHeader($header) { $this->headers[] = $header; }

    public function redirect($url, $status = 302) {
        $url = preg_replace("#(^|[^:])//+#", "\$1/", $url);
        $url = str_replace(array("&", "\n", "\r"), array("&", "", ""), $url);
        header("Location: " . $url, true, $status);
        exit();
    }

    public function setCompression($level) { $this->level = $level; }
    public function getOutput() { return $this->output; }
    public function setOutput($output) { $this->output = $output; }
    public function appendOutput($output) { $this->output .= $output; }

    private function compress($data, $level = 0) {
        if (isset($_SERVER["HTTP_ACCEPT_ENCODING"]) && (strpos($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip") !== false)) $encoding = "gzip";
        if (isset($_SERVER["HTTP_ACCEPT_ENCODING"]) && (strpos($_SERVER["HTTP_ACCEPT_ENCODING"], "x-gzip") !== false)) $encoding = "x-gzip";
        if (!isset($encoding) || ($level < -1 || $level > 9)) return $data;
        if (!extension_loaded("zlib") || ini_get("zlib.output_compression")) return $data;
        if (headers_sent()) return $data;
        if (connection_status()) return $data;
        $this->addHeader("Content-Encoding: " . $encoding);
        return gzencode($data, (int)$level);
    }

    public function output() {
        if ($this->output) {
            $is_admin = false;
            foreach (array("SCRIPT_NAME", "PHP_SELF", "REQUEST_URI") as $k) {
                if (!empty($_SERVER[$k]) && preg_match("#(^|/)admin(/|$)#i", $_SERVER[$k])) { $is_admin = true; break; }
            }

            if ($is_admin) {
                $output = $this->level ? $this->compress($this->output, $this->level) : $this->output;
                if (!headers_sent()) {
                    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0", true);
                    header("Pragma: no-cache", true);
                    header("Expires: Thu, 01 Jan 1970 00:00:00 GMT", true);
                    foreach ($this->headers as $h) header($h, true);
                }
                echo $output;
                return;
            }

            // Template encoding handled at file level
            

            // JSON-LD schemas
            $route = isset($_GET["route"]) ? $_GET["route"] : "";
            $url = "https://fortuneprom.kz/" . (isset($_SERVER["REQUEST_URI"]) ? ltrim($_SERVER["REQUEST_URI"], "/") : "");
            $schemas = array();

            // Organization
            $schemas[] = array(
                "@context" => "https://schema.org",
                "@type" => "Organization",
                "name" => "FortunePROM.kz",
                "url" => "https://fortuneprom.kz/",
                "telephone" => "+77786245665",
                "email" => "info@fortuneprom.kz",
                "contactPoint" => array("@type" => "ContactPoint", "telephone" => "+77786245665", "contactType" => "sales")
            );

            // WebSite
            $schemas[] = array(
                "@context" => "https://schema.org",
                "@type" => "WebSite",
                "name" => "FortunePROM.kz",
                "url" => "https://fortuneprom.kz/",
                "potentialAction" => array(
                    "@type" => "SearchAction",
                    "target" => array("@type" => "EntryPoint", "urlTemplate" => "https://fortuneprom.kz/index.php?route=product/search&search={search_term_string}"),
                    "query-input" => "required name=search_term_string"
                )
            );

            // Product/category/footer controllers own JSON-LD. Keep this global
            // response cleanup active, but do not inject stale duplicate schemas here.
            $schemas = array();

            // Homepage SEO cleanup
            if ($route === "common/home" || $route === "") {
                $this->output = preg_replace_callback("/<div[^>]*class=\"title-module\"[^>]*>(.+?)<\/div>/isu", function($m) {
                    return "<h2 class=\"title-module\">" . preg_replace("/<\/?span[^>]*>/iu", "", $m[1]) . "</h2>";
                }, $this->output);
            }

            // Inject JSON-LD
            $schemaHtml = "";
            foreach ($schemas as $s) {
                $schemaHtml .= "<script type=\"application/ld+json\">" . json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
            }
            if ($schemaHtml) {
                $this->output = str_replace("</head>", $schemaHtml . "</head>", $this->output);
            }

            $output = $this->level ? $this->compress($this->output, $this->level) : $this->output;
            if (!headers_sent()) {
                foreach ($this->headers as $h) header($h, true);
            }
            echo $output;
        }
    }
}
