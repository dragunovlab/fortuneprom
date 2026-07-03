<?php
class ControllerExtensionModuleAgreePopup extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/agree_popup');
		$this->document->addStyle('view/stylesheet/agree_popup/style.css');
		$this->document->addScript('view/stylesheet/agree_popup/jscolor/jscolor.js');	
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('agreedata', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['lang'] = $this->language->get('lang');		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select_icon'] = $this->language->get('text_select_icon');
		$data['entry_text_btn_disagree_href'] = $this->language->get('entry_text_btn_disagree_href');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_text_btn_agree'] = $this->language->get('entry_text_btn_agree');
		$data['entry_text_btn_disagree'] = $this->language->get('entry_text_btn_disagree');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_status_btn_agree'] = $this->language->get('entry_status_btn_agree');
		$data['entry_status_btn_disagree'] = $this->language->get('entry_status_btn_disagree');
		$data['entry_background_btn_agree'] = $this->language->get('entry_background_btn_agree');
		$data['entry_background_hover_btn_agree'] = $this->language->get('entry_background_hover_btn_agree');
		$data['entry_border_btn_agree'] = $this->language->get('entry_border_btn_agree');
		$data['entry_border_hover_btn_agree'] = $this->language->get('entry_border_hover_btn_agree');
		$data['entry_text_color_btn_agree'] = $this->language->get('entry_text_color_btn_agree');
		$data['entry_text_color_hover_btn_agree'] = $this->language->get('entry_text_color_hover_btn_agree');
		$data['entry_icon_btn_agree'] = $this->language->get('entry_icon_btn_agree');
		$data['entry_background_btn_disagree'] = $this->language->get('entry_background_btn_disagree');
		$data['entry_background_hover_btn_disagree'] = $this->language->get('entry_background_hover_btn_disagree');
		$data['entry_border_btn_disagree'] = $this->language->get('entry_border_btn_disagree');
		$data['entry_border_hover_btn_disagree'] = $this->language->get('entry_border_hover_btn_agree');
		$data['entry_text_color_btn_disagree'] = $this->language->get('entry_text_color_btn_disagree');
		$data['entry_text_color_hover_btn_disagree'] = $this->language->get('entry_text_color_hover_btn_disagree');
		$data['entry_icon_btn_disagree'] = $this->language->get('entry_icon_btn_disagree');
		$data['entry_background_agree_header'] = $this->language->get('entry_background_agree_header');
		$data['entry_text_color_agree_header'] = $this->language->get('entry_text_color_agree_header');
		$data['entry_icon_agree_header'] = $this->language->get('entry_icon_agree_header');
		$data['entry_background_agree_footer'] = $this->language->get('entry_background_agree_footer');
		$data['entry_method_agree_popup'] = $this->language->get('entry_method_agree_popup');
		$data['entry_escape_click'] = $this->language->get('entry_escape_click');
		$data['entry_blur_click'] = $this->language->get('entry_blur_click');
		$data['text_method_cookie'] = $this->language->get('text_method_cookie');
		$data['text_method_session'] = $this->language->get('text_method_session');
		$data['text_cookie_day'] = $this->language->get('text_cookie_day');
		$data['text_delay_time'] = $this->language->get('text_delay_time');
		$data['help_delay_time'] = $this->language->get('help_delay_time');
		$data['help_method'] = $this->language->get('help_method');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_style'] = $this->language->get('tab_style');
		

		

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/agree_popup', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/agree_popup', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/agree_popup', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/agree_popup', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];
		if (isset($this->request->post['agreedata'])) {
			$data['agreedata'] = $this->request->post['agreedata'];
		} else {
			$data['agreedata'] = $this->config->get('agreedata');
		}
		$data['VERSION'] = VERSION;	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/agree_popup', $data));
	}
	public function uninstall() {
		$this->load->model('extension/extension');
		$this->load->model('setting/setting');
		$this->model_extension_extension->uninstall('module', 'agree_popup');
		$this->model_setting_setting->deleteSetting('agreedata');
  }	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/agree_popup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}