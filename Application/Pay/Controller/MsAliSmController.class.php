<?php

namespace Pay\Controller;

use Think\Log;

/**
 * 微扫付支付宝支付
 * Created by PhpStorm.
 * Date: 2019/4/22
 * Time: 12:05
 */

class MsAliSmController extends PayController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pay($array)
    {
        $orderid = I("request.pay_orderid");
        $body = I('request.pay_productname');
        $notifyurl = $this->_site . 'Pay_MsAli_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_MsAli_callbackurl.html'; //返回通知

        $parameter = array(
            'code' => 'MsAliSm',
            'title' => '微扫付支付宝（支付宝扫码）',
            'exchange' => 1, // 金额比例
            'gateway' => '',
            'orderid'=>'',
            'out_trade_id' => $orderid, //外部订单号
            'channel'=>$array,
            'body'=>$body
        );

        // 订单号，可以为空，如果为空，由系统统一的生成
        $return = $this->orderadd($parameter);

        // 发起支付
        $data = [];
        $data['parter'] = $return['appid'];
        $data['value']    = $return['amount']; # 金额
        $data['type'] = 'ali';        # 支付类型：wx=微信,wxwap=微信WAP,ali=支付宝,aliwap=支付宝WAP,qq=QQ,qqwap=QQWAP
        $data['orderid']= $return['orderid'];    #订单号
        $data['notifyurl'] = $notifyurl; #异步通知地址
        $data['callbackurl'] = $callbackurl; #同步地址
        $key = $return['signkey'];
        ksort($data);
        Log::write('md5原串：' . urldecode(http_build_query( $data) . '&key=' . $key), Log::INFO);
        $data['sign'] = md5(urldecode(http_build_query( $data) . '&key=' . $key));
        $url = 'https://wwww.lvseyj.com/api.php/pay/index';
        $this->sendForm($url, $data);
    }


    /**
     *将数组转为Form表单提交
     *@param $url string 提交地址
     *@param $data array 提交的参数
     *@param $method string 提交方式，默认get
     */
    public function sendForm( $url, $data, $method='get')
    {
        $form = "<form action='{$url}' id='sendForm' name='sendForm' method='{$method}'>\r\n";
        foreach ($data as $key => $value) {
            $form .= "<input type='hidden' name='".$key."' value='".$value."'>\r\n";
        }
        $form.= "</form><script>document.sendForm.submit();</script>";
        echo $form;
    }

    /**
     * 同步回调接口
     */
    public function callbackurl()
    {
        $Order = M("Order");
        $pay_status = $Order->where(['pay_orderid' => $_REQUEST["orderid"]])->getField("pay_status");
        if($pay_status <> 0){
            $this->EditMoney($_REQUEST['orderid'], 'MsAli', 1); //第三个参数为1时，页面会跳转到订单信息里面的 pay_callbackurl
        }else{
            exit("交易成功！");
        }
    }

    /**
     * 异步通知
     */
    public function notifyurl()
    {
        Log::write(print_r($_REQUEST, 1), Log::INFO);
        $key = 'e50aab1edb721dad46423f9388d0de29';
        $data['parter'] = $_REQUEST['parter'];
        $data['orderid']= $_REQUEST['orderid'];
        $data['opstate']= $_REQUEST['opstate'];
        $data['ovalue'] = $_REQUEST['ovalue'];
        ksort($data);
        $local_sign = md5( urldecode( http_build_query($data) . '&key=' . $key));
        $sign = $_REQUEST['sign'];
        if( $data['opstate'] == 1 && $sign == $local_sign ){
            $this->EditMoney($_REQUEST['orderid'], '', 0);
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
