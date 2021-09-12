<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Skpower\Bdynotice\BdyNotice;

class MemberController extends BaseController
{
    //选择员工页面列表
    public function index(){

        //发送员工数量
        $confArr = config('bdynotice.work');
        $noticeCache = $confArr['corp_id'] . ':' . 'notice';
        if (Cache::has($noticeCache)) {
            $noticeList = Cache::get($noticeCache);
            $count = count($noticeList);

        }else{
            $count = 0;
        }

        return view('skpower::member.index',['title' => '员工列表','sendCount' => $count]);
    }

    //同步接口
    public function syncMember(){
        try {
            $data = $this->getdepartUser();

            return $this->returnJosn('同步成功',$data);
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(),[],1);
        }

    }

    //加入发送列表
    public function addList(Request $request){
        try {
            $id = (int) $request->post('id');
            if (!isset($id)) {
                throw new \Exception('请指定一个员工');
            }
            $confArr = config('bdynotice.work');

            $cacheKey = $confArr['corp_id'] . ':' . 'department';

            if (!Cache::has($cacheKey)) {
                throw new \Exception('员工数据不存在,请先去同步');
            }

            $resUserArrUnique = Cache::get($cacheKey);

            //通过这个key值,删除全量缓存里的员工,加入到新的发送缓存中去
            if (empty($resUserArrUnique[$id])) {
                throw new \Exception('当前员工不存在,请同步更新员工列表');
            }


            //发送列表数组缓存key
            $noticeCache = $confArr['corp_id'] . ':' . 'notice';
            if (Cache::has($noticeCache)) {
                $noticeList = Cache::get($noticeCache);
                if (count($noticeList) >= 10) {
                    throw new \Exception('只能添加10个员工去测试消息发送');
                }
            }else{
                $noticeList = [];
            }

            //加入到发送列表数组中
            array_push($noticeList,$resUserArrUnique[$id]);
            //更新回发送列表缓存中去
            Cache::put($noticeCache, $noticeList, 86400*7);

            //把全量缓存里的这个员工删除掉
            unset($resUserArrUnique[$id]);
            $resUserArrUnique = array_values($resUserArrUnique);
            //更新回全量缓存中去
            Cache::put($cacheKey, $resUserArrUnique, 86400*7);

            return $this->returnJosn('加入成功');
        } catch (\Exception $e){

            return $this->returnJosn($e->getMessage(),[],1);
        }
    }

    //员工列表接口
    public function indexList(Request $request){
        try {
            $app = app(BdyNotice::class)->setEasyconf();

            $confArr = config('bdynotice.work');

            $cacheKey = $confArr['corp_id'] . ':' . 'department';

            if (Cache::has($cacheKey)) {
                $resUserArrUnique = Cache::get($cacheKey);
                return $this->returnJosn('',$resUserArrUnique);
            }

            $resUserArrUnique = $this->getdepartUser();

            return $this->returnJosn('',$resUserArrUnique);
        } catch (\Exception $e){
            if ($e->getCode() == 403) {
                $code = 403;
            }else{
                $code = 1;
            }
            return $this->returnJosn($e->getMessage(),[],$code);
        }
    }

    //返回企业员工
    public function list(){
        try {
            $app = app(BdyNotice::class)->setEasyconf();

            $confArr = config('bdynotice.work');

            $cacheKey = $confArr['corp_id'] . ':' . 'notice';

            $resUserArrUnique = [];

            if (Cache::has($cacheKey)) {
                $resUserArrUnique = Cache::get($cacheKey);
            }

            //$resUserArrUnique = $this->getdepartUser();

            return $this->returnJosn('',$resUserArrUnique);
        } catch (\Exception $e){
            if ($e->getCode() == 403) {
                $code = 403;
            }else{
                $code = 1;
            }
            return $this->returnJosn($e->getMessage(),[],$code);
        }

    }

    //获取企业下的所有部门
    public function getDepartment($app){
        $list = $app->department->list();
        if ($list['errcode'] != 0) {
            throw new \Exception($list['errmsg']);
        }


        //获取部门id
        $departIds = array_column($list['department'],'id');
        return $departIds;

    }

    //同步-获取部门下的所有员工
    public function getdepartUser(){

        $app = app(BdyNotice::class)->setEasyconf();

        $confArr = config('bdynotice.work');

        $cacheKey = $confArr['corp_id'] . ':' . 'department';

        //先获取部门列表
        $departList = $this->getDepartment($app);
        if (empty($departList)) {
            throw new \Exception('请检查自建应用的可见范围,必须选择一个部门,用于同步,建议选择根部门');
        }

        $userArr = [];
        foreach($departList as $departId){
            //按部门获取员工
            $users = $app->user->getDepartmentUsers($departId,true);
            if ($users['errcode'] != 0) {
                throw new \Exception($users['errmsg']);
            }
            array_push($userArr,$users['userlist']);
        }
        $userArr = array_filter($userArr);
        if (empty($userArr)) {
            throw new \Exception('当前没有同步到员工,请检查自建应用可见范围');
        }

        //处理员工数组
        $resUserArr = [];
        foreach ($userArr as $k=>$v) {
            foreach ($v as $k2=>$v2){
                unset($v2['department']);
                $resUserArr[] = $v2;
            }
        }

        //去重
        $resUserArrUnique = $this->array_unique_fb($resUserArr);

        //加入缓存

        Cache::put($cacheKey, $resUserArrUnique, 86400*7);

        //清空发送列表的缓存
        $noticeCacheKey = $confArr['corp_id'] . ':' . 'notice';
        Cache::forget($noticeCacheKey);

        return $resUserArrUnique;
    }

    function array_unique_fb($array2D) {

        $keys = array_keys(end($array2D));
        $key1 = 'user_id';
        $key2 = $keys[1];

        foreach ($array2D as $v) {


            $v = join(",", $v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串

            $temp[] = $v;

        }

        $temp = array_unique($temp);//去掉重复的字符串,也就是重复的一维数组

        $res = [];
        foreach ($temp as $k => $v) {
            $arr = explode(",", $v);//再将拆开的数组重新组装
            $res[$k][$key1] = $arr[0];
            $res[$k][$key2] = $arr[1];

        }

        return $res;

    }

}
