<?php
		require_once "pdo.php";
	include 'server.php';

	$dbServername="localhost";
	$dbUsername="root";
	$dbPassword="";
	$dbName="170204110";
	
	$connect=mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
	
	$k = "0";

	
	Function showpost(){
		
		$sql = "SELECT * FROM postshow ORDER BY Star DESC,Llike DESC";
		
		$result=mysqli_query($GLOBALS['connect'],$sql);
		$resultCheck=mysqli_num_rows($result);
		if($resultCheck>0){
			$count=0;
			while(($row=mysqli_fetch_assoc($result))&&$count<2){
				$count++;
				$projectName = $row['ProjectName'];
				$projectDescription = $row['ProjectDescription'];
				$tags = $row['tags'];
				$postid = $row['PS_id'];
		
				echo'<div class="card">
						<div class="card-header">
							<h6>'.$projectName.'</h6>
						</div>
					
						<div class="card-footer">
			
							<h6>Tags: '.$tags.'</h6>
				
						</div>
					</div>';
				
				echo '<br>';
			}
		}
		
	}
	Function getUserCard($sql){
		$result=mysqli_query($GLOBALS['connect'],$sql);
		$resultCheck=mysqli_num_rows($result);
		if($resultCheck>0){
			while($row=mysqli_fetch_assoc($result)){
				
				$Uname = $row['FirstName'].' '.$row['LastName'];
				$deptName = $row['Department'];
				$rank = $row['Rank'];
				$mailId = $row['Email_id'];
				$uimage ='DATABASE/User_Profile_Picture/'.$row['Image'];
		
				echo '<div class="row user-card">
							<div class="col-lg-2 col-3">
								<img src="'.$uimage.'" class="notification-photo-frame rounded-circle">
							</div>	
							<div class="col-lg-8 col-6">
								<h5><b>'.$Uname.'</b></h5>
								<h6>'.$rank.'</h6>
								<h7>'.$deptName.'</h7>
							</div>
							<div class="col-lg-2 col-3">
								<button type="button" class="btn btn-outline-info" onclick="window.location.href=\'viewProfileInfo.php?userId='.$mailId.'\'"><i class="fas fa-eye"></i> View</button>
							</div>
				</div>';
			}
		}	
	}
	
	Function getMultiPost($sql){
		//$sql = "SELECT * FROM postshow, user WHERE postshow.User_id = user.User_id ORDER BY postshow.timedate DESC";
		
		$result=mysqli_query($GLOBALS['connect'],$sql);
		$resultCheck=mysqli_num_rows($result);
		if($resultCheck>0){
			while($row=mysqli_fetch_assoc($result)){
				
				$Uname = $row['FirstName'].' '.$row['LastName'];
				$Pdate = $row['timedate'];
				$uimage ='DATABASE/User_Profile_Picture/'.$row['Image'];
				$projectName = $row['ProjectName'];
				$projectDescription = $row['ProjectDescription'];
				$Llike = $row['Llike'];
				$star = $row['Star'];
				$tags = $row['tags'];
				$postid = $row['PS_id'];
		
				echo'<div class="card">
						<div class="card-header">
							<div class="media">
								<img src="'.$uimage.'" alt="'.$Uname.'" class="align-self-center rounded-circle mr-2" style="width:50px;">
								<div class="media-body align-self-center">
									<a href="viewProfileInfo.php?userId='.$row['Email_id'].'" class="text-dark"><h6>'.$Uname.'</h6></a>
									<small><i>'.$Pdate.'</i></small>
								</div>
					
															<button type="button" ';
								if (userLiked($postid)) {
									echo 'id="post-'.$postid.'" class="btn btn-outline-primary mr-3 liked like-bt" ';
									if ($_SESSION['signed_in_rank']!="Teacher") {
										echo 'onclick="addLikes('.$postid.',\'unlike\')">';
									}
									else
										echo '>';
								}
								else
								{
									echo 'id="post-'.$postid.'" class="btn btn-outline-primary mr-3 like-bt" '; 
									if ($_SESSION['signed_in_rank']!="Teacher") {
										echo 'onclick="addLikes('.$postid.',\'like\')">';
									}
									else
										echo '>';
								}
								
								echo '<i class="far fa-thumbs-up"></i>
									<span class="badge badge-danger" id="likes-'.$postid.'">'.getLikes($postid).'</span>
								</button>

								<button type="button" ';
								if (userStarred($postid)) {
									echo 'id="postStar-'.$postid.'" class="btn btn-outline-success starred star-bt" ';
									if ($_SESSION['signed_in_rank']=="Teacher") {
										echo 'onclick="addStars('.$postid.',\'unstar\')">';
									}
									else
										echo '>';
								}
								else{
									echo 'id="postStar-'.$postid.'" class="btn btn-outline-success star-bt" ';
									if ($_SESSION['signed_in_rank']=="Teacher") {
										echo 'onclick="addStars('.$postid.',\'star\')">';
									}
									else
										echo '>';
								}	
								echo '<i class="far fa-star"></i>
									<span class="badge badge-danger" id="stars-'.$postid.'">'.getStars($postid).'</span>
								</button>
							</div>
						</div>
					
						<div class="card-body">
							<h4>'.$projectName.'</h4>
							<br>
							<p>'.$projectDescription.'</p>
							
							';
							getPostImage($postid);
							echo'
							
						</div> 
					
						<div class="card-footer">
			
							<h6>Tags: '.$tags.'</h6>
				
						</div>
					</div>';
				
				echo '<br>';
			}
		}
	}
	
	
	Function getPostImage($postid){
		
		$sql = "SELECT * FROM postimages WHERE postimages.PS_id = ".$postid;
		
		$result=mysqli_query($GLOBALS['connect'],$sql);
		$resultCheck=mysqli_num_rows($result);
		
		if($resultCheck>0){
			
			echo	'<div id="demo" class="carousel slide border" data-ride="carousel">
			
						<!-- The slideshow -->
						<div class="carousel-inner">
					
					';
			
			$slide = 0;
			
			while($row=mysqli_fetch_assoc($result)){
				
				$postImage = $row['ImageSource'];
				
				echo	'<div class="carousel-item';
				if($slide == 0)
					{echo' active';}
				echo'">
						<img src="'.$postImage.'" class="img-fluid">
					</div>
						';
				$slide++;
				
			}
			
			echo '	</div>';
			if($slide>1){
				echo '
					<!-- Left and right controls -->
					<a class="carousel-control-prev" href="#demo" data-slide="prev">
						<span class="carousel-control-prev-icon"></span>
					</a>
					<a class="carousel-control-next" href="#demo" data-slide="next">
						<span class="carousel-control-next-icon"></span>
					</a>';
					
			}
			echo	'</div>';
				
			
		}
		
	}
	
	
?>








