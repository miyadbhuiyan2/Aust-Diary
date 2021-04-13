<?php
session_start();
require_once "pdo.php";
global $opData;
$stmt = $pdo->query("SELECT * FROM outerpages");
$opDatas = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($opDatas as $data) {
$opData[$data['name']]=$data['value'];
}
global $row;
if ( ! isset($_SESSION['signed_in_user']) ) {
  $_SESSION["warning"] = "Please sign in first";
  header("Location:signin.php");
  return;
}
else{	$s_fName = null;
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

		$viewFlag=$_SESSION['signed_in_rank'];
		$stmt = $pdo->prepare("SELECT * FROM user where Email_id = :xyz");
		$stmt->execute(array(":xyz" => $_SESSION['signed_in_user']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($_SESSION['signed_in_rank']=='Student') {
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
		else if ($_SESSION['signed_in_rank']=='Teacher') {
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
		else if ($_SESSION['signed_in_rank']=='Company Representative'){
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
try {
	if (isset($_POST['update-student'])) {
		if (file_exists($_FILES['s-image']['tmp_name'])) {
			unlink('DATABASE/User_Profile_Picture/'.$row['Image']);
		  	
		  	$imageFileType = strtolower(pathinfo($_FILES["s-image"]["name"],PATHINFO_EXTENSION));
			$newImagename=$row['User_id'].'.'.$imageFileType;
			$target_dir = "DATABASE/User_Profile_Picture/".$newImagename;
		  	move_uploaded_file($_FILES['s-image']['tmp_name'], $target_dir);

		  	 $stmt = $pdo->prepare("UPDATE user SET Image =:im WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':im' => $newImagename));
		}

		if (empty($_POST['currentpassword'])==0) {
			if (hash('md5',$_POST['currentpassword'])==$row['Password']) {
				if ($_POST['newpassword']==$_POST['confirmnewpassword']) {
					 $stmt = $pdo->prepare("UPDATE user SET Password =:pw WHERE Email_id = :a_id;");
		            $stmt->execute(array(
		              ':a_id' => $_SESSION['signed_in_user'],
		          	  ':pw' => hash('md5',$_POST['newpassword'])));
				}
				else
				{
				$_SESSION['error'] = "New password doesn't match with confirm password";
				header("Location:editProfileInfo.php");
				return;
				}
			}
			else{
				$_SESSION['error'] = "Incorrect current password";
				header("Location:editProfileInfo.php");
				return;
			}
		}
      $stmt = $pdo->prepare("UPDATE user SET FirstName =:fn,LastName=:ln,Email_id=:ei,ContactNumber=:cn,Gender =:gen,IdNumber=:id,Department=:dept,Semester=:sem,Year =:yr,Address=:ad WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':fn' => $_POST['fname'],
              ':ln' => $_POST['lname'],
              ':ei' => $_POST['email'],
              ':cn' => $_POST['contactnumber'],
              ':gen' => $_POST['gender'],
              ':id' => $_POST['id'],
              ':dept' => $_POST['department'],
              ':sem' => $_POST['semester'],
              ':yr' => $_POST['year'],
          	  ':ad' => $_POST['address'])
            );
            $_SESSION['signed_in_user']=$_POST['email'];
            $_SESSION['success'] = "Account Information has been updated successfully";
            header("Location:viewProfileInfo.php?userId=".$_SESSION['signed_in_user']);
            return;
	}
	else if (isset($_POST['update-teacher'])) {
		if (file_exists($_FILES['t-image']['tmp_name'])) {
			unlink('DATABASE/User_Profile_Picture/'.$row['Image']);
		  	
		  	$imageFileType = strtolower(pathinfo($_FILES["t-image"]["name"],PATHINFO_EXTENSION));
			$newImagename=$row['User_id'].'.'.$imageFileType;
			$target_dir = "DATABASE/User_Profile_Picture/".$newImagename;
		  	move_uploaded_file($_FILES['t-image']['tmp_name'], $target_dir);

		  	 $stmt = $pdo->prepare("UPDATE user SET Image =:im WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':im' => $newImagename));
		}

		if (empty($_POST['currentpassword'])==0) {
			if (hash('md5',$_POST['currentpassword'])==$row['Password']) {
				if ($_POST['newpassword']==$_POST['confirmnewpassword']) {
					 $stmt = $pdo->prepare("UPDATE user SET Password =:pw WHERE Email_id = :a_id;");
		            $stmt->execute(array(
		              ':a_id' => $_SESSION['signed_in_user'],
		          	  ':pw' => hash('md5',$_POST['newpassword'])));
				}
				else
				{
				$_SESSION['error'] = "New password doesn't match with confirm password";
				header("Location:editProfileInfo.php");
				return;
				}
			}
			else{
				$_SESSION['error'] = "Incorrect current password";
				header("Location:editProfileInfo.php");
				return;
			}
		}
		 $stmt = $pdo->prepare("UPDATE user SET FirstName =:fn,LastName=:ln,Email_id=:ei,ContactNumber=:cn,Gender =:gen,Department=:dept,Address=:ad WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':fn' => $_POST['fname'],
              ':ln' => $_POST['lname'],
              ':ei' => $_POST['email'],
              ':cn' => $_POST['contactnumber'],
              ':gen' => $_POST['gender'],
              ':dept' => $_POST['department'],
          	  ':ad' => $_POST['address'])
            );
            $_SESSION['signed_in_user']=$_POST['email'];
            $_SESSION['success'] = "Account Information has been updated successfully";
            header("Location:viewProfileInfo.php?userId=".$_SESSION['signed_in_user']);
            return;
	}
	else if (isset($_POST['update-company'])) {
		if (file_exists($_FILES['c-image']['tmp_name'])) {
			unlink('DATABASE/User_Profile_Picture/'.$row['Image']);
		  	
		  	$imageFileType = strtolower(pathinfo($_FILES["c-image"]["name"],PATHINFO_EXTENSION));
			$newImagename=$row['User_id'].'.'.$imageFileType;
			$target_dir = "DATABASE/User_Profile_Picture/".$newImagename;
		  	move_uploaded_file($_FILES['c-image']['tmp_name'], $target_dir);

		  	 $stmt = $pdo->prepare("UPDATE user SET Image =:im WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':im' => $newImagename));
		}

		if (empty($_POST['currentpassword'])==0) {
			if (hash('md5',$_POST['currentpassword'])==$row['Password']) {
				if ($_POST['newpassword']==$_POST['confirmnewpassword']) {
					 $stmt = $pdo->prepare("UPDATE user SET Password =:pw WHERE Email_id = :a_id;");
		            $stmt->execute(array(
		              ':a_id' => $_SESSION['signed_in_user'],
		          	  ':pw' => hash('md5',$_POST['newpassword'])));
				}
				else
				{
				$_SESSION['error'] = "New password doesn't match with confirm password";
				header("Location:editProfileInfo.php");
				return;
				}
			}
			else{
				$_SESSION['error'] = "Incorrect current password";
				header("Location:editProfileInfo.php");
				return;
			}
		}
		 $stmt = $pdo->prepare("UPDATE user SET FirstName =:fn,LastName=:ln,Email_id=:ei,ContactNumber=:cn,Gender =:gen,CompanyName=:cpn,CompanyType=:cpt,Address=:ad WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_SESSION['signed_in_user'],
              ':fn' => $_POST['fname'],
              ':ln' => $_POST['lname'],
              ':ei' => $_POST['email'],
              ':cn' => $_POST['contactnumber'],
              ':gen' => $_POST['gender'],
              ':cpn' => $_POST['companyName'],
              ':cpt' => $_POST['companyType'],
          	  ':ad' => $_POST['address'])
            );
            $_SESSION['signed_in_user']=$_POST['email'];
            $_SESSION['success'] = "Account Information has been updated successfully";
            header("Location:viewProfileInfo.php?userId=".$_SESSION['signed_in_user']);
            return;
	}
} catch (Exception $e) {
			if (strpos($e->getMessage(),'ContactNumber')==true) {
				$_SESSION['error'] = "This Contact Number already exists";
				header("Location:editProfileInfo.php");
				return;
			}
			elseif (strpos($e->getMessage(),'Email_id')==true) {
				$_SESSION['error'] = "This Email Address already exists";
				header("Location:editProfileInfo.php");
				return;
			}
			elseif (strpos($e->getMessage(),'IdNumber')==true) {
				$_SESSION['error'] = "This ID Number already exists";
				header("Location:editProfileInfo.php");
				return;
			}
			elseif (strpos($e->getMessage(),'CompanyName')==true) {
				$_SESSION['error'] = "This Company Name already exists";
				header("Location:editProfileInfo.php");
				return;
			}
			else{
				$_SESSION['error'] = $e->getMessage();
				header("Location:editProfileInfo.php");
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
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">	<link type="text/css" rel="stylesheet" href ="css/profile_edit.css"/>
	<script src="https://kit.fontawesome.com/15fc770d9f.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script lang="JavaScript">
		function create () {
		document.getElementById("notification-dot").classList.add('d-none');	
         var xmlhttp=new XMLHttpRequest();
  		 xmlhttp.open("GET","updateNotification.php",true);
  		 xmlhttp.send();
    }
		function cancelPassword(rank){
			
			if(rank=='Student'){
			document.getElementById("changePasswordField11").classList.add('d-none');
			document.getElementById("changePasswordField22").classList.add('d-none');
			document.getElementById("changePasswordField33").classList.add('d-none');
			document.getElementById("changePasswordField11").required=false;
			document.getElementById("changePasswordField22").required=false;
			document.getElementById("changePasswordField33").required=false;

			document.getElementById("cancelPassBt11").classList.add('d-none');
			document.getElementById("changePassBt11").classList.remove('d-none');
			}
			else if(rank=='Teacher'){
			document.getElementById("changePasswordField111").classList.add('d-none');
			document.getElementById("changePasswordField222").classList.add('d-none');
			document.getElementById("changePasswordField333").classList.add('d-none');
			document.getElementById("changePasswordField111").required=false;
			document.getElementById("changePasswordField222").required=false;
			document.getElementById("changePasswordField333").required=false;

			document.getElementById("cancelPassBt111").classList.add('d-none');
			document.getElementById("changePassBt111").classList.remove('d-none');
			}
			else if(rank=='Company Representative'){
			document.getElementById("changePasswordField1").classList.add('d-none');
			document.getElementById("changePasswordField2").classList.add('d-none');
			document.getElementById("changePasswordField3").classList.add('d-none');
			document.getElementById("changePasswordField1").required=false;
			document.getElementById("changePasswordField2").required=false;
			document.getElementById("changePasswordField3").required=false;

			document.getElementById("cancelPassBt1").classList.add('d-none');
			document.getElementById("changePassBt1").classList.remove('d-none');	
			}
		}
		function changePassword(rank){
			if(rank=='Student'){
			document.getElementById("changePasswordField11").classList.remove('d-none');
			document.getElementById("changePasswordField22").classList.remove('d-none');
			document.getElementById("changePasswordField33").classList.remove('d-none');
			document.getElementById("changePasswordField11").required=true;
			document.getElementById("changePasswordField22").required=true;
			document.getElementById("changePasswordField33").required=true;

			document.getElementById("cancelPassBt11").classList.remove('d-none');
			document.getElementById("changePassBt11").classList.add('d-none');
			}
			else if(rank=='Teacher'){
			document.getElementById("changePasswordField111").classList.remove('d-none');
			document.getElementById("changePasswordField222").classList.remove('d-none');
			document.getElementById("changePasswordField333").classList.remove('d-none');
			document.getElementById("changePasswordField111").required=true;
			document.getElementById("changePasswordField222").required=true;
			document.getElementById("changePasswordField333").required=true;

			document.getElementById("cancelPassBt111").classList.remove('d-none');
			document.getElementById("changePassBt111").classList.add('d-none');	
			}
			else if(rank=='Company Representative'){
			document.getElementById("changePasswordField1").classList.remove('d-none');
			document.getElementById("changePasswordField2").classList.remove('d-none');
			document.getElementById("changePasswordField3").classList.remove('d-none');
			document.getElementById("changePasswordField1").required=true;
			document.getElementById("changePasswordField2").required=true;
			document.getElementById("changePasswordField3").required=true;

			document.getElementById("cancelPassBt1").classList.remove('d-none');
			document.getElementById("changePassBt1").classList.add('d-none');	
			}

		}
		function viewSelector(rank){

			if(rank=='Student'){
				document.getElementById("edit-student-info-content").classList.remove('edit-profile-show');
				document.getElementById("tasks-link").classList.remove('edit-profile-show');
				document.getElementById("bottom-tasks-link").classList.remove('edit-profile-show');
			}
			else if(rank=='Teacher'){
				document.getElementById("edit-teacher-info-content").classList.remove('edit-profile-show');
				document.getElementById("tasks-link").classList.remove('edit-profile-show');
				document.getElementById("bottom-tasks-link").classList.remove('edit-profile-show');
			}
			else if(rank=='Company Representative'){
				document.getElementById("edit-company-info-content").classList.remove('edit-profile-show');
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
							<a href="editProfileInfo.php" class="menu-link active" id="information-link"><li><i class="fas fa-info" id="info"></i>Account Information</li></a>
							
							<a href=<?php echo('"viewProfileIntro.php?userId='.$_SESSION['signed_in_user'].'"'); ?> class="menu-link" id="introduction-link"><li><i class="fas fa-user" id="intro"></i>Introduction</li></a>
							
							<a href="editProfileTasks.php" class="menu-link edit-profile-show" id="tasks-link"><li><i class="fas fa-tasks" id="tasks"></i>Tasks</li></a>

						</ul>
					</div>
				</div>
			</div>
	</div>
	<div class="col-lg-1 profile-division"></div>
<!-----------------------------Start Profile Content-------------------------->
<!-----------------------------Start Company Info Content--------------------->
	<div class="col-lg-8 profile-content edit-profile-show" id="edit-company-info-content">
		<form method="POST" enctype="multipart/form-data">
			<div class="container content-padding">
				
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-info"></i> Account Information</h1>
			</div>	
			<?php
			        	if (isset($_SESSION["error"])&&$_SESSION['signed_in_rank']=='Company Representative'){
						echo ('<div class="row alert-box">
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
					
					<div class="row">
						<div class="col-lg-6">
							<label>First Name:</label>
							<input type="text" name="fname" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter your first name" value="<?=  htmlentities($c_fName) ?>" required>
						</div>
						<div class="col-lg-6">
							<label>Last Name:</label>
							<input type="text" name="lname" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter your last name" value="<?= htmlentities($c_lName) ?>" required>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label>E-mail:</label>
						<input type="email" name="email" maxlength="100" class="form-control" placeholder="Enter your E-mail" value="<?= htmlentities($c_email) ?>" required>
						</div>	
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label>Contact Number:</label>
						<input type="tel" name="contactnumber" pattern="[0]{1}[1]{1}[3]{1}[0-9]{8}|[0]{1}[1]{1}[5]{1}[0-9]{8}|[0]{1}[1]{1}[6]{1}[0-9]{8}|[0]{1}[1]{1}[7]{1}[0-9]{8}|[0]{1}[1]{1}[8]{1}[0-9]{8}|[0]{1}[1]{1}[9]{1}[0-9]{8}" maxlength="11" class="form-control" placeholder="Enter your contact number" value="<?= htmlentities($c_contactNumber) ?>" required>	
						</div>	
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label for="img">Select Image (If you want to change the profile picture):</label><br>
						<input type="file" id="img" name="c-image" accept="image/png, image/jpeg">
						</div>	
					</div>			
					<div class="row">
						<div class="col-lg-12">
							<label>Password:</label>
							<br>
							<input type="password" name="currentpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField1" placeholder="Enter current password">

							<input type="password" name="newpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField2" placeholder="Enter new password">

							<input type="password" name="confirmnewpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField3" placeholder="Confirm new password">
							<input type="button" class="btn btn-danger d-none" id="cancelPassBt1" value="Cancel" onclick="cancelPassword('Company Representative')">
							<input type="button" class="btn btn-primary" id="changePassBt1" value="Change Password" onclick="changePassword('Company Representative')">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<label>Gender:</label>
							<select class="form-control select-border" name="gender" required>
							<option value="">Choose Gender</option>
							<option value="Male" <?php if($c_gender=="Male") echo 'selected="selected"'; ?>>Male</option>
							<option value="Female" <?php if($c_gender=="Female") echo 'selected="selected"'; ?>>Female</option>
							</select>
						</div>
					</div>
					<div class="row" id="companyName">
						<div class="col-lg-12">
						<label>Company Name:</label>
						<input type="text" name="companyName" id="companyNameBox" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter company name" value="<?= htmlentities($c_companyName) ?>" required>	
						</div>	
					</div>
					<div class="row" id="companyKind">
						<div class="col-lg-12">
						<label>What Kind of Comapny:</label>
						<input type="text" name="companyType" id="companyKindBox" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Example: Software Company" value="<?= htmlentities($c_companyType) ?>" required>	
						</div>	
					</div>
					<div class="row" id="address">
						<div class="col-lg-12">
						<label id="studentTeacherAddress">Company Address:</label>
						<textarea name="address" maxlength="200" required><?= htmlentities($c_companyAddress) ?>
						</textarea>
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
					<input type="button" name="cancel" value="CANCEL" onclick="window.location.href='<?php echo("viewProfileInfo.php?userId=".$_SESSION['signed_in_user']) ?>'" class="btn btn-warning btn-block btn-lg">
				</div>
		</div>
		</form>
	</div>	
<!-----------------------------Start Student Info Content-------------------->
	<div class="col-lg-8 profile-content edit-profile-show" id="edit-student-info-content">
		<form method="POST" enctype="multipart/form-data">
			<div class="container content-padding">	
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-info"></i> Account Information</h1>
			</div>	
			<?php
	        	if (isset($_SESSION["error"])&&$_SESSION['signed_in_rank']=='Student'){
				echo ('<div class="row alert-box">
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
					
					<div class="row">
						<div class="col-lg-6">
							<label>First Name:</label>
							<input type="text" name="fname" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter your first name" value="<?= htmlentities($s_fName) ?>" required>
						</div>
						<div class="col-lg-6">
							<label>Last Name:</label>
							<input type="text" name="lname" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter your last name" value="<?= htmlentities($s_lName) ?>" required>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label>E-mail:</label>
						<input type="email" name="email" maxlength="100" class="form-control" placeholder="Enter your E-mail" value="<?= htmlentities($s_email) ?>" required>
						</div>	
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label>Contact Number:</label>
						<input type="tel" name="contactnumber" pattern="[0]{1}[1]{1}[3]{1}[0-9]{8}|[0]{1}[1]{1}[5]{1}[0-9]{8}|[0]{1}[1]{1}[6]{1}[0-9]{8}|[0]{1}[1]{1}[7]{1}[0-9]{8}|[0]{1}[1]{1}[8]{1}[0-9]{8}|[0]{1}[1]{1}[9]{1}[0-9]{8}" maxlength="11" class="form-control" placeholder="Enter your contact number" value="<?= htmlentities($s_contactNumber) ?>" required>	
						</div>	
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label for="img">Select Image (If you want to change the profile picture):</label><br>
						<input type="file" id="img" name="s-image" accept="image/png, image/jpeg">
						</div>	
					</div>			
					<div class="row">
						<div class="col-lg-12">
							<label>Password:</label>
							<br>
							<input type="password" name="currentpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField11" placeholder="Enter current password">

							<input type="password" name="newpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField22" placeholder="Enter new password">

							<input type="password" name="confirmnewpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField33" placeholder="Confirm new password">
							<input type="button" class="btn btn-danger d-none" id="cancelPassBt11" value="Cancel" onclick="cancelPassword('Student')">
							<input type="button" class="btn btn-primary" id="changePassBt11" value="Change Password" onclick="changePassword('Student')">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<label>Gender:</label>
							<select class="form-control select-border" name="gender" required>
							<option value="">Choose Gender</option>
							<option value="Male" <?php if($s_gender=="Male") echo 'selected="selected"'; ?>>Male</option>
							<option value="Female" <?php if($s_gender=="Female") echo 'selected="selected"'; ?>>Female</option>
							</select>
						</div>
					</div>
			<div class="row" id="idNumber">
						<div class="col-lg-12">
						<label>ID Number:</label>
						<input type="text" name="id" id="idNumberBox" pattern="[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{3}" class="form-control" placeholder="Example:17.02.04.110" maxlength="15" value="<?= htmlentities($s_idNumber) ?>" required>	
						</div>	
			</div>
			<div class="row" id="department">
						<div class="col-lg-12">
							<label>Department:</label>
							<select class="form-control select-border" id="departmentBox" name="department" required>
							<option value="">Choose Department</option>
							<option value="Computer Science and Engineering" <?php if($s_department=="Computer Science and Engineering") echo 'selected="selected"'; ?>>Computer Science and Engineering</option>
							<option value="Electrical and Electronics Engineering" <?php if($s_department=="Electrical and Electronics Engineering") echo 'selected="selected"'; ?>>Electrical and Electronics Engineering</option>
							<option value="Textile Engineering" <?php if($s_department=="Textile Engineering") echo 'selected="selected"'; ?>>Textile Engineering</option>
							<option value="Civil Engineering" <?php if($s_department=="Civil Engineering") echo 'selected="selected"'; ?>>Civil Engineering</option>
							<option value="Mechanical Engineering" <?php if($s_department=="Mechanical Engineering") echo 'selected="selected"'; ?>>Mechanical Engineering</option>
							<option value="Industrial and Production Engineering" <?php if($s_department=="Industrial and Production Engineering") echo 'selected="selected"'; ?>>Industrial and Production Engineering</option>
							<option value="Architecture" <?php if($s_department=="Architecture") echo 'selected="selected"'; ?>>Architecture</option>
							</select>
						</div>
			</div>
			<div class="row" id="semester_year">
						<div class="col-lg-6" id="semester">
							<label>Semester:</label>
							<select class="form-control select-border" name="semester" id="semesterBox" value="<?= $s_semester ?>" required>
							<option value="">Choose Semester</option>
							<option value="Fall" <?php if($s_semester=="Fall") echo 'selected="selected"'; ?>>Fall</option>
							<option value="Spring" <?php if($s_semester=="Spring") echo 'selected="selected"'; ?>>Spring</option>
							</select>
						</div>
						<div class="col-lg-6" id="year">
							<label>Year:</label>
							<select class="form-control select-border" name="year" id="yearBox" value="<?= $s_year ?>" required>
							<option value="">Choose Year</option>
							<option value="1995" <?php if($s_year=="1995") echo 'selected="selected"'; ?>>1995</option>
							<option value="1996" <?php if($s_year=="1996") echo 'selected="selected"'; ?>>1996</option>
							<option value="1997" <?php if($s_year=="1997") echo 'selected="selected"'; ?>>1997</option>
							<option value="1998" <?php if($s_year=="1998") echo 'selected="selected"'; ?>>1998</option>
							<option value="1999" <?php if($s_year=="1999") echo 'selected="selected"'; ?>>1999</option>
							<option value="2000" <?php if($s_year=="2000") echo 'selected="selected"'; ?>>2000</option>
							<option value="2001" <?php if($s_year=="2001") echo 'selected="selected"'; ?>>2001</option>
							<option value="2002" <?php if($s_year=="2002") echo 'selected="selected"'; ?>>2002</option>
							<option value="2003" <?php if($s_year=="2003") echo 'selected="selected"'; ?>>2003</option>
							<option value="2004" <?php if($s_year=="2004") echo 'selected="selected"'; ?>>2004</option>
							<option value="2005" <?php if($s_year=="2005") echo 'selected="selected"'; ?>>2005</option>
							<option value="2006" <?php if($s_year=="2006") echo 'selected="selected"'; ?>>2006</option>
							<option value="2007" <?php if($s_year=="2007") echo 'selected="selected"'; ?>>2007</option>
							<option value="2008" <?php if($s_year=="2008") echo 'selected="selected"'; ?>>2008</option>
							<option value="2009" <?php if($s_year=="2009") echo 'selected="selected"'; ?>>2009</option>
							<option value="2010" <?php if($s_year=="2010") echo 'selected="selected"'; ?>>2010</option>
							<option value="2011" <?php if($s_year=="2011") echo 'selected="selected"'; ?>>2011</option>
							<option value="2012" <?php if($s_year=="2012") echo 'selected="selected"'; ?>>2012</option>
							<option value="2013" <?php if($s_year=="2013") echo 'selected="selected"'; ?>>2013</option>
							<option value="2014" <?php if($s_year=="2014") echo 'selected="selected"'; ?>>2014</option>
							<option value="2015" <?php if($s_year=="2015") echo 'selected="selected"'; ?>>2015</option>
							<option value="2016" <?php if($s_year=="2016") echo 'selected="selected"'; ?>>2016</option>
							<option value="2017" <?php if($s_year=="2017") echo 'selected="selected"'; ?>>2017</option>
							<option value="2018" <?php if($s_year=="2018") echo 'selected="selected"'; ?>>2018</option>
							<option value="2019" <?php if($s_year=="2019") echo 'selected="selected"'; ?>>2019</option>
							</select>
						</div>
			</div>
				<div class="row" id="address">
						<div class="col-lg-12">
						<label id="studentTeacherAddress">Address:</label>
						<textarea name="address" maxlength="200" required><?= htmlentities($s_address) ?>
						</textarea>
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
					<input type="button" name="cancel" value="CANCEL" onclick="window.location.href='<?php echo("viewProfileInfo.php?userId=".$_SESSION['signed_in_user']) ?>'" class="btn btn-warning btn-block btn-lg">
			</div>
		</div>
		</form>


	</div>
<!---------------------------Start teacher Info Content-------------------->
	<div class="col-lg-8 profile-content edit-profile-show" id="edit-teacher-info-content">
		<form method="POST" enctype="multipart/form-data">
			<div class="container content-padding">
				
			<div class="row">
				<h1 class="header-info-edit"><i class="fas fa-info"></i> Account Information</h1>
			</div>
			<?php
			        	if (isset($_SESSION["error"])&&$_SESSION['signed_in_rank']=='Teacher'){
						echo ('<div class="row alert-box">
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
					
					<div class="row">
						<div class="col-lg-6">
							<label>First Name:</label>
							<input type="text" name="fname" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter your first name" value="<?= htmlentities($t_fName) ?>" required>
						</div>
						<div class="col-lg-6">
							<label>Last Name:</label>
							<input type="text" name="lname" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter your last name" value="<?= htmlentities($t_lName) ?>" required>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label>E-mail:</label>
						<input type="email" name="email" maxlength="100" class="form-control" placeholder="Enter your E-mail" value="<?= htmlentities($t_email) ?>" required>
						</div>	
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label>Contact Number:</label>
						<input type="tel" name="contactnumber" pattern="[0]{1}[1]{1}[3]{1}[0-9]{8}|[0]{1}[1]{1}[5]{1}[0-9]{8}|[0]{1}[1]{1}[6]{1}[0-9]{8}|[0]{1}[1]{1}[7]{1}[0-9]{8}|[0]{1}[1]{1}[8]{1}[0-9]{8}|[0]{1}[1]{1}[9]{1}[0-9]{8}" maxlength="11" class="form-control" placeholder="Enter your contact number" value="<?= htmlentities($t_contactNumber) ?>" required>	
						</div>	
					</div>
					<div class="row">
						<div class="col-lg-12">
						<label for="img">Select Image (If you want to change the profile picture):</label><br>
						<input type="file" id="img" name="t-image" accept="image/png, image/jpeg">
						</div>	
					</div>			
					<div class="row">
						<div class="col-lg-12">
							<label>Password:</label>
							<br>
							<input type="password" name="currentpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField111" placeholder="Enter current password">

							<input type="password" name="newpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField222" placeholder="Enter new password">

							<input type="password" name="confirmnewpassword" maxlength="20" class="form-control pwd mb-2 d-none" id="changePasswordField333" placeholder="Confirm new password">
							<input type="button" class="btn btn-danger d-none" id="cancelPassBt111" value="Cancel" onclick="cancelPassword('Teacher')">
							<input type="button" class="btn btn-primary" id="changePassBt111" value="Change Password" onclick="changePassword('Teacher')">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<label>Gender:</label>
							<select class="form-control select-border" name="gender" required>
							<option value="">Choose Gender</option>
							<option value="Male" <?php if($t_gender=="Male") echo 'selected="selected"'; ?>>Male</option>
							<option value="Female" <?php if($t_gender=="Female") echo 'selected="selected"'; ?>>Female</option>
							</select>
						</div>
					</div>
					<div class="row" id="department">
						<div class="col-lg-12">
							<label>Department:</label>
							<select class="form-control select-border" id="departmentBox" name="department" required>
							<option value="">Choose Department</option>
							<option value="Computer Science and Engineering" <?php if($t_department=="Computer Science and Engineering") echo 'selected="selected"'; ?>>Computer Science and Engineering</option>
							<option value="Electrical and Electronics Engineering" <?php if($t_department=="Electrical and Electronics Engineering") echo 'selected="selected"'; ?>>Electrical and Electronics Engineering</option>
							<option value="Textile Engineering" <?php if($t_department=="Textile Engineering") echo 'selected="selected"'; ?>>Textile Engineering</option>
							<option value="Civil Engineering" <?php if($t_department=="Civil Engineering") echo 'selected="selected"'; ?>>Civil Engineering</option>
							<option value="Mechanical Engineering" <?php if($t_department=="Mechanical Engineering") echo 'selected="selected"'; ?>>Mechanical Engineering</option>
							<option value="Industrial and Production Engineering" <?php if($t_department=="Industrial and Production Engineering") echo 'selected="selected"'; ?>>Industrial and Production Engineering</option>
							<option value="Architecture" <?php if($t_department=="Architecture") echo 'selected="selected"'; ?>>Architecture</option>
						</select>
						</div>
					</div>
					<div class="row" id="address">
						<div class="col-lg-12">
						<label id="studentTeacherAddress">Address:</label>
						<textarea name="address" maxlength="200" required><?= htmlentities($t_address) ?>
						</textarea>

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
					<input type="button" name="cancel" value="CANCEL" onclick="window.location.href='<?php echo("viewProfileInfo.php?userId=".$_SESSION['signed_in_user']) ?>'" class="btn btn-warning btn-block btn-lg">
				</div>
		</div>
		</form>
	</div>	
	</div>	
</div>
<!-----------------------------End Profile Content---------------------->

<!--  JavaScript -->
	<script src="Bootstrap/js/jquery.min.js">
	</script>
	<script src="Bootstrap/js/popper.min.js"></script>
	<script src="Bootstrap/js/bootstrap.min.js"></script>
<!--Bottom Navigation-->
<nav class="nav-bottom" id="bottom-nav">
   <a href="editProfileInfo.php" class="nav__link nav__link--active" id="bottom-information-link">
    <i class="fas fa-info"></i>
    <span class="nav__text">Account Information</span>
  </a>
  <a href=<?php echo('"viewProfileIntro.php?userId='.$_SESSION['signed_in_user'].'"'); ?> class="nav__link" id="bottom-introduction-link">
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