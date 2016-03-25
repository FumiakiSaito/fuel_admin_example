<h3><i class="glyphicon glyphicon-user"></i>&nbsp;<?php echo $display_title ?></h3>
<table class="table table-striped">
	<tbody>
		<tr>
			<th>社員番号</th>
			<td> <?php echo $input['num']; ?></td>
		</tr>
		<tr>
			<th>名前</th>
			<td> <?php echo $input['name']; ?></td>
		</tr>
		<tr>
			<th>性別</th>
			<td>
				<?php if ($input['sex'] == SEX_MALE) : ?>
					男性
				<?php else: ?>
					女性
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th>所属部署</th>
			<td>
				<?php
					foreach ($divisions as $division_id => $division) {
						if ($input['division_id'] == $division_id) {
							echo $division;
						}
					}
				?>
			</td>
		</tr>
	</tbody>
</table>
<div class="box">
<?php
echo Form::open(array('action' => 'admin/staff/edit/'. $input['id'], 'name'=> 'add', 'method'=>'post', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
echo Form::submit('submit', '修正', array('class' => 'btn btn-default'));
echo Form::hidden('num', $input['num']);
echo Form::hidden('name', $input['name']);
echo Form::hidden('sex', $input['sex']);
echo Form::hidden('division_id', $input['division_id']);
echo Form::close();
?>
</div>
<div class="box">
<?php
echo Form::open(array('action' => 'admin/staff/send', 'name'=> 'add', 'method'=>'post', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
echo Form::submit('submit', '更新', array('class' => 'btn btn-default'));
echo Form::csrf();
echo Form::hidden('num', $input['num'], array('id' => 'num'));
echo Form::hidden('name', $input['name'], array('id' => 'name'));
echo Form::hidden('sex', $input['sex'], array('id' => 'sex'));
echo Form::hidden('division_id', $input['division_id'], array('id' => 'division_id'));
echo Form::hidden('id', $input['id'], array('id' => 'id'));
echo Form::close();
?>
</div>