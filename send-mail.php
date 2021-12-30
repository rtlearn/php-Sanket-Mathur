<?php

// checking if servername is set
if (!isset($_SERVER['SERVER_NAME'])) {
    die('Server error.');
}

$con = mysqli_connect($_SERVER['SERVER_NAME'], 'root', '');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con, 'rtcamp');

// extracting all the emails from the users table
$query = "SELECT email FROM users";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $emails[] = $row['email'];
    }
} else {
    die('Could not retrieve emails from the database.');
}

// fetching a random comic from the xkcd API
$no = rand(1, 2561);
$url = 'https://xkcd.com/' . $no . '/info.0.json';

// requesting the web server for JSON data
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($curl);
curl_close($curl);

// extracting data
$data = json_decode($result, true);
$img = $data['img'];
$title = $data['safe_title'];

// sending the comic over the email
$subject = 'Random XKCD Comics';
$message = '<h3>' . $title . '</h3><br>';
$message .= '<img src="' . $img . '" alt="' . $title . '">';
$header = 'MIME-Version: 1.0' . '\r\n';
$header .= 'Content-Type: text/html; UTF-8' . '\r\n';
$header .= 'From: solution@localhost.com' . '\r\n';
$header .= 'Reply-To: solution@localhost.com';

foreach ($emails as $email) {
    mail($email, $subject, $message, $header);
}

// printing the comic on the page
echo $message;

?>