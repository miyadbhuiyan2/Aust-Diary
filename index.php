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
	<title><?= htmlentities($row['pageName']) ?></title>
	<link rel="stylesheet" href="Bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/index_style.css">
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark">
	<a class="navbar-brand" href="index.php"><?php echo '<img src="DATABASE/img/'.htmlentities($row['logo']).'"/>'?></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarExpandView">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarExpandView">
		<ul class="navbar-nav ml-auto">
			<li>
				<a class="nav-link active" href="index.php">HOME</a>
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
				<a class="nav-link" href="contactUS.php">CONTACT</a>
			</li>
		</ul>
	</div>
</nav>
<div class="home">
<div class="heading-panel text-center">
	<h1 class="animation">Welcome to <?= htmlentities($row['pageName'])?></h1>
	<a class="btn btn-outline-light btn-lg" href="register.php">Get Started</a>
</div>
</div>
<!---Footer-->
<footer>
	<div class="row">
		<div class="col-12 text-center">
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['logo']).'"/>'?>
			<p><?= htmlentities($row['paragraph'])?></p>
		</div>
	</div>	
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

