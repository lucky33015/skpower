<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;

class ConfigController extends BaseController
{
    public function index(){
        $config = config('bdynotice.work');
        if (!empty($config['corp_id']) && !empty($config['agent_id']) && !empty($config['secret'])) {
            $status = true;
        }else{
            $status = false;
        }

        return view('skpower::conf.index',['title' => '配置自建应用参数','config' => $config, 'status' => $status]);
    }

    //接口写入配置文件
    public function writeConfig(Request $request){
        try {
            $corp_id = $request->post('corp_id');
            $agent_id = (int)$request->post('agent_id');
            $secret = $request->post('secret');

            if (empty($corp_id) || empty($agent_id) || empty($secret)) {
                throw new \Exception('缺少必要的参数,请检查');
            }

            $config = [
                'corp_id' => $corp_id,
                'agent_id' => $agent_id,
                'secret' => $secret,
            ];

            $this->setConfig('work',$config);

            return $this->returnJosn('恭喜您!配置成功~');
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(),[],1);
        }

    }


    //获取配置文件配置
    public function getConfig(){
        $config = [
            //初始化企微配置 --agent_id 参数一定要是int类型
            'work' => [
                'corp_id' => '',
                'agent_id' => 0,
                'secret' => '',
            ],

            //调试模式
            'sandbox' => "env('TEST_NOTICE',true)",
        ];

        return $config;
    }

    //动态配置config配置文件
    public function setConfig($name,$value){
        $config = $this->getConfig();
        $config[$name] = $value;
        return file_put_contents(config_path('bdynotice.php'), "<?php \n return ".var_export($config, true)  . ";");
    }

    //切换企业
    public function update(){
        $config = config('bdynotice.work');
        if (!empty($config['corp_id']) && !empty($config['agent_id']) && !empty($config['secret'])) {
            $status = true;
        }else{
            $status = false;
        }

        return view('skpower::conf.update',['title' => '切换企业','config' => $config, 'status' => $status]);
    }

    //切换企业接口
    public function updateAction(Request $request){
        try {
            $corp_id = $request->post('corp_id');
            $agent_id = (int)$request->post('agent_id');
            $secret = $request->post('secret');

            if (empty($corp_id) || empty($agent_id) || empty($secret)) {
                throw new \Exception('缺少必要的参数,请检查');
            }

            $config = [
                'corp_id' => $corp_id,
                'agent_id' => $agent_id,
                'secret' => $secret,
            ];

            $this->setConfig('work',$config);

            return $this->returnJosn('恭喜您!切换成功~');
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(),[],1);
        }
    }

}
