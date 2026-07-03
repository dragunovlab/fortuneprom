<?php
class ControllerExtensionModuleFastImages extends Controller {
    public function index() {
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
            // WebP supported
        }
    }
}
?>