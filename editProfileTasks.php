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
	$viewFlag=$_SESSION['signed_in_rank'];
	$stmt = $pdo->prepare("SELECT * FROM user where Email_id = :xyz");
	$stmt->execute(array(":xyz" => $_SESSION['signed_in_user']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
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
     function addLikes(id,action) {
    	var likes = parseInt($('#likes-'+id).text());

         var xmlhttp=new XMLHttpRequest();
  		 xmlhttp.open("GET","updateNotification.php?id="+id+"&action="+action,true);
  		 xmlhttp.send();

	switch(action) {
		case "like":
		$('#post-'+id).addClass("liked");
		$('#post-'+id).attr("onclick","addLikes("+id+",'unlike')");
		likes = likes+1;
		break;
		case "unlike":
		$('#post-'+id).removeClass("liked");
		$('#post-'+id).attr("onclick","addLikes("+id+",'like')");
		likes = likes-1;
		break;
	}
	$('#likes-'+id).text(likes);
}
function addStars(id,action) {
    	var stars = parseInt($('#stars-'+id).text());

         var xmlhttp=new XMLHttpRequest();
  		 xmlhttp.open("GET","updateNotification.php?id="+id+"&action="+action,true);
  		 xmlhttp.send();

	switch(action) {
		case "star":
		$('#postStar-'+id).addClass("starred");
		$('#postStar-'+id).attr("onclick","addStars("+id+",'unstar')");
		stars = stars+1;
		break;
		case "unstar":
		$('#postStar-'+id).removeClass("starred");
		$('#postStar-'+id).attr("onclick","addStars("+id+",'star')");
		stars = stars-1;
		break;
	}
	$('#stars-'+id).text(stars);
}
	</script>
</head>
<body>
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
							<a href=<?php echo('"viewProfileInfo.php?userId='.$_SESSION['signed_in_user'].'"'); ?> class="menu-link" id="information-link"><li><i class="fas fa-info" id="info"></i>Account Information</li></a>

							<a href=<?php echo('"viewProfileIntro.php?userId='.$_SESSION['signed_in_user'].'"'); ?> class="menu-link" id="introduction-link"><li><i class="fas fa-user" id="intro"></i>Introduction</li></a>

							<a href=<?php echo("editProfileTasks.php"); ?> class="menu-link active" id="tasks-link"><li><i class="fas fa-tasks" id="tasks"></i>Tasks</li></a>

						</ul>
					</div>
				</div>
			</div>
	</div>
	<div class="col-lg-1 profile-division"></div>
<!-----------------------------Start Profile Content-------------------------->
<!-----------------------------Start tasks Content-------------------->
	<div class="col-lg-8 profile-content" id="edit-tasks-content">
		<div class="container content-padding">
				
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-tasks"></i> Tasks</h1>
			</div>
			<?php 
				if (isset($_SESSION["success"])) {
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
					<div class="task-panel">
					<?php
					include 'postshowProfile.php';
					getMultiPost($_SESSION['signed_in_user']);
					?>	
					</div>	
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-lg-12 pb-0 pt-0">
				<button class="btn btn-outline-info btn-md w-100 mb-2" data-toggle="modal" data-target="#exampleModalShareProject"><i class="fas fa-paperclip"></i> Add A New Task</button>
			</div>
		</div>
	</div>
<!-----------------------------End Profile Content---------------------->		
</div>	
</div>
<!-----------------------------Add New task-------------------->
<div class="modal" id="exampleModalShareProject" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content anime">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="exampleModalScrollableTitle">Share Your Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="container">
		<form action="postUpload.php"  method="post" class="needs-validation"  enctype="multipart/form-data" novalidate >
			<div class="form-group mt-1">
				<label for="uname">Project Name:<span class="req">*</span></label>
				<input type="text" class="form-control" id="ProjectName" placeholder="Project Name" name="ProjectName" required>
				<div class="invalid-feedback">Please fill out this field.</div>
			</div>

			<div class="form-group">
				<label for="description">Project Description:<span class="req">*</span></label>
				<textarea class="form-control" rows="10" id="description" placeholder="Write about your project" name="description" required></textarea>
				<div class="invalid-feedback">Please fill out this field.</div>
			</div>

			<div class="custom-file">
				<input type="file" class="custom-file-input" id="customFile" name="file[]" multiple="">
				<label class="custom-file-label" for="customFile">Choose file</label>
			</div>
			
			<h6>Project Type:</h6>
			
			<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" id="customCheck1" name="ch[]" value="SD">
				<label class="custom-control-label" for="customCheck1">Software Development</label>
			</div>
			
			<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" id="customCheck2" name="ch[]" value="HD">
				<label class="custom-control-label" for="customCheck2">Hardware Development</label>
			</div>
			
			<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" id="customCheck3" name="ch[]" value="Robo">
				<label class="custom-control-label" for="customCheck3">Robotics</label>
			</div>
			
			<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" id="customCheck4" name="ch[]" value="Art">
				<label class="custom-control-label" for="customCheck4">Art</label>
			</div>
			
			<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" id="customCheck5" name="ch[]" value="Docu">
				<label class="custom-control-label" for="customCheck5">Documentation or Paper</label>
			</div>
			<div class="form-group">
			<button type="submit" name="posting" class="btn btn-outline-success mt-2 w-100">POST</button>	
			</div>
		</form>
	</div>
      </div>
    </div>
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
  <a href=<?php echo('"viewProfileIntro.php?userId='.$_SESSION['signed_in_user'].'"'); ?> class="nav__link" id="bottom-introduction-link">
    <i class="fas fa-user"></i>
    <span class="nav__text">Introduction</span>
  </a>
  <a href="editProfileTasks.php" class="nav__link nav__link--active" id="bottom-tasks-link">
    <i class="fas fa-tasks"></i>
    <span class="nav__text">Tasks</span>
  </a>
</nav>	
</body>
</html>
