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
		
if (isset($_POST['submit'])) {
	if ($_POST['password']==$_POST['repassword']) {
	try {
		if ($_POST['rank']=='Student') { 
			$stmt = $pdo->prepare("INSERT INTO user(FirstName, LastName, Email_id,ContactNumber,Password,IdNumber,Address,Gender,Rank,Department,Semester,Year) VALUES ( :fn, :ln, :ei,:cn,:pw,:id,:ad,:gen,:rk,:dep,:sem,:yr)");
            $stmt->execute(array(
              ':fn' => $_POST['fname'],
              ':ln' => $_POST['lname'],
              ':ei' => $_POST['email'],
          	  ':cn' => $_POST['contactnumber'],
              ':pw' => hash('md5',$_POST['password']),
              ':id' => $_POST['id'],
          	  ':ad' => $_POST['address'],
              ':gen' => $_POST['gender'],
              ':rk' => $_POST['rank'],
              ':dep' => $_POST['department'],
          	  ':sem' => $_POST['semester'],
			  ':yr' => $_POST['year']));

            $stmt = $pdo->query("SELECT * FROM user where Email_id='".$_POST['email']."'");
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$imageFileType = strtolower(pathinfo($_FILES["img"]["name"],PATHINFO_EXTENSION));
			$newImagename=$row['User_id'].'.'.$imageFileType;
			$target_dir = "DATABASE/User_Profile_Picture/".$newImagename;
		  	move_uploaded_file($_FILES['img']['tmp_name'], $target_dir);
	 		
	 		 $stmt = $pdo->prepare("UPDATE user SET Image =:im WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_POST['email'],
              ':im' => $newImagename));

            $_SESSION['success'] = "You have been registred successfully";
            header("Location:register.php");
            return;
        }
        else if ($_POST['rank']=='Teacher') {
			$stmt = $pdo->prepare("INSERT INTO user(FirstName, LastName, Email_id,ContactNumber,Password,Address,Gender,Rank,Department) VALUES ( :fn, :ln, :ei,:cn,:pw,:ad,:gen,:rk,:dep)");
            $stmt->execute(array(
              ':fn' => $_POST['fname'],
              ':ln' => $_POST['lname'],
              ':ei' => $_POST['email'],
          	  ':cn' => $_POST['contactnumber'],
              ':pw' => hash('md5',$_POST['password']),
          	  ':ad' => $_POST['address'],
              ':gen' => $_POST['gender'],
              ':rk' => $_POST['rank'],
              ':dep' => $_POST['department']));

             $stmt = $pdo->query("SELECT * FROM user where Email_id='".$_POST['email']."'");
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$imageFileType = strtolower(pathinfo($_FILES["img"]["name"],PATHINFO_EXTENSION));
			$newImagename=$row['User_id'].'.'.$imageFileType;
			$target_dir = "DATABASE/User_Profile_Picture/".$newImagename;
		  	move_uploaded_file($_FILES['img']['tmp_name'], $target_dir);
	 		
	 		 $stmt = $pdo->prepare("UPDATE user SET Image =:im WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_POST['email'],
              ':im' => $newImagename));

            $_SESSION['success'] = "You have been registred successfully and your account is in verification process";
            header("Location:register.php");
            return;
        }
        else{
			$stmt = $pdo->prepare("INSERT INTO user(FirstName, LastName, Email_id,ContactNumber,Password,Address,Gender,Rank,companyName,companyType) VALUES ( :fn, :ln, :ei,:cn,:pw,:ad,:gen,:rk,:cpn,:ct)");
            $stmt->execute(array(
              ':fn' => $_POST['fname'],
              ':ln' => $_POST['lname'],
              ':ei' => $_POST['email'],
          	  ':cn' => $_POST['contactnumber'],
              ':pw' => hash('md5',$_POST['password']),
          	  ':ad' => $_POST['address'],
              ':gen' => $_POST['gender'],
              ':rk' => $_POST['rank'],
              ':cpn' => $_POST['companyName'],
              ':ct' => $_POST['companyType']));
            
            $stmt = $pdo->query("SELECT * FROM user where Email_id='".$_POST['email']."'");
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$imageFileType = strtolower(pathinfo($_FILES["img"]["name"],PATHINFO_EXTENSION));
			$newImagename=$row['User_id'].'.'.$imageFileType;
			$target_dir = "DATABASE/User_Profile_Picture/".$newImagename;
		  	move_uploaded_file($_FILES['img']['tmp_name'], $target_dir);
	 		
	 		 $stmt = $pdo->prepare("UPDATE user SET Image =:im WHERE Email_id = :a_id;");
            $stmt->execute(array(
              ':a_id' => $_POST['email'],
              ':im' => $newImagename));

            $_SESSION['success'] = "You have been registred successfully";
            header("Location:register.php");
            return;
        }    
	} catch (Exception $e) {
			if (strpos($e->getMessage(),'ContactNumber')==true) {
				$_SESSION['error'] = "This Contact Number already exists";
				header("Location:register.php");
				return;
			}
			elseif (strpos($e->getMessage(),'Email_id')==true) {
				$_SESSION['error'] = "This Email Address already exists";
				header("Location:register.php");
				return;
			}
			elseif (strpos($e->getMessage(),'IdNumber')==true) {
				$_SESSION['error'] = "This ID Number already exists";
				header("Location:register.php");
				return;
			}
			elseif (strpos($e->getMessage(),'CompanyName')==true) {
				$_SESSION['error'] = "This Company Name already exists";
				header("Location:register.php");
				return;
			}
			else{
				$_SESSION['error'] = $e->getMessage();
				header("Location:register.php");
				return;
			}
	}
	}
	else
	{
		$_SESSION['error'] = "Password doesn't match with Re-type Password";
		header("Location:register.php");
		return;	
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="Bootstrap/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href ="css/register_style.css"/>
	<script language="Javascript">
	
		function showStudent()
		{
			document.getElementById("companyAddress").style.display="none";
    		document.getElementById("companyName").style.display="none";
    		document.getElementById("companyKind").style.display="none";
    		document.getElementById("semester_year").style.display="";
    		document.getElementById("idNumber").style.display="";
    		document.getElementById("studentTeacherAddress").style.display="";
			document.getElementById("department").style.display="";
			
			document.getElementById("companyNameBox").required=false;
			document.getElementById("companyKindBox").required=false;
			document.getElementById("idNumberBox").required=true;
			document.getElementById("semesterBox").required=true;
			document.getElementById("yearBox").required=true;
			document.getElementById("departmentBox").required=true;
			
		}
		function showTeacher()
		{
			document.getElementById("companyAddress").style.display="none";
    		document.getElementById("companyName").style.display="none";
    		document.getElementById("companyKind").style.display="none";
    		document.getElementById("semester_year").style.display="none";
    		document.getElementById("idNumber").style.display="none";
    		document.getElementById("studentTeacherAddress").style.display="";
			document.getElementById("department").style.display="";

			document.getElementById("companyNameBox").required=false;
			document.getElementById("companyKindBox").required=false;
			document.getElementById("idNumberBox").required=false;
			document.getElementById("semesterBox").required=false;
			document.getElementById("yearBox").required=false;
			document.getElementById("departmentBox").required=true;

		}
		function showCompany()
		{
			document.getElementById("companyAddress").style.display="";
    		document.getElementById("companyName").style.display="";
    		document.getElementById("companyKind").style.display="";
    		document.getElementById("semester_year").style.display="none";
    		document.getElementById("idNumber").style.display="none";
    		document.getElementById("studentTeacherAddress").style.display="none";
			document.getElementById("department").style.display="none";

			document.getElementById("companyNameBox").required=true;
			document.getElementById("companyKindBox").required=true;
			document.getElementById("idNumberBox").required=false;
			document.getElementById("semesterBox").required=false;
			document.getElementById("yearBox").required=false;
			document.getElementById("departmentBox").required=false;

		}

	</script>
</head>
<body onload="showStudent()">
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
				<a class="nav-link active" href="register.php">REGISTER</a>
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
<!---End Navigation-->	

<div class="container">
	<div class="row">
		<div class="col-lg-2"></div>
		<div class="col-lg-8">
			<div class="registerdiv">
				<h1 class="text-center">FILL THE FORM TO GET STARTED</h1>
				<form class="form-group" method="POST" enctype="multipart/form-data">
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
					else if (isset($_SESSION["success"])) {
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
					<div class="row spacing">
						<div class="col-lg-6 col_spacing">
							<label>First Name:</label>
							<input type="text" name="fname" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter your first name" required>
						</div>
						<div class="col-lg-6 col_spacing">
							<label>Last Name:</label>
							<input type="text" name="lname" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter your last name" required>
						</div>

					</div>
					<div class="row spacing">
						<div class="col-lg-12 col_spacing">
						<label>E-mail:</label>
						<input type="email" name="email" maxlength="100" class="form-control" placeholder="Enter your E-mail" required>	
						</div>	
					</div>
					<div class="row spacing">
						<div class="col-lg-12 col_spacing">
						<label>Contact Number:</label>
						<input type="tel" name="contactnumber" pattern="[0]{1}[1]{1}[3]{1}[0-9]{8}|[0]{1}[1]{1}[5]{1}[0-9]{8}|[0]{1}[1]{1}[6]{1}[0-9]{8}|[0]{1}[1]{1}[7]{1}[0-9]{8}|[0]{1}[1]{1}[8]{1}[0-9]{8}|[0]{1}[1]{1}[9]{1}[0-9]{8}" maxlength="11" class="form-control" placeholder="Enter your contact number" required>	
						</div>	
					</div>
			<div class="row spacing">
						<div class="col-lg-12 col_spacing">
						<label for="img">Select image:</label>
						<div class="row">
							<div class="col-lg-12">
								<input type="file" id="img" name="img" accept="image/png, image/jpeg" required>
							</div>
						</div>	
						</div>	
			</div>			
					<div class="row spacing">
						<div class="col-lg-6 col_spacing">
							<label>Password:</label>
								<input type="password" name="password" maxlength="20" class="form-control pwd" placeholder="Enter new password" required>
						</div>
						<div class="col-lg-6 col_spacing">
							<label>Re-type Password:</label>
							<input type="password" name="repassword" maxlength="20" class="form-control" placeholder="Re-type password" required>
						</div>

					</div>
					<div class="row spacing">
						<div class="col-lg-12 col_spacing">
							<label>Gender:</label>
							<select class="form-control" name="gender" required>
							<option value="">Choose Gender</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
							</select>
						</div>
					</div>
					<div class="row spacing">
						<div class="col-lg-12 col_spacing">
						<label>Sign Up As:</label>
						<div class="row">
							<div class="col-lg-4">
								<label class="radio-inline">
								<input type="radio" name="rank" value="Student" onchange="showStudent()" checked> Student
								</label>
							</div>
						<div class="col-lg-4">
								<label class="radio-inline">
							<input type="radio" name="rank" value="Teacher" onchange="showTeacher()"> Teacher
						</label>
						</div>		
						<div class="col-lg-4">
								<label class="radio-inline">
							<input type="radio" name="rank" value="Company Representative" onchange="showCompany()"> Company Representative
						</label>
						</div>
						
						</div>	
						</div>
						
					</div>
			<div class="row spacing" id="idNumber">
						<div class="col-lg-12 col_spacing">
						<label>ID Number:</label>
						<input type="text" name="id" id="idNumberBox" pattern="[0-9]{2}.[0]{1}[1-2]{1}.[0]{1}[1-7]{1}.[0-9]{3}" class="form-control" placeholder="Example:17.02.04.110" maxlength="15" required>	
						</div>	
			</div>
			<div class="row spacing" id="department">
						<div class="col-lg-12 col_spacing">
							<label>Department:</label>
							<select class="form-control" id="departmentBox" name="department" required>
							<option value="">Choose Department</option>
							<option value="Computer Science and Engineering">Computer Science and Engineering</option>
							<option value="Electrical and Electronics Engineering">Electrical and Electronics Engineering</option>
							<option value="Textile Engineering">Textile Engineering</option>
							<option value="Civil Engineering">Civil Engineering</option>
							<option value="Mechanical Engineering">Mechanical Engineering</option>
							<option value="Industrial and Production Engineering">Industrial and Production Engineering</option>
							<option value="Architecture">Architecture</option>
							</select>
						</div>
			</div>
			<div class="row spacing" id="semester_year">
						<div class="col-lg-6 col_spacing" id="semester">
							<label>Semester:</label>
							<select class="form-control" name="semester" id="semesterBox" required>
							<option value="">Choose Semester</option>
							<option value="Fall">Fall</option>
							<option value="Spring">Spring</option>
							</select>
						</div>
						<div class="col-lg-6 col_spacing" id="year">
							<label>Year:</label>
							<select class="form-control" name="year" id="yearBox" required>
							<option value="">Choose Year</option>
							<option value="1995">1995</option>
							<option value="1996">1996</option>
							<option value="1997">1997</option>
							<option value="1998">1998</option>
							<option value="1999">1999</option>
							<option value="2000">2000</option>
							<option value="2001">2001</option>
							<option value="2002">2002</option>
							<option value="2003">2003</option>
							<option value="2004">2004</option>
							<option value="2005">2005</option>
							<option value="2006">2006</option>
							<option value="2007">2007</option>
							<option value="2008">2008</option>
							<option value="2009">2009</option>
							<option value="2010">2010</option>
							<option value="2011">2011</option>
							<option value="2012">2012</option>
							<option value="2013">2013</option>
							<option value="2014">2014</option>
							<option value="2015">2015</option>
							<option value="2016">2016</option>
							<option value="2017">2017</option>
							<option value="2018">2018</option>
							<option value="2019">2019</option>
							</select>
						</div>
			</div>
				<div class="row spacing" id="companyName">
						<div class="col-lg-12 col_spacing">
						<label>Company Name:</label>
						<input type="text" name="companyName" id="companyNameBox" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Enter company name" required>	
						</div>	
				</div>
				<div class="row spacing" id="companyKind">
						<div class="col-lg-12 col_spacing">
						<label>What Kind of Comapny:</label>
						<input type="text" name="companyType" id="companyKindBox" title="Only alphabets are allowed" pattern="[A-Za-z ]+" maxlength="50" class="form-control" placeholder="Example:Software Company" required>	
						</div>	
				</div>
				<div class="row spacing" id="address">
						<div class="col-lg-12 col_spacing">
						<label id="studentTeacherAddress">Address:</label>
						<label id="companyAddress">Company Address:</label>
						<div class="row">
							<div class="col-lg-12">
							<textarea name="address" maxlength="200" required>
								
							</textarea>
							</div>
						
						</div>
						</div>	
					</div>
						<div class="row spacing">
						<div class="col-lg-6 col_spacing">
							<input type="reset" name="Reset" value="RESET" class="btn btn-warning btn-block btn-lg">
						</div>
						<div class="col-lg-6 col_spacing">
							<input type="submit" name="submit" value="SUBMIT" class="btn btn-success btn-block btn-lg">
						</div>
						</div>
					
				</form>
			</div>
		</div>
		<div class="col-lg-2"></div>
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
<!--JavaScript -->
	<script src="Bootstrap/js/jquery.min.js">
	</script>
	<script src="Bootstrap/js/popper.min.js"></script>
	<script src="Bootstrap/js/bootstrap.min.js"></script>
</body>
</html>