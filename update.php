<?php  

include 'components/connect.php';
include 'components/password_helper.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
$select_user->execute([$user_id]);
$fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
      $success_msg[] = 'name updated!';
   }

   if(!empty($email)){
      $verify_email = $conn->prepare("SELECT email FROM `users` WHERE email = ?");
      $verify_email->execute([$email]);
      if($verify_email->rowCount() > 0){
         $warning_msg[] = 'email already taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
         $success_msg[] = 'email updated!';
      }
   }

   if(!empty($number)){
      $verify_number = $conn->prepare("SELECT number FROM `users` WHERE number = ?");
      $verify_number->execute([$number]);
      if($verify_number->rowCount() > 0){
         $warning_msg[] = 'number already taken!';
      }else{
         $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
         $update_number->execute([$number, $user_id]);
         $success_msg[] = 'number updated!';
      }
   }

   // Password update with bcrypt
   $old_pass = $_POST['old_pass'];
   $new_pass = $_POST['new_pass'];
   $c_pass = $_POST['c_pass'];

   if(!empty($old_pass) && !empty($new_pass) && !empty($c_pass)){
      // Verify old password
      if(!verify_password($old_pass, $fetch_user['password'])){
         $warning_msg[] = 'old password not matched!';
      }elseif($new_pass != $c_pass){
         $warning_msg[] = 'confirm password not matched!';
      }else{
         // Hash new password with bcrypt
         $hashed_password = hash_password($new_pass);
         $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass->execute([$hashed_password, $user_id]);
         $success_msg[] = 'password updated successfully!';
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
   <title>update</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>update your account!</h3>
      <input type="tel" name="name" maxlength="50" placeholder="<?= $fetch_user['name']; ?>" class="box" autocomplete="name">
      <input type="email" name="email" maxlength="50" placeholder="<?= $fetch_user['email']; ?>" class="box" autocomplete="email">
      <input type="number" name="number" min="0" max="9999999999" maxlength="10" placeholder="<?= $fetch_user['number']; ?>" class="box" autocomplete="tel">
      <input type="password" name="old_pass" maxlength="20" placeholder="Enter Your Old Password" class="box" autocomplete="current-password">
      <input type="password" name="new_pass" maxlength="20" placeholder="Enter Your New Password" class="box" autocomplete="new-password">
      <input type="password" name="c_pass" maxlength="20" placeholder="confirm your new password" class="box" autocomplete="new-password">
      <input type="submit" value="update now" name="submit" class="btn">
   </form>

</section>






<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>
</body>
</html>