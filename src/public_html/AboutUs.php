<?php 
    require_once '../Back-end/DAO.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AboutUs</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Iconos / Otros estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="AboutUs.css">
    <link href="Footer.css" rel="stylesheet">
    <link href="Post.css" rel="stylesheet">
    <link href="responsive2.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <!-- Googlefonts -->
    <link href="https://fonts.googleapis.com/css2?family=Goblin+One&display=swap" rel="stylesheet">

</head>
<body>
    <div class="header">
        <div class="containerHeader">
            <button class="WEBName" id="LogoButton">StUpConnect</button>
            <h2>About Us</h2>
            <button class="customer-support-btn">
                💬 Contact us
            </button>
        </div>
    </div>

    <div class="about-us">
        <div class="Platform-Description">
            <h1>About Us</h1>
                <p><strong>StUpConnect</strong> is the platform that connects entrepreneurs with talent to build startups from anywhere in the world.
                            Our mission is to facilitate authentic collaborations and help great ideas become successful startups.<br></p>
        </div>
        <div class="My-Description">
            <h2>Our Story</h2>
            <p>I am a computer engineering student with an entrepreneurial spirit and a strong desire to transform my ideas into reality. While studying, I realized how difficult it can be to find people who share your passions and have the right motivation to build a startup. That's why I decided to create StUpConnect: a place where creativity and talent come together to turn ideas into reality.</p>
        </div>
        <div class="Offer">
            <h2>What We Offer</h2>
            <ul>
                <li><strong>🚀Post your startups:</strong> Find the ideal team to grow your idea.</li>
                <li><strong>✅Connect with entrepreneurs:</strong> Join innovative startups and leave your mark.</li>
                <li><strong>🔎Discover opportunities:</strong> Explore exciting projects in technology, sustainability, education, and more.</li>
            </ul>
        </div>
        <div class="Our-Vision">
            <h2>Our Vision & Mission</h2>
            <p><strong>👁️Our vision</strong> is to build a global community where every idea has the opportunity to find a team to work with and become a successful startup.</p>
            <p><strong>💪🏻Our mission</strong> is to facilitate access to entrepreneurship by enabling anyone with talent to collaborate in innovative startups, regardless of their location.</p>
        </div>
        <div class="Join-Us">
            <h2>Join Us</h2>
            <p><strong>Ready to get started?</strong> Join StUpConnect and be part of a new generation of startups. Post your first startup or find the team you need to take your idea to the next level.</p>
        </div>
        <div class="created-by">
            <p>---Created by <strong>Juan González Pardo</strong>---</p>
        </div>
    </div>

    <footer>
        <div class="lfooter"></div>
        <div class="Social-Net">  
            <a href="mailto:gzpardo04@gmail.com"><i class=" social fas fa-envelope"></i></a>
            <a href="#"><i class=" social fa-brands fa-twitter"></i></a>
            <a href="#"><i class=" social fa-brands fa-instagram"></i></a>
            <a href="#"><i class=" social fa-brands fa-tiktok"></i></a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script>

        <?php 
            $user = isset($_SESSION['UserG']) ? json_decode($_SESSION['UserG']) : null;
        ?>
        console.log(<?php echo json_encode($user); ?>);
        var isLoggedIn = <?php echo ($user) ? 'true' : 'false'; ?>;
        document.getElementById('LogoButton').addEventListener('click', function(){
            if(isLoggedIn) {
            window.location.href = 'Inicio.php';
            } else {
            window.location.href = 'index.php';
            }
        });
</script>


