<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class ChinaTeam extends controller{

    public function index(){
        return $this->view->fetch();
    }

}
