<?php
namespace app\offer\controller;

use think\Controller;

class IndexController extends Controller
{
    
    public function report()
    {
        $data = request()->param();
        //五个必要参数,广告主id,app名称,渠道id,频道id,创建时间
        if (empty($data['advertisers']) or empty($data['appname']) or empty($data['offer']) or empty($data['channel']) or empty($data['time'])) {
            return self::result('',412,'parameter error');
        } else {
            //记录到阿里云日志服务
            aliyun_log_put($data);
            //记录到redis
            $task_queue_key = $data['advertisers'] .':'.$data['appname'].':'.$data['offer'].':'.$data['channel'];
            redis('lpush',$task_queue_key,json_encode($data));
            return self::result('',200,'ok');
        }
    }

    public function repp()
    {
        var_dump(redis('EXISTS','app'));
    }
}
