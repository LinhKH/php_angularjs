<?php

use Fuel\Core\Controller_Hybrid;
use Fuel\Core\Theme;
use Fuel\Core\Inflector;
use Fuel\Core\Request;
use Fuel\Core\Lang;
use Fuel\Core\View;
use Fuel\Core\Asset;
use Fuel\Core\Response;

class Controller_Base_Core extends Controller_Hybrid
{

    public $template = 'layout';
    // protected $format = 'json';
    protected $module;
    protected $controller;
    protected $action;
    protected $is_login = false;
    protected $user = [];

    public function before()
    {
        $this->init();
        parent::before();
        $this->render_template();
        // $this->set_title();
    }

    public function after($response)
    {
        $response = parent::after($response);
        return $response;
    }

    public function router($resource, $arguments)
    {
        parent::router($resource, $arguments);
    }

    public function init()
    {
        
        Lang::load('app', true);
        $this->is_login = Model_Base_User::is_login();
        $this->user = Model_Base_User::getUserInfo();
        $this->module = strtolower(Request::active()->module);
        $this->controller = strtolower(substr(Inflector::denamespace(Request::active()->controller), 11));
        $this->action = Request::active()->action;  
        if (!empty($this->module) && $this->module != 'bookstore') {
            if (!$this->is_login) {
                Response::redirect('/login');
            }
            // $this->template = $this->module . '::' . $this->template;
        }
        
        View::set_global('module', $this->module);
        View::set_global('controller', $this->controller);
        View::set_global('action', $this->action);
        View::set_global('user', $this->user);
        
    }

    public function render_template()
    {
        switch ($this->module) {                        
            case 'bookstore':
                $this->theme = Theme::instance();
                $this->theme->active('default');
                $this->theme->set_template('layout');
                $this->theme->set_partial('head_tag', 'partials/head_tag');
                $this->theme->set_partial('header_top', 'partials/header_top');
                $this->theme->set_partial('menu', 'partials/menu');
                $this->theme->set_partial('footer', 'partials/footer');
                $this->theme->set_partial('header_bottom', 'partials/header_bottom');
                $this->theme->set_partial('modal', 'partials/modal');
                $this->theme->set_partial('scripts', 'partials/scripts');
                View::set_global('theme', $this->theme);
                Asset::add_path('themes/default/js/', 'js');
                break;
            default:
                break;
        }
    }

    public function set_title()
    {
        if (isset($this->theme) && is_object($this->theme)) {
            $this->theme->get_template()->set('title', Lang::get('app.title'));
        } elseif (is_object($this->template)) {
            $this->template->title = Lang::get('app.title');
        }
    }

}
