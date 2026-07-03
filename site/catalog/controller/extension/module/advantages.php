<?php
class ControllerExtensionModuleAdvantages extends Controller {
	public function index($setting) {

		$this->load->model('tool/image');
		$this->load->language('extension/module/advantages');

		$data['text_readmore'] = $this->language->get('text_readmore');

	if (isset($setting['module_description'][$this->config->get('config_language_id')])) {

		if (isset($setting['title'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = $setting['title'][$this->config->get('config_language_id')];
		} else {
			$data['heading_title'] = $setting['name'];	
		}

		@$data['icon_size'] = $setting['icon_size'];
		@$data['icon_color'] = $setting['icon_color'];
		@$data['title_size'] = $setting['title_size'];
		@$data['title_color'] = $setting['title_color'];
		@$data['text_color'] = $setting['text_color'];
		@$data['background'] = $setting['background'];
		@$data['background_color'] = $setting['background_color'];
		@$data['image_position'] = $setting['image_position'];
		@$data['title_show'] = $setting['title_show'];
		@$data['readmore_show'] = $setting['readmore_show'];


		if (isset($setting['blocks_inline'])) {
			$data['blocks_inline'] = $setting['blocks_inline'];
		} else {
			$data['blocks_inline'] = 4;
		}

		$data['icon1'] = $setting['icon1'][0];
		$data['icon2'] = $setting['icon2'][0];
		$data['icon3'] = $setting['icon3'][0];
		$data['icon4'] = $setting['icon4'][0];
	
		if ($setting['image1'][0]) {
			$data['image1'] = $this->model_tool_image->resize($setting['image1'][0], $setting['image_width'], $setting['image_height']);
		} else {
			$data['image1'] = '';
		}
		$data['title1'] = $setting['module_description'][$this->config->get('config_language_id')]['title_1'];
		$data['description1'] = $setting['module_description'][$this->config->get('config_language_id')]['description_1'];
		$data['link1'] = $setting['module_description'][$this->config->get('config_language_id')]['link_1'];
		
		if ($setting['image2'][0]) {
			$data['image2'] = $this->model_tool_image->resize($setting['image2'][0], $setting['image_width'], $setting['image_height']);
		} else {
			$data['image2'] = '';
		}
		$data['title2'] = $setting['module_description'][$this->config->get('config_language_id')]['title_2'];
		$data['description2'] = $setting['module_description'][$this->config->get('config_language_id')]['description_2'];
		$data['link2'] = $setting['module_description'][$this->config->get('config_language_id')]['link_2'];
		
		if ($setting['image3'][0]) {
			$data['image3'] = $this->model_tool_image->resize($setting['image3'][0], $setting['image_width'], $setting['image_height']);
		} else {
			$data['image3'] = '';
		}
		$data['title3'] = $setting['module_description'][$this->config->get('config_language_id')]['title_3'];
		$data['description3'] = $setting['module_description'][$this->config->get('config_language_id')]['description_3'];
		$data['link3'] = $setting['module_description'][$this->config->get('config_language_id')]['link_3'];
		
		if ($setting['image4'][0]) {
			$data['image4'] = $this->model_tool_image->resize($setting['image4'][0], $setting['image_width'], $setting['image_height']);
		} else {
			$data['image4'] = '';
		}
		$data['title4'] = $setting['module_description'][$this->config->get('config_language_id')]['title_4'];
		$data['description4'] = $setting['module_description'][$this->config->get('config_language_id')]['description_4'];
		$data['link4'] = $setting['module_description'][$this->config->get('config_language_id')]['link_4'];
	
	}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/advantages.css')) {
			$this->document->addStyle('catalog/view/theme/'. $this->config->get('config_template') . '/stylesheet/advantages.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/advantages.css');
		}

		return $this->load->view('extension/module/advantages', $data);
	}
}