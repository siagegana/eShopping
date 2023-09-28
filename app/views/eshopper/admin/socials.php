<?php $this->view("admin/header", $data); ?>

<?php $this->view("admin/sidebar", $data); ?>

<style type="text/css">

	.details{
		background-color: #eee;
		box-shadow: 0px 0px 10px #aaa;
		width: 100%;
		position: absolute;
		min-height: 100px;
		left: 0px;
		padding: 10px;
		z-index: 2;
	}

</style>
<form method="post">
	<table class="table table-striped table-advance table-hover">
		<thead>
			<tr>
				<th>Setting</th>
				<th>Value</th>
			</tr>
		</thead>

		<tbody>
			<?php if(isset($settings) && is_array($settings)):?>
				<?php foreach($settings as $setting):?>
					<tr>
						<td><?=ucwords(str_replace("_", " ", $setting->setting))?></td>
						<td><input name="<?=$setting->setting?>" class="form-control" type="text" value="<?=$setting->value?>"/></td>
										
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>

	</table>
	<input type="submit" value="Save Settings" class="btn btn-warning pull-right" name="">
		
	</ins>
</form>
<?php $this->view("admin/footer", $data); ?>