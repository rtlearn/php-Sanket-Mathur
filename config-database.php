<?php

// checking if servername is set
if (!isset($_SERVER['SERVER_NAME'])) {
    die('Server error.');
}

// connecting to the server
$con = mysqli_connect($_SERVER['SERVER_NAME'], 'root', '');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

// creating the database
$query = "CREATE DATABASE IF NOT EXISTS rtcamp";
if (!mysqli_query($con, $query)) {
    die('Database creation failed: ' . mysqli_error($con));
}
mysqli_select_db($con, 'rtcamp');

// creating temp_users table
$query = "CREATE TABLE IF NOT EXISTS temp_users (
    email VARCHAR(50) NOT NULL,
    verification_code VARCHAR(35) PRIMARY KEY
)";
if (!mysqli_query($con, $query)) {
    die('Table creation failed: ' . mysqli_error($con));
}

// creating users table
$query = "CREATE TABLE IF NOT EXISTS users (
    email VARCHAR(50) PRIMARY KEY,
    unsubscribe_token VARCHAR(35) NOT NULL UNIQUE
)";
if (!mysqli_query($con, $query)) {
    die('Table creation failed: ' . mysqli_error($con));
}

echo '<p>Database and tables created successfully.</p>';

mysqli_close($con);

?>