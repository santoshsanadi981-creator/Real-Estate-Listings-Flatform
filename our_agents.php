<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Our Agents</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
   .agents-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 1.5rem;
      padding: 2rem;
      max-width: 1200px;
      margin: 0 auto;
   }
   
   .agent-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 1.5rem;
      overflow: hidden;
      transition: all 0.3s ease;
      border: 1px solid #e8ecf4;
      position: relative;
      text-align: center;
   }
   
   .agent-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
   }
   
   .agent-header {
      margin-bottom: 1rem;
      text-align: center;
   }
   
   .agent-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      margin: 0 auto 1rem;
      border: 3px solid #667eea;
      position: relative;
      overflow: hidden;
   }
   
   .agent-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
   }
   
   .agent-name {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.3rem;
      color: #333;
   }
   
   .agent-agency {
      font-size: 0.85rem;
      color: #667eea;
      margin-bottom: 0.8rem;
      font-weight: 500;
   }
   
   .agent-rating {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.2rem;
      margin-bottom: 1rem;
   }
   
   .rating-number {
      margin-left: 0.5rem;
      font-weight: 600;
      font-size: 0.9rem;
   }
   
   .agent-info {
      text-align: left;
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
   }
   
   .agent-specialization {
      background: #667eea;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.8rem;
      color: white;
      margin-bottom: 1rem;
      text-align: center;
      font-weight: 500;
      display: inline-block;
   }
   
   .agent-stats {
      display: flex;
      justify-content: space-around;
      margin-bottom: 1rem;
   }
   
   .stat-item {
      text-align: center;
      flex: 1;
   }
   
   .stat-number {
      font-size: 1rem;
      font-weight: 600;
      color: #667eea;
      display: block;
   }
   
   .stat-label {
      font-size: 0.7rem;
      color: #666;
      text-transform: uppercase;
   }
   
   .agent-detail {
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
      font-size: 0.8rem;
      color: #555;
   }
   
   .agent-detail i {
      width: 16px;
      margin-right: 0.5rem;
      color: #667eea;
      font-size: 0.9rem;
   }
   
   .agent-contact {
      display: flex;
      gap: 0.5rem;
      margin-top: 1rem;
   }
   
   .contact-btn {
      padding: 0.6rem 1rem;
      border-radius: 8px;
      text-decoration: none;
      font-size: 0.8rem;
      font-weight: 500;
      text-align: center;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.3rem;
      flex: 1;
   }
   
   .email-btn {
      background: #667eea;
      color: white;
   }
   
   .phone-btn {
      background: #28a745;
      color: white;
   }
   
   .contact-btn:hover {
      transform: translateY(-2px);
      opacity: 0.9;
   }
   
   .agent-license {
      margin-top: 1rem;
      padding: 0.5rem;
      background: #f8f9fa;
      border-radius: 5px;
      font-size: 0.7rem;
      color: #6c757d;
      text-align: center;
      border: 1px solid #dee2e6;
   }
   
   .agent-bio {
      margin: 1rem 0;
      padding: 1rem;
      background: #fff;
      border-left: 4px solid #667eea;
      border-radius: 0 8px 8px 0;
      font-size: 0.9rem;
      line-height: 1.6;
      color: #495057;
      font-style: italic;
   }
   
   .agent-info-list {
      text-align: left;
      margin-bottom: 1rem;
   }
   
   .info-item {
      display: flex;
      align-items: center;
      padding: 0.4rem 0;
      font-size: 0.9rem;
      color: #333;
      border-bottom: 1px solid #f0f0f0;
   }
   
   .info-item:last-child {
      border-bottom: none;
   }
   
   .agent-name-item {
      font-size: 1.1rem;
      padding: 0.6rem 0;
      border-bottom: 2px solid #667eea;
      margin-bottom: 0.5rem;
   }
   
   .agent-name-item strong {
      color: #333;
      font-weight: 600;
   }
   
   .info-item i {
      width: 20px;
      margin-right: 0.8rem;
      color: #667eea;
      font-size: 0.9rem;
      text-align: center;
   }
   
   .info-item span {
      color: #555;
   }
   
   @media (max-width: 768px) {
      .agents-container {
         grid-template-columns: 1fr;
         padding: 1rem;
      }
   }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- agents section starts  -->

<section class="listings">

   <h1 class="heading">Our Professional Agents</h1>

   <div class="agents-container">
   
   <?php
      $select_agents = $conn->prepare("SELECT * FROM `agents` WHERE status = 'active' ORDER BY date_joined DESC");
      $select_agents->execute();
      
      if($select_agents->rowCount() > 0){
         while($fetch_agent = $select_agents->fetch(PDO::FETCH_ASSOC)){
   ?>
   
   <div class="agent-card">
      <div class="agent-header">
         <div class="agent-avatar">
            <?php 
            // Assign different profile images to agents
            $agent_images = ['pic-1.png', 'pic-2.png', 'pic-3.png', 'pic-4.png', 'pic-5.png', 'pic-6.png'];
            // Ensure we have a valid numeric ID, fallback to 1 if not
            $agent_id = isset($fetch_agent['id']) && is_numeric($fetch_agent['id']) ? (int)$fetch_agent['id'] : 1;
            $image_index = ($agent_id - 1) % count($agent_images);
            $agent_image = $agent_images[$image_index];
            ?>
            <img src="../../desgin/images/<?= $agent_image; ?>" alt="<?= $fetch_agent['name']; ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="agent-initials" style="display: none; width: 100%; height: 100%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; backdrop-filter: blur(10px);"><?= strtoupper(substr($fetch_agent['name'], 0, 1) . substr(strstr($fetch_agent['name'], ' '), 1, 1)); ?></div>
         </div>
      </div>
      
      <div class="agent-info-list">
         <div class="info-item agent-name-item">
            <strong><?= $fetch_agent['name']; ?></strong>
         </div>
         
         <div class="info-item">
            <i class="fas fa-envelope"></i>
            <span><?= $fetch_agent['email']; ?></span>
         </div>
         
         <div class="info-item">
            <i class="fas fa-phone"></i>
            <span><?= $fetch_agent['number']; ?></span>
         </div>
         
         <div class="info-item">
            <i class="fas fa-id-card"></i>
            <span>License: <?= $fetch_agent['license_number']; ?></span>
         </div>
         
         <div class="info-item">
            <i class="fas fa-building"></i>
            <span><?= $fetch_agent['agency_name']; ?></span>
         </div>
         
         <div class="info-item">
            <i class="fas fa-briefcase"></i>
            <span>Experience: <?= $fetch_agent['experience']; ?> years</span>
         </div>
         
         <div class="info-item">
            <i class="fas fa-tag"></i>
            <span>Specialization: <?= $fetch_agent['specialization']; ?></span>
         </div>
         
         <div class="info-item">
            <i class="fas fa-star"></i>
            <span>Rating: <?php 
            $rating = isset($fetch_agent['rating']) ? $fetch_agent['rating'] : 4.5;
            echo $rating;
            ?>/5</span>
         </div>
         
         <div class="info-item">
            <i class="fas fa-calendar"></i>
            <span>Joined: <?= date('M j, Y', strtotime($fetch_agent['date_joined'])); ?></span>
         </div>
      </div>
      
      <div class="agent-contact">
         <a href="mailto:<?= $fetch_agent['email']; ?>" class="contact-btn email-btn">
            <i class="fas fa-envelope"></i> Email
         </a>
         <a href="tel:<?= $fetch_agent['number']; ?>" class="contact-btn phone-btn">
            <i class="fas fa-phone"></i> Call
         </a>
      </div>
   </div>
   
   <?php
         }
      }else{
         echo '<p class="empty">No agents found!</p>';
      }
   ?>
   
   </div>
   
   <div style="text-align: center; margin: 3rem 0;">
      <h2>Want to Join Our Team?</h2>
      <p>Become a professional real estate agent with HomeHive</p>
      <a href="agent_register.php" class="btn" style="display: inline-block; margin-top: 1rem;">
         <i class="fas fa-user-plus"></i> Join As Agent
      </a>
   </div>

</section>

<!-- agents section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>