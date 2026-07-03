<?php
class ControllerExtensionModuleAutoFeatured extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/autofeatured');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('autofeatured', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_attribute'] = $this->language->get('text_attribute');
		$data['text_random'] = $this->language->get('text_random');

		$data['entry_one_cat'] = $this->language->get('entry_one_cat');
		$data['entry_in_stock'] = $this->language->get('entry_in_stock');
		$data['default_title'] = $this->language->get('default_title');
		$data['entry_sortby'] = $this->language->get('entry_sortby');
		$data['entry_attr'] = $this->language->get('entry_attr');
		$data['entry_category_source'] = $this->language->get('entry_category_source');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_title_view'] = $this->language->get('entry_title_view');
		$data['title_view_name'] = $this->language->get('title_view_name');
		$data['title_view_all'] = $this->language->get('title_view_all');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');

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

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
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
				'href' => $this->url->link('extension/module/autofeatured', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/autofeatured', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/autofeatured', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/autofeatured', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		
		$this->load->model('catalog/attribute');
		
		$data['attributes'] = array();

		if (isset($this->request->post['attribute'])) {
			$attributes = $this->request->post['attribute'];
		} elseif (!empty($module_info)) {
			$attributes = $module_info['attribute'];
		} else {
			$attributes = array();
		}
		if ($attributes){
			foreach ($attributes as $attribute_id) {
				$attribute_info = $this->model_catalog_attribute->getAttribute($attribute_id);

				if ($attribute_info) {
					$data['attributes'][] = array(
						'attribute_id' 	=> $attribute_info['attribute_id'],
						'name'       	=> $attribute_info['name']
					);
				}
			}
		}
		$this->load->model('catalog/category');
		
		$data['categories_source'] = array();

		if (isset($this->request->post['category_source'])) {
			$categories_source = $this->request->post['category_source'];
		} elseif (!empty($module_info)) {
			$categories_source = $module_info['category_source'];
		} else {
			$categories_source = array();
		}
		if ($categories_source){
			foreach ($categories_source as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);

				if ($category_info) {
					$data['categories_source'][] = array(
						'category_id' 	=> $category_info['category_id'],
						'name'       	=> $category_info['name']
					);
				}
			}
		}
		$data['categories'] = array();

		if (isset($this->request->post['category'])) {
			$categories = $this->request->post['category'];
		} elseif (!empty($module_info)) {
			$categories = $module_info['category'];
		} else {
			$categories = array();
		}
		if ($categories){
			foreach ($categories as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);

				if ($category_info) {
					$data['categories'][] = array(
						'category_id' 	=> $category_info['category_id'],
						'name'       	=> $category_info['name']
					);
				}
			}
		}
		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}
		
		if (isset($this->request->post['titleview'])) {
			$data['titleview'] = $this->request->post['titleview'];
		} elseif (!empty($module_info)) {
			$data['titleview'] = $module_info['titleview'];
		} else {
			$data['titleview'] = 0;
		}
		
		if (isset($this->request->post['one_cat'])) {
			$data['one_cat'] = $this->request->post['one_cat'];
		} elseif (!empty($module_info)) {
			$data['one_cat'] = $module_info['one_cat'];
		} else {
			$data['one_cat'] = 0;
		}

		if (isset($this->request->post['in_stock'])) {
			$data['in_stock'] = $this->request->post['in_stock'];
		} elseif (!empty($module_info)) {
			$data['in_stock'] = $module_info['in_stock'];
		} else {
			$data['in_stock'] = 0;
		}
		
		if (isset($this->request->post['sortby'])) {
			$data['sortby'] = $this->request->post['sortby'];
		} elseif (!empty($module_info)) {
			$data['sortby'] = $module_info['sortby'];
		} else {
			$data['sortby'] = 'random';
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
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

		$this->response->setOutput($this->load->view('extension/module/autofeatured', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/autofeatured')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}