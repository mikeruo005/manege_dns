<?php

namespace app\common\library;

use app\common\exception\UploadException;
use app\common\model\Attachment;
use app\common\model\Attendance;
use app\common\model\Department;
use app\common\model\Holiday;
use app\common\model\Rest;
use app\common\model\Task;
use app\common\model\Tguser;
use app\common\model\Workdetail;
use think\db\Expression;
use think\log;

/**
 * tg回复
 */
class Tg
{





    public static function sendMessage($content,$chat_id,$messageId="",$username="",$parse_mode="HTML"){


        $url = "https://api.telegram.org/bot" . config('site.tgtoken') . "/sendMessage";
        // 发送回复消息和内联键盘
        if($username){
            $content ="@$username \n$content";
        }
        $data = [
            'text' => $content,
            'chat_id' => $chat_id,
            'reply_to_message_id' => $messageId
        ];
        $data['parse_mode'] = 'HTML';
        if($parse_mode != 'HTML'){
            $data['parse_mode'] = $parse_mode;
        }
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        try {
            $result = file_get_contents($url, false, $context);
            //\think\Log::info($result);
            file_put_contents('/tmp/bot-respone.log',$result.PHP_EOL,FILE_APPEND);
        } catch (\Exception $e) {
            // 记录日志
            file_put_contents('/tmp/bot-sendmessage.log',$e->getFile().'---'.$e->getMessage().'---'.$e->getLine().'---'.$e->getCode().PHP_EOL.PHP_EOL,FILE_APPEND);
        }

    }


    public static function setCommands($chat_id){

        $list = Task::getList();
        $keyboard = [];
        $n = 0;
        foreach ($list as $k => $v){
            $i = intval($n / 3);
            $n++;
            $keyboard[$i][] = $k;
        }
        $replyMarkup = [
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ];
        // 将键盘布局转换为JSON格式
        $replyMarkup = json_encode($replyMarkup,256);
        $data = [
            'text'     => '请选择你需要的操作?',
            'chat_id' => $chat_id,
            'reply_markup' => $replyMarkup,
        ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $url = "https://api.telegram.org/bot" . config('site.tgtoken') . "/sendMessage";
        try {
            $result = file_get_contents($url, false, $context);

            file_put_contents('/tmp/bot-respone.log',$result.PHP_EOL,FILE_APPEND);
        } catch (\Exception $e) {
            // 记录日志
            file_put_contents('/tmp/bot-sendmessage.log',$e->getFile().'---'.$e->getMessage().'---'.$e->getLine().'---'.$e->getCode().PHP_EOL.PHP_EOL,FILE_APPEND);

        }
    }


    //上班
    public static function start_work($data){

        try {
            $tgId = $data['from']['id'];
            $username = $data['from']['username'];
            $list = Tguser::get(['tg_account' => $tgId]);
            $department_id = $list['department_id'] ?? '';
            $nowtime = date('Y-m-d H:i:s');
            $tg_nick = $data['from']['first_name'];
            if (!$list || !$department_id) {
                $nowtime = date('Y-m-d H:i:s');
                $msg = "用户:$tg_nick
用户标识: $tgId
打卡类型: 上班
时间: $nowtime
状态:打卡失败
提示:你暂未分配部门,请联系主管分配,重新打卡";
            } else {
                $worklist = Department::get($department_id);
                $nowdate = date('Y-m-d');
                $tomorrow = date('Y-m-d', strtotime('+1 days'));
                $startdate = $nowdate . ' ' . $worklist['start_work_time'];
                if ($worklist['start_work_time'] >= $worklist['off_work_time']) {
                    $enddate = $tomorrow . ' ' . $worklist['off_work_time'];
                } else {
                    $enddate = $nowdate . ' ' . $worklist['off_work_time'];
                }
                $workWhere = [
                    'tg_account' => ['=', $tgId],
                    'set_start_time' => ['=', $startdate],
                    'set_off_time' => ['=', $enddate],
                    'status' => ['=', '1'],
                ];
                $workDetail = Workdetail::get($workWhere);
                if (!$workDetail) { //没有数据
                    $model = Workdetail::createData($tgId, $tg_nick, $department_id);
                    $model->data(['start_work_time' => $nowtime])->save();
                    $msg = "用户:$tg_nick
用户标识: $tgId 
打卡类型: 上班 
时间: $nowtime
状态: 打卡成功
提示:请记得下班时打卡哦";
                } else { //有数据

                    if (isset($workDetail['start_work_time']) && $workDetail['start_work_time']) {
                        $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 上班 
时间: $nowtime 
状态: 打卡失败 
提示:今日上班已签到;请勿重复签到";
                    } else {
                        $workDetail->save(['start_work_time' => $nowtime]);
                        $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 上班 
时间: $nowtime 
状态: 打卡成功 
提示:请记得下班时打卡哦";
                    }
                }
            }
            $chatId = $data['chat']['id'];
            $messageId = $data['message_id'];

            self::sendMessage($msg, $chatId, $messageId, $username);
        }catch (\Exception $e){
            file_put_contents('/tmp/start-work.log',$e->getFile().'---'.$e->getMessage().'---'.$e->getLine().'---'.$e->getCode().PHP_EOL,FILE_APPEND);
        }


    }
    //下班
    public static function off_work($data){

        try {

        $tgId = $data['from']['id'];
        $username = $data['from']['username'];
        $list = Tguser::get(['tg_account'=>$tgId]);
        $nowtime = date('Y-m-d H:i:s');
        $tg_nick = $data['from']['first_name'];
        $department_id = $list['department_id']??'';
        if(!$list || !$department_id){

            $msg = "用户:$tg_nick
用户标识: $tgId
打卡类型: 下班
时间: $nowtime
状态:打卡失败
提示:你暂未分配部门,请联系主管分配,重新打卡";
        }else{
            $worklist = Department::get($department_id);
            $nowdate    = date('Y-m-d');
            $tomorrow   = date('Y-m-d',strtotime('+1 days'));
            $startdate = $nowdate.' '.$worklist['start_work_time'];
            if($worklist['start_work_time'] >= $worklist['off_work_time']){
                $enddate   = $tomorrow.' '.$worklist['off_work_time'];
            }else{
                $enddate   = $nowdate.' '.$worklist['off_work_time'];
            }
            $workWhere = [
                'tg_account'        => ['=',$tgId],
                'set_start_time'    => ['=',$startdate],
                'set_off_time'      => ['=',$enddate],
                'status'            => ['=','1'],
            ];
            $workDetail = Workdetail::where($workWhere)->order('id asc')->find();
            if(!$workDetail){
                $msg = "今日未打上班卡";
            }elseif(isset($workDetail['off_work_time']) && $workDetail['off_work_time']){
                $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 下班 
时间: $nowtime 
状态:打卡失败 
提示:今日下班已打卡;请勿重复打卡";
            }else{

                if($workDetail['start_work_time']) {
                    $alltime = strtotime($nowtime) - strtotime($workDetail['start_work_time']);
                    $work_time = time_format($alltime);
                    $work_true_time = time_format($alltime - $workDetail['leave_table_mins']);
                    $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 下班 
时间: $nowtime 
状态: 打卡成功 
今日工作总计: $work_time
纯工作时间:$work_true_time 
提示:本日工作时间已结算";
                    $workDetail->data(['off_work_time' => $nowtime])->save();
                }else{
                    $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 下班 
时间: $nowtime 
状态: 打卡成功 
提示:今日上班卡未签到";
                }
                $workDetail->data(['off_work_time' => $nowtime])->save();
            }

        }
        $chatId = $data['chat']['id'];
        $messageId = $data['message_id'];
        self::sendMessage($msg,$chatId,$messageId,$username);
        }catch (\Exception $e){
            file_put_contents('/tmp/off-work.log',$e->getFile().'---'.$e->getMessage().'---'.$e->getLine().'---'.$e->getCode().PHP_EOL,FILE_APPEND);
        }

    }
    //离桌
    public static function leave_table($data){

        $tgId = $data['from']['id'];
        $username = $data['from']['username'];
        $list = Tguser::get(['tg_account'=>$tgId]);
        $nowtime = date('Y-m-d H:i:s');
        $tg_nick = $data['from']['first_name'];
        $department_id = $list['department_id']??'';
        $worklist = Department::get($department_id);
        $nowdate    = date('Y-m-d');
        $tomorrow   = date('Y-m-d',strtotime('+1 days'));
        $startdate = $nowdate.' '.$worklist['start_work_time'];
        if($worklist['start_work_time'] >= $worklist['off_work_time']){
            $enddate   = $tomorrow.' '.$worklist['off_work_time'];
        }else{
            $enddate   = $nowdate.' '.$worklist['off_work_time'];
        }
        $workWhere = [
            'tg_account'        => ['=',$tgId],
            'set_start_time'    => ['=',$startdate],
            'set_off_time'      => ['=',$enddate],
            'status'            => ['=','1'],
        ];
        $workDetail = Workdetail::where($workWhere)->order('id asc')->find();
        if(!$workDetail || $nowtime<$startdate || $nowtime > $enddate){
            $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 离岗 
时间: $nowtime 
状态: 失败 
提示:你今日暂未上班，无需离岗";
        }elseif(isset($workDetail['off_work_time']) && $workDetail['off_work_time']){
            $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 离岗 
时间: $nowtime 
状态: 失败 
提示:你今日已打卡下班，离岗无效";
        }else{
            $where = [
                'tg_account' => ['=',$tgId],
                'leave_time'    => ['between',[strtotime($startdate),strtotime($enddate)]],
                'back_time' => ['exp',new Expression('IS NULL')]
            ];
            $model = Rest::where($where)->order('id desc')->find();
            if($model){
                $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 离岗 
时间: $nowtime 
状态: 失败 
提示:你上一次离岗还未回岗，请先回岗打卡";
            }else {
                $datas = [
                    'tg_account' => $tgId,
                    'tg_nick' => $data['from']['first_name'],
                    'leave_time' => time(),
                ];
                Rest::create($datas);

                $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 离岗 
时间: $nowtime 
状态: 成功 
提示:离岗成功;请记得回岗后打卡";
            }
        }
        $chatId = $data['chat']['id'];
        $messageId = $data['message_id'];
        self::sendMessage($msg,$chatId,$messageId,$username);

    }

    //回岗
    public static function back_table($data){
        $chatId = $data['chat']['id'];
        $messageId = $data['message_id'];
        $nowtime = date('Y-m-d H:i:s');
        $tg_nick = $data['from']['first_name'];
        $username = $data['from']['username'];
        $tgId = $data['from']['id'];
        $list = Tguser::get(['tg_account'=>$tgId]);
        $department_id = $list['department_id']??'';
        $worklist = Department::get($department_id);
        $nowdate    = date('Y-m-d');
        $tomorrow   = date('Y-m-d',strtotime('+1 days'));
        $startdate = $nowdate.' '.$worklist['start_work_time'];
        if($worklist['start_work_time'] >= $worklist['off_work_time']){
            $enddate   = $tomorrow.' '.$worklist['off_work_time'];
        }else{
            $enddate   = $nowdate.' '.$worklist['off_work_time'];
        }

        $where = [
            'tg_account' => ['=',$tgId],
            'leave_time'    => ['between',[strtotime($startdate),strtotime($enddate)]],
            'back_time' => ['exp',new Expression('IS NULL')]
        ];
        $model = Rest::where($where)->order('id desc')->find();
        if(!$model){
            $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 回岗 
时间: $nowtime 
状态: 失败 
提示:你未申请离岗，无需回岗";
        }else{
            $now = time();
            $model->tg_account    = $tgId;
            $model->tg_nick       = $data['from']['first_name'];
            $model->back_time     = $now;
            $seconds = $now - $model->leave_time;
            $model->seconds     = $seconds;

            $model->save();
            $dtime = time_format($seconds);
            $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 离岗 
时间: $nowtime 
状态: 成功 
提示:你本次离岗时间: $dtime";
        }

        self::sendMessage($msg,$chatId,$messageId,$username);
    }
    //请假
    public static function ask_for_leave($data){

        $nowtime = date('Y-m-d H:i:s');
        $tg_nick = $data['from']['first_name'];
        $tgId = $data['from']['id'];
        $tomorrow = strtotime('tomorrow');
        $list = Tguser::get(['tg_account'=>$tgId]);
        $department_id = $list['department_id']??'';
        $worklist = Department::get($department_id);

        $nowdate    = date('Y-m-d',strtotime('+1 days'));
        $tomorrow   = date('Y-m-d',strtotime('+2 days'));
        $start = $nowdate.' '.$worklist['start_work_time'];
        if($worklist['start_work_time'] >= $worklist['off_work_time']){
            $end   = $tomorrow.' '.$worklist['off_work_time'];
        }else{
            $end   = $nowdate.' '.$worklist['off_work_time'];
        }
        $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 请假 
时间: $nowtime 
状态: 成功 
提示:请复制加粗字体并修改为具体时间回复
示例:
<b>请假:$start~$end</b>";
        $chatId = $data['chat']['id'];
        $messageId = $data['message_id'];
        $username = $data['from']['username'];
        self::sendMessage($msg,$chatId,$messageId,$username);
    }


    //补勤
    public static function update_attendance($data){
        file_put_contents('/tmp/bot-message.log','补勤进来了'.PHP_EOL,FILE_APPEND);

        $nowtime = date('Y-m-d H:i:s');
        $tg_nick = $data['from']['first_name'];
        $tgId = $data['from']['id'];
        $list = Tguser::get(['tg_account'=>$tgId]);
        $department_id = $list['department_id']??'';
        $worklist = Department::get($department_id);
        $nowdate    = date('Y-m-d',strtotime('-2 days'));
        $yestoday   = date('Y-m-d',strtotime('-1 days'));
        $start = $nowdate.' '.$worklist['start_work_time'];
        if($worklist['start_work_time'] >= $worklist['off_work_time']){
            $end   = $yestoday.' '.$worklist['off_work_time'];
        }else{
            $end   = $nowdate.' '.$worklist['off_work_time'];
        }
        $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 补勤 
时间: $nowtime 
状态: 成功 
提示:请复制加粗字体并修改为具体时间回复 
示例:
<b>补勤:$start~$end</b>";
        $chatId = $data['chat']['id'];
        $messageId = $data['message_id'];
        $username = $data['from']['username'];
        self::sendMessage($msg,$chatId,$messageId,$username);
    }


    public static function ask_for_leave_action($data){
        $nowtime = date('Y-m-d H:i:s');
        $tg_nick = $data['from']['first_name'];
        $tgId = $data['from']['id'];
        $username = $data['from']['username'];
        $tomorrow = strtotime('tomorrow');
        $start = date('Y-m-d 08:00:00',$tomorrow);
        $end = date('Y-m-d 23:00:00',$tomorrow);
        $now = date('Y-m-d H:i:s');
        $str = mb_substr($data['text']??'',3);
        $arr = explode('~',$str);
        if(count($arr) != 2){
            $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 请假 
时间: $nowtime 
状态: 失败 
提示:请假回复格式错误1,请按照加粗字体回复 
示例:
<b>请假:$start~$end</b>";
        }else{
            if(!check_date($arr[0]) || !check_date($arr[1]) || $arr[1] < $arr[0]){
                $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 请假 
时间: $nowtime 
状态: 失败 
提示:请假回复格式错误2,请按照加粗字体回复 
示例:
<b>请假:$start~$end</b>";
            }else{
                if($arr[0] < $now || $arr[1] < $now){
                    $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 请假 
时间: $nowtime 
状态: 失败 
提示:你申请的假期不能为之前的日期";
                }else {
                    $datas = [
                        'tg_account' => $data['from']['id'],
                        'tg_nick' => $data['from']['first_name'],
                        'chat_id' => $data['chat']['id'],
                        'username' => $data['from']['username'],
                        'message_id' => $data['message_id'],
                        'start_time' => $arr[0],
                        'end_time' => $arr[1],
                    ];
                    Holiday::create($datas);
                    $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 请假 
时间: $nowtime 
状态: 成功 
提示:请假申请成功,请等待审核";
                }
            }



        }
        $chatId = $data['chat']['id'];
        $messageId = $data['message_id'];
        $username = $data['from']['username'];
        self::sendMessage($msg,$chatId,$messageId,$username);
    }

    public static function update_attendance_action($data){
        $nowtime = date('Y-m-d H:i:s');
        $tg_nick = $data['from']['first_name'];
        $tgId = $data['from']['id'];
        $username = $data['from']['username'];
        $yestoday = strtotime('-1 days');
        $start = date('Y-m-d 08:00:00',$yestoday);
        $end = date('Y-m-d 23:00:00',$yestoday);
        $now = date('Y-m-d H:i:s');
        $str = mb_substr($data['text']??'',3);
        $arr = explode('~',$str);
        if(count($arr) != 2){
            $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 补勤 
时间: $nowtime 
状态: 失败 
提示:补勤回复格式错误1,请按照加粗字体回复 
示例:
<b>补勤:$start~$end</b>";
        }else{

            if(!check_date($arr[0]) || !check_date($arr[1]) || $arr[1] < $arr[0]){
                $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 补勤 
时间: $nowtime 
状态: 失败 
提示:补勤回复格式错误2,请按照加粗字体回复 
<b>补勤:$start~$end</b>";
            }else{
                if($arr[0] > $now || $arr[1] > $now){
                    $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 补勤 
时间: $nowtime 
状态: 失败 
提示:你申请的补勤不能为之后的日期";
                }else {
                    $datas = [
                        'tg_account' => $data['from']['id'],
                        'tg_nick' => $data['from']['first_name'],
                        'chat_id' => $data['chat']['id'],
                        'username' => $data['from']['username'],
                        'message_id' => $data['message_id'],
                        'start_time' => $arr[0],
                        'end_time' => $arr[1],
                    ];
                    Attendance::create($datas);
                    $msg = "用户:$tg_nick 
用户标识: $tgId 
打卡类型: 补勤 
时间: $nowtime 
状态: 成功 
提示:补勤申请成功,请等待审核";
                }
            }
        }
        $chatId = $data['chat']['id'];
        $messageId = $data['message_id'];
        $username = $data['from']['username'];
        self::sendMessage($msg,$chatId,$messageId,$username);
    }

}
