<?php

// Database settings
define('dbhost', 'localhost');
define('dbuser', 'root');
define('dbpass', '');
define('dbname', 'useditems');


// Site settings
define('glob_site_name', 'Used Items');   // Site Name
define('glob_site_url', "http://localhost/used-items"); // full path of website
define('glob_site_url_fd', 'useditems.com');




// SMTP SETTINGS
define('smtp_secure', 'ssl');  //'tls';
define('smtp_port', '465');   //587;
define('smtp_host', 'smtp.gmail.com');
define('smtp_username', 'jamesmiguel999@gmail.com');
define('smtp_sender_name', 'Love Joy');
define('smtp_password', 'vpkvfmarmxikgayo');



define('search_per_page', 10);

define('glob_colors', ["White", "Red", "Pink", "Blue", "Black", "Green", "Orange", "Yellow", "Grey", "Brown"]);






function plain_validate($value)
{
	return htmlspecialchars($value,  ENT_COMPAT);
}


function getError($key, $errors)
{

	$thisError = [];
	foreach ($errors as $error) {
		if (isset($error[$key])) {
			$thisError[] = $error[$key];
		}
	};
	return implode('<br>', $thisError);
}



function validPassword($password)
{
	if (preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/", $password)) {
		return true;
	}
	if (preg_match("/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{6,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))/", $password)) {
		return true;
	}
	return false;
}



function cookies_login()
{
}




// Database class

class mypdo
{
	public $pdc = null;
	public function __construct()
	{
		$host = dbhost;
		$db   =  dbname;
		$user  =  dbuser;
		$pass  =   dbpass;
		$charset = 'utf8mb4';
		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
		$opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false,];
		$this->pdc = new PDO($dsn, $user, $pass, $opt);
	}



	public function exec_query($qry, $val = '', $val2 = '')
	{

		$stmt = $this->pdc->prepare($qry);
		if ($val != '') {
			$stmt->bindParam(1, $val, PDO::PARAM_STR);
		}
		if ($val2 != '') {
			$stmt->bindParam(2, $val2, PDO::PARAM_STR);
		}
		$stmt->execute();
		if($stmt->rowCount() > 0){
			return true;
		}
		return false;
	}


	public function get_one($qry, $val = '')
	{

		$stmt = $this->pdc->prepare($qry);
		if ($val != '') {
			$stmt->bindParam(1, $val, PDO::PARAM_STR);
		}
		$stmt->execute();
		if ($stmt->rowCount() > 0) return $stmt->fetch();
		else return null;
	}

	public function get_all($qry, $val = '')
	{

		$stmt = $this->pdc->prepare($qry);
		if ($val != '') {
			$stmt->bindParam(1, $val, PDO::PARAM_STR);
		}
		$stmt->execute();
		return $stmt->fetchAll();
	}


	public function get_all_var($qry, $values){
		
		$stmt = $this->pdc->prepare($qry);
	   
	   for($i = 0; $i < count($values); $i++){
			$j = $i + 1;
		   $stmt->bindParam($j, $values[$i], PDO::PARAM_STR);
	   }
		$stmt->execute();
		return $stmt->fetchAll();
	   
   }

	public function new_user($email, $phone, $fname, $password, $role, $address, $timestamp)
	{
		$qry = "INSERT INTO user(email, phone, fname,  password, role, address, reg_date) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->pdc->prepare($qry);
		$stmt->bindParam(1, $email, PDO::PARAM_STR);
		$stmt->bindParam(2, $phone, PDO::PARAM_STR);
		$stmt->bindParam(3, $fname, PDO::PARAM_STR);
		$stmt->bindParam(4, $password, PDO::PARAM_STR);
		$stmt->bindParam(5, $role, PDO::PARAM_STR);
		$stmt->bindParam(6, $address, PDO::PARAM_STR);
		$stmt->bindParam(7, $timestamp, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) die("Error in database entry");
		return $this->pdc->lastInsertId();
	}

	public function update_user($uid, $email, $phone, $fname, $address)
	{
		$qry = "UPDATE user SET email = ?, phone  = ?, fname = ?,  address  = ? WHERE user_id = ?";
		$stmt = $this->pdc->prepare($qry);
		$stmt->bindParam(1, $email, PDO::PARAM_STR);
		$stmt->bindParam(2, $phone, PDO::PARAM_STR);
		$stmt->bindParam(3, $fname, PDO::PARAM_STR);
		$stmt->bindParam(4, $address, PDO::PARAM_STR);
		$stmt->bindParam(5, $uid, PDO::PARAM_INT);
		$stmt->execute();
	}


	public function update_user_password($email, $password)
	{
		$qry = "UPDATE user SET password = ? WHERE email = ?";
		$stmt = $this->pdc->prepare($qry);
		$stmt->bindParam(1, $password, PDO::PARAM_STR);
		$stmt->bindParam(2, $email, PDO::PARAM_STR);
		$stmt->execute();
	}


	public function new_item($uid, $cat_id, $loc_id, $prd_name, $prd_desc, $price, $color, $size, $added_date)
	{
		$qry = "INSERT INTO items(user_id, cat_id, loc_id, prd_name, prd_desc, price, color, size, added_date)VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->pdc->prepare($qry);
		$stmt->bindParam(1, $uid, PDO::PARAM_INT);
		$stmt->bindParam(2, $cat_id, PDO::PARAM_INT);
		$stmt->bindParam(3, $loc_id, PDO::PARAM_INT);
		$stmt->bindParam(4, $prd_name, PDO::PARAM_STR);
		$stmt->bindParam(5, $prd_desc, PDO::PARAM_STR);
		$stmt->bindParam(6, $price, PDO::PARAM_STR);
		$stmt->bindParam(7, $color, PDO::PARAM_STR);
		$stmt->bindParam(8, $size, PDO::PARAM_STR);
		$stmt->bindParam(9, $added_date, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() == 0) die("Error in database entry");
		return $this->pdc->lastInsertId();
	}


	public function update_item($prd_id, $uid, $cat_id, $loc_id, $prd_name, $prd_desc, $price, $color, $size)
	{
		$qry = "UPDATE items SET cat_id = ?, loc_id  = ?, prd_name = ?, prd_desc = ?, price = ?, color = ?, size = ? WHERE prd_id = ? AND user_id = ?";
		$stmt = $this->pdc->prepare($qry);
		$stmt->bindParam(1, $cat_id, PDO::PARAM_INT);
		$stmt->bindParam(2, $loc_id, PDO::PARAM_INT);
		$stmt->bindParam(3, $prd_name, PDO::PARAM_STR);
		$stmt->bindParam(4, $prd_desc, PDO::PARAM_STR);
		$stmt->bindParam(5, $price, PDO::PARAM_STR);
		$stmt->bindParam(6, $color, PDO::PARAM_STR);
		$stmt->bindParam(7, $size, PDO::PARAM_STR);
		$stmt->bindParam(8, $prd_id, PDO::PARAM_INT);
		$stmt->bindParam(9, $uid, PDO::PARAM_INT);
		$stmt->execute();
	}
}
