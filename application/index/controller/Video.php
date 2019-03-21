<?php
namespace app\index\controller;

class Video extends Base
{
    protected $model;
    public function __construct()
    {
        $this->model = model('video');
    }
    public function getVideo()
    {
        $videoId = input('id');
        if ($videoId) {
            $videoList = ['video' => $this->model->searchData(['id' => $videoId], "*", 1)];
        } else {
            $pageSize = input('pageSize');
            $pageNum = input('pageNum');
            /* 分页查询 */
            if ($pageSize) {
                $searchRes = $this->model->searchByPage([], "*", $pageNum, $pageSize);
                $countRes = count($this->model->searchData([], "", 0));
                $videoList = ['videoList' => $searchRes, 'totalPage' => ceil($countRes / $pageSize)];
            } else {
                $videoList = ['videoList' => $this->model->searchData([], "*", 0)];
            }
        }
        echo json_encode($videoList);
    }
    public function setVideo()
    {
        $videoInfo = input('post.');
        $result = $this->model->addData($videoInfo);
        echo json_encode(['insertedNum' => $result]);
    }
    public function deleteVideo()
    {
        $deleteRes = $this->model->del(['id' => input('id')]);
        echo json_encode(['deleteRes' => $deleteRes]);
    }
    public function updateVideo()
    {
        $params = input('post.');
        $updateRes = $this->model->editData(['id' => $params['id']], $params['condition']);
        echo json_encode(['updateRes' => $updateRes]);
    }
}

