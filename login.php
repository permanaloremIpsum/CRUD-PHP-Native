<?php 
session_start();

if(isset($_SESSION["login"])){
	header("Location: index.php");
	exit;
}
require 'functions.php';

// cek cookie
if(isset($_COOKIE['id']) && isset($_COOKIE['key'])){
	$id = $_COOKIE['id'];
	$key = $_COOKIE['key'];

	//ambil username berdasarkan id
	$result = mysqli_query($db, "SELECT username FROM user WHERE id = '$id'");
	$row = mysqli_fetch_assoc($result);

	// cek cookie dan username
	if($key === hash('sha256', $row["username"])){
		$_SESSION['login'] = true;
	}
}

if(isset($_SESSION["login"])){
	header("Location: index.php");
	exit;
}

if(isset($_POST["login"])){
	$username = $_POST["username"];
	$password = $_POST["password"];

	$result = mysqli_query($db, "SELECT * FROM user WHERE username = '$username'");

	// cek username
	if(mysqli_num_rows($result) === 1){
		// cek password
		$row = mysqli_fetch_assoc($result);
		if(password_verify($password, $row["password"])){
			// set session
			$_SESSION["login"] = true;

			// cek remember me
			if(isset($_POST["remember"])){
				//buat cookie

				setcookie('id', $row['id'], time()+300);
				setcookie('key', hash('sha256', $row['username']), time()+300);
			}

			header("Location: index.php");
			exit;
		}
	}

	$error = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="dashboard/img/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="dashboard/vendors/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="dashboard/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="dashboard/vendors/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="dashboard/vendors/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="dashboard/vendors/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="dashboard/css/util.css">
	<link rel="stylesheet" type="text/css" href="dashboard/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="dashboard/img/img-01.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" action="" method="post">
					<span class="login100-form-title">
						Halaman Login
					</span>

					<?php if(isset($error)) : ?>
						<p class="notif">username / password salah</p>
					<?php endif ?>

					<div class="wrap-input100 validate-input" data-validate = "Username is required">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="wrap-input101">
						<input class="input101" type="checkbox" name="remember">
						<span class="symbol-input101">
							Remember me
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit" name="login">
							Login
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							Lupa
						</span>
						<a class="txt2" href="#">
							Username / Password?
						</a>
					</div>

					<div class="text-center p-t-50">
						<a class="txt2" href="register.php">
							Buat Akun Baru 
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="dashboard/vendors/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="dashboard/vendors/bootstrap/js/popper.js"></script>
	<script src="dashboard/vendors/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="dashboard/vendors/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="dashboard/vendors/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="dashboard/js/main.js"></script>

</body>
</html>