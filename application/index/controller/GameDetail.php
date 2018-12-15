<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class GameDetail extends controller{

    public function index(){
        return $this->view->fetch();
    }

}
