<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        vendor('aliyun_log.Log_Autoload');
        $endpoint = 'cn-hongkong.log.aliyuncs.com'; // 选择与上面步骤创建 project 所属区域匹配的 Endpoint
        $accessKeyId = 'LTAINKk4s3Xh1ZVb';        // 使用你的阿里云访问秘钥 AccessKeyId
        $accessKey = 'RiVyTOLjcfefO3qAnmB2gTColfHEmk';             // 使用你的阿里云访问秘钥 AccessKeySecret
        $project = 'apiv1';                  // 上面步骤创建的项目名称
        $logstore = 'channel_1';                // 上面步骤创建的日志库名称
        $client = new \Aliyun_Log_Client($endpoint, $accessKeyId, $accessKey);
        #列出当前 project 下的所有日志库名称
        $req1 = new \Aliyun_Log_Models_ListLogstoresRequest($project);
        $res1 = $client->listLogstores($req1);
        //var_dump($res1);

        $topic = "";
        $source = "";
        $logitems = array();
        for ($i = 0; $i < 5; $i++) {
            $contents = [
                'appname' => 'tapai_mini',
                'channel' => 'slkdjflsjdlfkj',
                'idfa' => '124lksdjf',
                'offerid' => 'sdjfklgiowoejkh',
                'callback' => 'www.tapai.tv'
            ];
            $logItem = new \Aliyun_Log_Models_LogItem();
            $logItem->setTime(time());
            $logItem->setContents($contents);
            array_push($logitems, $logItem);
        }
        $req2 = new \Aliyun_Log_Models_PutLogsRequest($project, $logstore, $topic, $source, $logitems);
        $res2 = $client->putLogs($req2);
        //var_dump($res2);

        $listShardRequest = new \Aliyun_Log_Models_ListShardsRequest($project, $logstore);
        $listShardResponse = $client->listShards($listShardRequest);
        foreach ($listShardResponse->getShardIds() as $shardId) {
            #对每一个 ShardId，先获取 Cursor
            $getCursorRequest = new \Aliyun_Log_Models_GetCursorRequest($project, $logstore, $shardId, null, time() - 60);
            $response = $client->getCursor($getCursorRequest);
            $cursor = $response->getCursor();
            $count = 100;
            while (true) {
                #从 cursor 开始读数据
                $batchGetDataRequest = new \Aliyun_Log_Models_BatchGetLogsRequest($project, $logstore, $shardId, $count, $cursor);
                var_dump($batchGetDataRequest);
                $response = $client->batchGetLogs($batchGetDataRequest);
                if ($cursor == $response->getNextCursor()) {
                    break;
                }
                $logGroupList = $response->getLogGroupList();
                foreach ($logGroupList as $logGroup) {
                    print($logGroup->getCategory());
                    foreach ($logGroup->getLogsArray() as $log) {
                        foreach ($log->getContentsArray() as $content) {
                            print($content->getKey() . ":" . $content->getValue() . "\t");
                        }
                        print("\n");
                    }
                }
                $cursor = $response->getNextCursor();
            }
        }
    }
    public function test()
    {
        return josn($_POST);
    }
}
