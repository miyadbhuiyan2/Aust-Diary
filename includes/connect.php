<?php
	$dbServername="localhost";
	$dbUsername="root";
	$dbPassword="";
	$dbName="170204110";
	
	$connect=mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
	
	
	Function getData($sql,$columnName){
		
		$result=mysqli_query($GLOBALS['connect'],$sql);
		$resultCheck=mysqli_num_rows($result);
		if($resultCheck>0){
			while($row=mysqli_fetch_assoc($result)){
				echo $row[$columnName];
			}
		}
	}
	Function getDataMulti($sql,$columnName1,$columnName2,$columnName3){
		
		$result=mysqli_query($GLOBALS['connect'],$sql);
		$resultCheck=mysqli_num_rows($result);
		if($resultCheck>0){
			while($row=mysqli_fetch_assoc($result)){
				echo $row[$columnName1];
				echo "	";
				echo $row[$columnName2];
				echo "	";
				echo $row[$columnName3];
				echo "<br>";
			}
		}
	}
	
	Function insertData($sql){
		
		mysqli_query($GLOBALS['connect'],$sql);
		echo'<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Success!</strong> This alert box could indicate a successful or positive action.
			</div>';
	}
	
	Function selectData($sql,$columnName){
		
		$result=mysqli_query($GLOBALS['connect'],$sql);
		$data=mysqli_fetch_assoc($result);
		return $data[$columnName];
	}

?>