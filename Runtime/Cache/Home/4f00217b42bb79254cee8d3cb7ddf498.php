<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=yes">
<link href="/favicon.ico" rel="shortcut icon"/>
<title><?php echo ($sitename); ?> - 产品中心 - 支付技术服务商，让支付简单、专业、快捷！</title>
<link rel="stylesheet" href="<?php echo ($style); ?>css/qietu.css">
<link rel="stylesheet" type="text/css" href="<?php echo ($style); ?>css/iconfont.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ($style); ?>css/animate.min.css"/>
<link rel="stylesheet" href="<?php echo ($style); ?>css/style.css">
<link rel="stylesheet" href="<?php echo ($style); ?>css/responsive.css">
</head>

<body>
<div class="header  bj-3d7bf8">
    <div class="wrapper">
        <div class="logo">
            <a href="index.html"><img src="<?php echo ($logo); ?>"/></a>
        </div>
        <div class="nav-wrap">
            <div class="nav">
                <ul>
                    <li class="home_index">
                        <strong><a href="/">首页 </a></strong>
                    </li>
                    <li>
                        <strong><a href="#">产品中心<i class="iconfont icon-jiantou8"></i></a></strong>
                        <dl>
                            <dd><a href="<?php echo ($products); ?>#pro1">聚合收款</a></dd>
                            <dd><a href="<?php echo ($products); ?>#pro2">子商户系统</a></dd>
                            <dd><a href="<?php echo ($products); ?>#pro3">代付系统</a></dd>
                            <dd><a href="<?php echo ($products); ?>#pro4">二维码支付</a></dd>
                        </dl>
                    </li>
                    <!-- <li>
                        <strong><a href="/home/index/help.html">帮助中心</a></strong>
                    </li> -->
                    <li >
                        <strong><a href="#">开发者中心<i class="iconfont icon-jiantou8"></i></a></strong>
                        <dl>
                           <dd><a href="/demo">DEMO体验</a></dd>
                            <dd><a href="<?php echo ($document); ?>">API开发文档</a></dd>
                            <dd><a href="<?php echo ($sdk); ?>">SDK下载</a></dd>
                        </dl>
                    </li>
                    <!---->
                    <li>
                        <strong><a href="#">业务登陆<i class="iconfont icon-jiantou8"></i></a></strong>
                        <dl>
                           <dd><a href="<?php echo ($user_login); ?>">商户登录</a></dd>
                            <dd><a href="<?php echo ($agent_login); ?>">代理登陆</a></dd>
                        </dl>

                   </li>
                </ul>
            </div>
            <div class="btns">
                <ul>       
                 
                        <li class=" reg"><a href="<?php echo ($register); ?>">商户注册</a></li>
                                    </ul>
            </div>
        </div>
        <div class="gh"><a href="#"></a></div>
    </div>
</div>
<div class="probanner">
	<div class="wrapper">
		<div class="wow fadeInLeft probanner-l g-txt">
			<h2>聚合支付SDK</h2>
			<p>开发者无需重复集成繁琐的支付接口,使用 <?php echo ($sitename); ?> 聚合SDK轻松接入所有支付方式,应对各类支付场景,同时获得高效能,安全可靠的支付基础设施服务，让支付接口产生价值。</p>
			<a href="javascript:openKefuLink();" class="btn g-home-btn">立即接入</a>
		</div>
		<div class="wow fadeInRight probanner-r g-img">
			<img src="<?php echo ($style); ?>picture/banner-6.png"/>
		</div>
	</div>
</div>
<div class="pro-pay">
	<div class="wrapper">
		<div class="pro-hd g-hd" id="pro1">
			<h2 class="wow fadeInUp">一码收款</h2>
			<p class="wow fadeInUp" data-wow-delay=".5s">-Aggregate receipts-</p>
		</div>
		<div class="pro-bd">
			<div class="wow fadeInLeft pro-pay-l">
				<img src="<?php echo ($style); ?>picture/img_38.png"/>
			</div>
			<div class="wow fadeInRight pro-pay-r">
				<h2 class="line-27">手机APP支付</h2>
				<p class="g-describe">
					<?php echo ($sitename); ?> 为iOS/Android原生/H5 App提供全套<br />
					支付解决方案：支持微信支付、支付宝支付、银联、手机支付、<br />
					QQ钱包 ,京东钱包等.
				</p>
				<ul class="clear">
					<li>
						<img src="<?php echo ($style); ?>picture/img_39.png"/>
						<span>手机APP支付</span>
					</li>
					<li>
						<img src="<?php echo ($style); ?>picture/img_40.png"/>
						<span>公众号支付</span>
					</li>
					<li>
						<img src="<?php echo ($style); ?>picture/img_41.png"/>
						<span>PC网页支付	</span>
					</li>
					<li>
						<img src="<?php echo ($style); ?>picture/img_42.png"/>
						<span>线下扫码支付</span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="pro-sys">
	<div class="wrapper">
		<div class="pro-hd g-hd" id="pro2">
			<h2 class="wow fadeInUp">子商户系统</h2>
			<p class="wow fadeInUp" data-wow-delay=".2s">-Merchant System-</p>
			<p class="wow fadeInUp pro-txt"data-wow-delay=".5s" >
				<?php echo ($sitename); ?> 子商户系统是面向平台、分销、供应链管理等类型商户，支持多级子商户管理的支付产品通过子商户系<br />
				统，可实现对代理商、分销商、分店、供应商等角色进行分层管理，与聚合收款产品相结合，实现自由灵活<br />
				的主子商户交易分账，与资金存管产品相结合，实现为主子商户进行清分结算。
			</p>
		</div>
		
		<div class="pro-bd">
			<div class="pro-sys-l">
				<ul>                 
					<li class="on"><a href="#"><i class="iconfont icon-shangdian"></i>商户管理</a></li>
					<li><a href="#"><i class="iconfont icon-jiaoyijilu"></i>交易查询</a></li>
					<li><a href="#"><i class="iconfont icon-qian"></i>财务对账</a></li>
					<li><a href="#"><i class="iconfont icon-renwuguanli"></i>分润管理</a></li>
					<li ><a href="#"><i class="iconfont icon-qianbao"></i>资金安全</a></li>
				</ul>
			</div>
			<div class="pro-sys-r">
				<div class="img">
					<img src="<?php echo ($style); ?>picture/img_50.png"/>
				</div>
				<div class="txt">
					<h2 class="line-27">子商户系统</h2>
					<p>
						提供完善的商户体系,易化商户结构梳理，灵活组建复杂场景<br />
						下的商户关系,便捷的管理商户层级和基本信息。
					</p>	
				</div>
				<div class="btn">
					<a class="g-btn" href="<?php echo ($register); ?>">了解更多</a>
				</div>
			</div>
			<div class="pro-sys-r pro-sys-r-none">
				<div class="img">
					<img src="<?php echo ($style); ?>picture/img_49.png"/>
				</div>
				<div class="txt">
					<h2 class="line-27">详细记录每一条交易信息</h2>
					<p>
						全方位、多方面的交易数据汇总统计<br />
						深度分析展示每单交易的收益和信息价值
					</p>	
				</div>
				<div class="btn">
					<a class="g-btn" href="<?php echo ($register); ?>">了解更多</a>
				</div>
			</div>
			<div class="pro-sys-r pro-sys-r-none">
				<div class="img">
					<img src="<?php echo ($style); ?>picture/img_48.png"/>
				</div>
				<div class="txt">
					<h2 class="line-27">精细化的财务展示、高效的账务核对</h2>
					<p>
						清晰的数据逻辑和准确的财务数据<br />
						轻松实现内部对账、商户对账和接口对账，降低财务成本
					</p>	
				</div>
				<div class="btn">
					<a class="g-btn" href="javascript:openKefuLink();">了解更多</a>
				</div>
			</div>
			<div class="pro-sys-r pro-sys-r-none">
				<div class="img">
					<img src="<?php echo ($style); ?>picture/img_47.png"/>
				</div>
				<div class="txt">
					<h2 class="line-27">轻松实现不同场景下的高效分润</h2>
					<p>
						针对服务平台、O2O商家、商超平台提供相应的分润模型<br />
						灵活的自主选择以适应自身业务形态
					</p>	
				</div>
				<div class="btn">
					<a class="g-btn" href="javascript:openKefuLink();">了解更多</a>
				</div>
			</div>
			<div class="pro-sys-r pro-sys-r-none">
				<div class="img">
					<img src="<?php echo ($style); ?>picture/img_43.png"/>
				</div>
				<div class="txt">
					<h2 class="line-27">接口放资金直清</h2>
					<p>
						<?php echo ($sitename); ?> 提供支付接口对接服务<br />
						资金由接口方进行清算，<?php echo ($sitename); ?> 提供支付接口技术服务，安全，放心。
					</p>
				</div>
				<div class="btn">
					<a class="g-btn" href="javascript:openKefuLink();">了解更多</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="pro-df even">
	<div class="wrapper">
		<div class="pro-hd g-hd" id="pro3">
			<h2 class="wow fadeInUp">代付系统</h2>
			<p class="wow fadeInUp" data-wow-delay=".2s">-Payment system-</p>
		</div>
		<div class="pro-bd clear">
			<div class="wow fadeInLeft pro-df-l txt">
				<h2 class="line-27 bj-4ac4fd">代付系统</h2>
				<p class="g-describe">一次接入所有主流支付接口，99.99% 系统可用性，满<br />足你丰富的交易场景需求,为你的用户提供完美支付体验.<br /><br />

一个 API，在任何平台和场景接入支付功能。支持微信、支付宝、<br />银行卡多种账户类型，灵活满足你的付款需求。</p>
				<a href="javascript:openKefuLink();" class="g-btn btn">立即联系</a>
			</div>
			<div class="wow fadeInRight pro-df-r img">
				<img src="<?php echo ($style); ?>picture/img_44.png"/>
			</div>
		</div>
	</div>
</div>
<div class="pro-qr odd">
	<div class="wrapper">
		<div class="pro-hd g-hd" id="pro4">
			<h2 class="wow fadeInUp">二维码支付</h2>
			<p class="wow fadeInUp" data-wow-delay=".2s">-QR code payment-</p>
		</div>
		<div class="pro-bd">
			<div class="wow fadeInLeft pro-qr-r img">
				<img src="<?php echo ($style); ?>picture/img_45.png"/>
			</div>
			<div class="wow fadeInRight pro-qr-l txt">
				<h2 class="line-27 bj-4ac4fd">扫二维码支付</h2>
				<p class="g-describe">线下商户二维码台卡，收款方便，商户日常经营一目了然<br /> 从收款、查看流水、核对账单、等等，<br />将商户与消费者紧密相连。</p>
				<a href="javascript:openKefuLink();" class="g-btn btn">立即联系</a>
			</div>
		</div>
	</div>
</div>
<div class="hstart">
	<div class="wrapper">
		<div class="wow fadeInLeft hstart-txt">
			<h2>立即开启支付新时代！</h2>
			<p><?php echo ($sitename); ?>，支付技术服务商，让支付简单、专业、快捷！</p>
		</div>
		<div class="wow fadeInRight hstart-btn ">
			<a class="g-btn" href="javascript:openKefuLink();">立即开启</a>
		</div>
	</div>
</div>

<div class="footer">
    <div class="wrapper">
        <dl class="s">
            <dt>联系我们</dt>
            <dd><a href="#about2">联系方式</a></dd>
            <dd><img src="<?php echo ($logo); ?>" style="width: 120px" /></dd>
        </dl>
        <dl>
            <dt>产品项目</dt>
           <dd><a href="<?php echo ($products); ?>#pro1">聚合收款</a></dd>
            <dd><a href="<?php echo ($products); ?>#pro2">子商户系统</a></dd>
            <dd><a href="<?php echo ($products); ?>#pro3">代付系统</a></dd>
        </dl>
        <dl>
            <dt>关于公司</dt>
            <dd><a href="<?php echo ($about); ?>">关于我们</a></dd>
            <dd><a href="javascript:openKefuLink();">接口合作</a></dd>
            <dd><a href="javascript:openKefuLink();">流量合作</a></dd>
        </dl>
        <dl>
            <dt>开发者</dt>
            <dd><a href="/demo">DEMO体验</a></dd>
            <dd><a href="<?php echo ($document); ?>">API开发文档</a></dd>
            <dd><a href="<?php echo ($sdk); ?>">SDK下载</a></dd>
        </dl>
        <dl class="s">
            <dt>扫一扫</dt>
            <dd><img src="<?php echo ($style); ?>picture/mobile.png" style="width: 109px;"/></dd>
        </dl>
    </div>
</div>
<div class="copyright">
    <div class="wrapper">
        Copyright © 2018 <?php echo ($sitename); ?> All rights reserved. 版权所有
    </div>
</div>

<script type="text/javascript">
function openKefuLink()
{
    window.open("http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($qq); ?>&site=qq&menu=yes",'_blank');
}

</script>

 
<script type="text/javascript" src="<?php echo ($style); ?>js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo ($style); ?>js/wow.min.js"></script>
<script type="text/javascript" src="<?php echo ($style); ?>js/script.js"></script>
<script type="text/javascript">
	$(function() {
	  $('.pro-sys-l li').click(function(){
			$(this).addClass('on').siblings().removeClass('on');
			$('.pro-sys-r').hide().eq($(this).index()).show();
			return false
		})
	})
</script>
</body>
</html>