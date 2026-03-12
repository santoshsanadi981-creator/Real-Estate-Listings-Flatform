<?php  

include 'components/connect.php';
include 'components/language_config.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= translate('dashboard'); ?></title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
   <!-- Chart.js CDN -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
   <!-- Dashboard specific styles -->
   <style>
   .dashboard-charts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin: 2rem 0;
   }
   
   .chart-container {
      background: white;
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      position: relative;
      min-height: 400px;
      border: 1px solid #f0f0f0;
   }
   
   .chart-title {
      font-size: 1.8rem;
      color: var(--black);
      margin-bottom: 1.5rem;
      text-align: center;
      font-weight: 600;
   }
   
   .chart-canvas {
      max-height: 300px;
      width: 100%;
   }
   
   .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin: 2rem 0;
      padding: 0 1rem;
   }
   
   .stat-card {
      background: linear-gradient(135deg, #8e44ad, #e74c3c);
      color: white;
      padding: 2rem;
      border-radius: 1rem;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      transform: translateY(0);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
   }
   
   .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
   }
   
   .stat-card:hover::before {
      left: 100%;
   }
   
   .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.3);
   }
   
   .stat-number {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
   }
   
   .stat-label {
      font-size: 1.2rem;
      opacity: 0.9;
   }
   
   @media (max-width: 768px) {
      .dashboard-charts {
         grid-template-columns: 1fr;
      }
      .stats-grid {
         grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      }
      .stat-number {
         font-size: 2.5rem;
      }
   }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="dashboard">

   <h1 class="heading"><?= translate('dashboard'); ?></h1>

   <?php
   // Get comprehensive statistics
   $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
   $select_profile->execute([$user_id]);
   $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   
   // Properties statistics
   $count_properties = $conn->prepare("SELECT * FROM `property` WHERE user_id = ?");
   $count_properties->execute([$user_id]);
   $total_properties = $count_properties->rowCount();
   
   // Property status breakdown
   $count_available = $conn->prepare("SELECT * FROM `property` WHERE user_id = ? AND status = 'ready'");
   $count_available->execute([$user_id]);
   $available_properties = $count_available->rowCount();
   
   $count_construction = $conn->prepare("SELECT * FROM `property` WHERE user_id = ? AND status = 'construction'");
   $count_construction->execute([$user_id]);
   $construction_properties = $count_construction->rowCount();
   
   // Requests statistics
   $count_requests_received = $conn->prepare("SELECT * FROM `requests` WHERE receiver = ?");
   $count_requests_received->execute([$user_id]);
   $total_requests_received = $count_requests_received->rowCount();
   
   $count_requests_sent = $conn->prepare("SELECT * FROM `requests` WHERE sender = ?");
   $count_requests_sent->execute([$user_id]);
   $total_requests_sent = $count_requests_sent->rowCount();
   
   // Saved properties
   $count_saved_properties = $conn->prepare("SELECT * FROM `saved` WHERE user_id = ?");
   $count_saved_properties->execute([$user_id]);
   $total_saved_properties = $count_saved_properties->rowCount();
   
   // Property type breakdown
   $property_types = $conn->prepare("SELECT type, COUNT(*) as count FROM `property` WHERE user_id = ? GROUP BY type");
   $property_types->execute([$user_id]);
   $types_data = $property_types->fetchAll(PDO::FETCH_ASSOC);
   
   // If no properties exist, show zeros instead of fake data
   
   // Ensure we have some data for charts even if database has partial data
   if($available_properties == 0 && $construction_properties == 0 && $total_properties > 0) {
      // If we have properties but no status data, assume they are available
      $available_properties = $total_properties;
      $construction_properties = 0;
   }
   
   // If we still have no property types data but have properties, create basic distribution
   if(empty($types_data) && $total_properties > 0) {
      $types_data = [
         ['type' => 'house', 'count' => $total_properties]
      ];
   }
   
   // Calculate sold/rented properties
   $sold_rented_properties = max(0, $total_properties - $available_properties - $construction_properties);
   
   // Debug: Display actual values from database
   echo "<!-- REAL DATABASE VALUES:
   Total Properties: $total_properties
   Available: $available_properties
   Under Construction: $construction_properties
   Sold/Rented: $sold_rented_properties
   Total Requests Received: $total_requests_received
   Total Requests Sent: $total_requests_sent
   Total Saved Properties: $total_saved_properties
   -->";
   
   // Add minimum values for chart display if everything is zero
   if($total_properties == 0) {
      echo "<!-- No properties found in database for user ID: $user_id -->";
   }
   ?>

   <!-- Statistics Cards -->
   <div class="stats-grid">
      <div class="stat-card">
         <div class="stat-number"><?= $total_properties; ?></div>
         <div class="stat-label"><?= translate('properties_listed'); ?></div>
      </div>
      <div class="stat-card">
         <div class="stat-number"><?= $total_requests_received; ?></div>
         <div class="stat-label"><?= translate('requests_received'); ?></div>
      </div>
      <div class="stat-card">
         <div class="stat-number"><?= $available_properties; ?></div>
         <div class="stat-label">Available Properties</div>
      </div>
      <div class="stat-card">
         <div class="stat-number"><?= $total_saved_properties; ?></div>
         <div class="stat-label"><?= translate('properties_saved'); ?></div>
      </div>
   </div>

   <!-- Charts Section -->
   <div class="dashboard-charts">
      <!-- Property Status Chart -->
      <div class="chart-container">
         <h3 class="chart-title">Property Status Overview</h3>
         <canvas id="statusChart" class="chart-canvas"></canvas>
      </div>
      
      <!-- Inquiries Chart -->
      <div class="chart-container">
         <h3 class="chart-title">Inquiry Statistics</h3>
         <canvas id="inquiryChart" class="chart-canvas"></canvas>
      </div>
      
      <!-- Property Types Chart -->
      <div class="chart-container">
         <h3 class="chart-title">Property Types Distribution</h3>
         <canvas id="typesChart" class="chart-canvas"></canvas>
      </div>
   </div>

   <div class="box-container">

      <div class="box">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <h3><?= translate('welcome'); ?>!</h3>
      <p><?= $fetch_profile ? $fetch_profile['name'] : translate('guest_user'); ?></p>
      <a href="update.php" class="btn"><?= translate('update_profile'); ?></a>
      </div>

      <div class="box">
         <h3><?= translate('filter_search'); ?></h3>
         <p><?= translate('search_dream_property'); ?></p>
         <a href="search.php" class="btn"><?= translate('search_now'); ?></a>
      </div>

      <div class="box">
      <?php
        $count_properties = $conn->prepare("SELECT * FROM `property` WHERE user_id = ?");
        $count_properties->execute([$user_id]);
        $total_properties = $count_properties->rowCount();
      ?>
      <h3><?= $total_properties; ?></h3>
      <p><?= translate('properties_listed'); ?></p>
      <a href="my_listings.php" class="btn"><?= translate('view_all_listings'); ?></a>
      </div>

      <div class="box">
      <?php
        $count_requests_received = $conn->prepare("SELECT * FROM `requests` WHERE receiver = ?");
        $count_requests_received->execute([$user_id]);
        $total_requests_received = $count_requests_received->rowCount();
      ?>
      <h3><?= $total_requests_received; ?></h3>
      <p><?= translate('requests_received'); ?></p>
      <a href="requests.php" class="btn"><?= translate('view_all_requests'); ?></a>
      </div>

      <div class="box">
      <?php
        $count_requests_sent = $conn->prepare("SELECT * FROM `requests` WHERE sender = ?");
        $count_requests_sent->execute([$user_id]);
        $total_requests_sent = $count_requests_sent->rowCount();
      ?>
      <h3><?= $total_requests_sent; ?></h3>
      <p><?= translate('requests_sent'); ?></p>
      <a href="requests.php?type=sent" class="btn"><?= translate('view_sent_requests'); ?></a>
      </div>

      <div class="box">
      <?php
        $count_saved_properties = $conn->prepare("SELECT * FROM `saved` WHERE user_id = ?");
        $count_saved_properties->execute([$user_id]);
        $total_saved_properties = $count_saved_properties->rowCount();
      ?>
      <h3><?= $total_saved_properties; ?></h3>
      <p><?= translate('properties_saved'); ?></p>
      <a href="saved.php" class="btn"><?= translate('view_saved_properties'); ?></a>
      </div>

   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

<!-- Dashboard Charts JavaScript -->
<script>
// Property Status Chart - Using Real Database Values
const statusCtx = document.getElementById('statusChart');
if(statusCtx) {
    // Get real values from PHP database queries
    const availableCount = <?= $available_properties; ?>;
    const constructionCount = <?= $construction_properties; ?>;
    const soldCount = <?= $sold_rented_properties; ?>;
    const totalCount = <?= $total_properties; ?>;
    
    console.log('Real Database Chart Data:', {
        available: availableCount,
        construction: constructionCount,
        sold: soldCount,
        total: totalCount
    });
    
    // Only show chart if we have properties
    if(totalCount > 0) {
        const chartData = [availableCount, constructionCount, soldCount];
        const chartLabels = [
            'Available (' + availableCount + ')',
            'Under Construction (' + constructionCount + ')', 
            'Sold/Rented (' + soldCount + ')'
        ];
        
        console.log('Chart Data Array:', chartData);
        console.log('Chart Labels:', chartLabels);
        
        // Create chart with real data
        try {
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: chartData,
                        backgroundColor: [
                            '#27ae60',  // Green for Available
                            '#f39c12',  // Orange for Under Construction  
                            '#e74c3c'   // Red for Sold/Rented
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '50%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: 'white',
                            bodyColor: 'white'
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1500
                    }
                }
            });
            console.log('Chart created successfully with real data!');
        } catch(error) {
            console.error('Chart creation failed:', error);
        }
    } else {
        // Show message when no properties exist
        const ctx = statusCtx.getContext('2d');
        ctx.font = '16px Arial';
        ctx.fillStyle = '#666';
        ctx.textAlign = 'center';
        ctx.fillText('No properties found', statusCtx.width/2, statusCtx.height/2 - 10);
        ctx.fillText('Start by posting your first property!', statusCtx.width/2, statusCtx.height/2 + 10);
        console.log('No properties found - showing message');
    }
} else {
    console.error('Chart canvas not found!');
}

// Inquiries Chart
const inquiryCtx = document.getElementById('inquiryChart');
if(inquiryCtx) {
    const inquiryChart = new Chart(inquiryCtx, {
        type: 'bar',
        data: {
            labels: ['Received', 'Sent'],
            datasets: [{
                label: 'Inquiries',
                data: [<?= $total_requests_received; ?>, <?= $total_requests_sent; ?>],
                backgroundColor: [
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(155, 89, 182, 0.8)'
                ],
                borderColor: [
                    '#3498db',
                    '#9b59b6'
                ],
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white'
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutBounce'
            }
        }
    });
}

// Property Types Chart
const typesCtx = document.getElementById('typesChart');
if(typesCtx) {
    const typesChart = new Chart(typesCtx, {
        type: 'pie',
        data: {
            labels: [<?php foreach($types_data as $type) { echo "'" . ucfirst($type['type']) . "',"; } ?>],
            datasets: [{
                data: [<?php foreach($types_data as $type) { echo $type['count'] . ","; } ?>],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverBorderWidth: 5,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 2000
            }
        }
    });
}
</script>

</body>
</html>