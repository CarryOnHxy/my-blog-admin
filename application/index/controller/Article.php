<?php
namespace app\index\controller;
use think\Db;

class Article extends Base
{
    protected $model;
    public function __construct()
    {
        $this->model = model('article');
    }
    public function getArticle()
    {
        $articleId = input('id');
        if ($articleId) {
            $articleList = ['article' => $this->model->searchData(['id' => $articleId], "*", 1)];
        } else {
            $pageSize = input('pageSize');
            $pageNum = input('pageNum');
            /* 分页查询 */
            if($pageSize){
                $searchRes = $this->model->searchByPage([], "*", $pageNum,$pageSize);
                $countRes = count($this->model->searchData([], "", 0));
                $articleList = ['articleList' => $searchRes,'totalPage'=>ceil($countRes/$pageSize)];
            }else{
                $articleList = ['articleList' => $this->model->searchData([], "*", 0)];
            }
        }
        echo json_encode($articleList);
    }
    public function getArticleInCate()
    {
        $categroryId = input('categroryId');
        $articleList = ['articleList' => $this->model->searchData(['categrory_id' => $categroryId], '*', 0)];
        echo json_encode($articleList);
    }
    public function insertArticle()
    {
        $articleData = input('post.');
        $this->model->addData($articleData);
    }
    public function selectArticleByKey()
    {
        //SELECT * FROM tp_article WHERE detail LIKE '%的%'
        $queryKey = input('queryKey');
        $articleList =$this->model->field('*')->where('detail','like',"%$queryKey%")->select();
        echo json_encode(['articleList'=>$articleList]);
    }
    public function deleteArticle(){
        $id = input('id');
        $deleteRes = $this->model->del(['id' => $id]);
        echo json_encode(['deleteRes'=>$deleteRes]);
    }
    public function updateArticle(){
        $params = input('post.');
            $updateRes = $this->model->editData(['id'=>$params['id']],$params['condition']);
        echo json_encode(['updateRes'=>$updateRes]);

    }
} 