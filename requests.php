<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

if(isset($_POST['delete'])){

   $delete_id = $_POST['request_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = $conn->prepare("SELECT * FROM `requests` WHERE id = ?");
   $verify_delete->execute([$delete_id]);

   if($verify_delete->rowCount() > 0){
      $delete_request = $conn->prepare("DELETE FROM `requests` WHERE id = ?");
      $delete_request->execute([$delete_id]);
      $success_msg[] = 'Request Deleted Successfully!';
   }else{
      $warning_msg[] = 'Request Deleted Already!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Requests</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="requests">

   <h1 class="heading"><?= isset($_GET['type']) && $_GET['type'] == 'sent' ? 'Requests Sent' : 'All Requests'; ?></h1>

   <div class="box-container">

   <?php
      // Check if viewing sent or received requests
      $request_type = isset($_GET['type']) && $_GET['type'] == 'sent' ? 'sender' : 'receiver';
      $select_requests = $conn->prepare("SELECT * FROM `requests` WHERE $request_type = ?");
      $select_requests->execute([$user_id]);
      if($select_requests->rowCount() > 0){
         while($fetch_request = $select_requests->fetch(PDO::FETCH_ASSOC)){

        // Get the other user (sender if viewing received, receiver if viewing sent)
        $other_user_id = ($request_type == 'sender') ? $fetch_request['receiver'] : $fetch_request['sender'];
        $select_other_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_other_user->execute([$other_user_id]);
        $fetch_other_user = $select_other_user->fetch(PDO::FETCH_ASSOC);

        $select_property = $conn->prepare("SELECT * FROM `property` WHERE id = ?");
        $select_property->execute([$fetch_request['property_id']]);
        $fetch_property = $select_property->fetch(PDO::FETCH_ASSOC);
   ?>
   <div class="box">
      <p><?= ($request_type == 'sender') ? 'Sent To' : 'From'; ?> : <span><?= $fetch_other_user['name']; ?></span></p>
      <p>Number : <a href="tel:<?= $fetch_other_user['number']; ?>"><?= $fetch_other_user['number']; ?></a></p>
      <p>Email : <a href="mailto:<?= $fetch_other_user['email']; ?>"><?= $fetch_other_user['email']; ?></a></p>
      <p>Property : <span><?= $fetch_property['property_name']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="request_id" value="<?= $fetch_request['id']; ?>">
         <input type="submit" value="Delete Request" class="btn" onclick="return confirm('Remove This Request?');" name="delete">
         <a href="view_property.php?get_id=<?= $fetch_property['id']; ?>" class="btn">View Property</a>
      </form>
   </div>
   <?php
    }
   }else{
      echo '<p class="empty">You Have No ' . (($request_type == 'sender') ? 'Sent' : 'Received') . ' Requests!</p>';
   }
   ?>

   </div>

</section>






















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>