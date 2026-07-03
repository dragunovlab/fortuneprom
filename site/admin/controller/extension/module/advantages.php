<?php
class ControllerExtensionModuleAdvantages extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('extension/module/advantages');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('advantages', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title_module'] = $this->language->get('entry_title_module');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_size_image'] = $this->language->get('entry_size_image');
		$data['entry_image_width'] = $this->language->get('entry_image_width');
		$data['entry_image_height'] = $this->language->get('entry_image_height');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_icon_size'] = $this->language->get('entry_icon_size');
		$data['entry_icon_color'] = $this->language->get('entry_icon_color');
		$data['entry_title_size'] = $this->language->get('entry_title_size');
		$data['entry_title_color'] = $this->language->get('entry_title_color');
		$data['entry_text_color'] = $this->language->get('entry_text_color');
		$data['entry_image_position'] = $this->language->get('entry_image_position');
		$data['entry_title_show'] = $this->language->get('entry_title_show');
		$data['entry_readmore_show'] = $this->language->get('entry_readmore_show');
		$data['entry_background'] = $this->language->get('entry_background');
		$data['entry_background_color'] = $this->language->get('entry_background_color');
		$data['entry_blocks_inline'] = $this->language->get('entry_blocks_inline');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_link'] = $this->language->get('entry_link');
	
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_icon_size'] = $this->language->get('help_icon_size');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       			'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
   		);

   		$data['breadcrumbs'][] = array(
       			'text'      => $this->language->get('text_extension'),
				'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);
			
		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/advantages', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/advantages', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/advantages', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/advantages', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($module_info['title'])) {
			$data['title'] = $module_info['title'];
		} else {
			$data['title'] = array();
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image1'][0]) && is_file(DIR_IMAGE . $this->request->post['image1'][0])) {
			$data['image1'] = $this->request->post['image1'][0];
			$data['thumb1'] = $this->model_tool_image->resize($this->request->post['image1'][0], 100, 100);
		} elseif (!empty($module_info['image1'][0]) && is_file(DIR_IMAGE . $module_info['image1'][0])) {
			$data['image1'] = $module_info['image1'][0];
			$data['thumb1'] = $this->model_tool_image->resize($module_info['image1'][0], 100, 100);
		} else {
			$data['image1'] = '';
			$data['thumb1'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['icon1'])) {
			$data['icon1'] = $this->request->post['icon1'][0];
		} elseif (!empty($module_info)) {
			$data['icon1'] = $module_info['icon1'][0];
		} else {
			$data['icon1'] = '';
		}


		if (isset($this->request->post['image2'][0]) && is_file(DIR_IMAGE . $this->request->post['image2'][0])) {
			$data['image2'] = $this->request->post['image2'][0];
			$data['thumb2'] = $this->model_tool_image->resize($this->request->post['image2'][0], 100, 100);
		} elseif (!empty($module_info['image2'][0]) && is_file(DIR_IMAGE . $module_info['image2'][0])) {
			$data['image2'] = $module_info['image2'][0];
			$data['thumb2'] = $this->model_tool_image->resize($module_info['image2'][0], 100, 100);
		} else {
			$data['image2'] = '';
			$data['thumb2'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['icon2'])) {
			$data['icon2'] = $this->request->post['icon2'][0];
		} elseif (!empty($module_info['icon2'])) {
			$data['icon2'] = $module_info['icon2'][0];
		} else {
			$data['icon2'] = '';
		}


		if (isset($this->request->post['image3'][0]) && is_file(DIR_IMAGE . $this->request->post['image3'][0])) {
			$data['image3'] = $this->request->post['image3'][0];
			$data['thumb3'] = $this->model_tool_image->resize($this->request->post['image3'][0], 100, 100);
		} elseif (!empty($module_info['image3'][0]) && is_file(DIR_IMAGE . $module_info['image3'][0])) {
			$data['image3'] = $module_info['image3'][0];
			$data['thumb3'] = $this->model_tool_image->resize($module_info['image3'][0], 100, 100);
		} else {
			$data['image3'] = '';
			$data['thumb3'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['icon3'])) {
			$data['icon3'] = $this->request->post['icon3'][0];
		} elseif (!empty($module_info['icon3'])) {
			$data['icon3'] = $module_info['icon3'][0];
		} else {
			$data['icon3'] = '';
		}


		if (isset($this->request->post['image4'][0]) && is_file(DIR_IMAGE . $this->request->post['image4'][0])) {
			$data['image4'] = $this->request->post['image4'][0];
			$data['thumb4'] = $this->model_tool_image->resize($this->request->post['image4'][0], 100, 100);
		} elseif (!empty($module_info['image4'][0]) && is_file(DIR_IMAGE . $module_info['image4'][0])) {
			$data['image4'] = $module_info['image4'][0];
			$data['thumb4'] = $this->model_tool_image->resize($module_info['image4'][0], 100, 100);
		} else {
			$data['image4'] = '';
			$data['thumb4'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['icon4'])) {
			$data['icon4'] = $this->request->post['icon4'][0];
		} elseif (!empty($module_info['icon4'])) {
			$data['icon4'] = $module_info['icon4'][0];
		} else {
			$data['icon4'] = '';
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


		if (isset($this->request->post['module_description'])) {
			$data['module_description'] = $this->request->post['module_description'];
		} elseif (!empty($module_info)) {
			$data['module_description'] = $module_info['module_description'];
		} else {
			$data['module_description'] = array();
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();


		if (isset($this->request->post['image_width'])) {
			$data['image_width'] = $this->request->post['image_width'];
		} elseif (!empty($module_info['image_width'])) {
			$data['image_width'] = $module_info['image_width'];
		} else {
			$data['image_width'] = 60;
		}

		if (isset($this->request->post['image_height'])) {
			$data['image_height'] = $this->request->post['image_height'];
		} elseif (!empty($module_info['image_height'])) {
			$data['image_height'] = $module_info['image_height'];
		} else {
			$data['image_height'] = 60;
		}

		if (isset($this->request->post['icon_size'])) {
			$data['icon_size'] = $this->request->post['icon_size'];
		} elseif (!empty($module_info['icon_size'])) {
			$data['icon_size'] = $module_info['icon_size'];
		} else {
			$data['icon_size'] = 60;
		}

		if (isset($this->request->post['icon_color'])) {
			$data['icon_color'] = $this->request->post['icon_color'];
		} elseif (!empty($module_info['icon_color'])) {
			$data['icon_color'] = $module_info['icon_color'];
		} else {
			$data['icon_color'] = '#9BC5CA';
		}

		if (isset($this->request->post['title_size'])) {
			$data['title_size'] = $this->request->post['title_size'];
		} elseif (!empty($module_info['title_size'])) {
			$data['title_size'] = $module_info['title_size'];
		} else {
			$data['title_size'] = 18;
		}

		if (isset($this->request->post['title_color'])) {
			$data['title_color'] = $this->request->post['title_color'];
		} elseif (!empty($module_info['title_color'])) {
			$data['title_color'] = $module_info['title_color'];
		} else {
			$data['title_color'] = '#68777D';
		}

		if (isset($this->request->post['text_color'])) {
			$data['text_color'] = $this->request->post['text_color'];
		} elseif (!empty($module_info['text_color'])) {
			$data['text_color'] = $module_info['text_color'];
		} else {
			$data['text_color'] = '#575757';
		}

		$data['image_positions'] = array(
			"top" => $this->language->get('text_image_position_1'), 
			"left" => $this->language->get('text_image_position_2')
		);

		if (isset($this->request->post['image_position'])) {
			$data['image_position'] = $this->request->post['image_position'];
		} elseif (!empty($module_info)) {
			$data['image_position'] = $module_info['image_position'];
		} else {
			$data['image_position'] = '';
		}

		if (isset($this->request->post['title_show'])) {
			$data['title_show'] = $this->request->post['title_show'];
		} elseif (!empty($module_info['title_show'])) {
			$data['title_show'] = $module_info['title_show'];
		} else {
			$data['title_show'] = '';
		}

		if (isset($this->request->post['readmore_show'])) {
			$data['readmore_show'] = $this->request->post['readmore_show'];
		} elseif (!empty($module_info['readmore_show'])) {
			$data['readmore_show'] = $module_info['readmore_show'];
		} else {
			$data['readmore_show'] = '';
		}

		if (isset($this->request->post['background_color'])) {
			$data['background_color'] = $this->request->post['background_color'];
		} elseif (!empty($module_info['background_color'])) {
			$data['background_color'] = $module_info['background_color'];
		} else {
			$data['background_color'] = '';
		}

		if (isset($this->request->post['background'])) {
			$data['background'] = $this->request->post['background'];
		} elseif (!empty($module_info['background'])) {
			$data['background'] = $module_info['background'];
		} else {
			$data['background'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['background']) && is_file(DIR_IMAGE . $this->request->post['background'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['background'], 100, 100);
		} elseif (!empty($module_info['background']) && is_file(DIR_IMAGE . $module_info['background'])) {
			$data['thumb'] = $this->model_tool_image->resize($module_info['background'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['blocks'] = array(
			"4" => 3, 
			"6" => 2,
			"12" => 1
		);

		if (isset($this->request->post['blocks_inline'])) {
			$data['blocks_inline'] = $this->request->post['blocks_inline'];
		} elseif (!empty($module_info['blocks_inline'])) {
			$data['blocks_inline'] = $module_info['blocks_inline'];
		} else {
			$data['blocks_inline'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/advantages', $data));

	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/advantages')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		foreach ($this->request->post['title'] as $language_id => $title) {
			if ((utf8_strlen($title) < 3) || (utf8_strlen($title) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}

		if (!$this->request->post['image_width'] || !$this->request->post['image_height']) {
			$this->error['warning'] = $this->language->get('error_size');
		}

		return !$this->error;
	}
}