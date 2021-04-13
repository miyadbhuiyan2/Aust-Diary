<?php
include "pdo.php";

function userLiked($post_id)
{	include "pdo.php";
	$stmt = $pdo->prepare("SELECT * FROM notification where Post_id= :pi AND User_id=:ui AND React='Like'");
		$stmt->execute(array(":pi" => $post_id,
							":ui" => $_SESSION['signed_in_id']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($row)) {
			return false;
		}
		else
			return true;
}
function userStarred($post_id)
{	include "pdo.php";
	$stmt = $pdo->prepare("SELECT * FROM notification where Post_id= :pi AND User_id=:ui AND React='Star'");
		$stmt->execute(array(":pi" => $post_id,
							":ui" => $_SESSION['signed_in_id']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($row)) {
			return false;
		}
		else
			return true;
}
function getLikes($id)
{include "pdo.php";
	$stmt = $pdo->prepare("SELECT * FROM postshow where PS_id= :pi");
		$stmt->execute(array(":pi" => $id));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row['Llike'];
}
function getStars($id)
{include "pdo.php";
	$stmt = $pdo->prepare("SELECT * FROM postshow where PS_id= :pi");
		$stmt->execute(array(":pi" => $id));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row['Star'];
}
?>