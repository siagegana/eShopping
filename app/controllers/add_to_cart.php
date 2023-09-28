<?php

class Add_to_cart extends Controller
{
	private $redirect_to = ""; // This variable stores the URL to redirect to after cart operations.

	public function index($id = '')
	{
		$this->set_redirect(); // Set the redirect URL before performing any cart operations.

		$id = esc($id);
		$DB = Database::newInstance(); // Create a new instance of the Database class.

		$ROWS = $DB->read("select * from products where id = :id limit 1", ["id"=>$id]); // Fetch product information from the database.

		if($ROWS)
		{
			$ROW = $ROWS[0]; // Get the first row from the fetched result.
			if(isset($_SESSION['CART']))
			{
				$ids = array_column($_SESSION['CART'], "id"); // Create an array of product IDs in the cart.

				if(in_array($ROW->id, $ids))
				{
					$key = array_search($ROW->id, $ids); // Find the key of the product in the cart.
					$_SESSION['CART'][$key]['qty']++; // Increment the quantity of the existing product in the cart.
				}
				else
				{
					$arr['id'] = $ROW->id;
					$arr['qty'] = 1;

					$_SESSION['CART'][] = $arr; // Add a new product with quantity 1 to the cart.
				}
			}
			else
			{
				$arr['id'] = $ROW->id;
				$arr['qty'] = 1;

				$_SESSION['CART'][] = $arr; // Add the product to the cart if the cart is empty.
			}
			
		}
		$this->redirect(); // Redirect to the specified URL.
	}

	// Similar functions for adding, subtracting, and removing quantities from the cart.

	private function redirect()
	{
		header("Location: ". $this->redirect_to); // Perform the actual redirection.
		die; // Terminate the script execution.
	}

	private function set_redirect()
	{
		if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") 
		{
			$this->redirect_to = $_SERVER['HTTP_REFERER']; // Set the redirect URL to the previous page.
		}
		else
		{
			$this->redirect_to = ROOT . "shop"; // If no referrer, redirect to the "shop" page.
		}
	}
}
