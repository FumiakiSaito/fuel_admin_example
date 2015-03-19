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
		<tr>
			<td><?php echo '1'; ?></td>
			<td><?php echo '0001'; ?></td>
			<td><?php echo 'taro'; ?></td>
			<td>
				<?php echo Html::anchor('admin/staff/view/1', '参照'); ?> |
				<?php echo Html::anchor('admin/staff/edit/1', '編集'); ?> |
				<?php echo Html::anchor('admin/staff/delete/1', '削除', array('onclick' => "return confirm('本当に削除しますか？')")); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo '1'; ?></td>
			<td><?php echo '0002'; ?></td>
			<td><?php echo 'hanako'; ?></td>
			<td>
				<?php echo Html::anchor('admin/staff/view/1', '参照'); ?> |
				<?php echo Html::anchor('admin/staff/edit/1', '編集'); ?> |
				<?php echo Html::anchor('admin/staff/delete/1', '削除', array('onclick' => "return confirm('本当に削除しますか？')")); ?>
			</td>
		</tr>
	</tbody>
</table>
<?php else: ?>
<p>社員データが存在しません</p>
<?php endif; ?>
<p>
	<?php echo Html::anchor('admin/staff/add', '新規追加', array('class' => 'btn btn-success')); ?>
</p>
