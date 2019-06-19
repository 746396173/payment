<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="author" content="bufpay.com">
    <meta name="description" content="即时到账收款接口，支持支付宝支付和微信支付，免签约即时到账，只需个人微信支付宝账号即可收款，即开即用，超稳定不漏单，支持多微信支付宝账户并行收款">
    <title>扫码支付</title>
    <style>
        html {
            font-family: "Roboto", "Helvetica Neue", Helvetica, Arial , sans-serif, "Microsoft YaHei", "微软雅黑", STXihei, "华文细黑", serif;
            background-color: #eeeeee;
            padding: 0;
            margin: 0;
        }
        .copyright {
            font-size: 13px;
            color: #ccc;
            text-decoration: none;
            padding: 15px;
            margin: auto auto;
            display: block;
            text-align: center;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            letter-spacing: 1px;
            margin-top: 20px;
            color: #ffffff;
            margin-bottom: 0;
            font-weight: normal;
            text-align: center;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            white-space: nowrap;
            padding: 6px 16px;
            font-size: 13px;
            line-height: 1.846;
            border-radius: 3px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: #0c84e4;
        }
        #alipay_btn {
            display: none;
            background-color: #5da9d9;
        }
        #feedback_box {
            display: none;
        }
        .area {
            margin-top: 10px;
            background:white;
            border-radius: 3px;
            text-align: center;
        }
        .ticket-bd {
            background: radial-gradient(circle, #eeeeee, #eeeeee 50%, white 50%, white 100% ) -9px -8px / 16px 16px repeat-x;
            height: 8px;
        }
        .ticket-bd-bottom {
            background: radial-gradient(circle, #eeeeee, #eeeeee 50%, white 50%, white 100% ) -9px -0px / 16px 16px repeat-x;
            height: 8px;
        }
        .icon {
            background-image: url('data:image/png;base64,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');
        }
        .type {
            height: 60px;width: 120px;
            margin: auto;
            background-size: 100%;
            background-repeat: no-repeat;
        }
        .wechat {
            background-position: 1px -53px;
        }
        .alipay {
            background-position: 1px 6px;
        }
        .scan {
            width: 60px;
            height: 100%;
            display: inline-block;
            background-size: 220%;
            background-position: 96px -126px;
        }
    </style>
</head>
<body>
<div style="max-width: 600px;margin: auto;">
    <div class="area">
        <div class="ticket-bd"></div>

        <div class="type icon alipay"></div>

        <h1 style="margin: 20px 0px;">￥<?php echo ($res["price"]); ?></h1>

        <div>
            <img id="qr" style="width:150px; border-radius: 3px;" src="<?php echo ($res['qr_src']); ?>"/>
        </div>
    <a href="" target="_blank" class="btn" id="alipay_btn">打开支付宝App支付</a>

        <div id="feedback_box"><a href="" class="btn" style="background-color: #FF9800;">已付款未到账，联系客服</a></div>

        <p style="color:#ff9800;" id="count_down"></p>
        <p style="color: #ff132e;margin:20px;">如无法支付</p>
         <p style="color: #ff132e;margin:20px;">请用另一部手机，打开支付宝「扫一扫」付款</p>
        <div style="height:60px;border-top: dashed #eeeeee 2px;" id="footer">
            <div class="scan icon"></div>
            <span style="display: inline-block;vertical-align: middle;line-height: normal;margin-top:-47px;">「扫一扫」付款</span>
        </div>

        <div style="height:60px;border-top: dashed #eeeeee 2px;display:none;" id="wechat_footer">
            <div class="scan icon"></div>
            <span style="display: inline-block;vertical-align: middle;line-height: normal;margin-top:-47px;">「扫一扫」付款</span>
        </div>


        <div class="ticket-bd-bottom"></div>
    </div>
    <a href="" target="_blank" class="copyright">由「支付平台」提供收款支持</a>
</div>

<script>
    if(/micromessenger/.test(navigator.userAgent.toLowerCase())) {
        document.getElementById("footer").style.display = "none";
        document.getElementById("wechat_footer").style.display = "block";
    };

    var expires_in = 299;
    var do_expire = function (h) {
        clearInterval(h);
        document.getElementById("alipay_btn").style.display = 'none';

        document.getElementById("count_down").innerText = "订单已过期";
        document.getElementById("qr").src = "data:image/png;base64,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";


    };

    var handler = setInterval(function () {
        expires_in--;
        if(expires_in<=0) {
            do_expire(handler);
        } else {
            var min = parseInt(expires_in / 60);
            document.getElementById("count_down").innerText = (min?(min+ " 分 "):"") + expires_in % 60 + " 秒后订单过期，请及时支付";
        };
    }, 1000);

    var $jsonp = (function(){
        var that = {};
        that.send = function(src, options) {
            var callback_name = options.callbackName || 'callback',
                on_success = options.onSuccess || function(){},
                on_timeout = options.onTimeout || function(){},
                timeout = options.timeout || 10; // sec
            var timeout_trigger = window.setTimeout(function(){
                window[callback_name] = function(){};
                on_timeout();
            }, timeout * 1000);
            window[callback_name] = function(data){
                window.clearTimeout(timeout_trigger);
                on_success(data);
            }
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.async = true;
            script.src = src;
            document.getElementsByTagName('head')[0].appendChild(script);
        }
        return that;
    })();

    var pool_handler = setInterval(function () {
        $jsonp.send("https://bufpay.com/api/query/<?php echo ($res['aoid']); ?>?callback=cb&_=" + new Date().getTime(), {
            callbackName: 'cb',
            onSuccess: function(ret){
                if(ret.status=='payed' || ret.status=='success' || ret.status=='fee_error') {
                    location.href = "<?php echo ($res['return_url']); ?>";
                };
                if(ret.status=='expire') {
                    clearInterval(pool_handler);
                    do_expire(handler);
                };
            }
        });
    }, 5000);

    if(/Mobi|Android/i.test(navigator.userAgent)) {
    
        document.getElementById("alipay_btn").href = "<?php echo ($res['jump_url']); ?>";
        document.getElementById("alipay_btn").style.display = 'inline-block';
      
    };
</script>

  
</body>
</html>