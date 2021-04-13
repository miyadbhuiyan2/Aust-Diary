<?php
session_start();	
include 'includes\connect.php';
	if(isset($_POST['posting'])){
		
		$projectName=$_POST['ProjectName'];
		$porjectDescription=$_POST['description'];
		
		$status=count($_FILES['file']['name']);
		
		$Llike=0;
		$star=0;
		
		$date= date("Y-m-d H:i:s");
		
		$ptype=implode(',',$_POST['ch']);
		
		$user_id = $_SESSION['signed_in_id'];
		
		$sql="INSERT INTO postshow(ProjectName, ProjectDescription, NumofImage, Llike, Star, timedate, tags, User_id) 
				VALUES ('$projectName','$porjectDescription','$status','$Llike','$star','$date','$ptype','$user_id');";
		insertData($sql);
		
		//postid from the database
		$sql1='SELECT PS_id from postshow where projectname="'.$projectName.'";';
		$dpostid=selectData($sql1,'PS_id');
		//echo $dpostid;
		
		//file to the database
		$fileCount=count($_FILES['file']['name']);
		if($fileCount>0){
			for($i=0;$i<$fileCount;$i++){
				$fileName=$_FILES['file']['name'][$i];			
				$fileTmpName=$_FILES['file']['tmp_name'][$i];
				$fileSize=$_FILES['file']['size'][$i];
				$fileError=$_FILES['file']['error'][$i];
				$fileType=$_FILES['file']['type'][$i];
			
				$fileExt=explode('.',$fileName);
				$fileActualExt=strtolower(end($fileExt));
				$allowed=array('','','','');
				
				
				$newFileName=time().'_'.$fileName;
				$target='postImages/'.$newFileName;
				move_uploaded_file($fileTmpName,$target);
				
				
				//sending files to database
				$sql2="INSERT INTO postimages(NumofImage, ImageSource, PS_id) 
						VALUES ('$status','$target','$dpostid');";
				insertData($sql2);
				 
				
			}

		}
		$_SESSION['success'] = 'The task has been added successfully';
		header( 'Location: editProfileTasks.php' ) ;
    			return;
		
	}
?>
	
<script>
// Disable form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>