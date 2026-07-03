<?php
class ControllerExtensionModuleXmasSnowFlake extends Controller {
	private $error = [];

	private $code = 'module_mp_xmas_snow_flake';
	private $events = [[
		'trigger' => 'admin/view/common/column_left/before',
		'action' => 'extension/module/xmas_snow_flake/getMenu'
	],[
		'trigger' => 'catalog/view/common/footer/after',
		'action' => 'extension/xmas_snow_flake/xmas_snow_flake'
	]];

	public function install() {
		// install events
		$this->load->model('setting/event');

		$defaults = [
			'status' => 1,
			'sort_order' => 0,
			'description' => 'Modulepoints xmas/christmas snow flakes',
		];

		$this->model_setting_event->deleteEventByCode($this->code);

		foreach ($this->events as $event) {

			// add default keys in array
			foreach ($defaults as $key => $value) {
				if (!isset($event[$key])) {
					$event[$key] = $value;
				}
			}

			$this->model_setting_event->addEvent(
				$this->code,
				$event['trigger'],
				$event['action'],
				$event['status'],
				$event['sort_order']
			);
		}

	}

	public function uninstall() {
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode($this->code);
	}


	public function index() {

		$this->document->addStyle('view/stylesheet/xmas_snow_flake/stylesheet.css');
		$this->document->addStyle('view/javascript/xmas_snow_flake/colorpicker/css/colorpicker.css');
		$this->document->addScript('view/javascript/xmas_snow_flake/colorpicker/js/colorpicker.js');
		
		$this->load->language('extension/module/xmas_snow_flake');

		$this->document->setTitle($this->language->get('heading_title'));


		$store_id = $data['store_id'] = 0;

		if (isset($this->request->get['store_id'])) {
			$store_id = $data['store_id'] = (int)$this->request->get['store_id'];
		}

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_xmas_snow_flake', $this->request->post, $store_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_min'] = $this->language->get('text_min');
		$data['text_max'] = $this->language->get('text_max');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_color'] = $this->language->get('entry_color');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_intensity'] = $this->language->get('entry_intensity');
		$data['entry_size'] = $this->language->get('entry_size');
		$data['entry_opacity'] = $this->language->get('entry_opacity');
		$data['entry_drift'] = $this->language->get('entry_drift');
		$data['entry_speed'] = $this->language->get('entry_speed');

		$data['help_color'] = $this->language->get('help_color');
		$data['help_icon'] = $this->language->get('help_icon');
		$data['help_intensity'] = $this->language->get('help_intensity');
		$data['help_size'] = $this->language->get('help_size');
		$data['help_opacity'] = $this->language->get('help_opacity');
		$data['help_drift'] = $this->language->get('help_drift');
		$data['help_speed'] = $this->language->get('help_speed');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_support'] = $this->language->get('tab_support');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


		$data['breadcrumbs'] = [];

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
			'href' => $this->url->link('extension/module/xmas_snow_flake', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/xmas_snow_flake', 'user_token=' . $this->session->data['user_token']. '&store_id='. $store_id, true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$module_info = $this->model_setting_setting->getSetting('module_xmas_snow_flake', $store_id);

		if (isset($this->request->post['module_xmas_snow_flake_status'])) {
			$data['module_xmas_snow_flake_status'] = $this->request->post['module_xmas_snow_flake_status'];
		} elseif (isset($module_info['module_xmas_snow_flake_status'])) {
			$data['module_xmas_snow_flake_status'] = $module_info['module_xmas_snow_flake_status'];
		} else {
			$data['module_xmas_snow_flake_status'] = 0;
		}

		if (isset($this->request->post['module_xmas_snow_flake_color'])) {
			$data['module_xmas_snow_flake_color'] = $this->request->post['module_xmas_snow_flake_color'];
		} elseif (isset($module_info['module_xmas_snow_flake_color'])) {
			$data['module_xmas_snow_flake_color'] = $module_info['module_xmas_snow_flake_color'];
		} else{
			$data['module_xmas_snow_flake_color'] = '71C7D8';
		}

		if (isset($this->request->post['module_xmas_snow_flake_icon'])) {
			$data['module_xmas_snow_flake_icon'] = $this->request->post['module_xmas_snow_flake_icon'];
		} elseif (isset($module_info['module_xmas_snow_flake_icon'])) {
			$data['module_xmas_snow_flake_icon'] = $module_info['module_xmas_snow_flake_icon'];
		} else{
			$data['module_xmas_snow_flake_icon'] = '&#10052;';
		}

		if (isset($this->request->post['module_xmas_snow_flake_intensity'])) {
			$data['module_xmas_snow_flake_intensity'] = $this->request->post['module_xmas_snow_flake_intensity'];
		} elseif (isset($module_info['module_xmas_snow_flake_intensity'])) {
			$data['module_xmas_snow_flake_intensity'] = $module_info['module_xmas_snow_flake_intensity'];
		} else{
			$data['module_xmas_snow_flake_intensity'] = '40';
		}

		if (isset($this->request->post['module_xmas_snow_flake_sizemin'])) {
			$data['module_xmas_snow_flake_sizemin'] = $this->request->post['module_xmas_snow_flake_sizemin'];
		} elseif (isset($module_info['module_xmas_snow_flake_sizemin'])) {
			$data['module_xmas_snow_flake_sizemin'] = $module_info['module_xmas_snow_flake_sizemin'];
		} else{
			$data['module_xmas_snow_flake_sizemin'] = '12';
		}

		if (isset($this->request->post['module_xmas_snow_flake_sizemax'])) {
			$data['module_xmas_snow_flake_sizemax'] = $this->request->post['module_xmas_snow_flake_sizemax'];
		} elseif (isset($module_info['module_xmas_snow_flake_sizemax'])) {
			$data['module_xmas_snow_flake_sizemax'] = $module_info['module_xmas_snow_flake_sizemax'];
		} else{
			$data['module_xmas_snow_flake_sizemax'] = '30';
		}

		if (isset($this->request->post['module_xmas_snow_flake_opacitymin'])) {
			$data['module_xmas_snow_flake_opacitymin'] = $this->request->post['module_xmas_snow_flake_opacitymin'];
		} elseif (isset($module_info['module_xmas_snow_flake_opacitymin'])) {
			$data['module_xmas_snow_flake_opacitymin'] = $module_info['module_xmas_snow_flake_opacitymin'];
		} else{
			$data['module_xmas_snow_flake_opacitymin'] = '0.4';
		}

		if (isset($this->request->post['module_xmas_snow_flake_opacitymax'])) {
			$data['module_xmas_snow_flake_opacitymax'] = $this->request->post['module_xmas_snow_flake_opacitymax'];
		} elseif (isset($module_info['module_xmas_snow_flake_opacitymax'])) {
			$data['module_xmas_snow_flake_opacitymax'] = $module_info['module_xmas_snow_flake_opacitymax'];
		} else{
			$data['module_xmas_snow_flake_opacitymax'] = '1';
		}

		if (isset($this->request->post['module_xmas_snow_flake_driftmin'])) {
			$data['module_xmas_snow_flake_driftmin'] = $this->request->post['module_xmas_snow_flake_driftmin'];
		} elseif (isset($module_info['module_xmas_snow_flake_driftmin'])) {
			$data['module_xmas_snow_flake_driftmin'] = $module_info['module_xmas_snow_flake_driftmin'];
		} else{
			$data['module_xmas_snow_flake_driftmin'] = '-2';
		}

		if (isset($this->request->post['module_xmas_snow_flake_driftmax'])) {
			$data['module_xmas_snow_flake_driftmax'] = $this->request->post['module_xmas_snow_flake_driftmax'];
		} elseif (isset($module_info['module_xmas_snow_flake_driftmax'])) {
			$data['module_xmas_snow_flake_driftmax'] = $module_info['module_xmas_snow_flake_driftmax'];
		} else{
			$data['module_xmas_snow_flake_driftmax'] = '2';
		}

		if (isset($this->request->post['module_xmas_snow_flake_speedmin'])) {
			$data['module_xmas_snow_flake_speedmin'] = $this->request->post['module_xmas_snow_flake_speedmin'];
		} elseif (isset($module_info['module_xmas_snow_flake_speedmin'])) {
			$data['module_xmas_snow_flake_speedmin'] = $module_info['module_xmas_snow_flake_speedmin'];
		} else{
			$data['module_xmas_snow_flake_speedmin'] = '55';
		}

		if (isset($this->request->post['module_xmas_snow_flake_speedmax'])) {
			$data['module_xmas_snow_flake_speedmax'] = $this->request->post['module_xmas_snow_flake_speedmax'];
		} elseif (isset($module_info['module_xmas_snow_flake_speedmax'])) {
			$data['module_xmas_snow_flake_speedmax'] = $module_info['module_xmas_snow_flake_speedmax'];
		} else{
			$data['module_xmas_snow_flake_speedmax'] = '120';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/xmas_snow_flake', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/xmas_snow_flake')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}