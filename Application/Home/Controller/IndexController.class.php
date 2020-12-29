<?php
namespace Home\Controller;
use Home\Model\PjtTodosModel;
use Think\Controller;
class IndexController extends Controller {
    public function __construct()
    {
        header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); // 允许请求的类型
        header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
        header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with,x-token,Origin'); // 设置
    }

    public function todoList(){
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $convertData = [];
        $pageSize = $data['pageSize'];
        $pageNum = $data['pageNum'];
        foreach ($data as $key => $item) {
            $newKey = uncamelize($key);
            $convertData[$newKey] = $item;
        }
        $result['sql'] = $convertData;
        $map = [];
        if($convertData['assignee']){
            $map['assignee'] = $convertData['assignee'];
        }
        if($convertData['dealline']){
            $map['dealline'] = $convertData['dealline'];
        }
        if($convertData['priority']){
            $map['priority'] = $convertData['priority'];
        }
        if($convertData['pjt_id']){
            $map['pjt_id'] = $convertData['pjt_id'];
        }
        if($convertData['product_id']){
            $map['product_id'] = $convertData['product_id'];
        }
        if($convertData['todo_title']){
            $map['todo_title'] = ['like','%'.$convertData['todo_title'].'%'];
        }
        if($convertData['todo_status']){
            $map['todo_status'] = $convertData['todo_status'];
        }
        if($convertData['todo_type']){
            $map['todo_type'] = $convertData['todo_type'];
        }
        $model = new PjtTodosModel();
        $total = $model->where($map)->count();
        $start = ($pageNum - 1) * $pageSize;

        $list = $model->where($map)->order('todo_id desc')->limit($start,$pageSize)->select();
        $sql = $model->getLastSql();
        $convList = [];
        foreach ($list as $k => $v){
            $conv = convertUnderlineArr($v);
            $convList[$k] = $conv;
        }
        $result['code'] = 200;
        $result['msg'] = 'chengg';
        $result['rows'] = $convList;
        $result['total'] = intval($total);
        $result['sql'] = $sql;
        $result['t'] = $start;
        $result['g'] = $convertData;
        $this->ajaxReturn($result);
    }
    public function test1(){
        $a = 'test_rrr';
//        var_dump(convertUnderline3($a));die;
        $model = new PjtTodosModel();
        var_dump($model->getList());die;
        $result = array();
        $result['code'] = 0;
        $result['msg'] = '请选择书籍';
        $this->ajaxReturn($result);
    }
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function addTodo(){
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $convertData = [];
        foreach ($data as $key => $item) {
            $newKey = uncamelize($key);
            $convertData[$newKey] = $item;
        }
//        $convertData['create_time'] = time();
        $model = new PjtTodosModel();
//        $res = $model->fetchSql(true)->add($convertData);
        $res = $model->add($convertData);
        $result = [];
//        $result['sql'] = $model->getLastSql();
        $result['code'] = 200;
        $result['sqlError'] = $model->getDbError();
        $result['msg'] = $data;
        $result['res'] = $res;
        $this->ajaxReturn($result);
    }

    public function delTodos(){
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $model = new PjtTodosModel();
        $map = [];
        if(is_array($data['todoId'])){
            $map['todo_id'] = ['in',implode(',',$data['todoId'])];
        }else{
            $map['todo_id'] = $data['todoId'];
        }
        $res = $model->where($map)->delete();
        $result['code'] = 200;
        $result['msg'] = $res;
        $this->ajaxReturn($result);
    }
    public function updateTodos(){
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $convertData = [];
        foreach ($data as $key => $item) {
            $newKey = uncamelize($key);
            $convertData[$newKey] = $item;
        }
//        $convertData['update_time'] = time();
        $model = new PjtTodosModel();
        $res = $model->where(['todo_id'=>$data['todoId']])->save($convertData);
        $result['code'] = 200;
        $result['msg'] = $res;
        $this->ajaxReturn($result);
    }
    public function getTodos(){
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $model = new PjtTodosModel();
//        $list = $model->where(['todo_id'=>$data['todoId']])->fetchSql(true)->select();
        $list = $model->where(['todo_id'=>$data['todoId']])->select();
        $result['code'] = 200;
        $result['msg'] = $data;
        $result['data'] = convertUnderlineArr($list[0]);
        $this->ajaxReturn($result);
    }
}