<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<title>Table</title>
<link rel="stylesheet" href="/Public/Admin/plugins/layui/css/layui.css"
	media="all" />
<link rel="stylesheet" href="/Public/Admin/css/global.css" media="all">
<link rel="stylesheet" href="/Public/Admin/css/table.css" />
</head>

<body>
	<div class="admin-main page">
		<blockquote class="layui-elem-quote"
			style="margin-bottom: 0px; padding: 0px; height: 38px;">
			<div class="layui-form-item">
				<div class="layui-inline">
					<label class="layui-form-label">姓名</label>
					<div class="layui-input-inline">
						<input type="text" name="username" autocomplete="off"
							class="layui-input">
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">用户编码</label>
					<div class="layui-input-inline">
						<input type="text" name="usercode" autocomplete="off"
							class="layui-input">
					</div>
				</div>
				<a href="javascript:query()" class="layui-btn layui-btn-small">
					<i class="layui-icon">&#xe615;</i>查询
				</a> 
				<a href="<?php echo U('add_edit_user');?>" class="layui-btn layui-btn-small">
					<i class="layui-icon">&#xe608;</i> 添加信息
				</a>
			</div>
		</blockquote>
		<fieldset class="layui-elem-field">
			<legend>数据列表</legend>

			<div class="layui-field-box layui-form">
				<table class="layui-table">
					<colgroup>
						<col width="80">
						<col width="150">
						<col width="150">
						<col width="150">
						<col width="150">
						<col>
					</colgroup>
					<thead>
						<tr>
							<th>操作</th>
							<th>ID</th>
							<th>姓名</th>
							<th>用户编码</th>
							<th>角色</th>
							<th>创建时间</th>
						</tr>
					</thead>
					<tbody id="tbody">

					</tbody>
				</table>
			</div>
		</fieldset>
		<div class="admin-table-page">
			<div id="paged" class="page"></div>
		</div>
	</div>
	<script src="/Public/static/jquery.js" charset="utf-8"></script>
	<script src="/Public/Admin/layui.js" charset="utf-8"></script>
	<script>
		query(0);
		function query(p) {
			layui.use([ 'laypage', 'layer' ], function() {
				var laypage = layui.laypage, layer = layui.layer;
				var data = queryUser(p);
				laypage.render({
					elem : 'paged',
					limit : 20,
					count : data['count'],
					layout : [ 'count', 'prev', 'page', 'next' ],
					jump : function(obj) {
						queryUser(obj['curr']-1);
					}
				});
			});
		}

		function queryUser(p) {
			var dataTemp = null;
			$.ajax({
						url : "<?php echo U('queryUser');?>",
						async : false,
						data : {
							"username" : $("input[name='username']").val(),
							"usercode" : $("input[name='usercode']").val(),
							"p" : p
						},
						type : "POST",
						success : function(data) {
							dataTemp = JSON.parse(data);
							if (dataTemp) {
								var dataArr = dataTemp['list'];
								var html = "";
								for (var i = 0; i < dataArr.length; i++) {
									html += "<tr><td><a href='<?php echo U('add_edit_user');?>?userid="
											+ dataArr[i]['userid']
											+ "' title='编辑'><i class='layui-icon'>&#xe642;</i></a>"
											+ "<a href='javascript:deleteuser("
											+ dataArr[i]['userid']
											+ ")' title='删除'><i class='layui-icon'>&#xe640;</i></a></td>"
											+ "<td>"
											+ dataArr[i]['userid']
											+ "</td><td>"
											+ dataArr[i]['username']
											+ "</td>"
											+ "<td>"
											+ dataArr[i]['usercode']
											+ "</td><td>"
											+ dataArr[i]['role']
											+ "</td><td>"
											+ dataArr[i]['create_time']
											+ "</td></tr>";
								}
								$("#tbody").html(html);
							}
						},
						error : function(data) {
							console.log(data['responseText']);
						}
					});
			return dataTemp;
		}
		function deleteuser(userid) {
			if (userid) {
				console.log(userid);
			}
		}
	</script>
</body>

</html>