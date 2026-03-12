<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['send'])){

   $msg_id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_contact = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_contact->execute([$name, $email, $number, $message]);

   if($verify_contact->rowCount() > 0){
      $warning_msg[] = 'message sent already!';
   }else{
      $send_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $send_message->execute([$msg_id, $name, $email, $number, $message]);
      $success_msg[] = 'message send successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact Us</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">
      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>
      <form action="" method="post">
         <h3>get in touch</h3>
         <input type="text" name="name" required maxlength="50" placeholder="Enter Your Name" class="box" autocomplete="name">
         <input type="email" name="email" required maxlength="50" placeholder="Enter Your Email" class="box" autocomplete="email">
         <input type="number" name="number" required maxlength="10" max="9999999999" min="0" placeholder="Enter Your Number" class="box" autocomplete="tel">
         <textarea name="message" placeholder="Enter Your Message" required maxlength="1000" cols="30" rows="10" class="box"></textarea>
         <input type="submit" value="send message" name="send" class="btn">
      </form>
   </div>

</section>

<!-- contact section ends -->

<!-- faq section starts  -->

<section class="faq" id="faq">

   <h1 class="heading">FAQ</h1>

   <div class="box-container">

      <div class="box active">
         <h3><span>How can I cancel my booking?</span><i class="fas fa-angle-down"></i></h3>
         <p>You can easily cancel your booking by visiting your dashboard and selecting the "My Bookings" section. Choose the booking you want to cancel and click on "Cancel." Please review the cancellation policy for applicable charges or refund timelines.</p>
      </div>

      <div class="box active">
         <h3><span>When will I get the possession?</span><i class="fas fa-angle-down"></i></h3>
         <p>Possession dates are mentioned in your booking confirmation or agreement. You can also check your possession timeline by logging into your account or contacting our support team for an update on your property status.</p>
      </div>

      <div class="box">
         <h3><span>Where can I pay the rent?</span><i class="fas fa-angle-down"></i></h3>
         <p>Rent payments can be made securely through our online portal using debit/credit cards, UPI, or net banking. Go to the "Payments" section in your account and follow the on-screen instructions to complete your transaction.</p>
      </div>

      <div class="box">
         <h3><span>How can I contact the buyers?</span><i class="fas fa-angle-down"></i></h3>
         <p>To reach out to potential buyers, go to the "Leads" or "Messages" section in your dashboard. From there, you can view buyer inquiries and chat or call them directly using the provided contact options.</p>
      </div>

      <div class="box">
         <h3><span>Why is my listing not showing up?</span><i class="fas fa-angle-down"></i></h3>
         <p>If your listing isn't visible, it might still be under review or missing some required details. Please check your listing status in the "My Listings" section and ensure all fields, including images and pricing, are complete and accurate.</p>
      </div>

      <div class="box">
         <h3><span>How can I promote my listing?</span><i class="fas fa-angle-down"></i></h3>
         <p>You can increase your listing's visibility by opting for our Promote or Featured Listing plans. Visit the "Promotions" tab in your dashboard to explore available packages and choose the one that best fits your marketing goals.</p>
      </div>

   </div>

</section>

<!-- faq section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>