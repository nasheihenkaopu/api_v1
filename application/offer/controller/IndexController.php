<?php
namespace app\offer\controller;

use think\Controller;

class IndexController extends Controller
{
    //记录到日志的标识,后台脚本统计数据时用
    const MARK = 'offer_to_adv';

    public function client()
    {
        $data = request()->param();
        //四个必要参数,app名称,渠道id,频道id,创建时间
        if (empty($data['appname']) or empty($data['offer']) or empty($data['channel']) or empty($data['time'])) {
            return self::result('',412,'parameter error');
        } else {
            $data['mark'] = self::MARK;
            //记录到阿里云日志服务
            aliyun_log_put($data);
            //记录到redis
            $task_queue_key = $data['appname'].':'.$data['offer'].':'.$data['channel'].':'.self::MARK;

            redis('lpush',$task_queue_key,json_encode($data));
            return self::result('',200,'ok');
        }
    }

    public function service()
    {
        $data = request()->param();
        //四个必要参数,app名称,渠道id,频道id,创建时间
        if (empty($data['appname']) or empty($data['offer']) or empty($data['channel']) or empty($data['time'])) {
            return self::result('',412,'parameter error');
        } else {
            $data['mark'] = self::MARK;
            //记录到阿里云日志服务
            aliyun_log_put($data);
            //记录到redis
            $task_queue_key = $data['appname'].':'.$data['offer'].':'.$data['channel'].':'.self::MARK;

            redis('lpush',$task_queue_key,json_encode($data));
            return self::result('',200,'ok');
        }
    }
}
