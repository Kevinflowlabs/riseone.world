<?php

if(isset($_POST['email'])) {

  $email_to = "info@riseone.world"; //to email address
  $email_subject = "RISE ONE MESSAGE"; // email subject

  $name = $_POST['name']; // required
  $phone = $_POST['phone']; // not required
  $email_from = $_POST['email']; // required
  $comments = $_POST['comments']; // required


  $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
  $string_exp = "/^[A-Za-z .'-]+$/";
  $error_message = "";
  $success_message = '<h1>Thanks for contacting RISE. We will get back to you as soon as possible ! </h1>';

  // validation expected data exists
  if(!isset($_POST['name']) || !isset($_POST['phone']) || !isset($_POST['email']) || !isset($_POST['comments'])) {
    throw new Exception('We are sorry, but there appears to be a problem with the form you submitted.');
    exit;
  }

  if(!preg_match($email_exp, $email_from)) {
    $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
  }

  if(!preg_match($string_exp, $name)) {
    $error_message .= 'The First Name you entered does not appear to be valid.<br />';
  }

  if(strlen($comments) < 2) {
    $error_message .= 'The Comments you entered do not appear to be valid.<br />';
  }

  if(strlen($error_message) > 0) {
    died($error_message);
  }

  $email_message = "Form details below.\n\n";

  function died($error) {
    $output = "We are very sorry, but there were error(s) found with the form you submitted. ";
    $output .= "These errors appear below.<br /><br />";
    $output .= $error . "<br /><br />";
    $output .= "Please go back and fix these errors.<br /><br />";
    die($output);
  }

  function clean_string($string) {
    return str_replace(array("content-type", "bcc:", "to:", "cc:", "href"), "", $string);
  }

  //creating email message
  $email_message .= "Name: " . clean_string($name) . "\n";
  $email_message .= "Phone: " . clean_string($phone) . "\n";
  $email_message .= "Email: " . clean_string($email_from) . "\n";
  $email_message .= "Comments: " . clean_string($comments) . "\n";

  // create email headers
  $headers = 'From: ' . $email_from . "\r\n" .
    'Reply-To: ' . $email_from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
  try {
    mail($email_to, $email_subject, $email_message, $headers);
    echo $success_message;
  } catch (Exception $e) {
    echo $e->getMessage();
  }
  exit;
}