<?php

class Home extends Controller
{
	public function index()
	{
		//check if it's a search
		$search = false;
		if(isset($_GET['find']))
		{
			$find = addslashes($_GET['find']);
			$search = true;
		}

		$user = $this->load_model('User');
		$image_class = $this->load_model('Image');
		$user_data = $user->check_login();

		if(is_object($user_data))
		{
			$data['user_data'] = $user_data;
		}

		$DB = Database::newInstance();
		if($search)
		{
			$arr['description'] = "%" . $find . "%";
			$ROWS = $DB->read("select * from products where description like :description", $arr); // % wildcard
		}
		else
		{
			$ROWS = $DB->read("select * from products");

		}
		$data ['page_title'] = "Home";
		if($ROWS)
		{
			foreach($ROWS as $key => $row)
			{
				$ROWS[$key]->image = $image_class->get_thumb_post($ROWS[$key]->image);
			}
		}

		//get all categories
		$category = $this->load_model('category');
		$data['categories'] = $category->get_all();

		$data ['ROWS'] = $ROWS;
		$data['show_search'] = true;

		$this->view("home", $data);
	}

}