<?php

namespace Pay\Controller;

use Think\Log;

/**
 * Created by PhpStorm.
 * Date: 2019/4/18
 * Time: 14:04
 */
class XorAliSmController extends PayController
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
        $notifyurl = $this->_site . 'Pay_XorAliSm_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_XorAliSm_callbackurl.html'; //跳转通知
        $parameter = array(
            'code' => 'XorAliSm',       // 通道代码
            'title' => 'Xor支付宝扫码支付', //通道名称
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
        $price = $return['amount']; # 从 URL 获取充值金额 price
        $name = $return['subject'];  # 订单商品名称
        $pay_type = 'alipay';     # 付款方式
        $order_id = $return['orderid'];    # 自己创建的本地订单号
        $notify_url = $notifyurl;   # 回调通知地址

        $this->secret = $return['appsecret'];     # app secret, 在个人中心配置页面查看
        $api_url = 'https://xorpay.com/api/pay/4272';   # 付款请求接口，在个人中心配置页面查看

        $sign = $this->sign([$name, $pay_type, $price, $order_id, $notify_url,$this->secret]);

        $data = [
            'name' => $name,
            'pay_type' => $pay_type,
            'price' => $price,
            'order_id' => $order_id,
            'notify_url' => $notify_url,
            'sign' => $sign,
            'secret' => $this->secret,
        ];
        Log::write(print_r($data, 1));
        $res = curlPost($api_url, $data);
        Log::write('json数据：' . $res);
        $res = json_decode($res, true);
        Log::write('数据：' . print_r($res, 1));
        $this->assign('res', $res);
        $this->assign('price', $price);
        $this->assign('callback_url', $callbackurl);
        $this->display('XorAliSm/pay');
        /*echo '<html>
              <head><title>redirect...</title></head>
              <body>
                  <form id="post_data" action="'.$api_url.'" method="post">
                      <input type="hidden" name="name" value="'.$name.'"/>
                      <input type="hidden" name="pay_type" value="'.$pay_type.'"/>
                      <input type="hidden" name="price" value="'.$price.'"/>
                      <input type="hidden" name="order_id" value="'.$order_id.'"/>
                      <input type="hidden" name="notify_url" value="'.$notify_url.'"/>
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
            $this->EditMoney($_REQUEST['order_id'], 'XorAliScan', 1); //第三个参数为1时，页面会跳转到订单信息里面的 pay_callbackurl
        }else{
            exit("交易成功！");
        }
    }

    /**
     * 异步通知
     */
    public function notifyurl()
    {
        $this->secret = 'fbc144fe125846d181fcd20df4f5677b';
        Log::write(print_r($_POST, 1), Log::INFO);
        $response = $_POST;
        // 验证签名
        $sign = $response['sign'];
        $sign_str = $this->sign(array($_POST['aoid'], $_POST['order_id'], $_POST['pay_price'], $_POST['pay_time'], $this->secret));
        Log::write('sign:' . $sign . '|' . $sign_str, Log::INFO);
        if ($sign == $sign_str) {
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
