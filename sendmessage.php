<?php
session_start();    
    if (isset($_POST['btn-send'])) 
    {
    $firstname =$_POST['fname'];
    $lastname = $_POST['lname'];
    $fullname=$firstname.' '.$lastname;
    $email = $_POST['email'];
    $message = $_POST['message'];
    $subject = $_POST['subject'];
    $to = 'miyadbhuiyan@gmail.com';
   $body = "First Name: $firstname\n Last Name: $lastname\n E-Mail Address: $email\n Message:\n $message";

    if (mail($to,$subject,$body,"From: $fullname<$email>")) 
    {   
       $_SESSION['success']="Message Sent"; 
       header("location:contactUS.php");
       return;
    }
    else{
        $_SESSION['error']="Sending Failed"; 
        header("location:contactUS.php");
        return;
    }
    }
   

?>