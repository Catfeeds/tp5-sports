<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class About extends controller{

    public function index(){
        return $this->view->fetch();
    }

}
