<?php

include 'components/connect.php';
include 'components/language_config.php';
include 'components/password_helper.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING); 
   $pass = $_POST['pass'];

   // Get user from database
   $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
   $select_users->execute([$email]);
   $row = $select_users->fetch(PDO::FETCH_ASSOC);

   if($select_users->rowCount() > 0){
      // Verify password using secure verification (supports both bcrypt and legacy SHA1)
      if(verify_password($pass, $row['password'])){
         
         // If password was SHA1, rehash it to bcrypt for future logins
         if(needs_password_rehash($row['password'])){
            $new_hash = rehash_password($pass);
            $update_hash = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_hash->execute([$new_hash, $row['id']]);
         }
         
         setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
         header('location:home.php?login=success');
      }else{
         $warning_msg[] = translate('invalid_credentials');
      }
   }else{
      $warning_msg[] = translate('invalid_credentials');
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- login section starts  -->

<section class="form-container">

   <form action="" method="post">
      <h3><?= translate('welcome_back'); ?></h3>
      <input type="email" name="email" required maxlength="50" placeholder="<?= translate('enter_email'); ?>" class="box" autocomplete="email">
      <input type="password" name="pass" required maxlength="20" placeholder="<?= translate('enter_password'); ?>" class="box" autocomplete="current-password">
      <p><?= translate('dont_have_account'); ?> <a href="register.php"><?= translate('register'); ?> New</a></p>
      <input type="submit" value="<?= translate('login'); ?> Now" name="submit" class="btn">
   </form>

</section>

<!-- login section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>