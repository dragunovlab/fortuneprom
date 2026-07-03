<?php
class ControllerExtensionModuleMegamessage extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/megamessage');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('megamessage', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_admin'] = $this->language->get('entry_admin');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['text_messenger'] = $this->language->get('text_messenger');
		$data['text_messenger_help'] = $this->language->get('text_messenger_help');

		$data['text_line'] = $this->language->get('text_line');
		$data['text_line_help'] = $this->language->get('text_line_help');

		$data['text_phone'] = $this->language->get('text_phone');
		$data['text_phone_help'] = $this->language->get('text_phone_help');

		$data['text_skype'] = $this->language->get('text_skype');
		$data['text_skype_help'] = $this->language->get('text_skype_help');

		$data['text_vk'] = $this->language->get('text_vk');
		$data['text_vk_help'] = $this->language->get('text_vk_help');

		$data['text_whatsapp'] = $this->language->get('text_whatsapp');
		$data['text_whatsapp_help'] = $this->language->get('text_whatsapp_help');

		$data['text_telegram'] = $this->language->get('text_telegram');
		$data['text_telegram_help'] = $this->language->get('text_telegram_help');

		$data['text_viber'] = $this->language->get('text_viber');
		$data['text_viber_help'] = $this->language->get('text_viber_help');

		$data['text_mail'] = $this->language->get('text_mail');
		$data['text_mail_help'] = $this->language->get('text_mail_help');

		$data['text_logo'] = $this->language->get('text_logo');
		$data['text_megamessage_position'] = $this->language->get('text_megamessage_position');

		$data['text_megamessage_position_left'] = $this->language->get('text_megamessage_position_left');
		$data['text_megamessage_position_right'] = $this->language->get('text_megamessage_position_right');

		
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/megamessage', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/megamessage', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		//messanger
		if (isset($this->request->post['megamessage_message'])) {
			$data['megamessage_message'] = $this->request->post['megamessage_message'];
		} else {
			$data['megamessage_message'] = $this->config->get('megamessage_message');
		}

		if (isset($this->request->post['megamessage_message_status'])) {
			$data['megamessage_message_status'] = $this->request->post['megamessage_message_status'];
		} else {
			$data['megamessage_message_status'] = $this->config->get('megamessage_message_status');
		}	

		//line
		if (isset($this->request->post['megamessage_line'])) {
			$data['megamessage_line'] = $this->request->post['megamessage_line'];
		} else {
			$data['megamessage_line'] = $this->config->get('megamessage_line');
		}

		if (isset($this->request->post['megamessage_line_status'])) {
			$data['megamessage_line_status'] = $this->request->post['megamessage_line_status'];
		} else {
			$data['megamessage_line_status'] = $this->config->get('megamessage_line_status');
		}

		//phone
		if (isset($this->request->post['megamessage_phone'])) {
			$data['megamessage_phone'] = $this->request->post['megamessage_phone'];
		} else {
			$data['megamessage_phone'] = $this->config->get('megamessage_phone');
		}

		if (isset($this->request->post['megamessage_phone_status'])) {
			$data['megamessage_phone_status'] = $this->request->post['megamessage_phone_status'];
		} else {
			$data['megamessage_phone_status'] = $this->config->get('megamessage_phone_status');
		}

		//skype
		if (isset($this->request->post['megamessage_skype'])) {
			$data['megamessage_skype'] = $this->request->post['megamessage_skype'];
		} else {
			$data['megamessage_skype'] = $this->config->get('megamessage_skype');
		}

		if (isset($this->request->post['megamessage_skype_status'])) {
			$data['megamessage_skype_status'] = $this->request->post['megamessage_skype_status'];
		} else {
			$data['megamessage_skype_status'] = $this->config->get('megamessage_skype_status');
		}

		//vk
		if (isset($this->request->post['megamessage_vk'])) {
			$data['megamessage_vk'] = $this->request->post['megamessage_vk'];
		} else {
			$data['megamessage_vk'] = $this->config->get('megamessage_vk');
		}

		if (isset($this->request->post['megamessage_vk_status'])) {
			$data['megamessage_vk_status'] = $this->request->post['megamessage_vk_status'];
		} else {
			$data['megamessage_vk_status'] = $this->config->get('megamessage_vk_status');
		}

		//whatsapp
		if (isset($this->request->post['megamessage_whatsapp'])) {
			$data['megamessage_whatsapp'] = $this->request->post['megamessage_whatsapp'];
		} else {
			$data['megamessage_whatsapp'] = $this->config->get('megamessage_whatsapp');
		}

		if (isset($this->request->post['megamessage_whatsapp_status'])) {
			$data['megamessage_whatsapp_status'] = $this->request->post['megamessage_whatsapp_status'];
		} else {
			$data['megamessage_whatsapp_status'] = $this->config->get('megamessage_whatsapp_status');
		}

		//telegram
		if (isset($this->request->post['megamessage_telegram'])) {
			$data['megamessage_telegram'] = $this->request->post['megamessage_telegram'];
		} else {
			$data['megamessage_telegram'] = $this->config->get('megamessage_telegram');
		}

		if (isset($this->request->post['megamessage_telegram_status'])) {
			$data['megamessage_telegram_status'] = $this->request->post['megamessage_telegram_status'];
		} else {
			$data['megamessage_telegram_status'] = $this->config->get('megamessage_telegram_status');
		}

		//telegram
		if (isset($this->request->post['megamessage_viber'])) {
			$data['megamessage_viber'] = $this->request->post['megamessage_viber'];
		} else {
			$data['megamessage_viber'] = $this->config->get('megamessage_viber');
		}

		if (isset($this->request->post['megamessage_viber_status'])) {
			$data['megamessage_viber_status'] = $this->request->post['megamessage_viber_status'];
		} else {
			$data['megamessage_viber_status'] = $this->config->get('megamessage_viber_status');
		}

		//mail
		if (isset($this->request->post['megamessage_mail'])) {
			$data['megamessage_mail'] = $this->request->post['megamessage_mail'];
		} else {
			$data['megamessage_mail'] = $this->config->get('megamessage_mail');
		}

		if (isset($this->request->post['megamessage_mail_status'])) {
			$data['megamessage_mail_status'] = $this->request->post['megamessage_mail_status'];
		} else {
			$data['megamessage_mail_status'] = $this->config->get('megamessage_mail_status');
		}

		
		//megamessage_color
		if (isset($this->request->post['megamessage_color'])) {
			$data['megamessage_color'] = $this->request->post['megamessage_color'];
		} else {
			$data['megamessage_color'] = $this->config->get('megamessage_color');
		}

		if (isset($this->request->post['megamessage_color_status'])) {
			$data['megamessage_color_status'] = $this->request->post['megamessage_color_status'];
		} else {
			$data['megamessage_color_status'] = $this->config->get('megamessage_color_status');
		}

		//megamessage_logo
		if (isset($this->request->post['megamessage_logo'])) {
			$data['megamessage_logo'] = $this->request->post['megamessage_logo'];
		} else {
			$data['megamessage_logo'] = $this->config->get('megamessage_logo');
		} 

		if (!$this->config->get('megamessage_logo')) {
			$data['megamessage_logo'] = '#229ecd';
		}

		//megamessage_position 1 - лево 0 - право
		if (isset($this->request->post['megamessage_position'])) {
			$data['megamessage_position'] = $this->request->post['megamessage_position'];
		} else {
			$data['megamessage_position'] = $this->config->get('megamessage_position');
		}



		if (isset($this->request->post['megamessage_status'])) {
			$data['megamessage_status'] = $this->request->post['megamessage_status'];
		} else {
			$data['megamessage_status'] = $this->config->get('megamessage_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/megamessage', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/megamessage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}