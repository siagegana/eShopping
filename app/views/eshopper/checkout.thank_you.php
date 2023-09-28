<?php $this->view("header", $data); ?>
<div style="text-align:center;">
	<h1>Thank you for shopping with us!</h1>
	<h4>Your order was successful</h4>
	<br><br>
	<div>
		What would you like to do next?
	</div><br>
	<a href="<?=ROOT?>shop">
		<input type="button" class="btn-warning" value="Continue shopping" name="">
	</a>
	<a href="<?=ROOT?>profile">
		<input type="button" class="btn-warning" value="View your orders" name="">
	<a/>
	<br><br>
</div>
	
<?php $this->view("footer", $data); ?>
