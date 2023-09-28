<?php

class Ajax_category extends Controller
{
	public function index()
	{
		$_SESSION['error'] = ""; // Initialize an empty error session variable.

		$data = file_get_contents("php://input"); // Get the raw POST data.
		$data = json_decode($data); // Decode the received JSON data.

		if (is_object($data) && isset($data->data_type)) {
			$DB = Database::getInstance(); // Get an instance of the Database class.
			$category = $this->load_model('Category'); // Load the Category model.

			if ($data->data_type == 'add_category') {
				// Add a new category.
				$check = $category->create($data); // Create the new category.

				if (isset($_SESSION['error']) && $_SESSION['error'] != "") {
					$arr['message'] = $_SESSION['error'];
					$_SESSION['error'] = "";
					$arr['message_type'] = "error";
					$arr['data'] = "";
					$arr['data_type'] = "add_new";

					echo json_encode($arr);
				} else {
					$arr['message'] = "Category added successfully!";
					$arr['message_type'] = "info";
					$cats = $category->get_all();
					$arr['data'] = $category->make_table($cats);
					$arr['data_type'] = "add_new";

					echo json_encode($arr);
				}
			} else if ($data->data_type == 'edit_cat') {
				$category->edit($data); // Edit an existing category.
				$arr['message'] = "Row successfully edited!";
				$_SESSION['error'] = "";

				$arr['message_type'] = "info";

				$cats = $category->get_all();
				$arr['data'] = $category->make_table($cats);

				$arr['data_type'] = "edit_cat";

				echo json_encode($arr);
			} else if ($data->data_type == 'disable_row') {
				$disabled = ($data->current_state == "Enabled") ?  1 : 0;
				$id = $data->id;

				$query = "update categories set disabled  = '$disabled' where id = '$id' limit 1";
				$DB->write($query); // Update the "disabled" state of a category.

				$arr['message'] = "";
				$_SESSION['error'] = "";

				$arr['message_type'] = "info";

				$cats = $category->get_all();
				$arr['data'] = $category->make_table($cats);

				$arr['data_type'] = "disable_row";

				echo json_encode($arr);
			} else if ($data->data_type == 'delete_row') {
				$category->delete($data->id); // Delete a category.
				$arr['message'] = "Row successfully deleted!";
				$_SESSION['error'] = "";

				$arr['message_type'] = "info";

				$cats = $category->get_all();
				$arr['data'] = $category->make_table($cats);

				$arr['data_type'] = "delete_row";

				echo json_encode($arr);
			}
		}
	}
}
