<?php
namespace app\index\controller;

class Music extends Base
{
    protected $model;
    public function __construct()
    {
        $this->model = model('music');
    }
    public function getMusic()
    {
        $musicId = input('id');
        if ($musicId) {
            $musicList = ['music' => $this->model->searchData(['id' => $musicId], "*", 1)];
        } else {
            $pageSize = input('pageSize');
            $pageNum = input('pageNum');
            /* 分页查询 */
            if ($pageSize) {
                $searchRes = $this->model->searchByPage([], "*", $pageNum, $pageSize);
                $countRes = count($this->model->searchData([], "", 0));
                $musicList = ['musicList' => $searchRes, 'totalPage' => ceil($countRes / $pageSize)];
            } else {
                $musicList = ['musicList' => $this->model->searchData([], "*", 0)];
            }
        }
        echo json_encode($musicList);
    }
    public function setMusic()
    {
        $musicInfo = input('post.');
        $result = $this->model->addData($musicInfo);
        echo json_encode(['insertedNum' => $result]);
    }
    public function deleteMusic()
    {
        $deleteRes = $this->model->del(['id' => input('id')]);
        echo json_encode(['deleteRes' => $deleteRes]);
    }
    public function updateMusic()
    {
        $params = input('post.');
        $updateRes = $this->model->editData(['id' => $params['id']], $params['condition']);
        echo json_encode(['updateRes' => $updateRes]);
    }
}

