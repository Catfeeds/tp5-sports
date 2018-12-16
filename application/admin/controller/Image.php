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
use think\Request;

class Image extends Controller
{
    use \app\admin\traits\controller\Controller;

    // public function ajaxuploadimage() {

    //     dump('11111');

    //     if ($this->request->isAjax() && $this->request->isGet()) {
    //         $request = Request::instance();
    //         $post = $request->param();

    //         dump($post);
    //         exit;
    //     }
    // }

    public function ajaxuploadimage() {
        if($this->request->isPost()){
            //接收参数
            $images = $this->request->file('file');
 
            //计算md5和sha1散列值，TODO::作用避免文件重复上传
            $md5 = $images->hash('md5');
            $sha1= $images->hash('sha1');
 
            //判断图片文件是否已经上传
            // $img = Db::name('HotNews')->where(['md5'=>$md5,'sha1'=>$sha1])->find();//我这里是将图片存入数据库，防止重复上传

            // 移动到框架应用根目录/public/uploads/picture/目录下
            $imgPath = 'public' . DS . 'uploads' . DS . 'picture';
            $info = $images->move(ROOT_PATH . $imgPath);
            $path = '/uploads/picture/'.date('Ymd',time()).'/'.$info->getFilename();
            $data = [
                'path' => $path ,
                'md5' => $md5 ,
                'sha1' => $sha1 ,
                'status' => 1 ,
                'create_time' => time() ,
            ];

            if($img_id=Db::name('Picture')->insertGetId($data)){
                return json(['status'=>1,'msg'=>'上传成功','data'=>['img_id'=>$img_id,'img_url'=>$this->request->root(true).'/'.$path]]);
            }else{
                return json(['status'=>0,'msg'=>'写入数据库失败']);
            }
            
        }else{
            return ['status'=>0,'msg'=>'非法请求!'];
        }
    }

}