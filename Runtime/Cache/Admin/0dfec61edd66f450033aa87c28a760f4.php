<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html>

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>登录</title>
<link rel="stylesheet" href="/Public/Admin/css/layui.css" media="all" />
<link rel="stylesheet" href="/Public/Admin/css/login.css" />
</head>

<body class="beg-login-bg">
	<div class="beg-login-box">
		<header>
			<h1>后台登录</h1>
		</header>
		<div class="beg-login-main">
			<form action="<?php echo U('login');?>" class="layui-form" method="post">
				<div class="layui-form-item">
					<label class="beg-login-icon"> <i class="layui-icon">&#xe612;</i>
					</label> <input type="text" name="username" lay-verify="username"
						autocomplete="off" placeholder="这里输入登录名" class="layui-input">
				</div>
				<div class="layui-form-item">
					<label class="beg-login-icon"> <i class="layui-icon">&#xe642;</i></label>
					<input type="password" name="password" lay-verify="password"
						autocomplete="off" placeholder="这里输入密码" class="layui-input">
				</div>
				<div class="layui-form-item">
					<div class="layui-inline">
						<div class="layui-input-inline" style="width: 120px;">
							<label class="beg-login-icon"><i class="layui-icon">&#xe6b2;</i></label>
							<input type="text" name="verify" placeholder="验证码"
								autocomplete="off" lay-verify="verify" class="layui-input">
						</div>
						<div class="layui-input-inline" style="width: 100px;">
							<img id="verify" src="/admin.php/Login/verify"
								style="height: 37px; width: 120px;" />
						</div>
					</div>
				</div>
				<span class="check-tips" style="color: red;"></span>
				<div class="beg-clear"></div>
				<div class="beg-pull-right">
					<button class="layui-btn layui-btn-primary" lay-submit
						lay-filter="login">
						<i class="layui-icon">&#xe650;</i> 登录
					</button>
				</div>
			</form>
		</div>
		
	</div>
	<script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="/Public/Admin/plugins/layui/layui.js"></script>
	<script>
		var verify = document.getElementById("verify");
	    verify.src = verify.src+'?'+Math.random();
		layui.use([ 'layer', 'form' ], function() {
			var layer = layui.layer, $ = layui.jquery, form = layui.form();
			form.verify({
				username : function(value, item) { //value：表单的值、item：表单的DOM对象
					if (value == null || value.length == 0) {
						return '用户名不能为空';
					}
				},
				password : function(value, item) { //value：表单的值、item：表单的DOM对象
					if (value == null || value.length == 0) {
						return '密码不能为空';
					}
				},
				verify : function(value, item) { //value：表单的值、item：表单的DOM对象
					if (value == null || value.length == 0) {
						return '验证码不能为空';
					}
				}
			});
		});
	</script>
</body>

</html>