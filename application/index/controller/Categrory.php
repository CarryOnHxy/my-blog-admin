<?php
namespace app\index\controller;

class Categrory extends Base
{
    protected $model;
    public function __construct()
    {
        $this->model = model('categrory');
    }
    public function getCategrory()
    {
        $categroryList = ['rows'=>$this->model->searchData([],"*",0)];
        echo json_encode($categroryList);
    }
}