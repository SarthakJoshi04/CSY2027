<?php
// academics.php
$pageTitle = "Contact";
$content = '
<div class="container">
    <div class="contact-form">
        <h1 class="contact-title">Get In Touch</h1>
        <p class="contact-subtitle">Contact us if you need support for your next event</p>
        <form action="submit.php" method="POST">
            <div class="form-row">
                <input type="text" name="first_name" class="input-field" placeholder="First name">
                <input type="text" name="last_name" class="input-field" placeholder="Last name">
            </div>
            <div class="form-row">
                <input type="email" name="email" class="input-field" placeholder="Email address">
                <input type="text" name="phone" class="input-field" placeholder="Phone number">
            </div>
            <div class="form-row">
                <textarea name="message" class="input-field" placeholder="Message"></textarea>
            </div>
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
    <div class="image-section"></div>
</div>



';
include 'layout.php';
?>









