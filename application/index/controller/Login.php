<?php 
namespace app\index\controller;
use think\Controller;
/**
 * 登录管理 登录 注销
 * @time 2018-08-15
 */
class Login extends Controller{



	public  function  index(){

		return view("./login");
	}

	//退出
	public function loginout(){
		session('userinfo',null);
		$this->redirect('login/index');
	}

	//验证码

	public function code(){
		return captcha('',array('length'=>4,'useCurve'=>false,'useNoise'=>false));
	}


	public  function  dologin(){

		//获取传递参数
		$param = input("post.");

		//验证验证码信息
		if(!captcha_check($param['verify'])){
 		//验证失败
			$this->error("验证码错误");
		};

		//验证员工表中是否有这个用户名的用户
		$info  =model("employee")->searchData(['user_code'=>$param['user']],"*",1);
		//数据信息为空  则用户不存在
		if(empty($info)){

			$this->error('用户信息不存在');

		}

		if($info['user_pwd']!=md5($param['password'])){

			$this->error('密码输入错误');
		}

		//查询角色信息
		$roleinfo = model('role')->searchData(['id'=>$info['role_id']],"role_name,role_rules",1);

		//查询该员工的信息


		$info['role_name'] = $roleinfo['role_name'];

		$info['role_rules'] = $roleinfo['role_rules'];

		

		//将角色姓名  角色权限存入到session的userinfo中

		
		session("userinfo",$info);


		

		$this->success('登陆成功','index/index');

	}
	public function loginBlog(){
		$userInfo  = input('post.');
		$userInfo['password'] = md5($userInfo['password']);
		$loginState = false;
		$result = model('user')->searchData($userInfo);
		if(count($result) > 0){
			$loginState = true;
		}
		$isLogin = ['loginState'=>$loginState];
		echo json_encode($isLogin);
	}
}	