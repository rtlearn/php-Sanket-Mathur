<?php

// checking if servername and host are set
if (!isset($_SERVER['SERVER_NAME']) || !isset($_SERVER['HTTP_HOST'])) {
    die('Server error.');
}

$con = mysqli_connect($_SERVER['SERVER_NAME'], 'root', '');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con, 'rtcamp');

// extracting all the emails from the users table
$query = "SELECT * FROM users";
$result = mysqli_query($con, $query);

$subscribers = array();
if ($result) {
    $row = mysqli_fetch_array($result);
    while ($row) {
        if ($row['email'] && $row['unsubscribe_token']) {
            array_push($subscribers, $row);
        }
        $row = mysqli_fetch_array($result);
    }
} else {
    die('Could not retrieve emails from the database.');
}

mysqli_close($con);

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

// printing the comic to the browser
echo '<img src="' . $img . '" alt="' . $title . '" />';

// sending the comic over the email
$subject = 'Random XKCD Comics';
$header = 'MIME-Version: 1.0' . '\r\n';
$header .= 'Content-Type: text/html; UTF-8' . '\r\n';
$header .= 'From: solution@localhost.com' . '\r\n';
$header .= 'Reply-To: solution@localhost.com';

foreach ($subscribers as $user) {
    // adding unsubscribe_token to the message
    $message = '<h3>' . $title . '</h3><br>';
    $message .= '<img src="' . $img . '" alt="' . $title . '"><br><br>';
    $message .= 'Click here to <a href="http://' . $_SERVER['HTTP_HOST'] . '/unsubscribe.php?unsubscribe_token=' . $user['unsubscribe_token'] . '">unsubscribe</a>.';

    mail($user['email'], $subject, $message, $header);
}

?>