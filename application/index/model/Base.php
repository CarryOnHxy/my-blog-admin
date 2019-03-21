<?php 
namespace app\index\model;

use think\Model;

use think\Db;

class Base extends Model{
	//数据查询
	public function searchData($where = array(),$fields="*",$issignle = 0){
		
		if($issignle){
			$data = Db::name($this->name)->field($fields)->where($where)->find();
		}else{
			$data = Db::name($this->name)->field($fields)->where($where)->select(); 
		}
		
		return $data;
	}


	//数据查询-带分页
	public function searchPage($where=array(),$fields="*",$num = 10){

		return Db::name($this->name)->field($fields)->where($where)->order("id DESC")->paginate($num);
	}

	public function searchByPage($where=array(),$fields="*",$pageNum=0,$pageSize = 10){
		return Db::name($this->name)->field($fields)->where($where)->page($pageNum,$pageSize)->select();
	}

	//数据添加操作

	public function addData($data){

		return Db::name($this->name)->insert($data);
	}


	//数据修改操作

	public function editData($where,$data){
		
		return Db::name($this->name)->where($where)->update($data);
	}


	//数据删除操作
	public function del($where){
		return Db::name($this->name)->where($where)->delete();
	}
}

 ?>