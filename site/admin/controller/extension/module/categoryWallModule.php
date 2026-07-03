<?php
class ControllerExtensionModuleCategoryWallModule extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/categoryWallModule');

		$this->document->setTitle($this->language->get('module_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('CategoryWallModule', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}			

			if ( isset($this->request->post['seoKeyword']) ) {
				$this->setSeoUrl($this->request->post['seoKeyword']);	
			}	

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}	
		
		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('module_title'),
				'href' => $this->url->link('extension/module/categoryWallModule', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('module_title'),
				'href' => $this->url->link('extension/module/categoryWallModule', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}


		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/categoryWallModule', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/categoryWallModule', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}	

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/store');

		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}


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

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}	

        $this->load->model('catalog/category');
		$allCategories = $this->model_catalog_category->getCategories(0);
		usort($allCategories, function($a, $b){
			return strcmp($a['name'], $b['name']);
		});

        if (isset($this->request->post['category'])
            && is_array($this->request->post['category'])
        ) {
            $categories = $this->request->post['category'];
        } elseif (!empty($module_info['category'])) {
            $categories = $module_info['category'];
        } else {
            $categories = array();
		}

		foreach($categories as $select_cat) {
			foreach ($allCategories as $key => $cat) {
				if(in_array($cat['category_id'], $select_cat)) {
					unset($allCategories[$key]);
				}
			}
		}
		$data['categories'] = $allCategories;
		$data['selected_cat'] =  $categories;

		if (isset($this->request->post['product_count'])) {
			$data['product_count'] = $this->request->post['product_count'];
		} elseif (!empty($module_info['product_count'])) {
			$data['product_count'] = $module_info['product_count'];
		} else {
			$data['product_count'] = 0;
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 230;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 230;
		}

		if (isset($this->request->post['qtyInRow'])) {
			$data['qtyInRow'] = $this->request->post['qtyInRow'];
		} elseif (!empty($module_info)) {
			$data['qtyInRow'] = $module_info['qtyInRow'];
		} else {
			$data['qtyInRow'] = 3;
		}
		
		if (isset($this->request->post['minWidth'])) {
			$data['minWidth'] = $this->request->post['minWidth'];
		} elseif (!empty($module_info)) {
			$data['minWidth'] = $module_info['minWidth'];
		} else {
			$data['minWidth'] = 230;
		}
		
		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($module_info)) {
			$data['description'] = $module_info['description'];
		} else {
			$data['description'] = array();
		}		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/categoryWallModule', $data));
	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/categoryWallModule')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		
		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}	
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	} 	
}