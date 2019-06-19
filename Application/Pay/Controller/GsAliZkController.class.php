<?php

namespace Pay\Controller;

use Think\Log;

/**
 * 怪兽支付宝转卡
 * Created by PhpStorm.
 * Date: 2019/4/29
 * Time: 12:23
 */

class GsAliZkController extends PayController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  发起支付
     */
    public function Pay($array)
    {
        $orderid = I("request.pay_orderid");
        $body = I('request.pay_productname');
        $notifyurl = $this->_site . 'Pay_GsAliZk_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_GsAliZk_callbackurl.html'; //跳转通知
        $parameter = array(
            'code' => 'GsAliZk',       // 通道代码
            'title' => '怪兽支付宝转卡',      //通道名称
            'exchange' => 1,          // 金额比例
            'gateway' => '',            //网关地址
            'orderid' => '',            //平台订单号（有特殊需求的订单号接口使用）
            'out_trade_id'=>$orderid,   //外部商家订单号
            'body'=>$body,              //商品名称
            'channel'=>$array,          //通道信息
        );
        //生成系统订单，并返回三方请求所需要参数
        $return = $this->orderadd($parameter);

        //支付网关
        $gateWayUrl = "https://paygs.v6591.com/api/";
        //支付接口预下单地址
        $prePayUrl = $gateWayUrl."order";
        //支付地址
        $goPayUrl = $gateWayUrl."pay/";

        $data = [];
        //商家ID
        $data['merchant_code'] = $return['mch_id'];
        //支付方式
        $data['method'] = "syceepay.alipay.bank";//支付宝转银行卡 syceepay.alipay.bank,支付宝转帐syceepay.alipay.transfer,微信syceepay.wechat.transfer
        //银行代码 可为空
        $data['bank_code'] = '';
        //交易金额
        $data['money'] = $return['amount'];
        //订单号
        $data['order_sn'] = $return ['orderid'];
        //同步返回地址 可为空
        $data['return_url'] = $callbackurl;
        //异步回调地址
        $data['notify_url'] = $notifyurl;
        //备注 可为空 不参与签名
        $data['remark'] = "";
        //服务类型 不参与签名
        $service_type = "";//direct_pay直连
        $localSign = [];
        foreach ($data as $key => $val) {
            if($val !== '' && !empty($val) && $key != 'remark' && $key != "service_type"){
                $localSign[$key] = $val;
            }
        }
        ksort($localSign);
        //商户私钥
        $privateKey = "-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKf7G8AXSgcyIKwB
8T2r+6YkRTKiRWO0wtxqPi6beQEigOichbWVv2jI3+P7Y8YtPD/KX6xyHQy1rQLG
QG9IP5YcVrJAB8UokuWw6ZxACL7K9NprzhjgFtLaiMl0TWzByRkJfW/8lhRP4L1C
Cty5HR0GSl+tbZZFaYTp0AWQR6D1AgMBAAECgYA3WzP46HPXzTghFv7F4RAr3xYC
qqENS3tPoZ9eQpPswM8UWMhjX7bVNCU7/xMRMsUBDJLcxDo4fwJFwMlpIrj5GSDJ
CZAOgIis/7iZ4b/R18DXbXn6UD6iM4OqTtQzFyi6N+ZEF2uAeHneVMDUHQnlRwqy
m5UbRuMdj4e5aToi/QJBANVCQKIOf/llK69JHtLE9G9y6HdgpCEyFvMbJeGUMlBa
ZiBxgB8oJ1z0L5faAzZtVKSqWoyTmhCA7Zl6Y/926HsCQQDJpcQHw9uM/zgQSFcW
pJpR/jQFvZ+tKUoSuAWSbtxDRu0IT3dp1sJcDHccsZeSMOAc4AVu5fiCKj4GjhD6
L7lPAkAoaWScEQlZNj2/+qg3opD7aZf5vzt6+uX0bYmRJKcgKPE/ZqKzxMUozTET
ooGHV9J5XbrI9tN0GyprMhEvANZvAkBTskw1dOPyGBTrHTuYmGW9Vqe9IkHem960
+lDFwOIHwq6r3mAQPCWcE0h1Tnk9IrBDC/D1YNrPHD12dw0w6xIdAkAcrEhSHsLj
xAOaf3bdjPfx6JYMVw15t7BEN7vDLQebnC51JO6OkoonV+zImnIeMfS14eTigxMo
mf+57rkNCWFI
-----END PRIVATE KEY-----";

        $signStr = urldecode(http_build_query($localSign));
        $merchant_private_key= openssl_get_privatekey($privateKey);
        openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
        $data['sign'] = base64_encode($sign_info);
        $data['service_type'] = $service_type;

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$prePayUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response=curl_exec($ch);
        $resArr = json_decode($response,true);
        Log::write(print_r($resArr ,1), Log::INFO);
        if($resArr['result'] === true){
            $payUrl = $goPayUrl.$resArr['data']['payment_code'];
            //重定向到支付地址，打开支付页面
            header("Location:$payUrl");
        }
    }

    /**
     * 同步回调接口
     */
    public function callbackurl()
    {
        //支付状态
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        if ($status == 1) {
            $Order = M("Order");
            $pay_status = $Order->where(['pay_orderid' => $_REQUEST["order_sn"]])->getField("pay_status");
            if($pay_status <> 0){
                $this->EditMoney($_REQUEST['order_sn'], 'GsAliSm', 1); //第三个参数为1时，页面会跳转到订单信息里面的 pay_callbackurl
            }else{
                exit("交易成功！");
            }
        }
    }

    /**
     * 异步通知
     */
    public function notifyurl()
    {
        Log::write(print_r($_REQUEST, 1), Log::INFO);
        //商户ID
        $merchant_code = isset($_REQUEST['merchant_code']) ? $_REQUEST['merchant_code'] : '';
        //支付金额
        $money = isset($_REQUEST['money']) ? $_REQUEST['money'] : '';
        //用户提交支付金额
        $submit_money = isset($_REQUEST['submit_money']) ? $_REQUEST['submit_money'] : '';
        //订单号
        $order_sn = isset($_REQUEST['order_sn']) ? $_REQUEST['order_sn'] : '';
        //上游订单号
        $trade_sn = isset($_REQUEST['trade_sn']) ? $_REQUEST['trade_sn'] : '';
        //支付时间
        $payment_time = isset($_REQUEST['payment_time']) ? $_REQUEST['payment_time'] : '';
        //支付状态
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        //备注 不参与签名
        $remark = isset($_REQUEST['remark']) ? $_REQUEST['remark'] : '';
        //支付方式
        $payment_method = isset($_REQUEST['payment_method']) ? $_REQUEST['payment_method'] : '';
        //RSA签名
        $sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : '';

        $data = [
            "merchant_code" => $merchant_code,
            "money" => $money,
            "submit_money" => $submit_money,
            "order_sn" => $order_sn,
            "trade_sn" => $trade_sn,
            "payment_time" => $payment_time,
            "status" => $status,
            "remark" => $remark,
            "payment_method" => $payment_method
        ];

        $localSign = [];
        foreach ($data as $key => $val) {
            if($val !== '' && !empty($val) && $key != 'remark'){
                $localSign[$key] = $val;
            }
        }
        //支付平台公钥
        $syceepayPublicKey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCfLbju6Fx5NrXxxufgUTtxMtoQ
EpgA2Zl7eR5rouCTeLASqCyBOTPWfBBz7ALnLdjKzvjrs5l+NyQSdeldfc1kJh6y
tHUaQaJKTIOMFnW4+XQqLMM/KW7sHNCwwL63ERtJCBnZCxmkQ1sXAHiFfxuYcsif
9zTksOt8E/Y0AwRPQwIDAQAB 
-----END PUBLIC KEY-----";
        ksort($localSign);
        $signStr = urldecode(http_build_query($localSign));

        $merPublicKeySsl = openssl_get_publickey($syceepayPublicKey);
        $flag = openssl_verify($signStr, base64_decode($sign), $merPublicKeySsl, OPENSSL_ALGO_MD5);
        if ($flag) {
            $this->EditMoney($_REQUEST['order_sn'], '', 0);
            echo "success";
        } else {
            echo "fail";
        }
    }
}