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
	$viewFlag=$_GET['userId'];
	$stmt = $pdo->prepare("SELECT * FROM user where Email_id = :xyz");
		$stmt->execute(array(":xyz" => $_GET['userId']));
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
							<a href=<?php echo('"viewProfileInfo.php?userId='.$_GET['userId'].'"'); ?> class="menu-link" id="information-link"><li><i class="fas fa-info" id="info"></i>Account Information</li></a>
							
							<a href=<?php echo('"viewProfileIntro.php?userId='.$_GET['userId'].'"'); ?> class="menu-link" id="introduction-link"><li><i class="fas fa-user" id="intro"></i>Introduction</li></a>

							<a href=<?php 
							if ($_GET['userId']==$_SESSION['signed_in_user']) {
								echo("editProfileTasks.php");	
								}
								else{
								echo('"viewProfileTasks.php?userId='.$_GET['userId'].'"');
								}	
							 ?> class="menu-link active" id="tasks-link"><li><i class="fas fa-tasks" id="tasks"></i>Tasks</li></a>

						</ul>
					</div>
				</div>
			</div>
	</div>
	<div class="col-lg-1 profile-division"></div>
<!-----------------------------Start Profile Content-------------------------->
<!-----------------------------Start tasks Content-------------------->
		<div class="col-lg-8 profile-content" id="view-tasks-content">
			<div class="container content-padding">
				
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-tasks"></i> Tasks</h1>
			</div>	
			<div class="row">
				<div class="col-lg-12  notification-panel">
					<div class="scroll-bar">
						<div class="task-panel">
						<?php
						include 'postshowProfile.php';
						getMultiPost($_GET['userId']);
						?>	
						</div>
					</div>
				</div>
			</div>
		</div>
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
   <a href=<?php echo('"viewProfileInfo.php?userId='.$_GET['userId'].'"'); ?> class="nav__link" id="bottom-information-link">
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
	 ?> class="nav__link nav__link--active" id="bottom-tasks-link">
    <i class="fas fa-tasks"></i>
    <span class="nav__text">Tasks</span>
  </a>
</nav>	
</body>
</html>