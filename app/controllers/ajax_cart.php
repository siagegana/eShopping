<?php

class Ajax_cart extends Controller
{
	public function index()
	{
		// This function is empty, likely meant for the default behavior of the class.
		// Additional functionality can be added here if needed.
	}

	public function delete_item($data = "")
	{
		$obj = json_decode($data); // Decode the JSON data received.
		$id = esc($obj->id); // Sanitize the received item ID.

		$id = esc($id); // Re-sanitize the ID (redundant operation).
		if(isset($_SESSION['CART']))
		{
			foreach($_SESSION['CART'] as $key => $item)
			{
				if($item['id'] == $id)
				{
					unset($_SESSION['CART'][$key]); // Remove the item from the cart.
					$_SESSION['CART'] = array_values($_SESSION['CART']); // Re-index the cart array.
					break;
				}
			}
		}

		$obj->data_type = "delete_item"; // Add a data type to the object for identification.
		echo json_encode($obj); // Send the modified object back as JSON.
	}
	
	public function edit_quantity($data = "")
	{
		$obj = json_decode($data); // Decode the JSON data received.

		$quantity = esc($obj->quantity); // Sanitize the received quantity.
		$id = esc($obj->id); // Sanitize the received item ID.

		if(isset($_SESSION['CART']))
		{
			foreach($_SESSION['CART'] as $key => $item)
			{
				if($item['id'] == $id)
				{
					$_SESSION['CART'][$key]['qty'] = (int)$quantity; // Update the quantity of the item in the cart.
					break;
				}
			}
		}

		$obj->data_type = "edit_quantity"; // Add a data type to the object for identification.
		echo json_encode($obj); // Send the modified object back as JSON.
	}
}
