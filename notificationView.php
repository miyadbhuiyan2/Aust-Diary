<?php 
	require_once "pdo.php";
?>
<div class="modal" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content anime">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="exampleModalScrollableTitle"><i class="fas fa-bell"></i> Notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul>
        	
        	<?php
			if ($_SESSION['signed_in_rank']!='Company Representative') {
				$stmt = $pdo->prepare("SELECT p.ProjectName as TaskName, concat(u.FirstName,' ',U.LastName) as MemberName,n.Time as Time,n.React as React,u.Image as Image,n.Status as Status FROM postshow p INNER JOIN notification n ON p.PS_id = n.Post_id INNER JOIN user u ON u.User_id = n.User_id WHERE p.user_id=:xyz AND n.User_id!=:xyz ORDER BY n.Time desc");
				$stmt->execute(array(":xyz" => $_SESSION['signed_in_id']));
				$notifydatas = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (!empty($notifydatas)) {
					# code...
				
				foreach ($notifydatas as $notifydata) {
					if (strcasecmp($notifydata['Status'],'UNREAD')==0) {
						echo '<li style="background-color:#c4e9ce">';
					}
					else{
						echo '<li>';
					}
					
					echo  '<div class="row">
							<div class="col-3">
								<img src="DATABASE/User_Profile_Picture/'.$notifydata['Image'].'" class="notification-photo-frame rounded-circle">
							</div>	
							<div class="col-9 pl-0">';
								if (strcasecmp($notifydata['React'],'LIKE')==0) {
									echo '<h6><b>'.$notifydata['MemberName'].'</b> liked your task <b>'.$notifydata['TaskName'].'</b></h6><p><i class="fas fa-thumbs-up mr-1"></i>';
								}
								else {
									echo '<h6><b>'.$notifydata['MemberName'].'</b> starred your task <b>'.$notifydata['TaskName'].'</b></h6><p><i class="fas fa-star mr-1"></i>';
								}
								echo '<span class="text-muted">'.$notifydata['Time'].'</span></p> 
							</div>
							</div>
						</li>';
				}
			}
				}
			?>
		</ul>
      </div>
    </div>
  </div>
</div>