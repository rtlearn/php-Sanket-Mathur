<?php

ini_set('error_reporting', 0);

// checking if servername is set
if (!isset($_SERVER['SERVER_NAME'])) {
    die('Server error.');
}

// checking if verification code is set
if (!isset($_GET['verification_code'])) {
    die('Invalid URL');
}

$con = mysqli_connect($_SERVER['SERVER_NAME'], 'root', '');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con, 'rtcamp');

// extracting and sanitizing verification_code
$code = $_GET['verification_code'];
$code = mysqli_real_escape_string($con, $code);

// finding the email corresponding to the verification code
$query = "SELECT email FROM temp_users WHERE verification_code='$code'";
$result = mysqli_query($con, $query);

if($result) {
    $row = mysqli_fetch_array($result);
    $email = $row['email'];

    // preventing duplicate code entries
    if (!$email) {
        die('Verification code is invalid.');
    }
    
    // generating a 32-bit unsubscribe token
    $token = md5(rand(1000, 9999));
    $query = "INSERT INTO users (email, unsubscribe_token) VALUES ('$email', '$token')";
    if (!mysqli_query($con, $query)) {
        die('Registration failed. Try again later.');
    }
    echo '<p>You have been successfully subscribed to the newsletter.</p>';

    // deleting the temporary user
    $query = "DELETE FROM temp_users WHERE email='$email'";
    mysqli_query($con, $query);
} else {
    echo '<p>Verification code is invalid.</p>';
}

mysqli_close($con);

?>