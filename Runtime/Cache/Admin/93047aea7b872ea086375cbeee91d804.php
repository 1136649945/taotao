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
					<label class="layui-form-label">分类名称</label>
					<div class="layui-input-inline">
						<input type="text" name="catname" autocomplete="off"
							class="layui-input">
					</div>
				</div>

				<a href="javascript:query()" class="layui-btn layui-btn-small">
					<i class="layui-icon">&#xe615;</i>查询
				</a> <a href="<?php echo U('add_edit_user');?>" class="layui-btn layui-btn-small">
					<i class="layui-icon">&#xe608;</i> 添加文章分类
				</a>
			</div>
		</blockquote>
		<fieldset class="layui-elem-field layui-field-title"
			style="margin-top: 50px;">
			<legend>面板嵌套</legend>
		</fieldset>
		<div class="layui-collapse" lay-accordion="">
			<div class="layui-colla-item">
				<h2 class="layui-colla-title">文豪</h2>
				<div class="layui-colla-content layui-show">

					<div class="layui-collapse" lay-accordion="">
						<div class="layui-colla-item">
							<h2 class="layui-colla-title">唐代</h2>
							<div class="layui-colla-content layui-show">

								<div class="layui-collapse" lay-accordion="">
									<div class="layui-colla-item">
										<h2 class="layui-colla-title">杜甫</h2>
										<div class="layui-colla-content layui-show">伟大的诗人</div>
									</div>
									<div class="layui-colla-item">
										<h2 class="layui-colla-title">李白</h2>
										<div class="layui-colla-content">
											<p>据说是韩国人</p>
										</div>
									</div>
									<div class="layui-colla-item">
										<h2 class="layui-colla-title">王勃</h2>
										<div class="layui-colla-content">
											<p>千古绝唱《滕王阁序》</p>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="layui-colla-item">
							<h2 class="layui-colla-title">宋代</h2>
							<div class="layui-colla-content">
								<p>比如苏轼、李清照</p>
							</div>
						</div>
						<div class="layui-colla-item">
							<h2 class="layui-colla-title">当代</h2>
							<div class="layui-colla-content">
								<p>比如贤心</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="layui-colla-item">
				<h2 class="layui-colla-title">科学家</h2>
				<div class="layui-colla-content">
					<p>伟大的科学家</p>
				</div>
			</div>
			<div class="layui-colla-item">
				<h2 class="layui-colla-title">艺术家</h2>
				<div class="layui-colla-content">
					<p>浑身散发着艺术细胞</p>
				</div>
			</div>
		</div>
		<div class="admin-table-page">
			<div id="paged" class="page"></div>
		</div>
	</div>
	<script src="/Public/static/jquery.js" charset="utf-8"></script>
	<script src="/Public/Admin/layui.js" charset="utf-8"></script>
	<script>
		layui.use(['element', 'layer'], function(){
		  var element = layui.element;
		  var layer = layui.layer;
		  
		  //监听折叠
		  element.on('collapse(test)', function(data){
		    layer.msg('展开状态：'+ data.show);
		  });
		});
	</script>
	<!-- <script>
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
						queryUser(obj['curr'] - 1);
					}
				});
			});
		}

		function queryUser(p) {
			var dataTemp = null;
			$.ajax({url : "<?php echo U('queryUser');?>",
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
	</script> -->
</body>

</html>