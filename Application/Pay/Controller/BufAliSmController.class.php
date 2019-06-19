<?php

namespace Pay\Controller;

use Think\Log;

/**
 * Created by PhpStorm.
 * Date: 2019/4/18
 * Time: 14:04
 */
class BufAliSmController extends PayController
{
    private $secret = ''; // 密钥

    public function __construct()
    {
        parent::__construct();
    }

    public function pay($array)
    {
        $orderid = I("request.pay_orderid");
        $body = I('request.pay_productname');
        $notifyurl = $this->_site . 'Pay_BufAliSm_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_BufAliSm_callbackurl.html'; //跳转通知
        $parameter = array(
            'code' => 'BufAliSm',       // 通道代码
            'title' => 'Buf支付宝扫码支付', //通道名称
            'exchange' => 1,          // 金额比例
            'gateway' => '',            //网关地址
            'orderid' => '',            //平台订单号（有特殊需求的订单号接口使用）
            'out_trade_id'=>$orderid,   //外部商家订单号
            'body'=>$body,              //商品名称
            'channel'=>$array,          //通道信息
        );
        //生成系统订单，并返回三方请求所需要参数
        $return = $this->orderadd($parameter);

        // 参数配置
        $price = $return['amount'];
        $order_id = $return['orderid'];      # 自己创建的本地订单号
        $order_uid = '1051718919@qq.com';  # 订单对应的用户id
        $name = $return['subject'];  # 订单商品名称
        $pay_type = 'alipay';    # 付款方式
        $notify_url = $notifyurl;   # 回调通知地址
        $return_url = $callbackurl;   # 支付成功页面跳转地址

        $this->secret = $return['appsecret'];     # app secret, 在个人中心配置页面查看
        $api_url = 'https://bufpay.com/api/pay/97019';

        $sign = $this->sign([$name, $pay_type, $price, $order_id, $order_uid, $notify_url, $return_url, $this->secret]);

        $data = [
            'name' => $name,
            'pay_type' => $pay_type,
            'price' => $price,
            'order_id' => $order_id,
            'order_uid' => $order_uid,
            'notify_url' => $notify_url,
            'return_url' => $return_url,
            'sign' => $sign,
        ];
        $res = curlPost($api_url . '?format=json', $data);
        Log::write('json数据：' . $res);
        $res = json_decode($res, true);
        Log::write('数据：' . print_r($res, 1));
        $this->assign('res', $res);
        $this->display('BufAliSm/pay');
        /*echo '<html>
              <head><title>redirect...</title></head>
              <body>
                  <form id="post_data" action="'.$api_url.'" method="post">
                      <input type="hidden" name="name" value="'.$name.'"/>
                      <input type="hidden" name="pay_type" value="'.$pay_type.'"/>
                      <input type="hidden" name="price" value="'.$price.'"/>
                      <input type="hidden" name="order_id" value="'.$order_id.'"/>
                      <input type="hidden" name="order_uid" value="'.$order_uid.'"/>
                      <input type="hidden" name="notify_url" value="'.$notify_url.'"/>
                      <input type="hidden" name="return_url" value="'.$return_url.'"/>
                      <input type="hidden" name="sign" value="'.$sign.'"/>
                  </form>
                  <script>document.getElementById("post_data").submit();</script>
              </body>
              </html>';*/
    }

    /**
     * 同步回调接口
     */
    public function callbackurl()
    {
        $Order = M("Order");
        $pay_status = $Order->where(['pay_orderid' => $_REQUEST["order_id"]])->getField("pay_status");
        if($pay_status <> 0){
            $this->EditMoney($_REQUEST['order_id'], 'XbfAliScan', 1); //第三个参数为1时，页面会跳转到订单信息里面的 pay_callbackurl
        }else{
            exit("交易成功！");
        }
    }

    /**
     * 异步通知
     */
    public function notifyurl()
    {
        $this->secret = 'f02b31c4a8e64515b4b9d20afb5ff724';
        Log::write(print_r($_POST, 1), Log::INFO);
        $response = $_POST;
        // 验证签名
        $sign = $response['sign'];
        $sign_str = $response['aoid'] . $response['order_id'] . $response['order_uid'] . $response['price'] . $response['pay_price'] . $this->secret;
        Log::write('sign:' . $sign . '|' . md5($sign_str), Log::INFO);
        if ($sign == md5($sign_str)) {
            $this->EditMoney($response['order_id'], '', 0);
            exit('success');
        } else {
            Log::write('验签失败', Log::INFO);
        }
    }


    /**
     * 签名加密
     * @param $data_arr array
     * @return string
     */
    public function sign($data_arr) {
        return md5(join('',$data_arr));
    }
}
