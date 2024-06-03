<?php

namespace app\admin\command;

use app\admin\model\Account;
use app\admin\model\Domains;
use app\admin\model\record\Aliyun;
use app\admin\model\record\Huawei;
use app\common\library\Alicloud;
use app\common\library\Cloud;
use app\common\library\Huaweicloud;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Work extends Command
{


    protected function configure()
    {
        $this
                ->setName('work')
                ->addOption('module', 'm', Option::VALUE_REQUIRED, 'module name(frontend or backend),use \'all\' when build all modules', null)
                ->addOption('resource', 'r', Option::VALUE_REQUIRED, 'resource name(js or css),use \'all\' when build all resources', null)
                ->addOption('optimize', 'o', Option::VALUE_OPTIONAL, 'optimize type(uglify|closure|none)', 'none')
                ->setDescription('Compress js and css file');
    }

    protected function execute(Input $input, Output $output)
    {

        $is = config('site.isauto_async');
        $mins = config('site.async_mins');
        $min = date('i');
        if($is === '1'){
            if(!($min%$mins)){
                Cloud::syncData();
            }
        }else{
            if(!($min%$mins)){
                file_put_contents('/tmp/curl-'.date('Ymd').'.log',"-----关闭同步:".date('Y-m-d H:i:s').PHP_EOL,FILE_APPEND);
            }

        }
        die('执行完成');

    }


    /**
     * 获取基础模板
     * @param string $name
     * @return string
     */
    protected function getStub($name)
    {
        return __DIR__ . DS . 'Min' . DS . 'stubs' . DS . $name . '.stub';
    }
}
