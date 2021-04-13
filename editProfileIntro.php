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
else{
	$about_student = null;
		$student_skill = null;

		$about_teacher= null;
		$teacher_skill = null;

		$about_company = null;
	$viewFlag=$_SESSION['signed_in_rank'];
	$stmt = $pdo->prepare("SELECT * FROM user where Email_id = :xyz");
		$stmt->execute(array(":xyz" => $_SESSION['signed_in_user']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($_SESSION['signed_in_rank']=='Student') {
			$about_student = htmlentities($row['Description']);
			$student_skill = htmlentities($row['Skills']);
		}
		else if ($_SESSION['signed_in_rank']=='Teacher') {
			$about_teacher= htmlentities($row['Description']);
			$teacher_skill = htmlentities($row['Skills']);
		}
		else if ($_SESSION['signed_in_rank']=='Company Representative'){
			$about_company = htmlentities($row['Description']);
		}	
}
try {
	if (isset($_POST['update-student'])) {
		$totalSkills=explode(",",$_POST['skills']);
		$totalSkills=array_unique($totalSkills);
		$skillStr=implode(",",$totalSkills);

      $stmt = $pdo->prepare("UPDATE user SET Description =:des,Skills=:sk WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':des' => $_POST['bio'],
              ':sk' => $skillStr));
            $_SESSION['success'] = "Introduction has been updated successfully";
            header("Location:viewProfileIntro.php?userId=".$_SESSION['signed_in_user']);
            return;
	}
	else if (isset($_POST['update-teacher'])) {
		$totalSkills=explode(",",$_POST['teacherskills']);
		$totalSkills=array_unique($totalSkills);
		$skillStr=implode(",",$totalSkills);
		
		 $stmt = $pdo->prepare("UPDATE user SET Description =:des,Skills=:sk WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':des' => $_POST['bio'],
              ':sk' => $skillStr));
            $_SESSION['success'] = "Introduction has been updated successfully";
            header("Location:viewProfileIntro.php?userId=".$_SESSION['signed_in_user']);
            return;
	}
	else if (isset($_POST['update-company'])) {
		 $stmt = $pdo->prepare("UPDATE user SET Description =:des WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':des' => $_POST['bio']));
            $_SESSION['success'] = "Introduction has been updated successfully";
            header("Location:viewProfileIntro.php?userId=".$_SESSION['signed_in_user']);
            return;
	}
} catch (Exception $e) {
		if (strpos($e->getMessage(),'ContactNumber')==true) {
				$_SESSION['error'] = "This contact number already exists";
				header("Location:editProfileIntro.php");
				return;
			}
			elseif (strpos($e->getMessage(),'PRIMARY')==true) {
				$_SESSION['error'] = "This email address already exists";
				header("Location:editProfileIntro.php");
				return;
			}
			else{
				$_SESSION['error'] = "Error occured.Try again.";
				header("Location:editProfileIntro.php");
				return;
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
	<link type="text/css" rel="stylesheet" href ="css/profile_edit.css"/>
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
				document.getElementById("edit-student-introduction-content").classList.remove('edit-profile-show');
				document.getElementById("tasks-link").classList.remove('edit-profile-show');
				document.getElementById("bottom-tasks-link").classList.remove('edit-profile-show');
			}
			else if(rank=='Teacher'){
				document.getElementById("edit-teacher-introduction-content").classList.remove('edit-profile-show');
				document.getElementById("tasks-link").classList.remove('edit-profile-show');
				document.getElementById("bottom-tasks-link").classList.remove('edit-profile-show');
			}
			else if(rank=='Company Representative'){
				document.getElementById("edit-company-introduction-content").classList.remove('edit-profile-show');
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
				<a class="nav-link active" href=<?php echo('"viewProfileInfo.php?userId='.$_SESSION['signed_in_user'].'"'); ?>><i class="fas fa-user-alt"></i> PROFILE</a>
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
							<a href=<?php echo('"viewProfileInfo.php?userId='.$_SESSION['signed_in_user'].'"'); ?> class="menu-link" id="information-link"><li><i class="fas fa-info" id="info"></i>Account Information</li></a>

							<a href="editProfileIntro.php" class="menu-link active" id="introduction-link"><li><i class="fas fa-user" id="intro"></i>Introduction</li></a>

							<a href="editProfileTasks.php" class="menu-link edit-profile-show" id="tasks-link"><li><i class="fas fa-tasks" id="tasks"></i>Tasks</li></a>

						</ul>
					</div>
				</div>
			</div>
	</div>
	<div class="col-lg-1 profile-division"></div>
<!-----------------------------Start Profile Content-------------------------->
<!-----------------------------Start Company Introduction Content--------------->
	<div class="col-lg-8 profile-content edit-profile-show" id="edit-company-introduction-content">
		<form method="POST">
			<div class="container content-padding">
				
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-user"></i> Introduction</h1>
			</div>	
			<?php
			    if (isset($_SESSION["error"])&&$_SESSION['signed_in_rank']=='Company Representative'){
						echo ('<div class="row">
						<div class="col-lg-12 p-0">
						<div class="alert alert-danger text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    					<strong>'.htmlentities($_SESSION['error']).'</strong>
  						</div>
  						</div>
  						</div>');
						unset($_SESSION["error"]);
				}
			?>
			<div class="row">
				<div class="col-lg-12  notification-panel">
					<div class="scroll-bar">
				<div class="form-padding">		
			<div class="row">
				<h3 class="header-edit"><i class="fas fa-building"></i> About Your Company</h3>
			</div>
			<div class="row">
				<textarea id="description" maxlength="4294967295" rows="10" name="bio" class="textarea-edit" placeholder="Describe your company"><?= htmlentities($about_company) ?></textarea>
			</div>
			</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<input type="submit" name="update-company" value="UPDATE" class="btn btn-success btn-block btn-lg">
			</div>
			<div class="col-lg-6">
				<input type="button" name="cancel" value="CANCEL" onclick="window.location.href='<?php echo("viewProfileIntro.php?userId=".$_SESSION['signed_in_user']) ?>'" class="btn btn-warning btn-block btn-lg">
			</div>
			
		</div>
	</form>
	</div>
<!-----------------------------Start Student Introduction Content-------------->
	
	<div class="col-lg-8 profile-content edit-profile-show" id="edit-student-introduction-content">
			<form method="POST">
			<div class="container content-padding">
				
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-user"></i> Introduction</h1>
			</div>	
			<?php
			        	if (isset($_SESSION["error"])&&$_SESSION['signed_in_rank']=='Student'){
						echo ('<div class="row">
						<div class="col-lg-12 p-0">
						<div class="alert alert-danger text-center">
    					<strong>'.htmlentities($_SESSION['error']).'</strong>
  						</div>
  						</div>
  						</div>');
						unset($_SESSION["error"]);
					}
			?>
			<div class="row">
				<div class="col-lg-12  notification-panel">
					<div class="scroll-bar">
					<div class="form-padding">		
			<div class="row">
				<h3 class="header-edit"><i class="fas fa-address-card"></i> About Yourself</h3>
			</div>
			<div class="row">
				<textarea id="description"  rows="7" maxlength="4294967295" name="bio" class="textarea-edit" placeholder="Describe yourself"><?= htmlentities($about_student) ?></textarea>
			</div>
			<div class="row">
				<h3 class="header-edit"><i class="fas fa-star"></i> Skills</h3>
			</div>
			<div class="row">
				<input type="text" maxlength="16777215" name="skills" placeholder="Example: First Skill,Second Skill,...." id="input-skills-interests" value="<?= htmlentities($student_skill) ?>">
			</div>
			
			</div>
					</div>
				</div>
			</div>

		</div>
		<div class="row">
						<div class="col-lg-6">
							<input type="submit" name="update-student" value="UPDATE" class="btn btn-success btn-block btn-lg">
						</div>
						<div class="col-lg-6">
							<input type="button" name="cancel" value="CANCEL" onclick="window.location.href='<?php echo("viewProfileIntro.php?userId=".$_SESSION['signed_in_user']) ?>'" class="btn btn-warning btn-block btn-lg">
						</div>	
			</div>
		</form>
		</div>
<!-----------------------------Start teacher Introduction Content------------->
		<div class="col-lg-8 profile-content edit-profile-show" id="edit-teacher-introduction-content">
			<form method="POST">
			<div class="container content-padding">
				
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-user"></i> Introduction</h1>
			</div>	
			<?php
			        	if (isset($_SESSION["error"])&&$_SESSION['signed_in_rank']=='Teacher'){
						echo ('<div class="row">
						<div class="col-lg-12 p-0">
						<div class="alert alert-danger text-center">
    					<strong>'.htmlentities($_SESSION['error']).'</strong>
  						</div>
  						</div>
  						</div>');
						unset($_SESSION["error"]);
					}
			?>
			<div class="row">
				<div class="col-lg-12  notification-panel">
					<div class="scroll-bar">
					<div class="form-padding">		
			<div class="row">
				<h3 class="header-edit"><i class="fas fa-address-card"></i> About Yourself</h3>
			</div>
			<div class="row">
				<textarea id="description" rows="7" maxlength="4294967295" name="bio" class="textarea-edit" placeholder="Describe yourself"><?= htmlentities($about_teacher) ?></textarea>
			</div>
			<div class="row">
				<h3 class="header-edit"><i class="fas fa-star"></i> Area of Interests</h3>
			</div>
			<div class="row">
				<input type="text" maxlength="16777215" name="teacherskills" placeholder="Example: First Skill,Second Skill,...." id="input-skills-interests" value="<?= htmlentities($teacher_skill) ?>">
			</div>
			
			</div>
					</div>
				</div>
			</div>

		</div>
		<div class="row">
			<div class="col-lg-6">
				<input type="submit" name="update-teacher" value="UPDATE" class="btn btn-success btn-block btn-lg">
			</div>
			<div class="col-lg-6">
				<input type="button" name="cancel" value="CANCEL" onclick="window.location.href='<?php echo("viewProfileIntro.php?userId=".$_SESSION['signed_in_user']) ?>'" class="btn btn-warning btn-block btn-lg">
			</div>
		</div>
		</form>
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
	<a href=<?php echo('"viewProfileInfo.php?userId='.$_SESSION['signed_in_user'].'"'); ?> class="nav__link" id="bottom-information-link">
    <i class="fas fa-info"></i>
    <span class="nav__text">Account Information</span>
  	</a>

  <a href="editProfileIntro.php" class="nav__link nav__link--active" id="bottom-introduction-link">
    <i class="fas fa-user"></i>
    <span class="nav__text">Introduction</span>
  </a>
  
  <a href="editProfileTasks.php" class="nav__link edit-profile-show" id="bottom-tasks-link">
    <i class="fas fa-tasks"></i>
    <span class="nav__text">Tasks</span>
  </a>
</nav>	
</body>
</html>