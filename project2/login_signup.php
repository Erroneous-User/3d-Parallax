<?php
session_start();
require 'vendor/autoload.php'; // Include the Composer autoload.php

// MongoDB online server connection
$mongoClient = new MongoDB\Client(
  "mongodb+srv://Erroneous_User:EU_connect@cluster.wvsojew.mongodb.net/"
);

$db = $mongoClient->regform; // Select the database
$loginCollection = $db->login; // Select the collection
if (isset($_POST['signin'])) {
  $email = trim($_POST['login_user_name']);
  $password = trim($_POST['login_pass']);

  try {
      $result = $loginCollection->findOne(['user_name' => $email]);

      if ($result && password_verify($password, $result->pass)) {
          $_SESSION['user_id'] = (string) $result->_id;
          $_SESSION['user_name'] = $result->user_name;
          $_SESSION['name'] = $result->name;
          header('Location: project2/index.php');
          exit;
      } else {
          $_SESSION['signin_error'] = 'Incorrect Email or Password!';
          header('Location: project2/login_signup.php'); // Redirect back to the form page
          exit;
      }
  } catch (Exception $e) {
      $_SESSION['signin_error'] = 'Error: ' . $e->getMessage();
      header('Location: project2/login_signup.php');
      exit;
  }
}

if (isset($_POST['signup'])) {
  $name = trim($_POST['signup_name']);
  $email = trim($_POST['signup_user_name']);
  $password = password_hash(trim($_POST['signup_pass']), PASSWORD_DEFAULT);

  $result = $loginCollection->findOne(['user_name' => $email]);
  if(!$result)
  {
  try {
      $insertResult = $loginCollection->insertOne([
          'user_name' => $email,
          'pass' => $password,
          'name' => $name
      ]);

      if ($insertResult->getInsertedCount() == 1) {
          echo "<script>document.addEventListener('DOMContentLoaded', function() { toggleToSignIn(); });</script>";
          $_SESSION['signup_success'] = 'Signup Success. You may login now.';
      } else {
          $_SESSION['signup_error'] = 'Something went wrong during signup.';
      }
  }
  
  catch (Exception $e) {
      $_SESSION['signup_error'] = 'Error: ' . $e->getMessage();
  }
}else{
  $_SESSION['username_error'] = 'Username already exist.';
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up and Sign In Form</title>
  <link rel="stylesheet" href="project2/index.css">
</head>
<body>
  <div class="container" id="container" role="main">
  <section aria-labelledby="signup-title" class="form-container sign-up-container">
    <form action="login_signup.php" method="post" aria-label="Create Account Form">
        <h1 id="signup-title">Create Account</h1>
        <!-- Display error message if available -->
        <?php if (isset($_SESSION['signup_error'])):
          elseif (isset($_SESSION['username_error'])): 
          ?>
            <div class="alert alert-danger">
                <?= $_SESSION['signup_error']; ?>
                <?php unset($_SESSION['signup_error']);
                unset($_SESSION['username_error']);
                ?>
            </div>
        <?php endif; ?>
        <div class="social-container" aria-label="Social Media Sign Up Links">
         
          <a href="#" class="social" aria-label="Sign up with Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
          <a href="#" class="social" aria-label="Sign up with Google"><i class="fab fa-google-plus-g" aria-hidden="true"></i></a>
          <a href="#" class="social" aria-label="Sign up with LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
        </div>
        <input type="text" placeholder="Name" name="signup_name" aria-required="true" />
        <input type="email" placeholder="Email" name="signup_user_name" aria-required="true" />
        <input type="password" placeholder="Password" name="signup_pass" aria-required="true" />
        <button type="submit" name="signup">Sign Up</button>
      </form>
    </section>
    <section aria-labelledby="signin-title" class="form-container sign-in-container">
    <form action="login_signup.php" method="post" aria-label="Sign In Form">
        <h1 id="signin-title">Sign in</h1>
        <!-- Display error message if available -->
        <?php if (isset($_SESSION['signin_error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['signin_error']; ?>
                <?php unset($_SESSION['signin_error']); ?>
            </div>
        <?php endif; ?>
        <div class="social-container" aria-label="Social Media Sign In Links">
            <a href="#" class="social" aria-label="Sign in with Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
            <a href="#" class="social" aria-label="Sign in with Google"><i class="fab fa-google-plus-g" aria-hidden="true"></i></a>
            <a href="#" class="social" aria-label="Sign in with LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
        </div>

        <input type="email" placeholder="Email" name="login_user_name" aria-required="true" required />
        <input type="password" placeholder="Password" name="login_pass" aria-required="true" required />
        <a href="#" aria-label="Forgot your password?">Forgot your password?</a>
        <button type="submit" name="signin">Sign In</button>
    </form>
</section>
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>Welcome Back!</h1>
          <p>To keep connected with us please login with your personal info</p>
          <button class="ghost" id="signIn" name="login_script">Sign In</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>Hello, Friend!</h1>
          <p>Enter your personal details and start journey with us</p>
          <button class="ghost" id="signUp" name="signup_script">Sign Up </button>
        </div>
      </div>
    </div>
  </div>
  <footer>
    <p>
      Created with <i class="fa fa-heart" aria-hidden="true"></i> by
      <a target="_blank" href="#" aria-label="Visit Arun's homepage">Arun</a>
      - Read how I created this and how you can join the challenge
      <a target="_blank" href="#" aria-label="Read more about the challenge">here</a>.
    </p>
  </footer>
  <script src="index.js"></script>
</body>
</html>
