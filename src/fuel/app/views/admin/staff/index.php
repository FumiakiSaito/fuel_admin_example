<?php if ($staffs): ?>
<h3><i class="glyphicon glyphicon-user"></i>&nbsp;<?php echo $display_title ?></h3>
<div class='control-label'><?php //echo $pagination;?></div>
<table class="table table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>社員番号</th>
			<th>名前</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($staffs as $staff): ?>
			<tr>
				<td><?php echo $staff->id; ?></td>
				<td><?php echo $staff->num; ?></td>
				<td><?php echo $staff->name ?></td>
				<td>
					<?php echo Html::anchor('admin/staff/view/'.   $staff->id, '参照'); ?> |
					<?php echo Html::anchor('admin/staff/edit/'.   $staff->id, '編集'); ?> |
					<?php echo Html::anchor('admin/staff/delete/'. $staff->id, '削除', array('onclick' => "return confirm('本当に削除しますか？')")); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p>社員データが存在しません</p>
<?php endif; ?>
<p>
	<?php echo Html::anchor('admin/staff/add', '新規追加', array('class' => 'btn btn-success')); ?>
</p>
