<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 用户控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\View;
use think\Request;
use think\Validate;

class AdminUser extends Controller
{
    use \app\admin\traits\controller\Controller;

//    protected static $blacklist = ['delete', 'clear', 'deleteforever', 'recyclebin', 'recycle'];

    protected function filter(&$map)
    {
        //不查询管理员
        $map['id'] = ["gt", 1];

        if ($this->request->param('realname')) {
            $map['realname'] = ["like", "%" . $this->request->param('realname') . "%"];
        }
        if ($this->request->param('account')) {
            $map['realname'] = ["like", "%" . $this->request->param('account') . "%"];
        }
        if ($this->request->param('email')) {
            $map['realname'] = ["like", "%" . $this->request->param('email') . "%"];
        }
        if ($this->request->param('mobile')) {
            $map['realname'] = ["like", "%" . $this->request->param('mobile') . "%"];
        }
    }

    /**
     * 修改密码
     */
    public function password()
    {
        $id = $this->request->param('id/d');
        if ($this->request->isPost()) {
            //禁止修改管理员的密码，管理员id为1
            if ($id < 2) {
                return ajax_return_adv_error("缺少必要参数");
            }

            $password = $this->request->post('password');
            if (!$password) {
                return ajax_return_adv_error("密码不能为空");
            }
            if (false === Loader::model('AdminUser')->updatePassword($id, $password)) {
                return ajax_return_adv_error("密码修改失败");
            }
            return ajax_return_adv("密码已修改为{$password}", '');
        } else {
            // 禁止修改管理员的密码，管理员 id 为 1
            if ($id < 2) {
                throw new Exception("缺少必要参数");
            }

            return $this->view->fetch();
        }
    }

    public function index() {
        // $user = Session::has(Config::get('rbac.user_auth_key'));
        $uid = Session::get('UID');

        $user = Db::name("AdminUser")->where("id", $uid)->find();

        $this->view->assign('userInfo', $user);
        
        return $this->view->fetch();
    }


    public function addNew() {
        if(request()->isPost()){
            $request = Request::instance();
            $post = $request->param();

            $res = Db::name("AdminUser")->where("account", $post['account'])->find();

            if ($res) {
                return show(0,  "已经存在该账号");
            }

            if($post['password'] != $post['repassword']) {
                return show(0,  "两次输入的密码不一样");
            }

            $validate = new Validate([
                'account' => 'require',
                'mobile' => 'require|regex:1[3-8]{1}[0-9]{9}',
            ]);

            if (!$validate->check($post)) {
                return show(0,  $validate->getError());
            }


            $data = [
                'account' => $post['account'],
                'password' => md5($post['password']),
                'realname' => $post['realname'],
                'mobile' => $post['mobile'],
                'last_login_ip' => $request->ip(),
                'last_login_time' => time(),
                'create_time' => time(),
                'update_time' => time(),
                'email' => $post['email'],
                'status' => '1'
            ];

            $register = Db::name("AdminUser")->insert($data);

            if ($register){
                return show(1,'新增成功');
            }else{
                return show(0,'新增失败');
            }

        } else {
            return $this->view->fetch();
        }
    }
    /**
     * 禁用限制
     */
    protected function beforeForbid()
    {
        // 禁止禁用 Admin 模块,权限设置节点
        $this->filterId(1, '该用户不能被禁用', '=');
    }
}