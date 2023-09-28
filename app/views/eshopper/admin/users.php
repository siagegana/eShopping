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

<table class="table table-striped table-advance table-hover">
	<thead>
		<tr>
			<th>User ID</th>
			<th>Name</th>
			<th>E-mail</th>
			<th>Date created</th>
			<th>Orders count</th>
			<th>...</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($users as $user):?>
			<tr style="position: relative;">
				<td><?=$user->id?></td>
				<td><a href="<?=ROOT?>profile/<?=$user->url_address?>"><?=$user->name?></a></td>
				<td><?=$user->email?></td>
				<td><?=$user->date?></td>
				
				<td><?=$user->orders_count?></td>
				
			</tr>
		<?php endforeach; ?>
	</tbody>

</table>

<?php $this->view("admin/footer", $data); ?>