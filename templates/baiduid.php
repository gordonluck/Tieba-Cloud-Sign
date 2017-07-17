<?php if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); }
global $i;
global $m;
?>



<!-- NAVI -->
<ul class="nav nav-tabs" id="PageTab">
  <li class="active"><a href="#adminid" data-toggle="tab" onclick="$('#newid2').hide();$('#newid3').hide();$('#newid4').hide();$('#newid').hide();$('#adminid').show();">管理账号</a></li>
  <?php if (option::get('bduss_num') != '-1' || ISVIP) { ?><li><a href="#newid" data-toggle="tab" onclick="$('#newid').show();$('#adminid').hide();$('#newid2').hide();$('#newid3').hide();$('#newid4').hide();">自动绑定</a></li>
  <li><a href="#newid2" data-toggle="tab" onclick="$('#newid2').show();$('#adminid').hide();$('#newid').hide();$('#newid3').hide();$('#newid4').hide();">手动绑定</a></li>
  <li><a href="#newid3" data-toggle="tab" onclick="$('#newid').hide();$('#newid2').hide();$('#adminid').hide();$('#newid4').hide();$('#newid3').show();qrcodel();">扫码绑定</a></li>
  <li><a href="#newid4" data-toggle="tab" onclick="$('#newid').hide();$('#newid2').hide();$('#adminid').hide();$('#newid4').show();$('#newid3').hide();">短信绑定</a></li><?php } ?>
</ul>
<br/>
<!-- END NAVI -->

<!-- PAGE1: ADMINID-->
<div class="tab-pane fade in active" id="adminid">
<a name="#adminid"></a>
<?php if (option::get('bduss_num') == '-1' && ISVIP != true) { ?>
<div class="alert alert-danger" role="alert">
  本站禁止绑定百度账号，当前已绑定 <?php echo sizeof($i['user']['bduss']) ?> 个账号，PID 即为 账号ID
</div>
<?php } elseif(empty($i['user']['bduss'])) { ?>
<div class="alert alert-warning">
  无法显示列表，因为当前还没有绑定任何百度账号
  <br/>若要绑定账号，请点击上方的 [ 绑定新账号 ]
  <?php if (option::get('bduss_num') != '0' && ISVIP != true) echo '，您最多能够绑定 '.option::get('bduss_num').' 个账号'; ?>
</div>
<?php } else { ?>
<div class="alert alert-info">
  当前已绑定 <?php echo sizeof($i['user']['bduss']) ?> 个账号，PID 即为 账号ID
  <?php if (option::get('bduss_num') != '0' && ISVIP != true) echo '，您最多能够绑定 '.option::get('bduss_num').' 个账号'; ?>
。</div>
<?php } if(!empty($i['user']['bduss'])) { ?>

<table class="table table-striped">
  <thead>
    <tr>
      <th>PID</th>
      <th style="width:25%">百度名称</th>
      <th style="width:65%">BDUSS Cookie</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
   <?php
    foreach ($i['user']['bduss'] as $key => $value) {
      echo '<tr><td>'.$key.'</td>';
      $name = empty($i['user']['baidu'][$key]) ? '未记录百度ID' : $i['user']['baidu'][$key];
      if($name == '[E]') $name='<font color="red">已失效</font>';
      //echo '<td><a href="setting.php?mod=baiduid&reget='.$key.'"">'.$name.'</a></td>';
      echo '<td>'.$name.'</td>';
      echo '<td><input type="text" class="form-control" readonly value="'.$value.'"></td><td><a class="btn btn-default" href="setting.php?mod=baiduid&del='.$key.'">解绑</a></td></tr>';
    }
   ?>
  </tbody>
</table>
<?php } ?>
</div>
<!-- END PAGE1 -->

<!-- PAGE2: NEWID -->
<div class="tab-pane fade" id="newid" style="display:none">
<script type="text/javascript" src="./source/js/base.js">
  <script type="text/javascript">
  /*
      $(document).ready(function(){
          $("#addbdid_form").submit(function(e){
              $('#addbdid_submit').attr('disabled',true);
              $('#addbdid_prog').css({"display":""});
              $('.addbdis_text').html('正在拉取验证信息...');
              $('#addbdid_pb').css({"width":"25%"});
              $.ajax({
                  url:"ajax.php?mod=baiduid:getverify",
                  async:true,
                  dataType:"json",
                  type:'POST',
                  data: {
                      'bd_name': $('#bd_name').val() ,
                      'bd_pw': $('#bd_pw').val()
                  },
                  beforeSend: function(x) {
                      this.data += "&vcode=" + $('#addbdid_ver').val() + "&vcodestr=" + $('#vcodeStr').val();
                  },
                  complete: function(x,y) {
                      $('#addbdid_submit').removeAttr('disabled');
                  },
                  success: function(x) {
                      if(x.error == -3) {
                          $('#addbdid_pb').css({"width":"70%"});
                          $('#addbdid_vcodeRequired').attr('value','1');
                          $('#vcodeImg').attr('src', x.img);
                          $('#vcodeStr').attr('value', x.vcodestr);
                          $('.addbdis_text').html(x.msg);
                          $('#addbdid_msg').html(x.msg);
                          $('#addbdid_submit').removeAttr('disabled');
                          $('#addbdid_ver').css({"display":""});
                      } else if(x.error == 0) {
                          $('#addbdid_msg').html('成功绑定百度账号：' + x.name);
                          $('#addbdid_pb').css({"width":"100%"});
                          $('#addbdid_prog').fadeOut(500);
                      } else {
                          $('#addbdid_msg').html(x.msg);
                      }
                  },
                  error: function(x) {
                      $('#addbdid_prog').fadeOut(500);
                      $('#addbdid_msg').html('操作失败，未知错误。这可能是网络原因所致，请重试绑定#2');
                  }
              });
          });
      });
    function addbdid_getbduss() {

    $(document).ready(function(){

    });
  }
  */
</script>
<div id="addbdid_prog" style="display:block">
  <b><span class="addbdis_text"></span></b><br/><br/>
  <div class="progress" style="display:none;">
    <div class="progress-bar progress-bar-striped active" id="addbdid_pb" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
    </div>
  </div>
</div>
<a name="#newid"></a>
<div class="alert alert-warning" role="alert" id="addbdid_msg">如果您多次尝试绑定失败，可能是异地登录保护造成的原因，不妨试试 <a href="https://bduss.tbsign.cn" target="_blank">手动获取</a> 吧！</div>
<form method="post" id="addbdid_form" onsubmit="return false;">
<div class="input-group">
  <span class="input-group-addon">百度账号</span>
  <input type="text" class="form-control" id="bd_name" placeholder="你的百度账户名，建议填写邮箱" required>
</div>

<br/>

<div class="input-group">
  <span class="input-group-addon">百度密码</span>
  <input type="password" class="form-control" id="bd_pw" placeholder="你的百度账号密码" required>
</div>
<br/>
  <div id="addbdid_ver" style="display: none">
    <div class="alert alert-info">请在下面输入的字符，点击图片更换验证码</div>
	<div id="codeimg"></div>
    <div class="input-group">
      <span class="input-group-addon">验证码</span>
       <input type="text" class="form-control" id="bd_v" placeholder="请输入上图的字符" />
    </div>
    <br/>
    <input type="hidden" id="vcodeStr" name="vcodestr" value=""/>
      <input type="hidden" id="addbdid_vcodeRequired" value="0">
  </div>
  	<div id="security" class="input-group" style="display:none;">
		<td>
			<div class="input-group">
			<span class="input-group-addon">验证码</span>
			<input type="text" id="smscode" value="" class="form-control">
			<span class="input-group-btn">
			<button id="sendcode" class="btn btn-default" type="button">发送验证码</button>
			</span>
			</div>
		</td>
	<button type="button" id="submit2" class="btn btn-primary btn-block">提交</button>
		<pre>提示：60秒内只能发送一次验证码，否则会提示频繁</pre>
	</div>
<input type="submit" id="addbdid_submit" class="btn btn-primary" value="点击绑定">
</form>
<br/><br/>
<div class="panel panel-default">
	<div class="panel-heading" onclick="$('#win_bduss').fadeToggle();"><h3 class="panel-title"><span class="glyphicon glyphicon-chevron-down"></span> 关于提示登陆不成功的解决办法</h3></div>
	<div class="panel-body" id="win_bduss">
	    1.<b>登录不成功主要是因为您尝试登录的帐号开启了异地登陆保护！</b>
	    <br/><br/>2.所以我们试着关闭它，地址:<a href="https://passport.baidu.com/v2/accountsecurity" target="_blank">点击进入百度安全中心</a> （未登录百度请先登录再打开此链接）
	    <br/><br/>3.此时可以看到 <b>登录保护：未开通</b> 然后我们点击后面的开通，然后选择 <b>每次主动登录时需要验证安全中心手机版、短信或邮件验证码，三者任选其一</b> 然后点击确定提示验证，验证后提示设置成功。
        <br/><br/>4.然后返回 <a href="https://passport.baidu.com/v2/accountsecurity" target="_blank">百度安全中心</a> 可以看到 <b>登录保护：登录即启动保护</b> 然后我们点后面的 <b>修改</b> 。
        <br/><br/>5.最后，我们可以看到一个之前看不到的选项 <b>关闭登录保护</b> 选择它确定，直到提示成功后，然后再次在上面自动绑定处尝试进行登录，登陆成功后刷新贴吧列表即可！
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
function getpwd(pwd,time){
	var passwd = pwd+time;
	var rsa = "B3C61EBBA4659C4CE3639287EE871F1F48F7930EA977991C7AFE3CC442FEA49643212E7D570C853F368065CC57A2014666DA8AE7D493FD47D171C0D894EEE3ED7F99F6798B7FFD7B5873227038AD23E3197631A8CB642213B9F27D4901AB0D92BFA27542AE890855396ED92775255C977F5C302F1E7ED4B1E369C12CB6B1822F";
	setMaxDigits(131);
	var key = new RSAKeyPair("10001", "", rsa);
	return encryptedString(key, passwd);
}
function gettime(user,pwd,vcode,vcodestr){
	vcode=vcode||null;
	vcodestr=vcodestr||null;
	$('.addbdis_text').html('正在获取Token，请稍等...');
	var getvcurl="login.php?do=time";
	ajax.get(getvcurl, 'json', function(d) {
		if(d.code ==0){
			login(d.time,user,pwd,vcode,vcodestr);
		}else{
			alert(d.msg);
			$('.addbdis_text').html('');
		}
	});
}
function login(time,user,pwd,vcode,vcodestr){
	$('.addbdis_text').html('正在登录，请稍等...');
	var p = getpwd(pwd, time);
	//alert(p);return;
	var loginurl="login.php?do=login";
	ajax.post(loginurl,"time="+time+"&user="+user+"&pwd="+pwd+"&p="+p+"&vcode="+vcode+"&vcodestr="+vcodestr+"&r="+Math.random(1), 'json', function(d) {
		if(d.code ==0){
			////$('#login').hide();
			$('#addbdid_ver').hide();
			$('#addbdid_form').hide();
			$('#security').hide();
			$('#submit2').hide();
			$('.addbdis_text').html('登录成功！请刷新页面。登录账号：'+decodeURIComponent(d.displayname));
			//showresult(d);
		}else if(d.code ==400023){
			if(d.type == 'phone'){
				$('.addbdis_text').html("请验证密保后登录，密保手机是："+d.phone);
			}else{
				$('.addbdis_text').html("请验证密保后登录，密保邮箱是："+d.email);
			}
			//$('#addbdid_form').hide();
			$('#addbdid_ver').hide();
			$('#bd_v').val("");
			$('#security').show();$('#addbdid_submit').hide();
			$('#security').attr('type',d.type);
			$('#security').attr('lstr',encodeURIComponent(d.lstr));
			$('#security').attr('ltoken',d.ltoken);
		}else if(d.code ==310006 || d.code ==500001 || d.code ==500002){//需要验证码
			$('.addbdis_text').html(d.msg);
			getvc(d.vcodestr);
		}else if(d.code ==230048 || d.code ==400010){
			$('.addbdis_text').html("您输入的账号不存在，请重新输入");
			$('#addbdid_form').attr('do','submit');
			$('#addbdid_ver').hide();
			$('#bd_v').val("");
			$('#bd_name').focus();
			$('#bd_name').val("");
		}else if(d.code ==400011 || d.code ==400015){
			$('.addbdis_text').html("您输入的密码有误，请重新输入");
			$('#addbdid_form').attr('do','submit');
			$('#addbdid_ver').hide();
			$('#bd_v').val("");
			$('#bd_pw').focus();
			$('#bd_pw').val("");
		}else{
			$('.addbdis_text').html(d.msg+" ("+d.code+")");
			$('#addbdid_form').attr('do','submit');
			$('#addbdid_ver').hide();
			//$('#login').show();
		}
	});
	
}
function login2(type,lstr,ltoken,vcode){
	$('.addbdis_text').html('正在登录，请稍等...');
	var loginurl="login.php?do=login2";
	ajax.post(loginurl,"type="+type+"&lstr="+lstr+"&ltoken="+ltoken+"&vcode="+vcode+"&r="+Math.random(1), 'json', function(d) {
		if(d.code ==0){
			$('.addbdis_text').html('登录成功！请刷新页面。登录账号：'+decodeURIComponent(d.displayname));
			//$('#login').hide();
			$('#addbdid_ver').hide();
			$('#addbdid_form').show();
			$('#security').hide();
			$('#submit2').hide();
			//showresult(d);
		}else{
			$('.addbdis_text').html(d.msg+" ("+d.code+")");
			$('#addbdid_ver').hide();
			//$('#login').show();
		}
	});
	
}
function sendcode(type,lstr,ltoken){
	var loginurl="login.php?do=sendcode";
	ajax.post(loginurl,"type="+type+"&lstr="+lstr+"&ltoken="+ltoken+"&r="+Math.random(1), 'json', function(d) {
		if(d.code ==0){
			$('#addbdid_ver').hide();
			$('#smscode').focus();
			alert('验证码发送成功，请查收');
		}else{
			$('#addbdid_ver').hide();
			alert(d.msg);
		}
	});
	
}
function getvc(vcodestr){
	$('#codeimg').attr('vcodestr',vcodestr);
	$('#codeimg').html('<img onclick="this.src=\'login.php?do=getvcpic&vcodestr='+vcodestr+'&r=\'+Math.random();" src="login.php?do=getvcpic&vcodestr='+vcodestr+'&r='+Math.random(1)+'" title="点击刷新">');
	$('#addbdid_form').attr('do','code');
	$('#bd_v').val("");
	$('#addbdid_ver').show();
}/*
function //showresult(arr){
	console.log(arr);
	$('.addbdis_text').html('<div class="alert alert-success">登录成功！'+decodeURIComponent(arr.displayname)+'</div><div class="input-group"><span class="input-group-addon">用户UID</span><input id="uid" value="'+arr.uid+'" class="form-control" /></div><br/><div class="input-group"><span class="input-group-addon">用户名</span><input id="user" value="'+arr.user+'" class="form-control"/></div><br/><div class="input-group"><span class="input-group-addon">BDUSS</span><input id="bduss" value="'+arr.bduss+'" class="form-control"/></div><br/><div class="input-group"><span class="input-group-addon">PTOKEN</span><input id="ptoken" value="'+arr.ptoken+'" class="form-control"/></div><br/><div class="input-group"><span class="input-group-addon">STOKEN</span><input id="stoken" value="'+arr.stoken+'" class="form-control"/></div>');
}*/
function checkvc(user,pwd){
	$('.addbdis_text').html('登录中，请稍候...');
	var getvcurl="login.php?do=checkvc";
	ajax.post(getvcurl, 'user='+user, 'json', function(d) {
		if(d.code ==0){
			gettime(user,pwd);
		}else if(d.code ==1){
			$('.addbdis_text').html('请输入验证码。');
			getvc(d.vcodestr);
		}else{
			$('.addbdis_text').html(d.msg+" ("+d.code+")");
			$('#addbdid_ver').hide();
		}
	});
}
$(document).ready(function(){
	$('#addbdid_form').submit(function(){
		var self=$(this);
		var user=trim($('#bd_name').val()),
			pwd=trim($('#bd_pw').val());
		if(user==''||pwd=='') {
			alert("请确保每项不能为空！");
			return false;
		}
		$('.addbdis_text').show();
		if (self.attr("data-lock") === "true") return;
		else self.attr("data-lock", "true");
		if(self.attr('do') == 'code'){
			var vcode=trim($('#bd_v').val()),
				vcodestr=$('#codeimg').attr('vcodestr');
			gettime(user,pwd,vcode,vcodestr);
		}else{
			checkvc(user,pwd);
		}
		self.attr("data-lock", "false");
	});
	$('#submit2').click(function(){
		var self=$(this);
		var code=trim($('#smscode').val());
		if(code=='') {
			alert("验证码不能为空！");
			return false;
		}
		$('.addbdis_text').show();
		if (self.attr("data-lock") === "true") return;
		else self.attr("data-lock", "true");
		var type=$('#security').attr('type'),
			lstr=$('#security').attr('lstr'),
			ltoken=$('#security').attr('ltoken');
		login2(type,lstr,ltoken,code);
		self.attr("data-lock", "false");
	});
	$('#sendcode').click(function(){
		var self=$(this);
		$('.addbdis_text').show();
		if (self.attr("data-lock") === "true") return;
		else self.attr("data-lock", "true");
		var type=$('#security').attr('type'),
			lstr=$('#security').attr('lstr'),
			ltoken=$('#security').attr('ltoken');
		sendcode(type,lstr,ltoken);
		self.attr("data-lock", "false");
	});
});
</script>
<!-- END PAGE2 -->

<!-- PAGE3: NEWID2 -->
<div class="tab-pane fade" id="newid2" style="display:none">
<form action="setting.php" method="get">
<div class="input-group">
  <input type="hidden" name="mod" value="baiduid">
  <span class="input-group-addon">输入BDUSS</span>
  <input type="text" class="form-control" name="bduss" id="bduss_input">
  <span class="input-group-btn"><input type="submit" class="btn btn-primary" value="点击提交"></span>
</div>
</form>

<br/><br/><b>以下是贴吧账号手动绑定教程：</b><br/><br/>
<div class="panel panel-default">
	<div class="panel-heading" onclick="$('#chrome_bduss').fadeToggle();"><h3 class="panel-title"><span class="glyphicon glyphicon-chevron-down"></span> 点击查看在 Chrome 浏览器下的绑定方法</h3></div>
	<div class="panel-body" id="chrome_bduss" style="display:none">
	    1.使用 Chrome 或 Chromium 内核的浏览器
		<br/><br/>2.打开百度首页 <a href="http://www.baidu.com" target="_blank">http://www.baidu.com/</a>
   	    <br/><br/>3.右键，点击 <b>查看网页信息</b>
		<br/><br/>4.确保已经登录百度，然后点击 <b>显示 Cookie 和网站数据</b>
		<br/><br/>5.如图，依次展开 <b>passport.baidu.com</b> -> <b>Cookie</b> -> <b>BDUSS</b>
		<br/><br/><a href="source/doc/baiduid.png" target="_blank"><img src="source/doc/baiduid.png"></a>
		<br/><br/>6.按下 Ctrl+A 全选，然后复制并输入到上面的表单即可
    <br/><br/>请注意，一旦退出登录，可能导致 BDUSS 失效，因此建议在隐身模式下登录
	</div>
</div>
</div>
<!-- END PAGE3 -->
<!-- PAGE4: NEWID3 -->
<div class="tab-pane fade" id="newid3" style="display:none">
	<div class="panel-body" style="text-align: center;">
		<div class="list-group">
			<div class="list-group-item"><img src="https://m.baidu.com/static/index/plus/plus_logo.png" width="160px"></div>
			<div class="list-group-item list-group-item-info" style="font-weight: bold;" id="load">
				<span id="loginmsg">正在加载</span>
			</div>
			<div class="list-group-item" id="login" style="display:none;">
			<div class="list-group-item" id="qrimg">
			</div>
			<p><button type="button" id="submit" class="btn btn-success btn-block">已完成扫码</button></p>
			</div>
		</div>
	</div>
	<script>
	function getqrcode(){
		var getvcurl='login.php?do=getqrcode&r='+Math.random(1);
		$.get(getvcurl, function(d) {
			if(d.code ==0){
				$('#qrimg').attr('sign',d.sign);
				$('#qrimg').html('<img onclick="getqrcode()" src="https://'+d.imgurl+'" title="点击刷新">');
				$('#login').show();
				$('#loginmsg').html('请使用<a href="http://xbox.m.baidu.com/mo/" target="_blank" rel="noreferrer">手机百度app</a>扫描登录');
			}else{
				alert(d.msg);
			}
		}, 'json');
	}
	function qrlogin(){
		var sign=$('#qrimg').attr('sign');
		if(sign=='')return;
		var loginurl="login.php?do=qrlogin";
		$('#submit').html('Loading...');
		$.ajax({
			type: "POST",
			url: loginurl,
			async: true,
			dataType: 'json',
			timeout: 2000,
			data: "sign="+sign+"&r="+Math.random(1),
			cache:false,
			success: function(data,status) {
				$('#submit').html('已完成扫码');
				if(data.code ==0){
					$('#login').hide();
					$('#load').html('登录成功！请刷新页面。登录账号：'+decodeURIComponent(data.displayname));
					//showresult(data);
				}else{
					$('#load').html(data.msg+" ("+data.code+")");
					alert('未检测到登录状态');
					getqrcode();
				}
			},
			error: function(error) {
				$('#submit').html('已完成扫码');
				alert('未检测到登录状态');
				getqrcode();
			}
		});
		
	}
	function qrcodel(){
	$(document).ready(function(){
		getqrcode();
		$('#submit').click(function(){
			qrlogin();
		});
	});}
	</script>
</div>
<!-- END PAGE4 -->
<!-- PAGE5: NEWID4 -->
<div class="tab-pane fade" id="newid4" style="display:none">
	<script>
	function setIframeHeight(iframe) {
		if (iframe) {
			var iframeWin = iframe.contentWindow || iframe.contentDocument.parentWindow;
			if (iframeWin.document.body) {
			iframe.height = iframeWin.document.documentElement.scrollHeight || iframeWin.document.body.scrollHeight;
			}
		}
	};
	window.onload = function () {
		setIframeHeight(document.getElementById('iFrame'));
	};
	</script>
	<iframe id="iFrame" src="./login3.php" width="100%" frameborder="0" scrolling="no" onmouseout="setIframeHeight(document.getElementById('iFrame'));" onmousedown="setIframeHeight(document.getElementById('iFrame'));" onclick="setIframeHeight(document.getElementById('iFrame'));"></iframe>
</div>
<!-- END PAGE5 -->
<?php doAction('baiduid'); ?>
<br/><br/><br/><br/><br/><br/><?php echo SYSTEM_FN ?> V<?php echo SYSTEM_VER  . ' ' . SYSTEM_VER_NOTE ?> // 作者: <a href="https://kenvix.com" target="_blank">Kenvix</a> &amp; <a href="http://www.mokeyjay.com/" target="_blank">mokeyjay</a> &amp; <a href="http://fyy1999.lofter.com/" target="_blank">FYY</a> &amp; <a href="http://www.stusgame.com/" target="_blank">StusGame</a>
