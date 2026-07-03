<?php
class ControllerExtensionModuleFastImages extends Controller {
    private $patch_start = '#BEGIN Fast Images';
    private $patch_middle = '<IfModule mod_rewrite.c>
RewriteCond %{REQUEST_FILENAME} (?i).+\.(jpe?g|png)$
RewriteCond %{HTTP_ACCEPT} image/webp
RewriteRule (?i)(.+)\.(jpe?g|png)$ $1-$2.webp [L]
</IfModule>
<IfModule mod_headers.c>
 Header append Vary Accept env=REDIRECT_accept
</IfModule>
<IfModule mod_mime.c>
 AddType image/webp .webp
</IfModule>';

    private $nginx_area = '#BEGIN Fast Images
location ~* ^(.+)\.(jpe?g|png)$ {
    if ($http_accept ~* "webp") {
        add_header Vary Accept;
        rewrite ^(.+)\.(jpe?g|png)$ $1-$2.webp last;
    }
}
#END Fast Images
';
#rewrite ^(.+)\.(jpe?g|png)$ $1-$2.webp redirect;


    private $patch_finish = '#END Fast Images';
    private $patch_imgs = "RewriteCond %{REQUEST_URI} !.*\.(ico|gif|jpg|jpeg|png|js|css)";
    private $aSettings = [
        'status' => 1,
        'lazy' => 1,
        'towebp' => 1,
        'pregen' => 0,
        'quality' => '85'
    ] ;

    public function index() {
        $this->load->language('extension/module/fast_images');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('setting/setting');

            $aSettings = [];
            foreach ($this->aSettings as $k => $v){
                $name = 'module_fast_images_'.$k;
                if (isset($this->request->post[$name])) {
                    $aSettings[$name] = $this->request->post[$name];
                }
            }
            if (sizeof($aSettings)) {
                $this->model_setting_setting->editSetting('module_fast_images', $aSettings);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [[
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ], [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'type=module&user_token=' . $this->session->data['user_token'], true)
        ], [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/fast_images', 'user_token=' . $this->session->data['user_token'], true)
        ]];

        $data['action'] = $this->url->link('extension/module/fast_images', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'type=module&user_token=' . $this->session->data['user_token'], true);

        if ($this->user->hasPermission('modify', 'extension/module/fast_images')) {
            $this->load->model('setting/setting');
            foreach ($this->aSettings as $k => $v){
                $name = 'module_fast_images_'.$k;
                $val = $this->config->get($name);
                if ($val === '') $val = $v;
                $data[$name] = $val;
            }

            $data['is_permition'] = true;

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];
                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            if (isset($this->session->data['error_warning'])) {
                $data['error_warning'] = $this->session->data['error_warning'];
                unset($this->session->data['error_warning']);
            } else {
                $data['error_warning'] = '';
            }

            $gd = gd_info();
            if ($gd['WebP Support'] and function_exists('imagewebp')) {
                $data['gd_iswebp'] = true;
                $data['gd_descr'] = $this->language->get('gd_success');

                //$data['patch_label'] = $this->language->get('patch_label');
                $data['patch_button'] = $data['unpatch_button'] = $data['patch_area'] = '';

                $patch_str = $this->patch_start . PHP_EOL . $this->patch_middle . PHP_EOL . $this->patch_finish;
                $htaccess = $this->htaccess_filename();
                if (file_exists($htaccess)) $fin = file_get_contents($htaccess);
                else $fin = '';

                $this->load->model('setting/setting');

                $data['is_forced'] = false;
                $data['is_patched'] = true;
                $data['forced_false'] = $this->language->get('forced_false');

                $imgUrl = HTTP_SERVER . 'view/image/webp.png';
                $aHeaders = get_headers($imgUrl);
                $aHeader = [];
                foreach($aHeaders as $line){
                    $aLine = explode(':', $line);
                    if (!empty($aLine[1])){
                        $aHeader[mb_strtolower($aLine[0])] = trim($aLine[1]);
                    }
                }
                if (isset($aHeader['server'])){
                    if (mb_stripos($aHeader['server'], 'apache') !== false){
                        $data['forced_false'] = '';
                        $data['is_apache'] = true;
                        $data['is_forced'] = true;

                        if (mb_strpos($fin, $patch_str) !== false) {
                            //already patched
                            $data['is_patched'] = true;

                            $data['patch_descr'] = $this->language->get('patched_descr');

                            if (is_writable($htaccess)) {
                                $data['unpatch_button'] = $this->language->get('unpatch_button');
                                $data['unpatch_url'] = $this->url->link('extension/module/fast_images/unpatch', 'user_token=' . $this->session->data['user_token'], true);
                            }
                        } else {
                            $data['is_patched'] = false;

                            $data['patch_area'] = $patch_str;
                            if (is_writable($htaccess)) {
                                $data['patch_button'] = $this->language->get('patch_button');
                                $data['patch_url'] = $this->url->link('extension/module/fast_images/patch', 'user_token=' . $this->session->data['user_token'], true);
                            }
                        }
                    }

                    if (mb_stripos($aHeader['server'], 'nginx') !== false){
                        $data['is_forced'] = true;
                        $data['forced_false'] = '';
                        $data['is_nginx'] = true;
                        $data['forced_nginx'] = $this->language->get('forced_nginx');
                        $data['nginx_area'] = $this->nginx_area;
                    }
                }
            } else {
                $data['gd_iswebp'] = true;
                $data['gd_descr'] = $this->language->get('gd_failed');
            }
        } else {
            $data['error_warning'] = $this->language->get('error_permission');
            $data['is_permition'] = false;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/fast_images', $data));
    }

    private function htaccess_filename(){
        return $_SERVER['DOCUMENT_ROOT'].'/.htaccess';
    }

    public function patch(){
        $this->load->language('extension/module/fast_images');
        $htaccess = $this->htaccess_filename();

        if (is_writable($htaccess)){
            $fin = file_get_contents($htaccess);
            $fin = str_replace($this->patch_imgs, $this->patch_imgs."$", $fin);
            $start = mb_strrpos($fin, $this->patch_start);
            if ($start !== false){
                $finish = mb_strrpos($fin, $this->patch_finish);
                if ($finish !== false){
                    $x = mb_substr($fin, 0, $start);
                    $x .= $this->patch_start . PHP_EOL . $this->patch_middle . PHP_EOL;
                    $x .= mb_substr($fin, $finish);
                    file_put_contents($htaccess, $x);

                    if ($this->check(true)) {
                        $this->session->data['success'] = $this->language->get('patch_success');
                    } else {
                        $this->session->data['error_warning'] = $this->language->get('error_patching');
                    }
                } else {
                    $this->session->data['error_warning'] = sprintf($this->language->get('error_patching_finish'), $this->patch_finish);
                }
            } else {
                $search = 'RewriteEngine On';
                $patch_str = $this->patch_start . PHP_EOL . $this->patch_middle . PHP_EOL . $this->patch_finish;
                $replace = $search . PHP_EOL . $patch_str;
                $fin = str_replace($search, $replace, $fin);
                file_put_contents($htaccess, $fin);

                if ($this->check(true)) {
                    $this->session->data['success'] = $this->language->get('patch_success');
                } else {
                    $this->session->data['error_warning'] = $this->language->get('error_patching');
                }
            }
        }
        $this->response->redirect($this->url->link('extension/module/fast_images', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function unpatch(){
        $this->load->language('extension/module/fast_images');
        if ($this->restore()){
            $this->session->data['success'] = $this->language->get('unpatch_success');
        } else {
            $this->session->data['error_warning'] = $this->language->get('error_unpatching');
        }
        $this->response->redirect($this->url->link('extension/module/fast_images', 'user_token=' . $this->session->data['user_token'], true));
    }

    private function restore(){
        $htaccess = $this->htaccess_filename();
        $isRestore = false;
        if (is_writable($htaccess)){
            $fin = file_get_contents($htaccess);
            $fin = str_replace($this->patch_imgs."$", $this->patch_imgs, $fin);

            $start = mb_strrpos($fin, $this->patch_start);
            if ($start !== false) {
                $finish = mb_strrpos($fin, $this->patch_finish);
                if ($finish !== false) {
                    $afterFinish = mb_strpos($fin, PHP_EOL, $finish);
                    if (!$afterFinish){
                        $afterFinish = $finish + mb_strlen($this->patch_finish);
                    }
                    $afterFinish++;
                    $x = mb_substr($fin, 0, $start);
                    $x .= mb_substr($fin, $afterFinish);
                    file_put_contents($htaccess, $x);

                    if ($this->check(false)) {
                        $isRestore = true;
                    }
                }
            }
        }
        return $isRestore;
    }

    private function check($isPatched){
        $res = false;
        $htaccess = $this->htaccess_filename();
        $fin = file_get_contents($htaccess);
        $patch_str = $this->patch_start . PHP_EOL . $this->patch_middle . PHP_EOL . $this->patch_finish;
        $pos = mb_strpos($fin, $patch_str);
        if ($isPatched and $pos !== false) $res = true;
        if (!$isPatched and $pos === false) $res = true;
        return $res;
    }

    public function install() {
        $this->load->language('extension/module/fast_images');
        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('setting/event');
        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/error/not_found/before', 'extension/module/fast_images/get_webp');

        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/common/header/before', 'extension/module/fast_images/header_before');
        $this->model_setting_event->addEvent('fast_images', 'catalog/view/common/header/after', 'extension/module/fast_images/header_after');

        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/common/home/after', 'extension/module/fast_images/output_after');
        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/product/category/after', 'extension/module/fast_images/output_after');
        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/product/product/after', 'extension/module/fast_images/output_after');
        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/product/manufacturer/after', 'extension/module/fast_images/output_after');
        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/product/manufacturer/info/after', 'extension/module/fast_images/output_after');
        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/information/information/after', 'extension/module/fast_images/output_after');
        $this->model_setting_event->addEvent('fast_images', 'catalog/controller/information/contact/after', 'extension/module/fast_images/output_after');

        $this->load->model('setting/setting');
        $aSettings = [];
        foreach ($this->aSettings as $k => $v){
            $name = 'module_fast_images_'.$k;
            $val = $this->config->get($name);
            if ($val === '') $val = $v;
            $aSettings[$name] = $val;
        }
        $this->model_setting_setting->editSetting('module_fast_images', $aSettings);
    }

    public function uninstall() {
        $this->restore();

        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('fast_images');

        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_fast_images', ['module_fast_images_status' => 0]);

    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/fast_images')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}