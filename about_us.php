<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .about-section {
            padding: 50px;
            background-color: #f4f4f4;
            border-radius: 5px;
        }
        .team-member {
            margin-top: 20px;
            text-align: center;
        }
        .team-img {
            border-radius: 50%;
            height: 150px;
            width: 150px;
        }
        .social-icons {
            margin-top: 10px;
        }
        .social-icons a {
            margin: 0 5px;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="about-section">
                <h1 class="text-center">About Us</h1>
                <p class="mt-4">
                    Welcome to SubLover, the ultimate destination for movie enthusiasts who cherish a deep appreciation for cinematic stories from around the globe. At SubLover, our mission is simple: to archive an extensive collection of subtitles, enabling you to fully immerse in the art of film, no matter the language barrier.
                </p>
            </div>
        </div>
    </div>
    <h2 class="mt-5 text-center">About Me</h2>
    <div class="row text-center mt-3">
        <!-- Team Member 1 -->
        <div class="col-md-4 team-member">
        </div>
        <!-- Team Member 2 -->
        <div class="col-md-4 team-member">
            <img src="asset/profile.jpg" class="team-img mb-3" alt="Team Member">
            <h5>Atik Hasan</h5>
            <div class="social-icons">
                <a href="#" class="text-dark"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-dark"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-dark"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
        <!-- Team Member 3 -->
        <div class="col-md-4 team-member">
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php
include 'footer.php';
?>
</html>
