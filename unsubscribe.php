<?php

// checking if servername is set
if (!isset($_SERVER['SERVER_NAME'])) {
    die('Server error.');
}

// checking if unsubscribe token is set
if (!isset($_GET['unsubscribe_token'])) {
    die('Invalid URL');
}

$con = mysqli_connect($_SERVER['SERVER_NAME'], 'root', '');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con, 'rtcamp');

// deleting the user from the database
$token = $_GET['unsubscribe_token'];

$query = "DELETE FROM users WHERE unsubscribe_token='$token'";
if (mysqli_query($con, $query)) {
    echo '<p>You have been successfully unsubscribed from the newsletter.</p>';
} else {
    echo '<p>Unsubscribe failed. Try again later.</p>';
}

mysqli_close($con);

?>