<?php
class ControllerExtensionModuleWhatsAppShare extends Controller {
	private $moduleName;
	private $modulePath;
	private $moduleNameSmall;
	private $moduleData_module = 'whatsappshare_module';
	private $moduleModel;
	private $version;
	private $error;



	public function __construct($registry){
		parent::__construct($registry);
		$this->config->load('isenselabs/whatsappshare');
		$module = 'whatsappshare_';

		$this->moduleName = $this->config->get($module.'moduleName');
		$this->modulePath = $this->config->get($module.'modulePath');

		$this->moduleNameSmall = $this->config->get($module.'moduleName');
		$this->moduleModel = $this->config->get($module.'callModel');
		$this->version = $this->config->get($module.'moduleVersion');
		$this->extensionLink = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'].'&type=module', 'SSL');


	}
	
    public function index() { 
		$this->document->addScript('view/javascript/summernote/summernote.min.js');
		$this->document->addStyle('view/javascript/summernote/summernote.css');

		$data['moduleName'] = $this->moduleName;
		$data['modulePath'] = $this->modulePath;
		$data['moduleNameSmall'] = $this->moduleNameSmall;
		$data['moduleData_module'] = $this->moduleData_module;
		$data['moduleModel'] = $this->moduleModel;
	 
        $this->load->language($this->modulePath);
        $this->load->model($this->modulePath);
        $this->load->model('setting/store');
        $this->load->model('setting/setting');
        $this->load->model('localisation/language');
        $this->load->model('design/layout');
		
        $catalogURL = $this->getCatalogURL();
 
        $this->document->addStyle('view/stylesheet/'.$this->moduleNameSmall.'/'.$this->moduleNameSmall.'.css');
        $this->document->setTitle($this->language->get('heading_title'));

        if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0; 
        }
	
        $store = $this->getCurrentStore($this->request->get['store_id']);
		
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) { 	
            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->moduleNameSmall]['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }

            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->moduleNameSmall]['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }

            $module_status = array(
                'group' => $this->config->get('whatsappshare_status_group'),
                'value' => $this->config->get('whatsappshare_status_value')
            );
            $this->model_setting_setting->editSetting($module_status['group'], array($module_status['value'] => '1'));

        	$this->{$this->moduleModel}->editSetting($this->moduleNameSmall, $this->request->post, $this->request->post['store_id']);
        	

            $this->session->data['success'] = $this->language->get('text_success');
			
            $this->response->redirect($this->url->link($this->modulePath,'&user_token=' . $this->session->data['user_token']. '&store_id='.$this->request->post['store_id'], 'SSL'));
        }
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->extensionLink,
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($this->modulePath, 'user_token=' . $this->session->data['user_token'], 'SSL'),
        );

        $languageVariables = array(
		    // Main
			'heading_title',
			'error_permission',
			'text_success',
			'text_enabled',
			'text_disabled',
			'button_cancel',
			'save_changes',
			'text_default',
			'text_module',
			// Control panel
            'entry_code',
			'entry_code_help',
            'text_content_top', 
            'text_content_bottom',
            'text_column_left', 
            'text_column_right',
            'entry_layout',         
            'entry_position',       
            'entry_status',         
            'entry_sort_order',     
            'entry_layout_options',  
            'entry_position_options',
			'entry_action_options',
            'button_add_module',
            'button_remove',
			// whatsappshare
			'whatsappshare',
            'whatsappshare_help',
            'whatsappshare_placeholder',
			// Module dependin
			'text_image_dimensions',
			'text_image_dimensions_help',
			'text_pixels',
			'text_button_design_help',
			'text_button_design' ,
			'color_button',
			'bw_button',
			'text_button_preview'
        );
       
        foreach ($languageVariables as $languageVariable) {
            $data[$languageVariable] = $this->language->get($languageVariable);
        }

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
			if (strpos($_GET['route'], 'whatsappshare')) {
				$data['heading_title'] .= ' ' . $this->version;
			}
		}
 
        $data['stores'] = array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' (' . $data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());
        $data['languages']              = $this->model_localisation_language->getLanguages();
        $data['store']                  = $store;
        $data['user_token']                  = $this->session->data['user_token'];
        $data['action']                 = $this->url->link($this->modulePath, 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['cancel']                 = $this->extensionLink;
        $data['moduleSettings']			= $this->{$this->moduleModel}->getSetting($this->moduleNameSmall, $store['store_id']);
        $data['catalog_url']			= $catalogURL;
		
		$data['moduleData']				= isset($data['moduleSettings'][$this->moduleNameSmall]) ? $data['moduleSettings'][$this->moduleNameSmall] : array ();
		$data['moduleData']['ImageWidth'] = isset($data['moduleData']['ImageWidth']) ? trim($data['moduleData']['ImageWidth']) : "";
		$data['moduleData']['ImageHeight'] = isset($data['moduleData']['ImageHeight']) ? trim($data['moduleData']['ImageHeight']) : "";
		
		//var_dump($data['moduleSettings'], $data['moduleData']); exit;
		
		if ($this->config->get('featured_status')) {
			$data['whatsappshare_status'] = $this->config->get('whatsappshare_status');
		} else {
			$data['whatsappshare_status'] = '0';
		}
		
		$data['whatsappshare_modules'] = array();
		if (isset($data['moduleSettings']['whatsappshare_module'])) {
			foreach ($data['moduleSettings']['whatsappshare_module'] as $key => $module) {
				$data['whatsappshare_modules'][] = array(
					'key'    => $key,
					'limit'  => $module['limit'],
					'width'  => $module['width'],
					'height' => $module['height']
				);
			}
		}
		
		$data['header']	= $this->load->controller('common/header');
		$data['column_left']= $this->load->controller('common/column_left');
		$data['footer']	= $this->load->controller('common/footer');

		$data['tab_controlpanel_content']    = $this->load->view($this->modulePath . '/tab_controlpanel', $data);
        $data['tab_settings_content'] = $this->load->view($this->modulePath . '/tab_settings', $data);

		$this->response->setOutput($this->load->view($this->modulePath, $data));

    }
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', $this->modulePath)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

    private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        } 
        return $storeURL;
    }

    private function getServerURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }

    private function getCurrentStore($store_id) {    
        if($store_id && $store_id != 0) {
            $store = $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] = 0;
            $store['name'] = $this->config->get('config_name');
            $store['url'] = $this->getCatalogURL(); 
        }
        return $store;
    }
    
    public function install() {
	    $this->load->model($this->modulePath);
	    $this->{$this->moduleModel}->install();
    }
    
    public function uninstall() {
    	$this->load->model('setting/setting');
		
		$this->load->model('setting/store');
		$this->model_setting_setting->deleteSetting($this->moduleData_module,0);
		$stores=$this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$this->model_setting_setting->deleteSetting($this->moduleData_module, $store['store_id']);
		}
		
        $this->load->model($this->modulePath);
        $this->{$this->moduleModel}->uninstall();
    }
}

?>