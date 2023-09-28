<?php

class Ajax_checkout extends Controller
{
	public function index($data_type = '', $id = '')
	{
		$info = file_get_contents("php://input");
		$info = json_decode($info);

		$id = $info->data; // Decode the JSON data received as $id parameter.
		
		$countries = $this->load_model('Countries'); // Load the Countries model.
		$data = $countries->get_states($id->id); // Fetch states data based on the received ID.

		$info = (object)[]; // Initialize an empty object named 'info'.
		$info->data = $data; // Set the fetched states data to the 'data' property of the 'info' object.
		$info->data_type = "get_states"; // Set the data type to 'get_states'.
		echo json_encode($info); // Encode and echo the 'info' object as JSON.
	}
}
