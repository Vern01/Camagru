<?php
class camagru{
    public function __construct() {
        // open database connection
        $servername = "localhost";
    	$username = "root";
    	$password = "12345678";
    	$port = 8080;
    	$db="camagru";
    	$charset="UTF8MB4";
        $pdo;
        try {
            $this->pdo = new PDO("mysql:host=$servername;dbname=$db;port=$port", $username, $password);
	         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "connected succesfully";
            echo " hello : ";
            $_SESSION['conn'] = $this->pdo;
            //echo $this->pdo;
            echo " : hello :";
        } catch (PDOException $e) {
                echo $e->getMessage();
                echo "making new dtabse : ";
                $this->pdo = new PDO("mysql:host=$servername;port=$port;charset=$charset", $username, $password);
                echo "new pdo : ";
                $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`;
                        CREATE USER '$username'@'localhost' IDENTIFIED BY '$password';
                        GRANT ALL ON `$db`.* TO '$username'@'localhost';
                       FLUSH PRIVILEGES;")
                or die(print_r($this->pdo->errorInfo(), true));
                $this->pdo = new PDO("mysql:host=$servername;port=$port;dbname=$db;charset=$charset", $username, $password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $user_table = $this->get_user();
                $img_table = $this->get_image_table();
                $user_img = $this->user_img();
                $table_exists = $this->pdo->prepare("$user_table; $img_table; $user_img");
                $table_exists->execute();
            echo " Created user table : ";
            echo " created image_templates : ";
                echo "Just made you a new database son : ";
        }
    }
    /**
     * close the database connection
     */
//    public function __destruct() {
//        // close the database connection
//        echo "destructing : ";
//        $this->pdo = null;
//    }
private  function get_user()
    {
        echo "hello user table : ";
    	return ("CREATE TABLE IF NOT EXISTS `users` (`id` int(11) NOT NULL AUTO_INCREMENT,
      			`username` varchar(100) NOT NULL DEFAULT '',`password` varchar(255) NOT NULL,
      			`email` text NOT NULL,`reg_date` int(11) NOT NULL DEFAULT '0',
      			PRIMARY KEY (`id`),	KEY `reg_date` (`reg_date`)
    			) ENGINE=MyISAM  DEFAULT CHARSET=UTF8MB4");
    }

private    function get_image_table()
    {
    	return ("CREATE TABLE IF NOT EXISTS `templates` (`id` INT AUTO_INCREMENT PRIMARY KEY,
        		`mime` VARCHAR (255) NOT NULL,`data` BLOB NOT NULL)");
    }

private  function user_img()
    {
    	return ("CREATE TABLE `files` (
        `id`   INT           AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT,
        `likes` VARCHAR (1000),
        `comments` VARCHAR (3000),
        `mime` VARCHAR (255) NOT NULL,
        `data` LONGBLOB          NOT NULL
    		);");
    }
public function check_user($user)
{
    $query = $this->pdo->prepare("SELECT COUNT('id') FROM `users` WHERE `username` = '$user'");
	$query->execute();
	$count = $query->fetchcolumn();
    return ($count);
}

public function create_user()
{
    echo "new user son : ";
	$user = $_POST['login'];
	$password = hash("whirlpool", $_POST['password']);
	$email = $_POST['email'];
	$this->pdo->exec("INSERT INTO  `users` (  `username` ,  `email` , `password` )
				VALUES ( '$user', '$email', '$password');");
	echo "new user created son : ";
}
public function validate_user($pass)
{
	$user = $_POST['login'];
	$password = hash("whirlpool", $pass);
	$query = $this->pdo->prepare("SELECT COUNT('id') FROM `users` WHERE `username`='$user' AND `password`='$password'");
	$query->execute();
	$count = $query->fetchcolumn();
	if ($count == "0")
		header ('Location: http://localhost:8080/Camagru/index.html');
	else
		return ($count);
}

public function modify_user($id)
	{
		$user = $_POST['login'];
		$newpass = hash("whirlpool", $_POST['newpass']);
		$this->pdo->exec("UPDATE `users` SET `password`='$newpass' WHERE `id`=$id");
		echo "you've got a new password son!";
	}

public function login_user($id)
    {
    	session_start();
    	$_SESSION['user'] = $id;
        echo $_SESSION['user'];
        //echo $_SESSION['conn'];
        echo " is loggin in ";
    	header("Location: http://localhost:8080/Camagru/re/home.html");
    }

public function get_id($password)
    {
    	$user = $_POST['login'];
    	$qt = $this->pdo->prepare("SELECT `id` FROM `users` WHERE `username`='$user' AND `password`='$password'");
    	$qt->execute();
    	$id = $qt->fetchcolumn();
    	echo " your id is : ".$id." ";
    	return $id;
    }
public function insertBlob($blob, $mime)
    {
        // $blob = fopen($filePath, 'rb');
            session_start();
    //    $conn = $_SESSION['conn'];
         $sql = "INSERT INTO `files`(`mime`,`data`, `user_id`) VALUES(:mime,:data, :user_id)";
         $stmt = $this->pdo->prepare($sql);

         $stmt->bindParam(':mime', $mime);
    	 $stmt->bindParam(':user_id', $_SESSION['user']);
         $stmt->bindParam(':data', $blob, PDO::PARAM_LOB);

         return $stmt->execute();
     }
public function selectBlob()
{
            session_start();
            $id = $_SESSION['user'];
             $sql = "SELECT `mime`,
                             `data`
                        FROM `files`
                       WHERE `id` = $id;";

             $stmt = $this->pdo->prepare($sql);
             $stmt->execute(array("id" => $id));
             $stmt->bindColumn(1, $mime);
             $stmt->bindColumn(2, $data, PDO::PARAM_LOB);

             $stmt->fetch(PDO::FETCH_BOUND);

             return array("mime" => $mime,
                 "data" => $data);
         }
}
 ?>
