<?php

use Fuel\Core\View;
use Fuel\Core\Response;
use Auth\Auth;

class Controller_Auth extends Controller_Base_Core
{

    public function before()
    {
        parent::before();
    }

    public function after($response)
    {
        $response = parent::after($response);
        return $response;
    }

    public function action_login()
    {
        if ($this->is_login) {
            Response::redirect('/management');
        }
        $this->template->content = View::forge($this->controller . '/' . $this->action);
    }

    public function action_logout()
    {
        Auth::dont_remember_me();
        Auth::logout();
        Response::redirect('/auth');
    }

    public function action_forgot()
    {
        if ($this->is_login) {
            Response::redirect('/management');
        }
        $this->template->content = View::forge($this->controller . '/' . $this->action);
    }

    public function action_new_password($forgot_token = null)
    {
        if ($this->is_login) {
            Response::redirect('/management');
        }
        if (empty($forgot_token)) {
            Response::redirect('/auth/forgot');
        }
        if (!Model_Base_Core::validField('Model_MEmployee', 'forgot_token', $forgot_token)) {
            Response::redirect('/auth/forgot');
        }
        $this->template->content = View::forge($this->controller . '/' . $this->action, ['forgot_token' => $forgot_token]);
    }

    public function action_confirm($type = 1)
    {
        if ($this->is_login) {
            Response::redirect('/management');
        }
        $data['message'] = $type == 1 ? 'パスワードリセットのリンクを指定のメールに送付されました。' : 'パスワードが更新されました。';
        $this->template->content = View::forge($this->controller . '/' . $this->action, $data);
    }

}
