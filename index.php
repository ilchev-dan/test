<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Mismatch - Where opposites attract!</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>Mismatch - Where opposites attract!</h3>

<?php
    require_once('appwars.php');
    require_once('connect.php');
    session_start();

    // Generate the navigation menu
    if (isset($_SESSION['username'])) {
        echo '&#10084; <a href="viewprofile.php">View Profile</a><br>';
        echo '&#10084; <a href="editprofile.php">Edit Profile</a><br>';
        echo '&#10084; <a href="signout.php">Sign out ' . $_SESSION['username'] . '</a><br>';
    }
    else {
        echo '&#10084; <a href="signin.php">Sign in</a><br>';
        echo '&#10084; <a href="signup.php">Sign up</a><br>';
    }
    // Connect to the database 
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME); 

    // Retrieve the user data from MySQL
    $query = "SELECT id, first_name, picture, username FROM mismatch_user /*WHERE first_name IS NOT NULL */ORDER BY join_date DESC LIMIT 5";
    $data = mysqli_query($dbc, $query);

    // Loop through the array of user data, formatting it as HTML
    echo '<h4>Latest members:</h4>';
    echo '<table>';
    
    while ($row = mysqli_fetch_array($data)) {
        echo $row['username'];
        if (is_file(GW_UPLOADPATH . $row['picture']) && filesize(GW_UPLOADPATH . $row['picture']) > 0) {
            echo '<tr><td><img src="' . GW_UPLOADPATH . $row['picture'] . '" alt="' . $row['first_name'] . '" ></td>';
        }
        else {
            echo '<tr><td><img src="' . GW_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['first_name'] . '" width = "225"></td>';
        }
        if (isset($_SESSION['user_id'])) {
            echo '<td><a href="viewprofile.php?user_id=' . $row['id'] . '">' . $row['first_name'] . '</a></td></tr>';
        }
        else {
            echo '<td>' . $row['first_name'] . '</td></tr>';
        }
    }
    echo '</table>';

    mysqli_close($dbc);
?>

</body> 
</html>