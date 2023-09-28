<?php

class Ajax_product extends Controller
{
    public function index()
    {
        // If form data is present in POST, use it; otherwise, decode raw JSON data.
        if(count($_POST) > 0)
        {
            $data = (object)$_POST; // Convert POST data to an object.
        }
        else
        {
            $data = file_get_contents("php://input"); // Get raw JSON data.
            $data = json_decode($data); // Decode the JSON data.
        }

        if(is_object($data) && isset($data->data_type)) 
        {
            $DB = Database::getInstance(); // Get an instance of the Database class.
            $product = $this->load_model('Product'); // Load the Product model.
            $category = $this->load_model('Category'); // Load the Category model.
            $image_class = $this->load_model('Image'); // Load the Image model.

            if($data->data_type == 'add_product') {
                // Add a new product.
                $check = $product->create($data, $_FILES, $image_class);

                if(isset($_SESSION['error']) && $_SESSION['error'] != "") {
                    $arr['message'] = $_SESSION['error']; // Store error message.
                    $_SESSION['error'] = ""; // Reset error session.
                    $arr['message_type'] = "error"; // Set message type.
                    $arr['data'] = ""; // Initialize data.
                    $arr['data_type'] = "add_new"; // Set data type.

                    echo json_encode($arr); // Return JSON response.
                } else {
                    $arr['message'] = "Product added successfully!";
                    $arr['message_type'] = "info";
                    $cats = $product->get_all();
                    $arr['data'] = $product->make_table($cats, $category);
                    $arr['data_type'] = "add_new";

                    echo json_encode($arr);
                }
            } else if ($data->data_type == 'edit_product') {
                $product->edit($data, $_FILES, $image_class); // Edit an existing product.
                if($_SESSION['error'] != "")
                {
                    $arr['message'] = $_SESSION['error'];
                    $arr['message_type'] = "error";
                }
                else
                {
                    $arr['message'] = "Row successfully edited!";
                    $arr['message_type'] = "info";
                }
                $_SESSION['error'] = "";

                $cats = $product->get_all();
                $arr['data'] = $product->make_table($cats, $category);

                $arr['data_type'] = "edit_product";

                echo json_encode($arr);
            } else if ($data->data_type == 'disable_row') {
                $disabled = ($data->current_state == "Enabled") ?  1 : 0;
                $id = $data->id;

                $query = "update products set disabled  = '$disabled' where id = '$id' limit 1";
                $DB->write($query); // Update the "disabled" state of a product.

                $arr['message'] = "";
                $_SESSION['error'] = "";

                $arr['message_type'] = "info";

                $cats = $product->get_all();
                $arr['data'] = $product->make_table($cats);

                $arr['data_type'] = "disable_row";
                echo json_encode($arr);
            } else if ($data->data_type == 'delete_row') {
                $product->delete($data->id); // Delete a product.
                $arr['message'] = "Row successfully deleted!";
                $_SESSION['error'] = "";

                $arr['message_type'] = "info";

                $cats = $product->get_all();
                $arr['data'] = $product->make_table($cats, $category);

                $arr['data_type'] = "delete_row";

                echo json_encode($arr);
            }
        }
    }
}
