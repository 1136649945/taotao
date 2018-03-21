<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>layui</title>
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="/Public/Admin/plugins/layui/css/layui.css"
	media="all" />
</head>
<body>
	<fieldset class="layui-elem-field layui-field-title"
		style="margin-top: 20px;">
		<legend>人员管理</legend>
	</fieldset>
	<form class="layui-form" action="">
		<input type="hidden" name="userid" value=<?php echo ($user["userid"]); ?> />
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-inline">
				<input type="text" name="username" lay-verify="title"
					autocomplete="off" placeholder="姓名" class="layui-input"
					value=<?php echo ($user["username"]); ?>>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">用户编码</label>
			<div class="layui-input-inline">
				<input type="text" name="usercode" autocomplete="off"
					placeholder="用户编码" class="layui-input" value=<?php echo ($user["usercode"]); ?>>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-inline">
				<input type="text" name="text" value=<?php echo ($user["password"]); ?> disabled
					placeholder="请输入密码" class="layui-input">
			</div>
			<div class="layui-form-mid layui-word-aux">请填写6到12位密码</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">手机号</label>
				<div class="layui-input-inline">
					<input type="tel" name="phone" lay-verify="phone"
						autocomplete="off" class="layui-input" value=<?php echo ($user["phone"]); ?>>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">邮箱</label>
				<div class="layui-input-inline">
					<input type="text" name="email" lay-verify="email"
						autocomplete="off" class="layui-input" value=<?php echo ($user["email"]); ?>>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">岗位</label>
				<div class="layui-input-inline">
					<input type="text" name="role" autocomplete="off"
						class="layui-input" value=<?php echo ($user["role"]); ?>>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">岗位</label>
				<div class="layui-input-inline">
					<input type="text" name="create_time" autocomplete="off"
						class="layui-input" value=<?php echo ($user["create_time"]); ?>>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
			</div>
		</div>
	</form>
	<script src="/Public/static/jquery.js" charset="utf-8"></script>
	<script src="/Public/Admin/layui.js" charset="utf-8"></script>
	<script>
		layui.use([ 'form', 'layedit', 'laydate' ],
				function() {
					var form = layui.form(), layer = layui.layer, layedit = layui.layedit, laydate = layui.laydate;

					//自定义验证规则
					form.verify({
						title : function(value) {
							if (value.length < 5) {
								return '标题至少得5个字符啊';
							}
						},
						pass : [ /(.+){6,12}$/, '密码必须6到12位' ],
						content : function(value) {
							layedit.sync(editIndex);
						}
					});

					//监听指定开关
					form.on('switch(switchTest)', function(data) {
						layer.msg('开关checked：'
								+ (this.checked ? 'true' : 'false'), {
							offset : '6px'
						});
						layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF',
								data.othis)
					});

					//监听提交
					form.on('submit(demo1)', function(data) {
						layer.alert(JSON.stringify(data.field), {
							title : '最终的提交信息'
						})
						return false;
					});

			});
	</script>

</body>
</html>