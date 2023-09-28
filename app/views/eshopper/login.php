<?php $this->view("header", $data); ?>

<section id="form" style="margin-top: 6px;" ><!--form-->
	<div class="container">
		<div class="row" style="text-align: center;">

			<span style="font-size: 16px;color: red;">			
				<?php error_message() ?>
			</span>

			<div class="col-sm-4" style="float:none; display: inline-block;">
				<div class="login-form"><!--login form-->
					<h2>Login to your account</h2>
					<form method="post">
						
						<input type="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '';?>" name="email" placeholder="Email Address" />
						<input type="password" name="password" placeholder="Password" />
						<span>
							<input type="checkbox" class="checkbox"> 
							Keep me signed in
						</span>
						<button type="submit" class="btn btn-default">Login</button>
					</form>
					<br>
					<a href="<?=ROOT?>signup">Don't have an account? Signup here </a>
				</div><!--/login form-->
			</div>
		</div>
	</div>
</section><!--/form-->


<?php $this->view("footer", $data); ?>
