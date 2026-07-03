<?php
class ControllerAccountLogin extends Controller {
    public function index() {
        $email = isset($this->request->post['email']) ? $this->request->post['email'] : '';
        $password = isset($this->request->post['password']) ? $this->request->post['password'] : '';
    }
}
?>