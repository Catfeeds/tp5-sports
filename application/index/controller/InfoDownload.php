<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class InfoDownload extends controller{

    public function index(){
        return $this->view->fetch();
    }

}
