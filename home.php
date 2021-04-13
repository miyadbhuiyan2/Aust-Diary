<?php 
	session_start();
	include 'includes\connect.php';
	include 'notificationView.php';
	require_once "pdo.php";

	global $opData;
$stmt = $pdo->query("SELECT * FROM outerpages");
$opDatas = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($opDatas as $data) {
$opData[$data['name']]=$data['value'];
}
if (isset($_POST['projectDeptFilter'])||isset($_POST['projectSemesterFilter'])||isset($_POST['projectYearFilter'])||isset($_POST['projectTypeFilter'])) {
	$sql = "SELECT * FROM postshow, user WHERE postshow.User_id = user.User_id ";
 	if (isset($_POST['dept'])) {
 		$deptF = "'". implode("','", $_POST['dept']) ."'";
 		$sql.="AND user.Department IN (".$deptF.")";
 		
 	}
 	if (isset($_POST['semester'])) {
 		$semF = "'". implode("','", $_POST['semester']) ."'";
 		$sql.="AND user.Semester IN (".$semF.")";
 	}
 	if (isset($_POST['year'])) {
 		$yearF =implode(",", $_POST['year']);
 		$sql.="AND user.Year IN (".$yearF.")";
 	}
 	if (isset($_POST['pType'])) {
 		$pTypeF = implode(",", $_POST['pType']);
 		$sql.="AND postshow.tags LIKE '%".$pTypeF."%'";
 	}
					 	
	header("Location:home.php?query=".$sql);
	return;				
}
else if (isset($_POST['searchBt'])&&(!empty($_POST['searchBar']))) {
	header("Location:home.php?searchValue=".$_POST['searchBar']);
	return;
}
?>


<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	 <link rel="stylesheet" href="css/main.css" />
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
    <title>Home</title>
</head>
<body>
<!---Navigation-->
<nav class="navbar navbar-expand-md navbar-dark">
	<a class="navbar-brand" href="home.php"><?php echo '<img src="DATABASE/img/'.htmlentities($opData['logoWhite']).'"/>'?></a>
<form action="home.php" method="post" class="index-search-form">
  <div class="input-group">
  
   <input type="text" class="form-control" placeholder="Search Here" name="searchBar"
   <?php
   if (isset($_GET['searchValue'])) {
   	echo 'value="'.$_GET['searchValue'].'"';
   }
   ?>
   >
  <div class="input-group-append">
    <button class="btn btn-light" type="submit" name="searchBt"><i class="fas fa-search"></i></button>
  </div>
</div>
</form>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarExpandView">
		<span class="navbar-toggler-icon"></span><sup id="notification-dot"><i class="fas fa-circle"></i></sup>
	</button>
	<div class="collapse navbar-collapse" id="navbarExpandView">
		<ul class="navbar-nav ml-auto">
			<li data-toggle="modal" data-target="#exampleModalShareProject">
				<a class="nav-link"><i class="fas fa-plus" ></i> CREATE</a>
			</li>
			<li>
				<a class="nav-link active" href="home.php"><i class="fas fa-newspaper"></i> HOME</a>
			</li>
			<li>
				<a class="nav-link" href=<?php echo('"viewProfileInfo.php?userId='.$_SESSION['signed_in_user'].'"'); ?>><i class="fas fa-user-alt"></i> PROFILE</a>
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
<!-----------------------------Add New task-------------------->
<div class="modal" id="exampleModalShareProject" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content anime">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="exampleModalScrollableTitle"><i class="fas fa-plus" ></i> Share Your Project</h5>
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
<!-----------------------------Trending Modal-------------------->
<div class="modal" id="exampleModalTrending" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content anime">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="exampleModalScrollableTitle"><i class="fas fa-star" ></i> Trending For You</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<?php 
			include 'postshow.php';
			showpost(); 
		?>
      </div>
    </div>
  </div>
</div>
<!-----------------------------Filter Modal-------------------->
<div class="modal" id="exampleModalFilter" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content anime">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="exampleModalScrollableTitle"><i class="fas fa-sort-amount-down-alt" ></i> Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div>
		<div class="card">
						<div class="card-header">
							<a class="card-link" data-toggle="collapse" href="#collapseOne">Department</a>
						</div>
						<div id="collapseOne" class="collapse show" data-parent="#accordion">
							<div class="card-body">
								<form method="POST">
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="arc" name="dept[]" value="Architecture"
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Architecture")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="arc">Architecture</label>
									</div>
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="cse" name="dept[]" value="Computer Science And Engineering" <?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Computer Science And Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="cse">Computer Science And Engineering</label>
									</div>
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="eee" name="dept[]" value="Electrical And Electronics Engineering" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Electrical And Electronics Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="eee">Electrical And Electronics Engineering</label>
									</div>
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="te" name="dept[]" value="Textile Engineering" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Textile Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="te">Textile Engineering</label>
									</div>

									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="ipe" name="dept[]" value="Industrial And Production Engineering" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Industrial And Production Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="ipe">Industrial And Production Engineering</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="me" name="dept[]" value="Mechanical Engineering" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Mechanical Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="me">Mechanical Engineering</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="bba" name="dept[]" value="Bachelor of Business Administration" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Bachelor of Business Administration")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="bba">Bachelor of Business Administration</label>
									</div>
									
									<br>
									
										<button type="submit" name="projectDeptFilter" class="btn btn-outline-success btn-block btn-sm">Apply</button>
									
							</div>
						</div>
			</div>
<!------------------------------------- Semester -->
					<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">Semester</a>
						</div>
						<div id="collapseTwo" class="collapse" data-parent="#accordion">
							<div class="card-body">
							
								
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckS1" name="semester[]" value="Spring" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Spring")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckS1">Spring</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckS2" name="semester[]" value="Fall" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Fall")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckS2">Fall</label>
									</div>
									<br>
									
										<button type="submit"name="projectSemesterFilter" class="btn btn-outline-success btn-block btn-sm">Apply</button>
									
							</div>
						</div>
					</div>
<!------------------------------------- Year -->
					<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">Year</a>
						</div>
						<div id="collapseThree" class="collapse" data-parent="#accordion">
							<div class="card-body">
								
								
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY1" name="year[]" value="2020" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2020")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY1">2020</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY2" name="year[]" value="2019"
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2019")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY2">2019</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY3" name="year[]" value="2018" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2018")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY3">2018</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY4" name="year[]" value="2017" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2017")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY4">2017</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY5" name="year[]" value="2016" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2016")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY5">2016</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY6" name="year[]" value="2015" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2015")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY6">2015</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY7" name="year[]" value="2014" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2014")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY7">2014</label>
									</div>
									<br>
									
										<button type="submit" name="projectYearFilter" class="btn btn-outline-success btn-block btn-sm">Apply</button>
									
								
							</div>
						</div>
					</div>
					<!------------------------------------- Project type -->
		<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapseFour">Project Type</a>
						</div>
						<div id="collapseFour" class="collapse" data-parent="#accordion">
							<div class="card-body">
								
								
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT1" name="pType[]" value="SD" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"SD")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT1">Software Development</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT2" name="pType[]" value="HD" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"HD")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT2">Hardware Development</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT3" name="pType[]" value="Robo" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Robo")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT3">Robotics</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT4" name="pType[]" value="Art" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Art")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT4">Art</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT5" name="pType[]" value="Docu" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Docu")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT5">Documentation or Paper</label>
									</div>
									
									<br>
									
										<button type="submit" name="projectTypeFilter" class="btn btn-outline-success btn-block btn-sm">Apply</button>
									
							</form>

				</div>
			</div>
		</div>
		</div>
      </div>
    </div>
  </div>
</div>	
<!------------------------------------ Body ---------------------------------->			
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3 accordionHome fixed-top">
			<div id="accordion">
<!------------------------------------- Department -->
					<div class="card">
						<div class="card-header">
							<a class="card-link" data-toggle="collapse" href="#collapseOnee">Department</a>
						</div>
						<div id="collapseOnee" class="collapse show" data-parent="#accordion">
							<div class="card-body">
								<form method="POST">
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="arcc" name="dept[]" value="Architecture"
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Architecture")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="arcc">Architecture</label>
									</div>
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="csee" name="dept[]" value="Computer Science And Engineering" <?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Computer Science And Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="csee">Computer Science And Engineering</label>
									</div>
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="eeee" name="dept[]" value="Electrical And Electronics Engineering" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Electrical And Electronics Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="eeee">Electrical And Electronics Engineering</label>
									</div>
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="tee" name="dept[]" value="Textile Engineering" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Textile Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="tee">Textile Engineering</label>
									</div>

									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="ipee" name="dept[]" value="Industrial And Production Engineering" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Industrial And Production Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="ipee">Industrial And Production Engineering</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="mee" name="dept[]" value="Mechanical Engineering" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Mechanical Engineering")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="mee">Mechanical Engineering</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="bbaa" name="dept[]" value="Bachelor of Business Administration" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Bachelor of Business Administration")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="bbaa">Bachelor of Business Administration</label>
									</div>
									
									<br>
									
										<button type="submit" name="projectDeptFilter" class="btn btn-outline-success btn-block btn-sm">Apply</button>
									
							</div>
						</div>
			</div>
<!------------------------------------- Semester -->
					<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapseTwoo">Semester</a>
						</div>
						<div id="collapseTwoo" class="collapse" data-parent="#accordion">
							<div class="card-body">
							
								
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckS11" name="semester[]" value="Spring" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Spring")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckS11">Spring</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckS22" name="semester[]" value="Fall" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Fall")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckS22">Fall</label>
									</div>
									<br>
									
										<button type="submit"name="projectSemesterFilter" class="btn btn-outline-success btn-block btn-sm">Apply</button>
									
							</div>
						</div>
					</div>
<!------------------------------------- Year -->
					<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapseThreee">Year</a>
						</div>
						<div id="collapseThreee" class="collapse" data-parent="#accordion">
							<div class="card-body">
								
								
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY11" name="year[]" value="2020" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2020")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY11">2020</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY22" name="year[]" value="2019"
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2019")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY22">2019</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY33" name="year[]" value="2018" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2018")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY33">2018</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY44" name="year[]" value="2017" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2017")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY44">2017</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY55" name="year[]" value="2016" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2016")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY55">2016</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY66" name="year[]" value="2015" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2015")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY66">2015</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckY77" name="year[]" value="2014" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"2014")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckY77">2014</label>
									</div>
									<br>
									
										<button type="submit" name="projectYearFilter" class="btn btn-outline-success btn-block btn-sm">Apply</button>
									
								
							</div>
						</div>
					</div>
<!------------------------------------- Project type -->
		<div class="card">
						<div class="card-header">
							<a class="collapsed card-link" data-toggle="collapse" href="#collapseFourr">Project Type</a>
						</div>
						<div id="collapseFourr" class="collapse" data-parent="#accordion">
							<div class="card-body">
								
								
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT11" name="pType[]" value="SD" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"SD")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT11">Software Development</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT22" name="pType[]" value="HD" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"HD")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT22">Hardware Development</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT33" name="pType[]" value="Robo" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Robo")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT33">Robotics</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT44" name="pType[]" value="Art" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Art")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT44">Art</label>
									</div>
									
									<div class="custom-control custom-checkbox mb-3">
										<input type="checkbox" class="custom-control-input" id="customCheckPT55" name="pType[]" value="Docu" 
										<?php 
										if (isset($_GET['query'])&&strpos($_GET['query'],"Docu")) {
											echo "checked";
										}
										?>
										>
										<label class="custom-control-label" for="customCheckPT55">Documentation or Paper</label>
									</div>
									
									<br>
									
										<button type="submit" name="projectTypeFilter" class="btn btn-outline-success btn-block btn-sm">Apply</button>
									
							</form>

				</div>
			</div>
		</div>
	</div>
</div>
		<!------------------------------ Post Show Panel------------------------------------->	
			<div class="col-md-6 cardHome offset-sm-3">
				<div>
				<?php 
					if (isset($_GET['query'])) {
					 	getMultiPost($_GET['query']);
					 	unset($_GET['query']);
					
					}
					else if (isset($_GET['searchValue'])) {
						$sql = "SELECT * FROM user WHERE Concat(FirstName,' ',LastName) LIKE '%".$_GET['searchValue']."%'";
					 	getUserCard($sql);
					 	$sql = "SELECT * FROM postshow, user WHERE postshow.User_id = user.User_id AND Concat(FirstName,' ',LastName) LIKE '%".$_GET['searchValue']."%'";					 	
					 	getMultiPost($sql);
					 	$sql = "SELECT * FROM postshow, user WHERE postshow.User_id = user.User_id AND ProjectName LIKE '%".$_GET['searchValue']."%'";					 	
					 	getMultiPost($sql);
					 	unset($_GET['searchValue']);
					
					}
					else {

						$sql = "SELECT * FROM postshow, user WHERE postshow.User_id = user.User_id ORDER BY postshow.timedate DESC";
					getMultiPost($sql);
				
					}
					
				?>
			</div>
			</div>
			
			<div class="col-md-3 trandingHome" id="trendingPanel">
				<div class="container">
					<h2 class="ml-2">Trending for you</h2>
					<div class="container-fluid">
						<?php 
							showpost(); 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<!--  JavaScript -->
	<script src="Bootstrap/js/jquery.min.js">
	</script>
	<script src="Bootstrap/js/popper.min.js"></script>
	<script src="Bootstrap/js/bootstrap.min.js"></script>
<!--Bottom Navigation-->
<nav class="nav-bottom" id="bottom-nav">
  <a data-toggle="modal" href="#exampleModalFilter" class="nav__link" id="bottom-information-link">
   <i class="fas fa-sort-amount-down-alt"></i>
    <span class="nav__text">Filter</span>
  	</a>		
  <a data-toggle="modal" href="#exampleModalTrending" class="nav__link" id="bottom-introduction-link">
    <i class="fas fa-star"></i>
    <span class="nav__text">Trending</span>
  </a>
</nav>	
</body>
</html>