<?php

namespace Pay\Controller;

use Think\Log;

/**
 * 信比付支付宝扫码支付
 * Created by PhpStorm.
 * Date: 2019/4/17
 * Time: 14:25
 */

class XbfAliScanController extends PayController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 支付接口
     * @param $array array 参数
     */
    public function pay($array)
    {
        $orderid = I("request.pay_orderid");
        $body = I('request.pay_productname');
        $notifyurl = $this->_site . 'Pay_XbfAliScan_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_XbfAliScan_callbackurl.html'; //返回通知

        //支付方式
        $payType = 1;

        //正式请求网址
        //$web = 'https://wmw.shinbpay.com';

        //测试请求网址
        $web = 'https://dev.shinbpay.com';

        //请求页面
        $url = $web."/initiate/submit/order";

        $parameter = array(
            'code' => 'XbfAliScan',
            'title' => '信比付支付（支付宝扫码）',
            'exchange' => 1, // 金额比例
            'gateway' => '',
            'orderid'=>'',
            'out_trade_id' => $orderid, //外部订单号
            'channel'=>$array,
            'body'=>$body
        );

        // 订单号，可以为空，如果为空，由系统统一的生成
        $return = $this->orderadd($parameter);

        // 参数设置
        $appId = $return['appid'];
        $machId = $return['mch_id'];
        $apiKey = $return['appsecret'];
        $orderId = $return['orderid'];
        $md5Key = '';

        $order = [
            'orderId' => $orderId,
            'order' => [
                'streetName' => '测试地址',
                'sumPrice' =>$return['amount'],
                'freight' => 6,
                'name' => '测试订单',
                'mobile' => '12343290906'
            ]
        ];

        $sign_str = $appId . $orderId . $machId . $apiKey . $md5Key;
        Log::write('签名字符传：' . $sign_str, Log::INFO);
        $data = [
            'appId' => $appId,
            'machId' => $machId,
            'apiKey' => $apiKey,
            'orderId' => $orderId,
            'sign' => md5($appId . $orderId . $machId . $apiKey . $md5Key),
            'order' => json_encode($order),
            'payType'=> $payType, // 支付方式类别 1:支付宝 2：微信 4：银联 6：云闪付
            'timeStamp' => $this->ts_time('YmdHisu')
        ];

        //Post提交
        $result = $this->postCurl($url, $data);
        Log::write('data:' . print_r($data ,1), Log::INFO);

        //返回结果转数组
        $result = json_decode($result,true);
        Log::write(print_r($result ,1), Log::INFO);

        if($result['success']==true){
            $token = $result['result']['token'];
            $flowId= $result['result']['flowId'];
            $noteNum= $result['result']['noteNum'];
            $providerMobile= $result['result']['providerMobile'];
            $payType= $result['result']['payType'];
            $page = $result['result']['payPage'];
            $payurl = $web.$page.'?token='.$token.'&orderId='.$orderId.'&price='.$return['amount'].'&flowId='.$flowId.'&noteNum='.$noteNum.'&payType='.$payType.'&providerMobile='.$providerMobile;
            Log::write('跳转地址' . $payurl, Log::INFO);
            // echo '下面是支付链接，直接跳转页面到下面地址就可以：<br>';
            // echo $payurl;
            //重定向到支付地址，打开支付页面
            header("Location:$payurl");
        }
    }

    /**
     * 同步回调接口
     */
    public function callbackurl()
    {
        $Order = M("Order");
        $pay_status = $Order->where(['pay_orderid' => $_REQUEST["orderId"]])->getField("pay_status");
        if($pay_status <> 0){
            $this->EditMoney($_REQUEST['orderId'], 'XbfAliScan', 1); //第三个参数为1时，页面会跳转到订单信息里面的 pay_callbackurl
        }else{
            exit("交易成功！");
        }
    }

    /**
     * 异步通知
     */
    public function notifyurl()
    {
        Log::write(print_r($_POST, 1), Log::INFO);
        $response = $_POST;
        if ($response['statusId'] == 15) {
            // 验证签名
            $sign = $response['sign'];
            $sign_str = $response['shinBPayOrder'] . $response['orderPrice'] . $response['orderId'] . $response['statusId'] . $response['message'] . $response['MD5key'];
            if ($sign == md5($sign_str)) {
                $this->EditMoney($response['orderId'], '', 0);
                exit('success');
            }
        }
    }

    /**
     * 数据请求
     * @param $PostUrl
     * @param $post_data
     * @return bool|string
     */
    public function postCurl($PostUrl,$post_data) {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $PostUrl);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }

    /**
     * 获取时间戳
     * @return float
     */
    public function gettime()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

    /**
     * 年月日、时分秒 + 3位毫秒数
     * @param string $format
     * @param null $utimestamp
     * @return false|string
     */
    public function ts_time($format = 'u', $utimestamp = null) {
        if (is_null($utimestamp)){
            $utimestamp = microtime(true);
        }

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }
}
