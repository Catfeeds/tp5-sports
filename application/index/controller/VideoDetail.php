<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class VideoDetail extends controller{

    public function index(){
        return $this->view->fetch();
    }

}
