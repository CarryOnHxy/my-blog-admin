<?php
namespace app\index\controller;

class Comment {
    protected $model;
    public function __construct()
    {
        $this->model = model('comment');
    }
    public function getCommentList(){
        // echo '<pre>';
        $commentList = $this->model->searchData(['article_id'=>input('id')]);
        foreach($commentList as $key =>$comment){
            $replyId = $comment['reply_id'];
            if($replyId){
                $searchCondition = 'content as otherContent,nickName as otherNickname,timestamp as otherTimestamp';
                $otherComment = $this->model->searchData(['id'=>$replyId],$searchCondition,1);
                $commentList[$key] = array_merge($commentList[$key],$otherComment);
            }
        }
        echo json_encode(['commentList'=>$commentList]);
        
    }       
    /* 添加新评论并且发被回复者邮箱 */
    public function addComment(){
        $commentInfo = input('post.');
        $replyId = $commentInfo['reply_id'];
        $res = ['commentState'=> $this->model->addData($commentInfo)];
        if($replyId){
            $otherComment = $this->model->searchData(['id'=>$commentInfo['reply_id']],'content,email,nickName,timestamp',1);
          $emailText = "<p>".$otherComment['nickName']."说</p><p>".$otherComment['content']."</p><hr />";
          $emailText .= "<p>".$commentInfo['nickName']."回复</p><p>".$commentInfo['content']."</p>";
          sendEmail($commentInfo['email'],$emailText);  
        }
        echo json_encode($res);
    }
    /* 获取为博主未阅读的评论 */
    public function getCommentfirstlyNum(){
        $commentFirstlyList = $this->model->searchData(['reply_id' => 0,'is_read' => 2]);
        return json_encode(['commentNoRead'=>count($commentFirstlyList)]);
    }
    /* 获取无回复他人的评论 ————第一条评论*/
    public function getCommentfirstly(){
        $commentFirstlyList = $this->model->searchData(['reply_id' => 0,'is_read' => 2]);
        return json_encode(['commentNoRead'=>$commentFirstlyList]);
    }
    /* 修改评论已阅状态 */
    public function readComment(){
        $updateRes = $this->model->editData(['is_read' =>2,'id' => input('id')],['is_read' =>1]);
        echo json_encode(['updateResult' => $updateRes]);
    }
}