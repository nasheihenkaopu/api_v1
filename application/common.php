<?php

/**
 * 上传数据到阿里云日志服务
 * @param array
 */
function aliyun_log_put($data)
{
    vendor('aliyun_log.Log_Autoload');
    $endpoint = 'cn-hongkong.log.aliyuncs.com'; // 选择与上面步骤创建 project 所属区域匹配的 Endpoint
    $accessKeyId = 'LTAINKk4s3Xh1ZVb';        // 使用你的阿里云访问秘钥 AccessKeyId
    $accessKey = 'RiVyTOLjcfefO3qAnmB2gTColfHEmk';             // 使用你的阿里云访问秘钥 AccessKeySecret
    $project = 'apiv1';                  // 上面步骤创建的项目名称
    $logstore = 'channel_1';                // 上面步骤创建的日志库名称
    $client = new \Aliyun_Log_Client($endpoint, $accessKeyId, $accessKey);
        // #列出当前 project 下的所有日志库名称
        // $req1 = new \Aliyun_Log_Models_ListLogstoresRequest($project);
        // $res1 = $client->listLogstores($req1);
        // //var_dump($res1);
    $topic = "";
    $source = "";
    $logitems = array();
    $logItem = new \Aliyun_Log_Models_LogItem();
    $logItem->setTime(time());
    $logItem->setContents($data);
    array_push($logitems, $logItem);
    $req2 = new \Aliyun_Log_Models_PutLogsRequest($project, $logstore, $topic, $source, $logitems);
    $res2 = $client->putLogs($req2);
    // var_dump($res2);
}

/**
 * 返回redis对象
 * @return redis对象
 */
function redis($fun,$par, $par2 = false,$par3 = false){
    $redis = new Redis();
    $redis->connect(
        config('redis.host'),
        config('redis.port')
    );
    if($pwd = config('redis.password')){
        $redis->auth(config('redis.password'));
    }
    if($par3){
        $res = $redis->$fun($par, $par2,$par3);
    }else if($par2){
        $res = $redis->$fun($par, $par2);
    }else{
        $res = $redis->$fun($par);
    }
    $redis->close();
    return $res;
}