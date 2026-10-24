<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'db-conn.php';

$pageClass = 'inner-page';
include __DIR__ . '/includes/layout-start.php';
?>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }

        .header-about {
            margin-top: calc(var(--header-total) + 16px);

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 70vh;
            color: #ffffff;
            text-align: center;
            padding: 40px;
            position: relative;
        }
        .header-about img {
            width: 70%;
            max-height: 70%;
            object-fit: scale-down;
        }
        .about-content {
            padding: 60px 20px;
            background: linear-gradient(135deg, #3b0a45, #1a1a1d);

            
        }

        .about-text {
            margin-top: 30px;
            text-align: center;
        }

        .team-section {
            padding: 60px 20px;
            background-color: #1a1a1d;
        }

        .team-member {
            text-align: center;
            margin-bottom: 30px;
        }

        .team-member img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 15px;
            border: 2px solid #3b0a45;
        }

        .social-icons {
            text-align: center;
            padding: 30px 0;
            background-color: #3b0a45;
        }

        .social-icons a {
            color: #e0e0e0;
            margin: 0 15px;
            font-size: 24px;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #e91e63;
        }

        @media (max-width: 767px) {
    .header-about {
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .about-text {
        width: 100%;
        padding: 20px;
        text-align: center;
    }

    .team-member {
        width: 100%;
    }
}
    </style>

    <!-- Header Section -->
    <section class="header-about">
        <img src="assets/img/logo.png" alt="Space Image" class="img-fluid">
        <div class="about-text">
            <h1><?= t('about.title') ?></h1>
            <p>
            At Cosmixify, we strive to bring the wonders of the cosmos to your doorstep. Our mission is to ignite a 
            passion for curiosity and discovery by providing stunning astronomical images and rich information about space.
              </p><br><br>
        </div>
        
    </section>

    <!-- About Content Section -->
    <section class="about-content">
        <div class="container">
            <h2 class="text-center">Our Mission</h2>
            <p class="text-center">
            We aim to create a global community of astronomy enthusiasts and explorers who share their love for space. At Cosmixify, we open the door for everyone to share their own experiences through the astronomical images they capture. We believe that each person has a unique perspective that can add to our understanding of the universe. You can be part of this creative community and share your wonderful pictures of stars, planets, and galaxies that you capture yourself. In addition, we provide detailed information about upcoming astronomical events to help you better observe the sky.            </p>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2 class="text-center">Meet Our Team</h2>
            <div class="row justify-content-center">
                <div class="col-md-3 team-member" data-aos="fade-up" data-aos-delay="100">
                    <img src="https://muamel.pro/assets/images/me.jpg" alt="Team Member 1">
                   <a href="muamel.pro"> <h4>Muamel Tahsin</h4></a>
                    <p>Website manger &amp; Programmer</p>
                </div>
                <div class="col-md-3 team-member" data-aos="fade-up" data-aos-delay="200">
                    <img src="assets/img/def.jpg" alt="Team Member 2">
            <h4>Nabaa Thasin</h4>
                    <p>Editor & Translator </p>
                </div>
                
                <!-- Add more team members as needed -->
            </div>
        </div>
    </section>

    <!-- Social Icons Section -->
 <section class="social-icons">
        <div class="container">
            <a href="https://facebook.com"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/cosmixify_"><i class="fab fa-instagram"></i></a>
            <a href="mailto:contact@cosmixify.space"><i class="fas fa-envelope"></i></a>
        </div>
    </section>

    <!-- Include Bootstrap JS and other scripts -->
    <?php include __DIR__ . '/includes/layout-end.php'; ?>
