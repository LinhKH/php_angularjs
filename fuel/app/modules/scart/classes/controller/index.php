<?php
namespace Scart;

use \Fuel\Core\Controller;
use Controller_Base_Core;
use Fuel\Core\Theme;
use Fuel\Core\Response;

class Controller_Index extends Controller_Base_Core {
  public function before()
    {
        parent::before();
    }

    public function after($response)
    {
        if (empty($response) or ! $response instanceof Response) {
            $response = Response::forge(Theme::instance()->render());
        }
        return parent::after($response);
    }

    public function action_index()
    {
        
    }
}