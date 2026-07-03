<?php
class ControllerExtensionDashboardNotes extends Controller {
	private $error = array();

	private $path_module = 'extension/dashboard/notes';
	private $path_extension ='marketplace/extension&type=dashboard';
	private $module_name ='notes';
	private $my_model ='model_extension_dashboard_notes';
	private $token = 'user_token';

	private $colors = array(
		'ffffff' => '000000',
		'ed5565' => '000000',
		'23c6c8' => '000000',
		'f8ac59' => '000000',
	);

	private $prefix_module = 'dashboard_notes';

	public function getlist() {
		$this->load->language($this->path_module);
		$this->document->setTitle($this->language->get('heading_title_list'));

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
		$this->breadcrumbs($data);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_list'),
			'href' => $this->makeUrl($this->path_module . '/getlist')
		);
		$this->load->model($this->path_module);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		$filter_data = array(
			'order'          => $order,
			'sort'           => $sort,
			'start'          => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'          => $this->config->get('config_limit_admin')
		);
		$data['colors'] = $this->colors;
		$notes = $this->{$this->my_model}->getNotes($filter_data);
		$total = $this->{$this->my_model}->getTotalNotes($filter_data);
		$data['notes'] = array();
		if (isset($this->request->post['selected'])) {
			$data['selected'] = $this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$users = $this->{$this->my_model}->getUsers();
		foreach ($notes as $note) {
			$data['notes'][] = array(
				'notes_id' => $note['notes_id'],
				'title' => $note['title'],
				'notes' => $note['notes'],
				'date_added' => $note['date_added'],
				'color' => $note['color'],
				'from' => isset($users[$note['from_user_id']])?$users[$note['from_user_id']]:'system',
				'to' => isset($users[$note['to_user_id']])?$users[$note['to_user_id']]:'system',
				'edit' => $this->makeUrl($this->path_module . '/getForm', 'notes_id=' . $note['notes_id']),
			);
		}
		$data['delete'] = $this->makeUrl($this->path_module . '/deleteNotes');
		$data['add']   = $this->makeUrl($this->path_module . '/getForm');
		$url = '';
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->makeUrl($this->path_module . '/getlist', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));
		$this->nav($this->path_module . '/getlist', $data);
		$this->footer('/_list', $data);
	}

	public function index() {
		$this->load->language($this->path_module);

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->prefix_module, $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->makeUrl($this->path_module));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->request->post[$this->prefix_module . '_status'])) {
			$data[$this->prefix_module . '_status'] = $this->request->post[$this->prefix_module . '_status'];
		} else {
			$data[$this->prefix_module . '_status'] = $this->config->get($this->prefix_module . '_status');
		}

		if (isset($this->request->post[$this->prefix_module . '_sort_order'])) {
			$data[$this->prefix_module . '_sort_order'] = $this->request->post[$this->prefix_module . '_sort_order'];
		} else {
			$data[$this->prefix_module . '_sort_order'] = $this->config->get($this->prefix_module . '_sort_order');
		}

		if (isset($this->request->post[$this->prefix_module . '_width'])) {
			$data[$this->prefix_module . '_width'] = $this->request->post[$this->prefix_module . '_width'];
		} else {
			$data[$this->prefix_module . '_width'] = $this->config->get($this->prefix_module . '_width');
		}

		$data['columns'] = array();

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		$this->breadcrumbs($data);

		$data['action'] = $this->makeUrl($this->path_module);
		$data['cancel'] = $this->makeUrl($this->path_extension);

		$this->nav($this->path_module, $data);

		$this->footer('_form', $data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', $this->path_module)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	public function save() {
		$this->load->language($this->path_module);
		$this->load->model($this->path_module);
		if (isset($this->request->get['notes_id'])) {
			$notes_id = $this->request->get['notes_id'];
			$this->{$this->my_model}->editNote($this->request->get['notes_id'],$this->request->post);
		} else {
			$notes_id = $this->{$this->my_model}->addNote($this->request->post);
		}
		$users = $this->{$this->my_model}->getUsers();

		$note = $this->{$this->my_model}->getNote($notes_id);
		$notes_info = array(
			'notes_id' => $note['notes_id'],
			'title' => $note['title'],
			'notes' => $note['notes'],
			'date_added' => $note['date_added'],
			'color' => $note['color'],
			'from' => isset($users[$note['from_user_id']])?$users[$note['from_user_id']]:'system',
			'to' => isset($users[$note['to_user_id']])?$users[$note['to_user_id']]:'system',
			'edit' => $this->makeUrl($this->path_module . '/getForm', 'notes_id=' . $note['notes_id']),
			'delete' => $this->makeUrl($this->path_module . '/deleteNotes'),
			);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($notes_info));
	}

	public function getDataForm() {
		if (isset($this->request->get['notes_id'])) {
			$notes_info = $this->{$this->my_model}->getNote($this->request->get['notes_id']);
			$data['modal_title'] = $this->language->get('text_edit_note');
			$data['action'] = $this->makeUrlScript($this->path_module . '/save', 'notes_id=' . $this->request->get['notes_id']);
		} else {
			$data['modal_title'] = $this->language->get('text_add_note');
			$data['action'] = $this->makeUrlScript($this->path_module . '/save');
		}

		$fields = array(
			'title',
			'notes',
			'color',
		);
		foreach ($fields as $field) {
			$data[$field] = isset($notes_info[$field])?$notes_info[$field]:'';
		}
		$data['users'] = $this->{$this->my_model}->getUsers();
		$data['colors'] = $this->colors;
		return $data;
	}

	public function getForm() {
		if (isset($this->request->get['type'])) {
			if ($this->request->get['type'] == 'data') {
				$this->edit_form();
			} 
			if ($this->request->get['type'] == 'form') {
				$this->get_form();
			}
		}
	}

	public function edit_form() {
		$this->load->language($this->path_module);
		$this->load->model($this->path_module);
		$json = $this->getDataForm();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function get_form() {
		$this->load->language($this->path_module);
		$this->load->model($this->path_module);
		$data = $this->getDataForm();
		$this->response->setOutput($this->load->view($this->path_module . '/_add', $data));
	}

	public function dashboard() {
		$this->load->language($this->path_module);
		$this->load->model($this->path_module);
		$filter_data = array(
			'order'          => 'date_added',
			'sort'           => 'DESC',
			'start'          => 0,
			'limit'          => 10
		);
		
		$users = $this->{$this->my_model}->getUsers();
		$notes = $this->{$this->my_model}->getNotes($filter_data);
		$data['notes'] = array();
		foreach ($notes as $note) {
			$data['notes'][] = array( 
				'notes_id' => $note['notes_id'],
				'title' => $note['title'],
				'notes' => $note['notes'],
				'date_added' => $note['date_added'],
				'color' => $note['color'],
				'from' => isset($users[$note['from_user_id']])?$users[$note['from_user_id']]:'system',
				'to' => isset($users[$note['to_user_id']])?$users[$note['to_user_id']]:'system',
				'edit' => $this->makeUrl($this->path_module . '/getForm', 'notes_id=' . $note['notes_id']),
			);
		}
		$data['token'] = $this->session->data[$this->token];
		$data['add']   = $this->makeUrl($this->path_module . '/getForm');
		$data['view_all'] = $this->makeUrl($this->path_module . '/getlist');
		$data['delete'] = $this->makeUrl($this->path_module . '/deleteNotes');
		return $this->load->view($this->path_module . '/_dashboard', $data);
	}

	public function deleteNotes() {
		$this->load->model($this->path_module);
		$ok = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && (isset($this->request->post['notes_id']) || isset($this->request->post['selected']))) {
			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $notes_id) {
					$this->{$this->my_model}->deleteNotes($notes_id);
				}
				$this->response->redirect($this->makeUrl($this->path_module . '/getlist'));
			} else {
				$this->{$this->my_model}->deleteNotes($this->request->post['notes_id']);
				$ok = 'ok';
				$this->response->setOutput($ok);
			}
		}
	}

	public function install(){
		$this->load->model($this->path_module);
		$this->{$this->my_model}->install();
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting($this->prefix_module, array(
			$this->prefix_module . '_sort_order' => '-1',
			$this->prefix_module . '_width' => 12,
			$this->prefix_module . '_status' => 1,
		));
		$events = $this->getEvents();
		$this->load->model('setting/event');
		foreach ($events as $code=>$value) {
			$this->model_setting_event->deleteEventByCode($code);
			$this->model_setting_event->addEvent($code, $value['trigger'], $value['action'], 1,9999);
		}
	}

	public function uninstall(){
		$this->load->model($this->path_module);
		$this->{$this->my_model}->uninstall();
		$events = $this->getEvents();
		$this->load->model('setting/event');
		foreach ($events as $code=>$value) {
			$this->model_setting_event->deleteEventByCode($code);
		}
	}

	private function getEvents() {
		$events = array(
			'fa_Notes_top' => array(
				'trigger' => 'admin/controller/common/header/after',
				'action'  => $this->path_module . '/top',
			),
			'fa_Notes' => array(
				'trigger' => 'admin/controller/common/header/before',
				'action'  => $this->path_module . '/style_script',
			),
		);
		return $events;
	}

	public function style_script(&$route, &$args) {
		$this->document->addStyle('view/stylesheet/notes/notes.css');
		$this->document->addScript('view/javascript/notes/notes.js');
	}

	public function top(&$route, &$args, &$output) {
		if (isset($this->session->data[$this->token])) {
			$this->load->language($this->path_module);
			$this->load->model($this->path_module);
			$limit = 10;
			$filter_data = array(
				'order'          => 'date_added',
				'sort'           => 'DESC',
				'start'          => 0,
				'limit'          => $limit
			);
			$notes = $this->{$this->my_model}->getNotes($filter_data);
			$notes = $this->{$this->my_model}->getNotes($filter_data);
			$data['notes'] = array();
			foreach ($notes as $note) {
				$data['notes'][] = array( 
					'notes_id' => $note['notes_id'],
					'title' => $note['title'],
					'notes' => $note['notes'],
					'date_added' => $note['date_added'],
					'color' => $note['color'],
					'from' => isset($users[$note['from_user_id']])?$users[$note['from_user_id']]:'system',
					'to' => isset($users[$note['to_user_id']])?$users[$note['to_user_id']]:'system',
					'edit' => $this->makeUrl($this->path_module . '/getForm', 'notes_id=' . $note['notes_id']),
				);
			}

			$data['total'] = $total= $this->{$this->my_model}->getTotalNotes($filter_data);
			$data['results'] = sprintf('Показано %s из %s', count($notes), $total);
			$data['colors'] = $this->colors;
			$data['delete'] = $this->makeUrl($this->path_module . '/deleteNotes');
			$data['token'] = $this->session->data[$this->token];
			$data['view_all'] = $this->makeUrl($this->path_module . '/getlist');
			$data['add']   = $this->makeUrl($this->path_module . '/getForm');

			$top = $this->load->view($this->path_module . '/_top',$data);
			$output = str_replace('<ul class="nav navbar-nav navbar-right">', $top . '<ul class="nav navbar-nav navbar-right">', $output);
		}
	}

	private function makeUrl($route, $arg=''){
		if ($arg) {
			$arg = '&' . ltrim($arg,'&');
		}
		return $this->url->link($route, $this->token . '=' . $this->session->data[$this->token] . $arg, true);
	}

	private function makeUrlScript($route, $arg=''){
		return str_replace('&amp;','&',$this->makeUrl($route, $arg));
	}

	private function footer($template, $data) {
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->path_module . $template, $data));
	}

	private function breadcrumbs(&$data) {
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->makeUrl('common/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_modules'),
			'href' => $this->makeUrl($this->path_extension)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->makeUrl($this->path_module)
		);
	}

	private function nav($route,&$data) {
		$data['navs'] = array();

		if ($route == $this->path_module) $active = 'active'; else $active = '';
		if ($this->user->hasPermission('modify', $this->path_module)) {
			$data['navs'][] = array(
				'href' => $this->makeUrl($this->path_module),
				'text' => $this->language->get('text_nav_setting')
				,'active' => $active
			);
		}


		if ($route == $this->path_module . '/getlist') $active = 'active'; else $active = '';
		$data['navs'][] = array(
			'href' => $this->makeUrl($this->path_module . '/getlist'),
			'text' => $this->language->get('text_nav_list')
			,'active' => $active
		);
	}
}