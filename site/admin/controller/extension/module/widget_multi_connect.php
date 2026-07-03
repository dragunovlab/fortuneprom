<?php
/**
 * @author COREIT coreit.com.ua <core1@coreit.com.ua>
 */

class ControllerExtensionModuleWidgetMultiConnect extends Controller
{
	const ON  = "on";
	const OFF = "off";

	const TYPE_CHECK_NUMBER = "number";
	const TYPE_CHECK_EXIST  = "exist";
	private $error = [];

	public function install() {
		$this->load->model('setting/event');
		$this->model_setting_event
			->addEvent(
				'coreit-connect-widget',
				'catalog/controller/common/footer/after',
				'extension/module/widget_multi_connect'
			);
	}
	public function uninstall() {
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('coreit-connect-widget');
	}

    public function index()
    {
        $this->load->language('extension/module/widget_multi_connect');

        $this->load->model('setting/setting');

        $this->document->addStyle('view/stylesheet/widget-multi-connect/style.css');
        $this->document->addStyle('view/stylesheet/widget-multi-connect/bootstrap-toggle/css/bootstrap-toggle.min.css');
        $this->document->addStyle('view/stylesheet/widget-multi-connect/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css');
        $this->document->addScript('view/stylesheet/widget-multi-connect/bootstrap-toggle/js/bootstrap-toggle.min.js');
        $this->document->addScript('view/stylesheet/widget-multi-connect/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('multi_connect', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }
        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title']				= $this->language->get('heading_title');
        $data['text_edit']					= $this->language->get('text_edit');
        $data['text_success']				= $this->language->get('text_success');
        $data['button_save']				= $this->language->get('button_save');
        $data['button_cancel']				= $this->language->get('button_cancel');

        //Label
        $data['label_main_button']        	= $this->language->get('label_main_button');
        $data['label_telegram']           	= $this->language->get('label_telegram');
        $data['label_whatsapp']       		= $this->language->get('label_whatsapp');
        $data['label_skype']     			= $this->language->get('label_skype');
        $data['label_viber']				= $this->language->get('label_viber');
        $data['label_messenger']        	= $this->language->get('label_messenger');
        $data['label_line']					= $this->language->get('label_line');
        $data['label_vk']        			= $this->language->get('label_vk');
        $data['label_phone']   				= $this->language->get('label_phone');
        $data['label_email']       			= $this->language->get('label_email');
        $data['label_instagram']       		= $this->language->get('label_instagram');

        //Text
        $data['text_color']  				= $this->language->get('text_color');
        $data['text_position']  			= $this->language->get('text_position');
        $data['text_right']  				= $this->language->get('text_right');
        $data['text_left']  				= $this->language->get('text_left');
        $data['text_username']            	= $this->language->get('text_username');
        $data['text_phone']       			= $this->language->get('text_phone');
        $data['text_accont']           		= $this->language->get('text_accont');
        $data['text_id_name']     			= $this->language->get('text_id_name');
        $data['text_id']     				= $this->language->get('text_id');
        $data['text_email']       			= $this->language->get('text_email');
        $data['text_instagram']       		= $this->language->get('text_instagram');

        //Help
        $data['text_color_help']  			= $this->language->get('text_color_help');
        $data['text_position_help']  		= $this->language->get('text_position_help');
        $data['text_username_help']         = $this->language->get('text_username_help');
        $data['text_phone_help']       		= $this->language->get('text_phone_help');
        $data['text_instagram_help']       	= $this->language->get('text_instagram_help');
        $data['text_accont_help']           = $this->language->get('text_accont_help');
        $data['text_id_help']     			= $this->language->get('text_id_help');
        $data['text_line_help']     		= $this->language->get('text_line_help');
        $data['text_vk_help']     			= $this->language->get('text_vk_help');
        $data['text_telephone_help']     	= $this->language->get('text_telephone_help');
        $data['text_email_help']       		= $this->language->get('text_email_help');


        if (isset($this->error) && !empty($this->error)) {
            $data += $this->parseError();
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/widget_multi_connect', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('extension/module/widget_multi_connect', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $data['multi_connect_messenger_status']   = $this->prepareStatusData('multi_connect_messenger_status');
        $data['multi_connect_telegram_status']    = $this->prepareStatusData('multi_connect_telegram_status');
        $data['multi_connect_whatsupp_status']    = $this->prepareStatusData('multi_connect_whatsupp_status');
        $data['multi_connect_skype_status']       = $this->prepareStatusData('multi_connect_skype_status');
        $data['multi_connect_viber_status']       = $this->prepareStatusData('multi_connect_viber_status');
        $data['multi_connect_phone_status']       = $this->prepareStatusData('multi_connect_phone_status');
        $data['multi_connect_instagram_status']   = $this->prepareStatusData('multi_connect_instagram_status');
        $data['multi_connect_email_status']       = $this->prepareStatusData('multi_connect_email_status');
        $data['multi_connect_vk_status']          = $this->prepareStatusData('multi_connect_vk_status');
        $data['multi_connect_line_status']        = $this->prepareStatusData('multi_connect_line_status');

        $data['multi_connect_color_main_button']  = $this->prepareData("multi_connect_color_main_button");
        $data['multi_connect_position']           = $this->prepareData("multi_connect_position");
        $data['multi_connect_telegram_username']  = $this->prepareData("multi_connect_telegram_username");
        $data['multi_connect_whatsapp_phone_number'] = $this->prepareData("multi_connect_whatsapp_phone_number");
        $data['multi_connect_skype_login']        = $this->prepareData("multi_connect_skype_login");
        $data['multi_connect_viber_phone_number'] = $this->prepareData("multi_connect_viber_phone_number");
        $data['multi_connect_messenger_id']       = $this->prepareData("multi_connect_messenger_id");
        $data['multi_connect_phone_number']       = $this->prepareData("multi_connect_phone_number");
        $data['multi_connect_instagram']          = $this->prepareData("multi_connect_instagram");
        $data['multi_connect_email']              = $this->prepareData("multi_connect_email");
        $data['multi_connect_vk_id']              = $this->prepareData("multi_connect_vk_id");
        $data['multi_connect_line_id']            = $this->prepareData("multi_connect_line_id");



        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/widget_multi_connect', $data));

    }

    private function validate()
    {
        if (!isset($this->request->post['multi_connect_color_main_button'])) {
            $this->error['error_collor_main_button'] = $this->language->get('error_main_button');
        }
        if (!isset($this->request->post['multi_connect_position'])) {
            $this->error['error_position'] = $this->language->get('error_position');
        }
        if (isset($this->request->post['multi_connect_telegram_status'])
            && $this->request->post['multi_connect_telegram_status'] === self::ON) {
            $this->validator("multi_connect_telegram_username", self::TYPE_CHECK_EXIST);
        }
        if (isset($this->request->post['multi_connect_whatsupp_status'])
            && $this->request->post['multi_connect_whatsupp_status'] === self::ON) {
            $this->validator("multi_connect_whatsapp_phone_number", self::TYPE_CHECK_NUMBER);
        }
        if (isset($this->request->post['multi_connect_skype_status'])
            && $this->request->post['multi_connect_skype_status'] === self::ON) {
            $this->validator("multi_connect_skype_login", self::TYPE_CHECK_EXIST);
        }
        if (isset($this->request->post['multi_connect_viber_status'])
            && $this->request->post['multi_connect_viber_status'] === self::ON) {
            $this->validator("multi_connect_viber_phone_number", self::TYPE_CHECK_NUMBER);
        }
        if (isset($this->request->post['multi_connect_messenger_status'])
            && $this->request->post['multi_connect_messenger_status'] === self::ON) {
            $this->validator("multi_connect_messenger_id", self::TYPE_CHECK_EXIST);
        }
        if (isset($this->request->post['multi_connect_phone_status'])
            && $this->request->post['multi_connect_phone_status'] === self::ON) {
            $this->validator("multi_connect_phone_number", self::TYPE_CHECK_NUMBER);
        }
        if (isset($this->request->post['multi_connect_instagram_status'])
            && $this->request->post['multi_connect_instagram_status'] === self::ON) {
            $this->validator("multi_connect_instagram", self::TYPE_CHECK_EXIST);
        }
        if (isset($this->request->post['multi_connect_email_status'])
            && $this->request->post['multi_connect_email_status'] === self::ON) {
            $this->validator("multi_connect_email", self::TYPE_CHECK_EXIST);
        }
        if (isset($this->request->post['multi_connect_vk_status'])
            && $this->request->post['multi_connect_vk_status'] === self::ON) {
            $this->validator("multi_connect_vk_id", self::TYPE_CHECK_EXIST);
        }
        if (isset($this->request->post['multi_connect_line_status'])
            && $this->request->post['multi_connect_line_status'] === self::ON) {
            $this->validator("multi_connect_line_id", self::TYPE_CHECK_EXIST);
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $param_name
     * @param $type
     */
    private function validator($param_name, $type) {
        if (isset($this->request->post[$param_name]) && !$this->checkType($type, $this->request->post[$param_name])) {
            $this->load->language('extension/module/widget_multi_connect');
            $error_name = str_replace("multi_connect", "error", $param_name);
            $this->error[$error_name] = $this->language->get('text_' . $error_name);
        }
    }

    /**
     * @param $type
     * @param $data
     * @return bool
     */
	private function checkType($type, $data) {
		if ($type === self::TYPE_CHECK_NUMBER) {
			return !empty($data) && is_numeric($data) && strlen($data) < 20;
		}
		if ($type === self::TYPE_CHECK_EXIST) {
			return !empty($data) && strlen($data) > 1 && strlen($data) < 40;
		}
		return false;
	}

    /**
     * @return array
     */
	private function parseError() {
		$data = [];
		foreach ($this->error as $error_name => $error_text) {
			$data[$error_name] = ['text' => $error_text];
		}
		return $data;
	}
	private function prepareStatusData($status_name) {
		$data = null;
		if (isset($this->request->post[$status_name])) {
			$data = "checked";
		} elseif ($this->config->has($status_name)) {
			$data = "checked";
		} else {
			$data = '';
		}
		return $data;
	}

    /**
     * @param $data_name
     * @return string
     */
	private function prepareData($data_name) {
		$data = null;
		if (isset($this->request->post[$data_name])) {
			$data = $this->request->post[$data_name];
		} elseif ($this->config->has($data_name)) {
			$data = $this->config->get($data_name);
		} else {
			$data = '';
		}
		return $data;
	}
}
