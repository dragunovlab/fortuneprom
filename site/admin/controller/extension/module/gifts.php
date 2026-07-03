<?php
class ControllerExtensionModuleGifts extends Controller {
	private $error = array();
	
	public function index() {
		
		$this->load->language('extension/module/gifts');
		
		$data=$this->language->all();
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/module/gifts');
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(isset($this->request->post['product'])) {
				$this->model_extension_module_gifts->edit($this->request->post['product']);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', 'SSL'));
		}

		if(isset($this->error['warning'])) {
			$data['error_warning']=$this->error['warning'];
		} else {
			$data['error_warning']='';
		}

		$data['breadcrumbs']=array();
		$data['breadcrumbs'][]=array(
			'text'=>$this->language->get('text_home'),
			'href'=>$this->url->link('common/dashboard', 'user_token='.$this->session->data['user_token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text'=>$this->language->get('text_extension'),
			'href'=>$this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=module', 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text'=>$this->language->get('heading_title'),
			'href'=>$this->url->link('extension/module/gifts', 'user_token='.$this->session->data['user_token'], 'SSL')
		);

		$data['products']=array();
		foreach($this->model_extension_module_gifts->getGifts() as $product) {
			$data['products'][]=array(
				'product_id'=>$product['product_id'],
				'name'=>$product['name'],
				'activation_amount'=>$product['activation_amount'],
				'status'=>$product['status'],
				'show_link'=>$product['show_link']
			);
		}

		$data['action']=$this->url->link('extension/module/gifts', 'user_token='.$this->session->data['user_token'], 'SSL');
		$data['cancel']=$this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=module', 'SSL');
		$data['user_token']=$this->session->data['user_token'];
		
		$data['header']=$this->load->controller('common/header');
		$data['column_left']=$this->load->controller('common/column_left');
		$data['footer']=$this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/gifts', $data));
	}
	
    public function install() {
		$this->load->model('extension/module/gifts');
		$this->model_extension_module_gifts->install();
	}
	
	public function uninstall() {
		$this->load->model('extension/module/gifts');
		$this->model_extension_module_gifts->uninstall();
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/gifts')) {
			$this->error['warning']=$this->language->get('error_permission');
		}
		return !$this->error;
	}
}
?>