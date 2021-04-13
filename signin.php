<?php
session_start();
require_once "pdo.php";
global $row;
$stmt = $pdo->query("SELECT * FROM outerpages");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $data) {
$row[$data['name']]=$data['value'];
}

if (isset($_SESSION['signed_in_user'])) {
	 header("Location:home.php");
            return;
}
if (isset($_POST['signin'])) {
	unset($_SESSION['signed_in_user']); 
	try {
		$stmt = $pdo->query("SELECT * FROM user where Email_id='".$_POST['email']."'");
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($rows)) {
			$_SESSION['error'] = "E-mail address doesn't exist";
			header("Location:signin.php");
			return;	
		}
		elseif (hash('md5', $_POST['password'])==$rows['Password']) {  
			if ($rows['Account_Status']==1) {
			$_SESSION['signed_in_user']=$rows['Email_id'];
			$_SESSION['signed_in_rank']=$rows['Rank'];
			$_SESSION['signed_in_id']=$rows['User_id'];
            header("Location:home.php");
            return;
			}
			elseif ($rows['Account_Status']==2) {
			$_SESSION['warning'] = 'Your account is in verification process';
			header("Location:signin.php");
			return;	
			}
			elseif ($rows['Account_Status']==3) {
			$_SESSION['error'] = 'Your account has been suspended';
			header("Location:signin.php");
			return;	
			}
        }
        else{
			$_SESSION['error'] = 'Incorrect Password';
			header("Location:signin.php");
			return;	
		}   
	} catch (Exception $e) {
				$_SESSION['error'] = "Error occured.Try again.";
				header("Location:signin.php");
				return;
	}	
}
if (isset($_POST['reset'])) {
	    try {
        $stmt = $pdo->query("SELECT * FROM user where Email_id='".$_POST['email']."'");
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($rows)) {
            $_SESSION['error'] = "E-mail address doesn't exist";
            header("Location:signin.php");
            return;
        }
        else{
            $resetPassword=rand(1000,10000);

            $stmt = $pdo->prepare("UPDATE user SET Password =:pw WHERE Email_id = :e_id;");
            $stmt->execute(array(
              ':e_id' => $_POST['email'],
              ':pw' => hash('md5', $resetPassword)));

            $firstname =$rows['FirstName'];
            $lastname = $rows['LastName'];
            $fullname=$firstname.' '.$lastname;
            $subject ="Reset Password";
            $to = $rows['Email_id'];
            $body = "Hello $fullname,\nA request has been recieved to reset the password for your Aust Diary Account.\n\nHere is your new password:$resetPassword\n\nThank you,\nThe Aust Diary Team";

            if (mail($to,$subject,$body,"From:Aust Diary")) 
            {   
               $_SESSION['success']="We have sent you an email with the new password for this account"; 
               header("location:signin.php");
               return;
            }
            else{
                $_SESSION['error']="Error occured.Try again."; 
                header("location:signin.php");
                return;
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error occured.Try again.";
        header("Location:signin.php");
        return;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign IN</title>
	 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
  	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css">
    <link rel="stylesheet" href="Bootstrap/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href ="css/signin_style.css"/>	
	<script language="Javascript">
	
		function forgot()
		{	
			document.getElementById("passwordfield").style.display="none";
    		document.getElementById("signinbt").style.display="none";
    		document.getElementById("forgotlink").style.display="none";
    		document.getElementById("forgotmessage").classList.remove('visibility');
    		document.getElementById("submitbt").classList.remove('visibility');
			
			document.getElementById("passwordfieldbox").required=false;
		}
		
	</script>	
</head>

<body>
<!---Navigation-->
<nav class="navbar navbar-expand-md navbar-dark">
	<a class="navbar-brand" href="index.php"><?php echo '<img src="DATABASE/img/'.htmlentities($row['logo']).'"/>'?></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarExpandView">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarExpandView">
		<ul class="navbar-nav ml-auto">
			<li>
				<a class="nav-link" href="index.php">HOME</a>
			</li>
			<li>
				<a class="nav-link" href="register.php">REGISTER</a>
			</li>
			<li>
				<a class="nav-link active" href="signin.php">SIGN IN</a>
			</li>
			<li>
				<a class="nav-link" href="about.php">ABOUT US</a>
			</li>
			<li>
				<a class="nav-link" href="contactUS.php">CONTACT</a>
			</li>
		</ul>
	</div>
</nav>
<!---End Navigation-->	
<div class="home">
	<div class="container">
	<div class="row">
		<div class="col-lg-4"></div>
		<div class="col-lg-4">

			<div class="signindiv">
				<div class="row">
					<div class="col-lg-12 text-center user-img">
					<?php echo '<img src="DATABASE/img/'.htmlentities($row['signInLogo']).'"/>'?>
					</div>
				</div>
				<h1 class="text-center">SIGN IN</h1>
				<form method="POST">
					<?php
					   	if (isset($_SESSION["error"])){
						echo ('<div class="row spacing">
						<div class="col-lg-12 col_spacing">
						<div class="alert alert-danger text-center">
    					<strong>'.htmlentities($_SESSION['error']).'</strong>
  						</div>
  						</div>
  						</div>');
						unset($_SESSION["error"]);
						}
						elseif (isset($_SESSION["warning"])){
						echo ('<div class="row spacing">
						<div class="col-lg-12 col_spacing">
						<div class="alert alert-warning text-center">
    					<strong>'.htmlentities($_SESSION['warning']).'</strong>
  						</div>
  						</div>
  						</div>');
						unset($_SESSION["warning"]);
						}
						elseif (isset($_SESSION["success"])){
						echo ('<div class="row spacing">
						<div class="col-lg-12 col_spacing">
						<div class="alert alert-success text-center">
    					<strong>'.htmlentities($_SESSION['success']).'</strong>
  						</div>
  						</div>
  						</div>');
						unset($_SESSION["success"]);
						}
			        ?>
			        <div class="row spacing visibility" id="forgotmessage">
						<div class="col-lg-12 text-center">	
						<p class="text-warning">Enter your email address to reset the password</p>
						</div>	
					</div>
					<div class="row spacing">
						<div class="col-lg-12 form-group inputmail">	
						<input type="email" name="email" class="form-control" placeholder="Enter E-mail" required>	
						</div>	
					</div>

					<div class="row spacing" id="passwordfield">
						<div class="col-lg-12 form-group inputpass">
						<input type="password" name="password" class="form-control" id="passwordfieldbox" placeholder="Enter Password" required>	
						</div>	
					</div>

					<div class="row spacing">
						<div class="col-md-12 text-center">
						<button type="submit" name="signin" class="btn" id="signinbt">Sign in</button>
						<button type="submit" name="reset" class="btn visibility" id="submitbt">Submit</button>
						</div>
					</div>
					<div class="row spacing">
					<div class="col-lg-12 text-center">
						<a onclick="forgot()" class="text-white" id="forgotlink">Forgot Password?</a>
					</div>
					</div>	
				</form>
		</div>
		
	</div>
	<div class="col-lg-4"></div>
</div>
</div>
</div>
<!---Footer-->
<footer>	
	<hr>
	<div class="row">
		<div class="col-12 text-center">
			&copy;<?php echo htmlentities($row['pageName']).' '.date('Y'); ?>
		</div>	
	</div>
</footer>
<!--  JavaScript -->
	<script src="Bootstrap/js/jquery.min.js">
	</script>
	<script src="Bootstrap/js/popper.min.js"></script>
	<script src="Bootstrap/js/bootstrap.min.js"></script>
</body>
</html>





	
