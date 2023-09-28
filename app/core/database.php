<?php
/*
    * Read from database
    */
class Database
{
	public static $con;


    public function __construct()
    {
    	try{
    		$string = "mysql:hostname=".DBHOST.";dbname=".DBNAME;

    		self::$con = new PDO($string, DBUSER, DBPASS);

    	}
    	catch (PDOException $e)
    	{
    		die($e->getMessage());
    	}
    }

    public static function getInstance()
    {
    	if(self::$con) {
    		return self::$con;
    	}
    	return $instance = new self();

    }

    public static function newInstance()
    {
        return $instance = new self();

    }



    /*
    * Read from database
    */

    public function read($query, $data = array())
    {
    	$stmt = self::$con->prepare($query);
    	$result = $stmt->execute($data);

    	if($result) {
     		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
    	
    		if (is_array($data) && count($data) > 0){
    			return $data;
    		}
    	}

    	return false;
    }
    
    /*
    *
    * Write to database
    */

    public function write($query, $data = array())
    {
    	$stmt = self::$con->prepare($query);
    	$result = $stmt->execute($data);
        
    	if($result) {
     		
    		return true;
    	}
    	return false;
    }

}
