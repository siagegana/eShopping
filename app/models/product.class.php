<?php

class Product
{
	function create($DATA, $FILES, $image_class = null)
	{

		$_SESSION['error'] = "";
		$DB = Database::newInstance();

		$arr['description'] = ucwords($DATA->description);
		$arr['quantity'] = ucwords($DATA->quantity);
		$arr['category'] = ucwords($DATA->category);
		$arr['price'] = ucwords($DATA->price);
		$arr['date'] = date("Y-m-d H:i:s");
		$arr['user_url'] = $_SESSION['user_url'];
		$arr['slug'] = $this->str_to_url($DATA->description);

		if(!preg_match("/^[a-zA-Z 0-9._\-,]+$/", trim($arr['description']))) {
			$_SESSION['error'] .= "Please enter a valid description<br>";
		}
		if(!is_numeric($arr['quantity'])) {
			$_SESSION['error'] .= "Please enter a valid quantity<br>";
		}
		if(!is_numeric($arr['category'])) {
			$_SESSION['error'] .= "Please enter a valid category for this item<br>";
		}

		if(!is_numeric($arr['price'])) {
			$_SESSION['error'] .= "Please enter a valid price<br>";
		}

		//Make sure slug is unique
		$slug_arr['slug'] = $arr['slug'];
		$query = "select slug from products where slug = :slug limit 1";
		$check = $DB->read($query, $slug_arr);
			
		if ($check) {
			$arr['slug'] .= "=".rand(0,99999);
		}

		//Check for FILES
		$arr['image'] = "";
		$arr['image2'] = ""; 
		$arr['image3'] = ""; 
		$arr['image4'] = "";
		
		$allowed[] = "image/jpeg";
		$size = 10;
		$size = ($size * 1024 * 1024);
		$folder = "uploads/";

		if(!file_exists($folder))
		{
			mkdir($folder, 0777, true);
		}

		foreach ($FILES as $key => $img_row) {
			
			if($img_row['error'] == 0 && in_array($img_row['type'], $allowed))
			{
				if($img_row['size'] < $size)
				{
					$destination = $folder . $image_class->generate_filename(60). ".jpg";
					move_uploaded_file($img_row['tmp_name'] , $destination);
					$arr[$key] = $destination;
					$image_class->resize_image($destination,$destination,1500,1500);

				}else
				{
					$_SESSION['error'] .= $key . " bigger than required size<br>";
				}
			}
		}
		
		if(!isset($_SESSION['error']) || $_SESSION['error'] == "") {
			$query = "INSERT INTO products (description,quantity,category, price,date,user_url,image,image2,image3,image4,slug) VALUES (:description,:quantity,:category,:price,:date,:user_url,:image,:image2,:image3,:image4,:slug)";
			$check = $DB->write($query, $arr);
			
			if ($check) {
				//unset($_SESSION['error']);
				return true;
			}
		}

		return false;
	}

	function delete($id)
	{
		// Code for deleting a product
		$DB = Database::newInstance();
		$id = (int)$id;
		$query = "delete from products where id = '$id' limit 1";
		$DB->write($query);
	}

	function edit($data, $FILES, $image_class = null)
	{
		// Code for editing a description
		$arr['id'] = $data->id;		
		$arr['description'] = $data->description;
		$arr['quantity'] = $data->quantity;
		$arr['category'] = $data->category;
		$arr['price'] = $data->price;
		$images_string = "";

		//check which images were added

		if(!preg_match("/^[a-zA-Z 0-9._\-,]+$/", trim($arr['description'])))
		{
			$_SESSION['error'] .= "Please enter a valid description<br>";
		}

		if(!is_numeric($arr['quantity'])) 
		{
			$_SESSION['error'] .= "Please enter a valid quantity<br>";
		}

		if(!is_numeric($arr['category'])) 
		{
			$_SESSION['error'] .= "Please enter a valid category<br>";
		}

		if(!is_numeric($arr['price'])) {
			$_SESSION['error'] .= "Please enter a valid price<br>";
		}

		$allowed[] = "image/jpeg";
		$size = 10;
		$size = ($size * 1024 * 1024);
		$folder = "uploads/";

		if(!file_exists($folder))
		{
			mkdir($folder, 0777, true);
		}

		foreach ($FILES as $key => $img_row) {
			
			if($img_row['error'] == 0 && in_array($img_row['type'], $allowed))
			{
				if($img_row['size'] < $size)
				{
					$destination = $folder . $image_class->generate_filename(60). ".jpg";
					move_uploaded_file($img_row['tmp_name'] , $destination);
					$arr[$key] = $destination;
					$image_class->resize_image($destination,$destination,1500,1500);

					$images_string .= ",".$key." = :".$key;

				}else
				{
					$_SESSION['error'] .= $key . " is bigger than required size<br>";
				}
			}
		}

		if(!isset($_SESSION['error']) || $_SESSION['error'] == "") 
		{
			$DB = Database::newInstance();
			$query = "update products set description = :description,category = :category,price = :price,quantity = :quantity $images_string where id = :id limit 1";
			$check = $DB->write($query, $arr);
	    
		    if ($check) 
		    {
		        return true;
		    }
		    	return false;	
			}
		}

	function get_all()
	{
		$DB = Database::newInstance();
		return $DB->read("SELECT * FROM products order by id desc");
	}

	function single($id)
	{
		$id = (int)$id;
	    $DB = Database::newInstance();
	    $data = $DB->read("SELECT * FROM categories where id = '$id' limit 1");
	    if ($data && is_array($data) && count($data) > 0) {
	        return $data[0];
	    }
	    return null;
	}
	

	function make_table($cats, $model = null)
	{
		$result = "";

		if (is_array($cats)) 
		{
			foreach ($cats as $cats_row) 
			{
				$edit_args = $cats_row->id.",'".$cats_row->description."'";
				//$category_class = $this->load_model('Category');
				$info = array();
				$info['id'] = $cats_row->id;
				$info['description'] = $cats_row->description;
				$info['quantity'] = $cats_row->quantity;
				$info['price'] = $cats_row->price;
				$info['category'] = $cats_row->category;
				$info['image'] = $cats_row->image;
				$info['image2'] = $cats_row->image2;
				$info['image3'] = $cats_row->image3;
				$info['image4'] = $cats_row->image4;

				$info = str_replace('"', "'", json_encode($info));
				

				$single_cat = $model->single($cats_row->category);

				$result .= "<tr>";
				$result .= '
				<td><a href="basic_table.html#">' . $cats_row->id . '</a></td>

				<td><a href="basic_table.html#">' . $cats_row->description . '</a></td>
				<td><a href="basic_table.html#">' . $cats_row->quantity . '</a></td>

				<td><a href="basic_table.html#">' . ($single_cat ? $single_cat->category : '') . '</a></td>


				<td><a href="basic_table.html#">' . $cats_row->price . '</a></td>
				<td><a href="basic_table.html#">' . $cats_row->date . '</a></td>
				<td><a href="basic_table.html#"><img src="'.ROOT. $cats_row->image.'" style="width:50px; height:50px;" /></a></td>

				<td>
					<button info="'.$info.'" onclick="show_edit_product('.$edit_args.', event)" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
					<button onclick="delete_row('.$cats_row->id.')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
				</td>
				';
				$result .= "</tr>";
			}
		}

		return $result;
	}
	public Function str_to_url($url)
	{
		$url = preg_replace('~[^\\pL0-9_]+~u', '-' , $url);
		$url = trim($url,"-");
		$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
		$url = strtolower($url);
		$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
		return $url;
	}


}