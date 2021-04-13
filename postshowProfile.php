<?php
	include 'server.php';
	$dbServername="localhost";
	$dbUsername="root";
	$dbPassword="";
	$dbName="170204110";
	
	$connect=mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
	Function getMultiPost($user){
		$sql = "SELECT * FROM postshow, user WHERE postshow.User_id = user.User_id AND user.Email_id='".$user."' ORDER BY postshow.timedate DESC";
		
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
							<h6>'.$Uname.'</h6>
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
			
				<div class="card-body p-0">
					<div class="row">
						<div class="col-lg-4">';
					getPostImage($row['PS_id']);
		echo	'</div>
						<div class="col-lg-8">
						<h4 class="projectHeading">'.$projectName.'</h4>
						<p class="text-justify">'.$projectDescription.'</p>	
						</div>
					</div>
				</div> 	
						<div class="card-footer">
							<h6>Tags: '.$tags.'</h6>';
			if ($_SESSION['signed_in_user']==$user) {
					echo '<hr class="mt-0">
					<div class="row">
					<div class="col-12 p-0 text-center">
					<a href="postDelete.php?postId='.$row['PS_id'].'" type="button" class="btn btn-outline-danger btn-sm w-100">
						<i class="far fa-trash-alt"></i> Delete
					</a>
					</div>
					</div>';
			}
			echo '</div>
				</div>';
				
				echo '<br>';
			}
		}
	}
		Function getPostImage($postid){
		
		$sql = "SELECT * FROM postimages WHERE postimages.PS_id = ".$postid;
		
		$result=mysqli_query($GLOBALS['connect'],$sql);
		$resultCheck2=mysqli_num_rows($result);
		
		if($resultCheck2>0){
			
			echo	'<div id="demo" class="carousel slide" data-ride="carousel">
			
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
						<img src="'.$postImage.'" class="img-thumbnail">
					</div>
						';
				$slide++;
				
			}
			
			echo '	</div>';
			echo	'</div>';
				
			
		}
		else{
			echo	'<div id="demo" class="carousel slide" data-ride="carousel">
						<!-- The slideshow -->
						<div class="carousel-inner">
						<div class="carousel-item active">
					<img src="DATABASE/img/project.png" class="img-thumbnail">
						</div>
						</div>
					</div>';	
		}
		
	}
?>








