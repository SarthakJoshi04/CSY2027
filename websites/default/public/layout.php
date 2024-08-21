<?php
// layout.php
$pageTitle = "Woodland";
$activePage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylee.css">
    <title><?php echo $pageTitle; ?></title>
</head>
<body>
    <header class="index-header">
        <img class="logo" src="/images/Uni-logo.png" alt="Logo">
        <nav>
            <ul class="nav__links">
                <li class="<?php echo $activePage == 'index.php' ? 'active' : ''; ?>">
                    <a href="index.php">HOME</a>
                </li>
                <li class="<?php echo $activePage == 'academics.php' ? 'active' : ''; ?>">
                    <a href="academics.php">ACADEMICS</a>
                </li>
                <li class="<?php echo $activePage == 'students.php' ? 'active' : ''; ?>">
                    <a href="students.php">STUDENTS</a>
                </li>
                <li class="<?php echo $activePage == 'faculty.php' ? 'active' : ''; ?>">
                    <a href="faculty.php">FACULTY</a>
                </li>
                <li class="<?php echo $activePage == 'contact.php' ? 'active' : ''; ?>">
                    <a href="contact.php">CONTACT</a>
                </li>
            </ul>
        </nav>
        <a class="cta" href="student_login.php"><button>LOGIN</button></a>
    </header>

    <main>
        <?php echo $content; ?>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <img src="/images/WUC.png" alt="Woodland University College Logo">
            </div>
            <div class="footer-center">
                <nav>
                    <ul>
                        <li><a href="index.php">HOME</a></li>
                        <li><a href="academics.php">ACADEMICS</a></li>
                        <li><a href="students.php">STUDENTS</a></li>
                        <li><a href="faculty.php">FACULTY</a></li>
                    </ul>
                </nav>
            </div>
            <div class="footer-right">
                <div class="contact-info">
                    <p>CONTACT:</p>
                    <p><a href="mailto:woodlandinfo@uni.ac.uk">woodlandinfo@uni.ac.uk</a></p>
                    <p>+44 3645175647</p>
                    <button onclick="window.location.href='#';">APPLY NOW!</button>
                </div>
                <div class="social-icons">
                    <p>Socials</p>
                    <a href="#"><img src="/images/icons8-twitter-50.png" alt="X"></a>
                    <a href="#"><img src="/images/icons8-youtube-50.png" alt="YouTube"></a>
                    <a href="#"><img src="/images/icons8-facebook-50.png" alt="Facebook"></a>
                    <a href="#"><img src="/images/icons8-instagram-50.png" alt="Instagram"></a>
                    <a href="#"><img src="/images/icons8-linked-in-50.png" alt="LinkedIn"></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
