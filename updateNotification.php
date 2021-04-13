<?php
session_start();
require_once "pdo.php";
if(!empty($_GET['id'])) {
include "pdo.php";
switch($_GET['action']){

case "like":
	$stmt = $pdo->prepare("UPDATE postshow SET Llike=Llike+1 WHERE PS_id=:pi");
	$stmt->execute(array(":pi" => $_GET["id"]));
	
	$stmt = $pdo->prepare("INSERT INTO notification(Post_id, React,User_id) VALUES ( :pi,'Like',:ui)");
            $stmt->execute(array(
              ':pi' => $_GET["id"],
              ':ui' => $_SESSION["signed_in_id"]));	       		
break;		
case "unlike":
	$stmt = $pdo->prepare("UPDATE postshow SET Llike=Llike-1 WHERE PS_id=:pi");
	$stmt->execute(array(":pi" => $_GET["id"]));


	$stmt = $pdo->prepare("DELETE FROM notification WHERE Post_id=:pi AND User_id=:ui AND React='Like'");
            $stmt->execute(array(
              ':pi' => $_GET["id"],
              ':ui' => $_SESSION["signed_in_id"]));	
break;
case "star":
	$stmt = $pdo->prepare("UPDATE postshow SET Star=Star+1 WHERE PS_id=:pi");
	$stmt->execute(array(":pi" => $_GET["id"]));

	$stmt = $pdo->prepare("INSERT INTO notification(Post_id, React,User_id) VALUES ( :pi,'Star',:ui)");
            $stmt->execute(array(
              ':pi' => $_GET["id"],
              ':ui' => $_SESSION["signed_in_id"]));			
break;		
case "unstar":
	$stmt = $pdo->prepare("UPDATE postshow SET Star=Star-1 WHERE PS_id=:pi");
	$stmt->execute(array(":pi" => $_GET["id"]));


	$stmt = $pdo->prepare("DELETE FROM notification WHERE Post_id=:pi AND User_id=:ui AND React='Star'");
    $stmt->execute(array(
              ':pi' => $_GET["id"],
              ':ui' => $_SESSION["signed_in_id"]));	
break;		
}
}
else{
	$stmt = $pdo->prepare("UPDATE notification SET Status='READ' WHERE EXISTS
					(SELECT PS_id as Post_id FROM postshow WHERE notification.Post_id = postshow.PS_id AND postshow.user_id=:xyz)");
	$stmt->execute(array(":xyz" => $_SESSION['signed_in_id']));
}
?>