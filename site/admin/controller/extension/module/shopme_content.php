<?php
class ControllerExtensionModuleShopmeContent extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/shopme_content');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('shopme_content', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			if (isset($this->request->post['save']) && $this->request->post['save'] == 'stay' && $this->request->get['module_id']) {
				$this->response->redirect($this->url->link('extension/module/shopme_content', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)); 
			} else {
                $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
            }

		}
		
		if (isset($this->request->get['module_id'])) {
			$data['save_and_stay'] = true; 
		} else {
			$data['save_and_stay'] = false;
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_add_section'] = $this->language->get('text_add_section');
		$data['text_groups_heading'] = $this->language->get('text_groups_heading');
		$data['text_section_title'] = $this->language->get('text_section_title');
		$data['text_section_icon'] = $this->language->get('text_section_icon');
		$data['text_section_h3'] = $this->language->get('text_section_h3');
		
		$data['text_input_question'] = $this->language->get('text_input_question');
		$data['text_input_block'] = $this->language->get('text_input_block');
		
		$data['text_plain'] = $this->language->get('text_plain');
		$data['text_action1'] = $this->language->get('text_action1');
		$data['text_action2'] = $this->language->get('text_action2');
		$data['text_action2_help'] = $this->language->get('text_action2_help');
		$data['text_info1'] = $this->language->get('text_info1');
		$data['text_info1_full'] = $this->language->get('text_info1_full');
		$data['text_info2'] = $this->language->get('text_info2');
		$data['text_info3'] = $this->language->get('text_info3');
		$data['text_info4'] = $this->language->get('text_info4');
		
		$data['text_col1'] = $this->language->get('text_col1');
		$data['text_col2'] = $this->language->get('text_col2');
		$data['text_col3'] = $this->language->get('text_col3');
		$data['text_col4'] = $this->language->get('text_col4');
		$data['text_col5'] = $this->language->get('text_col5');
		$data['text_col6'] = $this->language->get('text_col6');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_module_title'] = $this->language->get('entry_module_title');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_columns'] = $this->language->get('entry_columns');

		$data['entry_bgblock'] = $this->language->get('entry_bgblock');
		$data['entry_bgblockh'] = $this->language->get('entry_bgblockh');
		$data['entry_colortext'] = $this->language->get('entry_colortext');
		$data['entry_colortexth'] = $this->language->get('entry_colortexth');
		$data['text_blocks'] = $this->language->get('text_blocks');

		$data['button_save'] = $this->language->get('button_save');
        $data['button_save_stay'] = $this->language->get('button_save_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_section'] = $this->language->get('button_add_section');
		$data['button_add_group'] = $this->language->get('button_add_group');
		$data['button_remove'] = $this->language->get('button_remove'); 
        
		$data['tab_section'] = $this->language->get('tab_section');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/shopme_content', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/shopme_content', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/shopme_content', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/shopme_content', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);
	
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

		if (isset($this->request->post['bgblock'])) {
			$data['bgblock'] = $this->request->post['bgblock'];
		} elseif (!empty($module_info)) {
			$data['bgblock'] = $module_info['bgblock'];
		} else {
			$data['bgblock'] = 'FAFAFA';
		}
		if (isset($this->request->post['bgblockh'])) {
			$data['bgblockh'] = $this->request->post['bgblockh'];
		} elseif (!empty($module_info)) {
			$data['bgblockh'] = $module_info['bgblockh'];
		} else {
			$data['bgblockh'] = '1BBEF0';
		}
		if (isset($this->request->post['colortext'])) {
			$data['colortext'] = $this->request->post['colortext'];
		} elseif (!empty($module_info)) {
			$data['colortext'] = $module_info['colortext'];
		} else {
			$data['colortext'] = '018BC8';
		}
		if (isset($this->request->post['colortexth'])) {
			$data['colortexth'] = $this->request->post['colortexth'];
		} elseif (!empty($module_info)) {
			$data['colortexth'] = $module_info['colortexth'];
		} else {
			$data['colortexth'] = 'FFFFFF';
		}
		
		if (isset($this->request->post['module_title'])) {
			$data['module_title'] = $this->request->post['module_title'];
		} elseif (!empty($module_info['module_title'])) {
			$data['module_title'] = $module_info['module_title'];
		} else {
			$data['module_title'] = '';
		}
		
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		
		if (isset($this->request->post['template'])) {
			$data['template'] = $this->request->post['template'];
		} elseif (!empty($module_info)) {
			$data['template'] = $module_info['template'];
		} else {
			$data['template'] = '';
		}
		
		if (isset($this->request->post['columns'])) {
			$data['columns'] = $this->request->post['columns'];
		} elseif (!empty($module_info)) {
			$data['columns'] = $module_info['columns'];
		} else {
			$data['columns'] = '';
		}
		        
        if (isset($this->request->post['sections'])) {
			$data['sections'] = $this->request->post['sections'];
		} elseif (!empty($module_info)) {
			$sections = $module_info['sections'];
		} else {
			$sections = array();
		}
		
		$data['sections'] = array();
		if (isset($sections) && !empty($sections)){
			foreach ($sections as $section) {
					
				$data['sections'][] = array(
					'title'   => $section['title'],
					'icon'   => $section['icon'],
					'h3'   => $section['h3'],
					'block'   => $section['block']
				);
			}
		}
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/shopme_content', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/shopme_content')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
				
		return !$this->error;
	}
}