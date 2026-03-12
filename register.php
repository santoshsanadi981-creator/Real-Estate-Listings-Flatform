<?php

include 'components/connect.php';
include 'components/password_helper.php';

// Temporary dev debug flag — set to false in production
$DEV_DEBUG = true;

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $c_pass = $_POST['c_pass'];

   // Validate password match
   if($pass != $c_pass){
      $warning_msg[] = 'Password not matched!';
   } else {
      // Check email already exists
      $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_users->execute([$email]);

      if($select_users->rowCount() > 0){
         $warning_msg[] = 'email already taken!';
      }else{
         // Hash password using bcrypt
         $hashed_password = hash_password($pass);
         
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, number, email, password) VALUES(?,?,?,?,?)");
         try{
            $executed = $insert_user->execute([$id, $name, $number, $email, $hashed_password]);
         }catch(PDOException $ex){
            // Log the error for debugging and show a generic message
            error_log('Register insert error: '. $ex->getMessage());
            $error_msg[] = 'Database error while creating account.';
            $executed = false;
         }

         if($executed){
            // Verify user created successfully
            $verify_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
            $verify_users->execute([$email]);
            $row = $verify_users->fetch(PDO::FETCH_ASSOC);

            if($verify_users->rowCount() > 0 && verify_password($pass, $row['password'])){
               setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
               header('location:home.php');
               exit;
            }else{
               // This usually indicates a hash truncation or verification failure
               error_log('Register verification failed for email: '. $email);
               $error_msg[] = 'something went wrong!';
            }
         }else{
            // execute failed but was handled above; if no exception, capture error info
            if($DEV_DEBUG){
               $err = $insert_user->errorInfo();
               error_log('Register execute failed: '. json_encode($err));
               $error_msg[] = 'DB insert failed: '.htmlspecialchars($err[2] ?? 'unknown', ENT_QUOTES);
            }
         }

      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- register section starts  -->

<section class="form-container">

   <form action="" method="post">
      <h3>create an account!</h3>
      <input type="text" name="name" required maxlength="50" placeholder="Enter Your Name" class="box" autocomplete="name">
      <input type="email" name="email" required maxlength="50" placeholder="Enter Your Email" class="box" autocomplete="email">
      <input type="number" name="number" required min="0" max="9999999999" maxlength="10" placeholder="Enter Your Number" class="box" autocomplete="tel">
      <input type="password" name="pass" required maxlength="20" placeholder="Enter Your Password" class="box" autocomplete="new-password">
      <input type="password" name="c_pass" required maxlength="20" placeholder="confirm your password" class="box" autocomplete="new-password">
   <p>already have an account? <a href="login.php">login now</a></p>
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- register section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>