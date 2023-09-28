<?php

class Cart extends Controller
{
	public function index()
	{
		$user = $this->load_model('User');
		$image_class = $this->load_model('Image');
		$user_data = $user->check_login();

		if(is_object($user_data))
		{
			$data['user_data'] = $user_data;
		}

		$DB = Database::newInstance();
		$ROWS = false;
		$prod_ids = array();
		
		if(isset($_SESSION['CART']))
		{
			$prod_ids = array_column($_SESSION['CART'], 'id');
			$str_ids = "'" . implode("','", $prod_ids) . "'";

			$ROWS = $DB->read("select * from products where id in ($str_ids)");

		}

	

		if(is_array($ROWS))
		{
			foreach($ROWS as $key => $row)
			{
				foreach ($_SESSION['CART'] as $item) 
				{
					// code...
				
					if($row->id == $item['id'])
					{
						$ROWS[$key]->cart_qty = $item['qty'];
						break;
					}
				}
			}
		}
		

		$data ['page_title'] = "Cart";
		$data['sub_total'] = 0;



		if($ROWS)
		{
			foreach($ROWS as $key => $row)
			{
				$ROWS[$key]->image = $image_class->get_thumb_post($ROWS[$key]->image);
				$mytotal =  $row->price * $row->cart_qty;
				$data['sub_total'] += $mytotal;

			}
		}
		if(is_array($ROWS))
		{
			rsort($ROWS);
		}
		$data['ROWS'] = $ROWS;

		$this->view("cart", $data);
	}

}