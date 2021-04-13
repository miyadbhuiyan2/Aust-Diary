<?php 
session_start();
if (isset($_SESSION['signed_in_user'])) {
	 header("Location:home.php");
            return;
}
require_once "pdo.php";
global $row;
$stmt = $pdo->query("SELECT * FROM outerpages");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $data) {
$row[$data['name']]=$data['value'];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Contact Us</title>
	<link rel="stylesheet" href="Bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/contactUs_style.css">
	<script src="https://kit.fontawesome.com/15fc770d9f.js" crossorigin="anonymous"></script>
	<script lang="JavasScript">
		window.setTimeout(function () {
    $(".alert-box").fadeTo(500, 0).slideUp(500, function () {
        $(this).remove();
    });
}, 5000);
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
				<a class="nav-link" href="signin.php">SIGN IN</a>
			</li>
			<li>
				<a class="nav-link" href="about.php">ABOUT US</a>
			</li>
			<li>
				<a class="nav-link active" href="contactUS.php">CONTACT</a>
			</li>
		</ul>
	</div>
</nav>
<div class="home">
<div class="container">
	<div class="row contact-box">
		<div class="col-lg-6 contact-details">
				<div class="row">
					<div class="col-lg-2"></div>
					<div class="col-lg-8">
						<h3 class="text-light"><i class="fas fa-map-marker-alt margin-icon"></i> Address</h3>
						<p class="margin-paragraph"><?= htmlentities($row['address']) ?></p>
						
						<h3 class="text-light"><i class="fas fa-phone margin-icon-call"></i> Lets Talk</h3>
						<p class="margin-paragraph"><?= htmlentities($row['phone']) ?></p>

						<h3 class="text-light"><i class="fas fa-fax margin-icon-fax"></i> Fax</h3>
						<p class="margin-paragraph"><?= htmlentities($row['fax']) ?></p>

						<h3 class="text-light"><i class="fas fa-envelope margin-icon-envelope"></i> Message</h3>
						<p class="margin-paragraph-message"><?= htmlentities($row['message']) ?></p>
					</div>
					<div class="col-lg-2"></div>
				</div>
		</div>
		<div class="col-lg-1"></div>
		<div class="col-lg-5 message-box">
			<div>
				<h2 class="text-center text-light">Send Us A Message</h2>
				<form class="form-group" action="sendmessage.php" method="post">
					<?php 
						if (isset($_SESSION['success'])) {
							echo '<div class="row alert-box">';
								echo '<div class="col-lg-12 text-center">';
									echo '<div class="alert alert-success">'.htmlentities($_SESSION['success']).'</div>';
								echo '</div>';
							echo '</div>';
							unset($_SESSION['success']);
						}
						elseif (isset($_SESSION['error'])) {
							echo '<div class="row alert-box">';
								echo '<div class="col-lg-12 text-center">';
									echo '<div class="alert alert-danger">'.htmlentities($_SESSION['error']).'</div>';
								echo '</div>';
							echo '</div>';
							unset($_SESSION['error']);				
						}
					?>	
					<div class="row">
						<div class="col-lg-6">
							<label>First Name:</label>
							<input type="text" name="fname" title="Only alphabets are allowed" pattern="[A-Za-z]+" class="form-control" placeholder="Enter your first name" required>
						</div>
						<div class="col-lg-6">
							<label>Last Name:</label>
							<input type="text" name="lname" title="Only alphabets are allowed" pattern="[A-Za-z]+" class="form-control" placeholder="Enter your last name" required>
						</div>

					</div>
					<div class="row">
						<div class="col-lg-12">
						<label>E-mail:</label>
						<input type="email" name="email" class="form-control" placeholder="Enter your E-mail" required>	
						</div>	
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label>Subject:</label>
						<input type="text" name="subject" class="form-control" placeholder="Enter suject of message" required>	
						</div>	
					</div>				
				<div class="row">
						<div class="col-lg-12">
						<label>Message:</label><br>
						<textarea name="message" class="form-control" placeholder="Type your message here..." required></textarea>
						</div>	
				</div>
				<div class="row">
						<div class="col-lg-12">
							<input type="submit" name="btn-send" value="SEND MESSAGE" class="btn btn-success btn-block btn-md">
						</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>	
</div>
<!---Footer-->
<footer>	
	<hr>
	<div class="row">
		<div class="col-12 text-center">
			&copy; <?php echo htmlentities($row['pageName']).' '.date('Y'); ?>
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

