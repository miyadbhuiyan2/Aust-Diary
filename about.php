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
	<title>Aust Diary</title>
	<link rel="stylesheet" href="Bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/about_style.css">
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
				<a class="nav-link" href="index.php">HOME</a>
			</li>
			<li>
				<a class="nav-link" href="register.php">REGISTER</a>
			</li>
			<li>
				<a class="nav-link" href="signin.php">SIGN IN</a>
			</li>
			<li>
				<a class="nav-link active" href="about.php">ABOUT US</a>
			</li>
			<li>
				<a class="nav-link" href="contactUS.php">CONTACT</a>
			</li>
		</ul>
	</div>
</nav>
<!--- Carusel Panel-->
<div id="carouselViewPanel" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
		<li data-target="#carouselViewPanel" data-slide-to="0" class="active"></li>
		<li data-target="#carouselViewPanel" data-slide-to="1"></li>
		<li data-target="#carouselViewPanel" data-slide-to="2"></li>
		<li data-target="#carouselViewPanel" data-slide-to="3"></li>
		<li data-target="#carouselViewPanel" data-slide-to="4"></li>
	</ol>
	<div class="carousel-inner">
		<!---Slide 1-->
		<div class="carousel-item active">
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['slide1']).'" class="image-slide"/>'?>		
		</div>
		<!--Slide 2-->
		<div class="carousel-item">
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['slide2']).'" class="image-slide"/>'?>		
		</div>
		<!--Slide 3-->
		<div class="carousel-item">
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['slide3']).'" class="image-slide"/>'?>		
		</div>
		<!--Slide 4-->
		<div class="carousel-item">
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['slide4']).'" class="image-slide"/>'?>		
		</div>
		<!--Slide 5-->
		<div class="carousel-item">
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['slide5']).'" class="image-slide"/>'?>		
		</div>	
	</div>
	<!---End Carousel Inner--->
	<!---Prev & Next Buttons-->
	<a class="carousel-control-prev" href="#carouselViewPanel" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
	</a>
	<a class="carousel-control-next" href="#carouselViewPanel" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
	</a>
</div>	
<!-- Description Panel-->
<div class="container">
	<div class="row">
		<div class="col-12 text-center">
		<h1><?= htmlentities($row['pageName']) ?></h1>
		<p class="description text-justify"><?= htmlentities($row['description']) ?></p>
		</div>
	</div>
</div>
<!-- Member Category Panel-->
<div class="member">
	<div class="row">
	<div class="col-12 text-center">
	<h3>Members</h3>
	</div>
	<div class="header-underline"></div>
	</div>
<div class="row p-0">
	<div class="col-sm-4 p-0">
		<div>
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['studentIcon']).'"/>'?>		
		</div>
	</div>
	<div class="col-sm-4 p-0">
		<div>
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['teacherIcon']).'"/>'?>		
		</div>
	</div>
	<div class="col-sm-4 p-0">
		<div>
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['companyRepresentativeIcon']).'"/>'?>		
		</div>
	</div>
</div>
</div>
<!--Developer Panel-->
<div class="developer">
	<div class="row">
	<div class="col-12 text-center">
	<h3>MEET THE DEVELOPERS</h3>
	</div>
	<div class="header-underline"></div>
	</div>

<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-4">
		<div class="card text-center">
			<?php echo '<img src="DATABASE/img/'.htmlentities($row['developerImage1']).'" class="card-img-top"/>'?>
			<div class="card-body">
				<h4><?= htmlentities($row['developerName1']) ?></h4>
				<p class="card-text"><?= htmlentities($row['developerDescription1']) ?></p> 
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="card text-center">
		<?php echo '<img src="DATABASE/img/'.htmlentities($row['developerImage2']).'" class="card-img-top"/>'?>
			<div class="card-body">
				<h4><?= htmlentities($row['developerName2'])?></h4>
				<p class="card-text"><?= htmlentities($row['developerDescription2'])?></p> 
			</div>
		</div>
	</div>
<div class="col-md-2"></div>
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

