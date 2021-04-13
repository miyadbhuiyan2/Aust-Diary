<?php
require_once "pdo.php";
session_start();
     $sql = "SELECT * FROM postimages WHERE PS_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_GET['postId']));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
    unlink($row['ImageSource']);
    }
    $sql = "DELETE FROM notification WHERE Post_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_GET['postId']));

    $sql = "DELETE FROM postimages WHERE PS_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_GET['postId']));

    $sql = "DELETE FROM postshow WHERE PS_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_GET['postId']));

    $_SESSION['success'] = 'The task has been deleted successfully';
    header( 'Location: editProfileTasks.php' ) ;
    return;

?>