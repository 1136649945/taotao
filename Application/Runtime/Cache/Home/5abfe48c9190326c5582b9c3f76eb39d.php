<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>taotao</title>
</head>
<body>
	<span>
		<?php if($core <= 60 ): ?>加油，差点就及格了再接在励
		<?php elseif($core <= 80 ): ?> 
			加油，不错哟
		<?php else: ?>
			  good<?php endif; ?>
	</span>
	<hr/>
	<table>
		<thead>
			<tr>
				<th>序号</th>
				<th>序号</th>
				<th>名称</th>
				<th>标题</th>
			</tr>
		</thead>
		<tbody>
			<?php if(is_array($list_grid)): $i = 0; $__LIST__ = $list_grid;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				  <td><?php echo ($key); ?></td>
				  <td><?php echo ($i); ?></td>
				  <td><?php echo ($vo["name"]); ?></td>
				  <td><?php echo ($vo["title"]); ?></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
	<hr/>
	<table>
		<thead>
			<tr>
				<th>序号</th>
				<th>序号</th>
				<th>名称</th>
				<th>标题</th>
			</tr>
		</thead>
		<tbody>
			<?php if(is_array($list_grid)): foreach($list_grid as $key=>$vo): ?><tr>
				  <td><?php echo ($key); ?></td>
				  <td><?php echo ($key+1); ?></td>
				  <td><?php echo ($vo["name"]); ?></td>
				  <td><?php echo ($vo["title"]); ?></td>
				</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
	<a src="/taotao/Public/static"></a>
</body>
</html>