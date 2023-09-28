<?php

class Category
{

	function create($DATA)
	{
		$DB = Database::newInstance();

		$arr['category'] = ucwords($DATA->category);
		$arr['parent'] = ucwords($DATA->parent);

		if (!preg_match("/^[a-zA-Z]+$/", trim($arr['category']))) 
		{
			$_SESSION['error'] = "Please enter a valid category name";
		}

		if (!isset($_SESSION['error']) || $_SESSION['error'] == "") {
			$query = "INSERT INTO categories (category, parent) VALUES (:category, :parent)";
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
		// Code for deleting a category
		$DB = Database::newInstance();
		$id = (int)$id;
		$query = "delete from categories where id = '$id' limit 1";
		$DB->write($query);
	}

	function edit($data)
	{
		// Code for editing a category
		$DB = Database::newInstance();
		$arr['id'] = $data->id;
		$arr['category'] = $data->category;
		$arr['parent'] = $data->parent;

		$query = "update categories set category = :category, parent = :parent where id = :id limit 1";
		$check = $DB->write($query, $arr);
    
	    if ($check) 
	    {
	        return true;
	    }

	    return false;	
	}


	function get_all()
	{
		$DB = Database::newInstance();
		return $DB->read("SELECT * FROM categories order by id desc");
	}

	function single($id) // get_one
	{
		$id = (int)$id;

	    $DB = Database::newInstance();
	    $data = $DB->read("SELECT * FROM categories where id = '$id' limit 1");
	    return $data[0];

    /*if ($data && is_array($data) && count($data) > 0) {
        return $data[0];
    }
    return null;*/
	}

	function get_one_by_name($name) // get_one
	{
		$name = addslashes($name);

	    $DB = Database::newInstance();
	    $data = $DB->read("SELECT * FROM categories where category like :name limit 1", ["name"=>$name]);
	    return $data[0];
	}

	function make_table($cats)
	{
		$result = "";

		if(is_array($cats)) 
		{
			foreach($cats as $cats_row) 
			{

				$color = $cats_row->disabled ? "#ae7c04" : "#5bc0de";
				$cats_row->disabled = $cats_row->disabled ? "Disabled" : "Enabled";

				$args = $cats_row->id.",'".$cats_row->disabled."'";
				$edit_args = $cats_row->id.",'".$cats_row->category."',".$cats_row->parent;
				$parent = "";

				foreach ($cats as $cats_row2)
				{
					if($cats_row->parent == $cats_row2->id);
					{
						$parent = $cats_row2->category;
					}

				} 

				$result .= "<tr>";
				$result .= '
				<td><a href="basic_table.html#">'.$cats_row->category.'</a></td>
				<td><a href="basic_table.html#">'.$parent.'</a></td>
				<td><span onclick="disable_row('.$args.')" class="label label-info label-mini" style="cursor:pointer;background-color:'.$color.';">'. $cats_row->disabled.'</span>
				</td>

				<td>
					<button onclick="show_edit_cat('.$edit_args.', event)" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
					<button onclick="delete_row('.$cats_row->id.')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
				</td>
				';
				$result .= "</tr>";
			}
		}

		return $result;
	}
}
