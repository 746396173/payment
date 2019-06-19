<?php
error_reporting(0);
header("Content-type: text/html; charset=utf-8");
$pay_memberid = "10068";   //商户ID
$pay_orderid = $_POST["orderid"];    //订单号
$pay_amount =  $_POST["amount"];    //交易金额
$pay_bankcode = $_POST["channel"];   //银行编码
if(empty($pay_memberid)||empty($pay_amount)||empty($pay_bankcode)){
    die("信息不完整！");
}
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$pay_notifyurl = "http://" . $_SERVER['HTTP_HOST'] . "/demo/server.php";   //服务端返回地址
$pay_callbackurl = "http://" . $_SERVER['HTTP_HOST'] . "/demo/page.php";  //页面跳转返回地址
$Md5key = "r1wvuvbcoqhzhhuvdrn2cdhrnyix3isk";   //密钥
$tjurl = "http://" . $_SERVER['HTTP_HOST'] . "/Pay_Index.html";   //提交地址


//扫码
$native = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
    "pay_callbackurl" => $pay_callbackurl,
);
ksort($native);
$md5str = "";
foreach ($native as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
//echo($md5str . "key=" . $Md5key);
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$native["pay_md5sign"] = $sign;
$native['pay_attach'] = "1234|456";
$native['pay_productname'] ='VIP基础服务';
if ($pay_bankcode == 903) {
    $native['pay_paytype'] = 'alipay';
} elseif ($pay_bankcode == 904) {
    $native['pay_paytype'] = 'alipaywap';
} elseif ($pay_bankcode == 902) {
    $native['pay_paytype'] = 'weixin'; //微信扫码
} elseif ($pay_bankcode == 901) {
    $native['pay_paytype'] = 'gzhpay'; //微信H5
} elseif ($pay_bankcode == 907) {
    $native['pay_paytype'] = 'bank';
} elseif ($pay_bankcode == 908) {
    $native['pay_paytype'] = 'qq';
} elseif ($pay_bankcode == 905) {
    $native['pay_paytype'] = 'qqwallet'; //qqwap
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

</head>
<body>
<div class="container">
    <div class="row" style="margin:15px;0;">
        <div class="col-md-12">
            <form class="form-inline" id="payform" method="post" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($native as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
                <button type="submit" style='display:none;' ></button>
            </form>
        </div>
    </div>
</div>
<script>
    document.forms['payform'].submit();
</script>
</body>
</html>

