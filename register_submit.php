<?php 
//form validation and inserting of data to database
if($_SERVER["REQUEST_METHOD"] === "POST") {

    //test_input for email
    function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }

    //get filename of uploaded photo
    $photo = basename($_FILES["fileToUpload"]["name"]) ?? null;

    if(strlen(trim( $user_name) ) < 1  ){
      $error_message .= 'User name is required.<br />';
      $error = 1;
    }

    if(strlen(trim( $email) ) < 1  ){
      $error_message .= 'Email is required.<br />';
      $error = 1;
    } else {
      $email = test_input($_POST["email"]);
      // check if e-mail address is valid
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format";
      $error = 1;
      }
    }

    if(strlen(trim( $password) ) < 1  ){
      $error_message .= 'Password is required.<br />';
      $error = 1;
    } 

    if(strlen(trim( $confirm_password) ) < 1  ){
      $error_message .= 'Please confirm your password.<br />';
      $error = 1;
    } 

    //check if password and confirm_password is the same
    if( $password != $confirm_password ){
      $error_message .= 'Password did not match, please try again.';
      $error = 1;
      $password_error = 1;
    }

    //if there is no error, proceed to inserting data to database
    if(empty($error_message)) {

    //insert data to database
    $data = "INSERT INTO user_info (user_name, email, password)VALUES ( :user_name , :email, :password)";
      if($statement = $pdo->prepare($data)){

    $statement->bindValue(':user_name', $user_name);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', md5($password));
    $statement->execute();

    header("Location: login_screen.php");
      }
    }

  }

?>