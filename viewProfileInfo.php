<?php
session_start();
require_once "pdo.php";
global $opData;
$stmt = $pdo->query("SELECT * FROM outerpages");
$opDatas = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($opDatas as $data) {
$opData[$data['name']]=$data['value'];
}
global $rows;
if ( ! isset($_SESSION['signed_in_user']) ) {
  $_SESSION["warning"] = "Please sign in first";
  header("Location:signin.php");
  return;
}
else{   	$s_fName = null;
			$s_lName = null;
			$s_email= null;
			$s_contactNumber =null;
			$s_image = null;
			$s_password = null;
			$s_gender = null;
			$s_idNumber = null;
			$s_department = null;
			$s_semester= null;
			$s_year = null;
			$s_address = null;

			$t_fName = null;
			$t_lName = null;
			$t_email= null;
			$t_contactNumber = null;
			$t_image = null;
			$t_password = null;
			$t_gender = null;
			$t_department = null;
			$t_address = null;

			$c_fName = null;
			$c_lName = null;
			$c_email= null;
			$c_contactNumber = null;
			$c_image = null;
			$c_password = null;
			$c_gender = null;
			$c_companyName =null;
			$c_companyType = null;
			$c_companyAddress = null;

		$stmt = $pdo->prepare("SELECT * FROM user where Email_id = :xyz");
		$stmt->execute(array(":xyz" => $_GET['userId']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$viewFlag=$row['Rank'];
		if ($row['Rank']=='Student') {
			$s_fName = htmlentities($row['FirstName']);
			$s_lName = htmlentities($row['LastName']);
			$s_email= htmlentities($row['Email_id']);
			$s_contactNumber = htmlentities($row['ContactNumber']);
			$s_image = htmlentities($row['Image']);
			$s_password = htmlentities($row['Password']);
			$s_gender = htmlentities($row['Gender']);
			$s_idNumber = htmlentities($row['IdNumber']);
			$s_department = htmlentities($row['Department']);
			$s_semester= htmlentities($row['Semester']);
			$s_year = htmlentities($row['Year']);
			$s_address = htmlentities($row['Address']);
		}
		else if ($row['Rank']=='Teacher') {
			$t_fName = htmlentities($row['FirstName']);
			$t_lName = htmlentities($row['LastName']);
			$t_email= htmlentities($row['Email_id']);
			$t_contactNumber = htmlentities($row['ContactNumber']);
			$t_image = htmlentities($row['Image']);
			$t_password = htmlentities($row['Password']);
			$t_gender = htmlentities($row['Gender']);
			$t_department = htmlentities($row['Department']);
			$t_address = htmlentities($row['Address']);
		}
		else{
			$c_fName = htmlentities($row['FirstName']);
			$c_lName = htmlentities($row['LastName']);
			$c_email= htmlentities($row['Email_id']);
			$c_contactNumber = htmlentities($row['ContactNumber']);
			$c_image = htmlentities($row['Image']);
			$c_password = htmlentities($row['Password']);
			$c_gender = htmlentities($row['Gender']);
			$c_companyName = htmlentities($row['CompanyName']);
			$c_companyType = htmlentities($row['CompanyType']);
			$c_companyAddress = htmlentities($row['Address']);
		}		
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?= htmlentities($row['FirstName'].' '.$row['LastName']) ?></title>
	 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link type="text/css" rel="stylesheet" href ="css/profile_view.css"/>
	<script src="https://kit.fontawesome.com/15fc770d9f.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script lang="JavaScript">
		function create () {
		document.getElementById("notification-dot").classList.add('d-none');	
         var xmlhttp=new XMLHttpRequest();
  		 xmlhttp.open("GET","updateNotification.php",true);
  		 xmlhttp.send();
    }
		function viewSelector(rank){

			if(rank=='Student'){
				document.getElementById("view-student-info-content").classList.remove('edit-profile-show');
				document.getElementById("tasks-link").classList.remove('edit-profile-show');
				document.getElementById("bottom-tasks-link").classList.remove('edit-profile-show');
			}
			else if(rank=='Teacher'){
				document.getElementById("view-teacher-info-content").classList.remove('edit-profile-show');
				document.getElementById("tasks-link").classList.remove('edit-profile-show');
				document.getElementById("bottom-tasks-link").classList.remove('edit-profile-show');
			}
			else if(rank=='Company Representative'){
				document.getElementById("view-company-info-content").classList.remove('edit-profile-show');
			}
		}
	</script>
</head>
<body onload="viewSelector('<?php echo (htmlentities($viewFlag)); ?>')">
<!---Navigation-->
<nav class="navbar navbar-expand-md navbar-dark">
	<a class="navbar-brand" href="home.php"><?php echo '<img src="DATABASE/img/'.htmlentities($opData['logoWhite']).'"/>'?></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarExpandView">
		<span class="navbar-toggler-icon"></span><sup id="notification-dot"><i class="fas fa-circle"></i></sup>
	</button>
	<div class="collapse navbar-collapse" id="navbarExpandView">
		<ul class="navbar-nav ml-auto">
			<li>
				<a class="nav-link" href="home.php"><i class="fas fa-newspaper"></i> HOME</a>
			</li>
			<li>
				<a class="nav-link active" href=<?php echo('"viewProfileInfo.php?userId='.$_GET['userId'].'"'); ?>><i class="fas fa-user-alt"></i> PROFILE</a>
			</li>
			<?php
			if ($_SESSION['signed_in_rank']!='Company Representative') {
				$stmt = $pdo->prepare("SELECT p.ProjectName as TaskName, concat(u.FirstName,' ',U.LastName) as MemberName,n.Time as Time,u.Image as Image FROM postshow p INNER JOIN notification n ON p.PS_id = n.Post_id INNER JOIN user u ON u.User_id = n.User_id WHERE p.user_id=:xyz AND n.User_id!=:xyz AND n.Status='UNREAD' ORDER BY n.Time ASC");
				$stmt->execute(array(":xyz" => $_SESSION['signed_in_id']));
				$notify = $stmt->fetchAll(PDO::FETCH_ASSOC);
				echo '<li id="notifications-li" data-toggle="modal" data-target="#exampleModalScrollable" onclick="create()">
				<a class="nav-link" id="notifications-link"><i class="fas fa-bell">';
				if (!empty($notify)) {
				echo '<sup id="notification-dot"><i class="fas fa-circle"></i></sup>';
				}
				echo '</i> NOTIFICATION</a>
				</li>';
				}
			?>
			<li>
				<a class="nav-link" href="signout.php"><i class="fas fa-sign-out-alt"></i> SIGN OUT</a>
			</li>
		</ul>
	</div>
</nav>
<!---End Navigation-->	
<!--Notification Modal -->
<?php
	include 'notificationView.php'
?>
<!----------End Notification modal---->
<div class="container">
	<div class="row profile">
		<div class="col-lg-3 profile-sidebar" id="profile_sidebar">
			<div class="row">
				<div class="col-lg-12">
					<div class="user-img">
						<?php
    						echo '<img src="DATABASE/User_Profile_Picture/'.$row['Image'].'" class="project-photo-frame rounded-circle" alt="'.htmlentities($row['FirstName'].' '.$row['LastName']).'"/>';
						?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="user-title">
						<div class="user-name">
							<h5 title="<?= htmlentities($row['FirstName'].' '.$row['LastName']) ?>"><?= htmlentities($row['FirstName'].' '.$row['LastName']) ?></h5>
						</div>
						<div class="user-dept">
							<h6><?php 
							if ($viewFlag=='Company Representative') {
								echo htmlentities($viewFlag);
							}
							else{
								echo htmlentities($row['Department']);
							}
							 ?></h6>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="user-menu">
						<ul class="nav">
							<a href=<?php echo('"viewProfileInfo.php?userId='.$_GET['userId'].'"'); ?> class="menu-link active" id="information-link"><li><i class="fas fa-info" id="info"></i>Account Information</li></a>
							
							<a href=<?php echo('"viewProfileIntro.php?userId='.$_GET['userId'].'"'); ?> class="menu-link" id="introduction-link"><li><i class="fas fa-user" id="intro"></i>Introduction</li></a>

							<a href=<?php 
							if ($_GET['userId']==$_SESSION['signed_in_user']) {
								echo("editProfileTasks.php");	
								}
								else{
								echo('"viewProfileTasks.php?userId='.$_GET['userId'].'"');
								}	
							 ?> class="menu-link edit-profile-show" id="tasks-link"><li><i class="fas fa-tasks" id="tasks"></i>Tasks</li></a>

						</ul>
					</div>
				</div>
			</div>
	</div>
	<div class="col-lg-1 profile-division"></div>
<!-----------------------------Start Profile Content-------------------------->
<!-----------------------------Start Company Info Content--------------------->
	<div class="col-lg-8 profile-content edit-profile-show" id="view-company-info-content">
			<div class="container content-padding">	
			<div class="row">
				<div class="col-lg-12 p-0">
					<h1 class="header-info-edit"><i class="fas fa-info"></i> Account Information</h1>
				</div>
			</div>	
			<?php 

				if (isset($_SESSION["success"])&&$_SESSION['signed_in_rank']=='Company Representative') {
						echo ('<div class="row alert-box">
						<div class="col-lg-12 p-0">
						<div class="alert alert-success text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    					<strong>'.htmlentities($_SESSION['success']).'</strong>
  						</div>
  						</div>
  						</div>');
  						unset($_SESSION["success"]);
  					}
			 ?>
			<div class="row">
				<div class="col-lg-12  notification-panel">

					<div class="scroll-bar">
						<div class="row" id="content-profile-picture">
							<div class="col-lg-3"></div>
							<div class="col-lg-6 text-center">
								<?php
    							echo '<img src="DATABASE/User_Profile_Picture/'.$row['Image'].'" class="project-photo-frame rounded-circle" alt="'.htmlentities($row['FirstName'].' '.$row['LastName']).'"/>';
								?>
							</div>
							<div class="col-lg-3"></div>
						</div>
					<ul class="account-info-list">
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>First Name</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="firstName"><?= htmlentities($c_fName) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Last Name</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="lastName"><?= htmlentities($c_lName) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>E-mail Address</label>
								</div>
								<div class="col-7 col-lg-8">
								    <label id="email"><?= htmlentities($c_email) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Contact Number</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="contactNumber"><?= htmlentities($c_contactNumber) ?></label>
								</div>
							</div>
						</li>
						<li> 				
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Gender</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="gender"><?= htmlentities($c_gender) ?></label>
								</div>
							</div>
						</li>
						<li> 		
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Rank</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="rank">Company Representative</label>
								</div>
							</div>
						</li>
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Company Name</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="companyName"><?= htmlentities($c_companyName) ?></label>
								</div>
							</div>
						</li>
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Company Type</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="companyType"><?= htmlentities($c_companyType) ?></label>
								</div>
							</div>
						</li>
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Company Address</label></div>
								<div class="col-7 col-lg-8">
									<label id="address"><?= htmlentities($c_companyAddress) ?></label>
								</div>
							</div>											
						</li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
			if ($_SESSION['signed_in_user']==$_GET['userId']) {
				echo '<div class="row">
						<div class="col-12 col-lg-12 pb-0 pt-0">
							<button onclick="document.location='."'editProfileInfo.php'".'" class="btn btn-outline-info btn-md w-100 mb-3"><i class="fas fa-edit"></i> Edit Account Information</button>
						</div>
					</div>';
		 }?>	
	</div>	
<!-----------------------------Start Student Info Content-------------------->
	<div class="col-lg-8 profile-content edit-profile-show" id="view-student-info-content">		
			<div class="container content-padding">
			<div class="row">
				<div class="col-lg-12 p-0">
					<h1 class="header-info-edit"><i class="fas fa-info"></i> Account Information</h1>
				</div>
			</div>	
			<?php

				if (isset($_SESSION['success'])&&$_SESSION['signed_in_rank']=='Student') 
				{
						echo ('<div class="row alert-box">
						<div class="col-lg-12 p-0">
						<div class="alert alert-success text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    					<strong>'.htmlentities($_SESSION['success']).'</strong>
  						</div>
  						</div>
  						</div>');
  						unset($_SESSION['success']);
  					}
			        
			 ?>
			<div class="row">
				<div class="col-lg-12  notification-panel">

					<div class="scroll-bar">
						<div class="row" id="content-profile-picture">
							<div class="col-lg-3"></div>
							<div class="col-lg-6 text-center">
								<?php
    							echo '<img src="DATABASE/User_Profile_Picture/'.$row['Image'].'" class="project-photo-frame rounded-circle" alt="'.htmlentities($row['FirstName'].' '.$row['LastName']).'"/>';
								?>
							</div>
							<div class="col-lg-3"></div>
						</div>
					<ul class="account-info-list">
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>First Name</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="firstName"><?= htmlentities($s_fName) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Last Name</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="lastName"><?= htmlentities($s_lName) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>E-mail Address</label>
								</div>
								<div class="col-7 col-lg-8">
								    <label id="email"><?= htmlentities($s_email) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Contact Number</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="contactNumber"><?= htmlentities($s_contactNumber) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Gender</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="gender"><?= htmlentities($s_gender) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Rank</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="rank">Student</label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>ID Number</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="idNumber"><?= htmlentities($s_idNumber) ?></label>
								</div>
							</div>
						</li>
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Department</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="department"><?= htmlentities($s_department) ?></label>
								</div>
							</div>
						</li>
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Semester</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="semester"><?= htmlentities($s_semester) ?></label>
								</div>
							</div>
						</li>
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Year</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="year"><?= htmlentities($s_year) ?></label>
								</div>
							</div>
						</li>
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Address</label></div>
								<div class="col-7 col-lg-8">
									<label id="address"><?= htmlentities($s_address) ?></label>
								</div>
							</div>					
						</li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
			if ($_SESSION['signed_in_user']==$_GET['userId']) {
				echo '<div class="row">
						<div class="col-12 col-lg-12 pb-0 pt-0">
							<button onclick="document.location='."'editProfileInfo.php'".'" class="btn btn-outline-info btn-md w-100 mb-3"><i class="fas fa-edit"></i> Edit Account Information</button>
						</div>
					</div>';
		 }?>
	</div>
<!---------------------------Start teacher Info Content-------------------->
	<div class="col-lg-8 profile-content edit-profile-show" id="view-teacher-info-content">
			<div class="container content-padding">
				
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-info"></i> Account Information</h1>
			</div>	
			<?php 
				if (isset($_SESSION["success"])&&$_SESSION['signed_in_rank']=='Teacher') {
						echo ('<div class="row alert-box">
						<div class="col-lg-12 p-0">
						<div class="alert alert-success text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    					<strong>'.htmlentities($_SESSION['success']).'</strong>
  						</div>
  						</div>
  						</div>');
  						unset($_SESSION["success"]);
  					}
			 ?>
			<div class="row">
				<div class="col-lg-12  notification-panel">

					<div class="scroll-bar">
						<div class="row" id="content-profile-picture">
							<div class="col-lg-3"></div>
							<div class="col-lg-6 text-center">
								<?php
    							echo '<img src="DATABASE/User_Profile_Picture/'.$row['Image'].'" class="project-photo-frame rounded-circle" alt="'.htmlentities($row['FirstName'].' '.$row['LastName']).'"/>';
								?>
							</div>
							<div class="col-lg-3"></div>
						</div>
					<ul class="account-info-list">
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>First Name</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="firstName"><?= htmlentities($t_fName) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Last Name</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="lastName"><?= htmlentities($t_lName) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>E-mail Address</label>
								</div>
								<div class="col-7 col-lg-8">
								    <label id="email"><?= htmlentities($t_email) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Contact Number</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="contactNumber"><?= htmlentities($t_contactNumber) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Gender</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="gender"><?= htmlentities($t_gender) ?></label>
								</div>
							</div>
						</li>
						<li> 
							
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Rank</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="rank">Teacher</label>
								</div>
							</div>
						</li>
				
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Department</label>
								</div>
								<div class="col-7 col-lg-8">
									<label id="department"><?= htmlentities($t_department) ?></label>
								</div>
							</div>
						</li>
						<li> 
							<div class="row">
								<div class="col-5 col-lg-4 font-weight-bold">
									<label>Address</label></div>
								<div class="col-7 col-lg-8">
									<label id="address"><?= htmlentities($t_address) ?></label>
								</div>
							</div>
							
							
						</li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
				if ($_SESSION['signed_in_user']==$_GET['userId']) {
					echo '<div class="row">
							<div class="col-12 col-lg-12 pb-0 pt-0">
								<button onclick="document.location='."'editProfileInfo.php'".'" class="btn btn-outline-info btn-md w-100 mb-3"><i class="fas fa-edit"></i> Edit Account Information</button>
							</div>
						</div>';
		 }?>
	</div>
<!-----------------------------End Profile Content---------------------->
</div>
</div>
<!--  JavaScript -->
	<script src="Bootstrap/js/jquery.min.js">
	</script>
	<script src="Bootstrap/js/popper.min.js"></script>
	<script src="Bootstrap/js/bootstrap.min.js"></script>
<!--Bottom Navigation-->
<nav class="nav-bottom" id="bottom-nav">
	<a href=<?php echo('"viewProfileInfo.php?userId='.$_GET['userId'].'"'); ?> class="nav__link nav__link--active" id="bottom-information-link">
    <i class="fas fa-info"></i>
    <span class="nav__text">Account Information</span>
  	</a>	
  <a href=<?php echo('"viewProfileIntro.php?userId='.$_GET['userId'].'"'); ?> class="nav__link" id="bottom-introduction-link">
    <i class="fas fa-user"></i>
    <span class="nav__text">Introduction</span>
  </a>
  
  <a href=<?php 
	if ($_GET['userId']==$_SESSION['signed_in_user']) {
		echo('"editProfileTasks.php?userId='.$_GET['userId'].'"');	
		}
		else{
		echo('"viewProfileTasks.php?userId='.$_GET['userId'].'"');
		}	
	 ?> class="nav__link edit-profile-show" id="bottom-tasks-link">
    <i class="fas fa-tasks"></i>
    <span class="nav__text">Tasks</span>
  </a>
</nav>	
</body>
</html>