<?php
// index.php
$pageTitle = "Home";
$content = '
    <!--Image-->

<div class = "mainpage">
    <div class="hero-section">
        <img src="/Images/priscilla-du-preez-XkKCui44iM0-unsplash.jpg" alt="Welcome Image" class="hero-image">
        <div class="text-content">
            <h1 class="welcome-text">WELCOME TO</h1>
        </div>
    </div>
    <div class = "content-section">
        <h2 class="university-name">WOODLAND UNIVERSITY COLLEGE</h2>
  </div>  
    <div class = "final-content"> 
        <p class="subtext">ACHIEVE YOUR DREAMS WITH US!</p>
        <p class="description">ENGAGE IN OUR TRANSFORMATIVE PROGRAMS, DEVELOP YOUR EXPERTISE, AND THRIVE IN A GLOBAL SOCIETY</p>
        </div>
    </div>
    <hr>


    <!--Second content about the university-->


    <div class="uni-page">
        <div class="uni-text">
            <p>Welcome to Woodland University College, where
                we empower our students to achieve their
                academic and personal goals. Our dedicated
                faculty and staff provide a supportive learning
                environment that fosters intellectual growth and
                critical thinking. At Woodland University College,
                we believe in providing quality education that
                prepares students for successful careers in their
                chosen fields. Join us on this exciting journey of
                knowledge and discovery.</p>
            <button class="apply-button">APPLY NOW!</button>
        </div>
        <div class="uni-container">
            <div class="uni-picture-box"></div>
            <div class="uni-picture-content"></div>
        </div>
    </div> 
        <hr>


    <!--Academics div-->

    <div class = "academics-page">
     <div class="academics-container">
        <div class="picture-box"></div>
        <div class="picture-content"></div>
    </div>
    <div class = "academics-text">
    <p>Discover your path with our
        wide range of academic
        programs. Our advisors are
        here to help you find the
        perfect fit and guide you
        towards your goals.</p>
      <button class="academics-button">ACADEMICS</button>
   </div>
   </div> 
    <hr>



   <!--Faculty div-->

  <div class="faculty-page">
        <div class="faculty-text">
            <p>Empower your teaching with our
               extensive resources and support
               services. We\'re committed to
               helping you inspire and educate
               the next generation.</p>
            <button class="faculty-button">FACULTY</button>
        </div>
        <div class="faculty-container">
            <div class="faculty-picture-box"></div>
            <div class="faculty-picture-content"></div>
        </div>
    </div> 
        <hr>
    
    <!--Students div-->
    <div class="students-page">
        <div class="students-container">
            <div class="students-content"></div>
            <div class="students-picture"></div>
        </div>
        <div class="students-text">
            <p>Unlock your potential and discover the support you need to thrive in your studies. We\'re here to help you every step of the way.</p>
            <button class="students-button">STUDENTS</button>
        </div>
    </div> 

    <hr>
    <!--Contact div-->
    <div class="contact-section">
      <p>Have questions or need assistance? Our dedicated team is here to help. Reach out to us anytime, and we\'ll provide the support you need.</p>
      <button class="contact-button">CONTACT US!</button>
   </div>
';
include 'layout.php';
?>