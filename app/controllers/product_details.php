<?php

class Product_details extends Controller
{
	public function index($slug)
	{
		$slug = esc($slug);

		$user = $this->load_model('User');
		$user_data = $user->check_login();

		if(is_object($user_data))
		{
			$data['user_data'] = $user_data;
		}

		$DB = Database::newInstance();
		$ROW = $DB->read("select * from products where slug = :slug", ['slug'=>$slug]);

		$data ['page_title'] = "Product Details";
		$data ['ROW'] = is_array($ROW) ? $ROW[0] : false;

		$this->view("product-details", $data);
	}

}