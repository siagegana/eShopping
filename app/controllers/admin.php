<?php

class Admin extends Controller
{
    public function index()
    {
        $User = $this->load_model('User'); // Load the User model.
        $user_data = $User->check_login(true, ["admin"]); // Check if User is logged in as an admin.

        if (is_object($user_data)) {
            $data['user_data'] = $user_data; // Store User data if the User is logged in.
        }

        $data['page_title'] = "Admin"; // Set the page title.
        $this->view("admin/index", $data); // Load the admin index view.
    }

    public function categories()
    {
        $User = $this->load_model('User'); // Load the User model.
        $user_data = $User->check_login(true, ["admin"]); // Check if User is logged in as an admin.

        if (is_object($user_data)) {
            $data['user_data'] = $user_data; // Store User data if the User is logged in.
        }

        $DB = Database::newInstance(); // Create a new instance of the Database class.
        $categories_all = $DB->read("SELECT * FROM categories order by id desc"); // Fetch all categories.
        $categories = $DB->read("SELECT * FROM categories where disabled = 0 order by id desc"); // Fetch enabled categories.

        $category = $this->load_model("Category"); // Load the Category model.

        $tbl_rows = $category->make_table($categories_all); // Generate HTML table rows for categories.
        $data['tbl_rows'] = $tbl_rows; // Store the generated table rows.
        $data['categories'] = $categories; // Store enabled categories.

        $data['page_title'] = "Admin - Categories"; // Set the page title.
        $this->view("admin/categories", $data); // Load the admin categories view.
    }

    public function products()
    {
        $User = $this->load_model('User'); // Load the User model.
        $user_data = $User->check_login(true, ["admin"]); // Check if User is logged in as an admin.

        if (is_object($user_data)) {
            $data['user_data'] = $user_data; // Store User data if the User is logged in.
        }

        $DB = Database::newInstance(); // Create a new instance of the Database class.
        $products = $DB->read("SELECT * FROM products order by id desc"); // Fetch all products.
        $categories = $DB->read("SELECT * FROM categories where disabled = 0 order by id desc"); // Fetch enabled categories.

        $product_model = $this->load_model("Product"); // Load the Product model.
        $category = $this->load_model("Category"); // Load the Category model.

        $tbl_rows = $product_model->make_table($products, $category); // Generate HTML table rows for products.
        $data['tbl_rows'] = $tbl_rows; // Store the generated table rows.
        $data['categories'] = $categories; // Store enabled categories.

        $data['page_title'] = "Admin - Products"; // Set the page title.
        $this->view("admin/products", $data); // Load the admin products view.
    }


    public function orders()
    {

        $User = $this->load_model('User'); // Load the User model.
        $Order = $this->load_model('Order'); // Load the Order model.
        
        $user_data = $User->check_login(true, ["admin"]); // Check if User is logged in as an admin.

        if (is_object($user_data)) {
            $data['user_data'] = $user_data; // Store User data if the User is logged in.
        }

        $orders = $Order->get_all_orders();

        if(is_array($orders))
        {
            foreach ($orders as $key => $row) {
                $details = $Order->get_order_details($row->id);
                $orders[$key]->grand_total = 0;

                if(is_array($details))
                {
                    $totals = array_column($details, "total");
                    $grand_total = array_sum($totals);
                    $orders[$key]->grand_total = $grand_total;
                }
                
                $orders[$key]->details = $details;

                $user = $User->get_user($row->user_url);

                $orders[$key]->user = $user;
            }
        }

        $data ['orders'] = $orders;

        $data['page_title'] = "Admin - Orders"; // Set the page title.

        //echo "order page";
        $this->view("admin/orders", $data); // Load the admin products view.

        }

        function users($type = "customers")
        {
            $User = $this->load_model('User'); // Load the User model.
            $Order = $this->load_model('Order'); // Load the User model.
            
            $user_data = $User->check_login(true, ["admin"]); // Check if User is logged in as an admin.

            if (is_object($user_data)) {
                $data['user_data'] = $user_data; // Store User data if the User is logged in.
            }

            if($type == "admins")
            {
                $users = $User->get_admins();
            }
            else
            {
                $users = $User->get_customers();
            }

            if(is_array($users))
            {
                foreach ($users as $key => $row) {
                    // code...
                    $orders_num = $Order->get_orders_count($row->url_address);
                    $users[$key]->orders_count = $orders_num;
                }
            }

            $data ['users'] = $users;
            $data['page_title'] = "Admin - $type"; // Set the page title.
            $this->view("admin/users", $data); // Load the admin products view.
        }

        function settings($type)
        {
            $User = $this->load_model('User'); // Load the User model.
            $Settings = $this->load_model('setting'); // Load the User model.
            
            $user_data = $User->check_login(true, ["admin"]); // Check if User is logged in as an admin.

            if (is_object($user_data)) 
            {
                $data['user_data'] = $user_data; // Store User data if the User is logged in.
            }

            if(count($_POST) > 0)
            {
                $error = $Settings->save($_POST);
                header(("Location: " . ROOT . "admin/settings/socials"));
                die;

            }

            $data['settings'] = $Settings->get_all();

            $data['page_title'] = "Admin - $type"; // Set the page title.
            $this->view("admin/socials", $data); // Load the admin products view.

        }
    }
