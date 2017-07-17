<?php require dirname(__FILE__).'/init.php'; ?>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>获取百度BDUSS</title>
  <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="//cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
  <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
	<div class="panel-body" style="text-align: center;">
		<div class="list-group">
			<div class="list-group-item"><img src="https://m.baidu.com/static/index/plus/plus_logo.png" width="160px"></div>
			<div id="load" class="alert alert-info" style="display:none;"></div>
			<div id="login" class="list-group-item">
			<div class="form-group">
			<div class="input-group"><div class="input-group-addon">手机号</div>
			<input type="text" id="phone" value="" class="form-control" onkeydown="if(event.keyCode==13){submit.click()}"/>
			</div></div>
			<div class="form-group" id="sms" style="display:none;">
			<div class="input-group"><div class="input-group-addon">验证码</div>
			<input type="text" id="smscode" value="" class="form-control" onkeydown="if(event.keyCode==13){submit.click()}" placeholder="输入短信验证码"/>
			<div class="input-group-addon" id="sendcode_button"><button type="button" id="sendcode">发送验证码</button></div>
			</div>
			</div>
			<div class="form-group code" style="display:none;">
			<div id="codeimg"></div>
			<div class="input-group"><div class="input-group-addon">验证码</div>
			<input type="text" id="code" class="form-control" onkeydown="if(event.keyCode==13){submit.click()}" placeholder="输入验证码">
			</div>
			</div>
			<button type="button" id="submit" class="btn btn-primary btn-block">提交</button>
			</div>
		</div>
	</div>
<script>
var ajax={
	get: function(url, dataType, callback) {
		dataType = dataType || 'html';
		$.ajax({
			type: "GET",
			url: url,
			async: true,
			dataType: dataType,
			cache:false,
			success: function(data,status) {
				if (callback == null) {
					return;
				}
				callback(data);
			},
			error: function(error) {
				alert('创建连接失败');
			}
		});
	},
	post: function(url, parameter, dataType, callback) {
		dataType = dataType || 'html';
		$.ajax({
			type: "POST",
			url: url,
			async: true,
			dataType: dataType,
			data: parameter,
			cache:false,
			success: function(data,status) {
				if (callback == null) {
					return;
				}
				callback(data);
			},
			error: function(error) {
				alert('创建连接失败');
			}
		});
	}
}
function trim(str){ //去掉头尾空格
	return str.replace(/(^\s*)|(\s*$)/g, "");
}
function login(phone,smsvc){
	$('#load').html('正在登录，请稍等...');
	var loginurl="login.php?do=login3";
	ajax.post(loginurl,"phone="+phone+"&smsvc="+smsvc+"&r="+Math.random(1), 'json', function(d) {
		if(d.code ==0){
			$('#login').hide();
			$('.code').hide();
			$('#submit').hide();
			$('#sms').hide();
			$('#load').html('登录成功！请刷新页面。登录账号：'+decodeURIComponent(d.displayname));
		//	showresult(d);
		}else{
			$('#load').html(d.msg+" ("+d.code+")");
			$('.code').hide();
			$('#login').show();
		}
	});
	
}
function sendsms(phone,vcode,vcodestr,vcodesign){
	vcode=vcode||null;
	vcodestr=vcodestr||null;
	vcodesign=vcodesign||null;
	$('#load').html('正在发送验证码...');
	var loginurl="login.php?do=sendsms";
	ajax.post(loginurl,"phone="+phone+"&vcode="+vcode+"&vcodestr="+vcodestr+"&vcodesign="+vcodesign+"&r="+Math.random(1), 'json', function(d) {
		if(d.code ==0){
			$('.code').hide();
			$('#sms').show();
			$('#submit').attr('do','smscode');
			$('#smscode').focus();
			$('#load').html('请输入短信验证码');
			alert('已发送验证码到 '+phone);
		}else if(d.code ==50020){
			$('#load').html(d.msg);
			$('#codeimg').attr('vcodesign',d.vcodesign);
			$('#sms').hide();
			$('#submit').attr('do','code');
			getvc(d.vcodestr);
		}else if(d.code ==500002 || d.code ==500001){
			$('#load').html('请输入验证码');
			$('#submit').attr('do','code');
			alert(d.msg);
		}else if(d.code ==50014){
			$('#load').html('提示：60秒内只能发送一次验证码，否则会提示频繁');
			$('.code').hide();
			alert(d.msg);
		}else{
			$('.code').hide();
			alert(d.msg);
		}
	});
	
}
function getphone(phone){
	$('#load').html('正在检测手机号是否存在...');
	var getvcurl="login.php?do=getphone";
	ajax.post(getvcurl, 'phone='+phone+"&r="+Math.random(1), 'json', function(d) {
		if(d.code ==0){
			sendsms(phone);
		}else if(d.code ==3){
			$('#load').html('');
			$('.code').hide();
			$('#submit').attr('do','submit');
			alert('该手机号不存在，请重新输入！');
		}else{
			$('#load').html(d.msg+" ("+d.code+")");
			$('#submit').attr('do','submit');
			$('.code').hide();
		}
	});
}
function getvc(vcodestr){
	$('#codeimg').attr('vcodestr',vcodestr);
	$('#codeimg').html('<img onclick="this.src=\'login.php?do=getvcpic&vcodestr='+vcodestr+'&r=\'+Math.random();" src="login.php?do=getvcpic&vcodestr='+vcodestr+'&r='+Math.random(1)+'" title="点击刷新">');
	$('#submit').attr('do','code');
	$('#code').val("");
	$('.code').show();
}
$(document).ready(function(){
	$('#submit').click(function(){
		var self=$(this);
		var phone=trim($('#phone').val()),
			smscode=trim($('#smscode').val());
		if(phone=='') {
			alert("手机号不能为空！");
			return false;
		}
		$('#load').show();
		if (self.attr("data-lock") === "true") return;
		else self.attr("data-lock", "true");
		if(self.attr('do') == 'smscode'){
			if(smscode=='') {
				alert("验证码不能为空！");
				return false;
			}
			login(phone,smscode);
		}else if(self.attr('do') == 'code'){
			if(code=='') {
				alert("验证码不能为空！");
				return false;
			}
			var code=trim($('#code').val()),
				vcodestr=$('#codeimg').attr('vcodestr'),
				vcodesign=$('#codeimg').attr('vcodesign');
			sendsms(phone,code,vcodestr,vcodesign);
		}else{
			getphone(phone);
		}
		self.attr("data-lock", "false");
	});
	$('#sendcode').click(function(){
		var self=$(this);
		var phone=trim($('#phone').val());
		if(phone=='') {
			alert("手机号不能为空！");
			return false;
		}
		$('#load').show();
		if (self.attr("data-lock") === "true") return;
		else self.attr("data-lock", "true");
		var code=trim($('#code').val()),
			vcodestr=$('#codeimg').attr('vcodestr'),
			vcodesign=$('#codeimg').attr('vcodesign');
		sendsms(phone,code,vcodestr,vcodesign);
		self.attr("data-lock", "false");
	});
});
</script>
</body>
</html>