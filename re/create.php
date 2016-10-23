<?php
require_once('admin.php');

function check_file()
{
	$conn = new camagru();
    echo "let's make a user son : ";
    $user = $_POST['login'];
	$passwd = hash("whirlpool", $_POST['password']);
    $count = $conn->check_user($user);

    if($count == "0")
	{
		echo "let's create a new user";
		$conn->create_user();
		$id = $conn->get_id($passwd);
		$conn->login_user($id);
	}
	else
    //    header ('Location: http://localhost:8080/Camagru_final/re/sign_up.html');
		echo "This Login is not avaialble son : ";
}

if($_POST["submit"] == "create")
{

	if (!empty($_POST["login"]) && !empty($_POST["password"]))
	{
		check_file();
	}
	else
		header ('Location: http://localhost:8080/Camagru_final/re/sign_up.html');
}
else
	header ('Location: http://localhost:8080/Camagru_final/re/sign_up.html');
?>
