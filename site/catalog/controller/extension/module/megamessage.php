<?php
class ControllerExtensionModuleMegamessage extends Controller {
	public function index() {


		$data['megamessage_status'] = 0;
		$data['megamessage_position'] = 0;

		$this->load->model('setting/setting');
		$data = $this->model_setting_setting->getSetting('megamessage');

		$this->load->language('extension/module/megamessage');

		$data['tooltip_messenger'] = $this->language->get('tooltip_messenger');
		$data['tooltip_line']      = $this->language->get('tooltip_line');
		$data['tooltip_skype']     = $this->language->get('tooltip_skype');
		$data['tooltip_vk']        = $this->language->get('tooltip_vk');
		$data['tooltip_telegram']  = $this->language->get('tooltip_telegram');
		$data['tooltip_whatsapp']  = $this->language->get('tooltip_whatsapp');
		$data['tooltip_viber']     = $this->language->get('tooltip_viber');
		$data['tooltip_mail']      = $this->language->get('tooltip_mail');
		$data['tooltip_phone']     = $this->language->get('tooltip_phone');

		if (isset($data['megamessage_position']) && ($data['megamessage_position'])) {
			$data['placement'] = 'left';
		} else {
			$data['placement'] = 'right';
		}

		if (isset($data['megamessage_position']) && ($data['megamessage_position'])) {
			$data['placement_tooltip'] = 'right';
		} else {
			$data['placement_tooltip'] = 'left';
		}

		$data['android_os'] = false;
		$data['ios_os'] = false;
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'iphone')) {
				$data['ios_os'] = true;
			} elseif (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) {
				$data['android_os'] = true;
			}
		}

		$this->document->addStyle('catalog/view/theme/default/stylesheet/megamessage.css');
		$this->document->addScript('catalog/view/javascript/megamessage/mm.js');

		return $this->load->view('extension/module/megamessage', $data);
	}
}