<?php
require_once('admin.php');

function check_id()
{
    $conn = new camagru();
    session_start();
    $_SESSION['conn'] = $conn;
    echo "let's log you son : ";
    $user = $_POST['login'];
    $count = $conn->validate_user($_POST['password']);
    if ($count == "0")
    {
    //    echo "faaaktup";
        header ('Location: http://localhost:8080/Camagru_final/index.html');
    }
    else
    {
        echo $count;
        $passwd = hash("whirlpool", $_POST['password']);
        $id = $conn->get_id($passwd);
        $conn->login_user($id);
    //    session_start();
    }

}

if($_POST["submit"] == "login!!!")
{
	if (!empty($_POST["login"]) && !empty($_POST["password"]))
	{
		echo "let's check you out son : ";
		check_id();
	}
	else
		header ('Location: http://localhost:8080/Camagru/index.html');
}

	if(isset($_POST['logout']))
	{
		session_destroy();
		echo "destroy session : ";
		header ('Location: http://localhost:8080/Camagru/index.html');
	}
 ?>