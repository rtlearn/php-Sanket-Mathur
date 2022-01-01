<?php

// checking if servername and host are set
if (!isset($_SERVER['SERVER_NAME']) || !isset($_SERVER['HTTP_HOST'])) {
    die('Server error.');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rtCamp Challenge - Email a random XKCD challenge</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Random XKCD Comics</h1>
        <p>You will receive comics every 5 minutes on your email after subscribing.</p>
        <!-- Form for user email submission -->
        <form action="" method="post">
            <label>Email Address: </label>
            <input type="email" name="email" size="50" required>
            <br>
            <button type="submit" name="submit">Send Verificaton Code</button>
        </form>
    </div>
</body>
</html>

<?php 
// MySQL connection
$con = mysqli_connect($_SERVER['SERVER_NAME'], 'root', '');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con, 'rtcamp');

// Handling on submit event
if (isset($_POST['submit']) && isset($_POST['email'])) {
    $email = $_POST['email'];
    // sanitize email
    $email = mysqli_real_escape_string($con, $email);

    // generating a 32-bit verification code
    $verification_code = md5(rand(1000, 9999));
    $verification_link = 'http://' . $_SERVER['HTTP_HOST'] . '/verify.php?verification_code=' . $verification_code;

    // Storing user email and verification code in the temporary table
    $query = "INSERT INTO temp_users (email, verification_code) VALUES ('$email', '$verification_code')";
    if (!mysqli_query($con, $query)) {
        die('Registration failed. Try again later.');
    }

    // sending verification link to the user's email
    $subject = 'Verification Code : Random XKCD Comics';
    $message = '<p>Click on this link to verify your email address: <a href=' . $verification_link . '>Verify Email</a></p>';
    $header = 'MIME-Version: 1.0' . '\r\n';
    $header .= 'Content-Type: text/html; UTF-8' . '\r\n';
    $header .= 'From: solution@localhost.com' . '\r\n';
    $header .= 'Reply-To: solution@localhost.com';
    
    if (mail($email, $subject, $message, $header)) {
        echo '<script>alert("Verification code sent");</script>';
    } else {
        echo '<script>alert("Could not send the verification code");</script>';
    }
}

mysqli_close($con);

?>