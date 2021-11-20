<?php

include "dbconfig.php";

$con = mysqli_connect($host, $username, $dbpassword, $dbname)
    or die("<br> Cannot connect to DB: $dbname on $host " . mysqli_connect_error());

// default time zone set to NY
date_default_timezone_set('America/New_York');

// get username from user 
if (isset($_POST["username"]) and isset($_POST["password"])) {
    $login = mysqli_real_escape_string($con, $_POST["username"]);

    // get the password form user 
    $password = mysqli_real_escape_string($con, $_POST["password"]);
}

$credentialSQL = "select * FROM 2021F_patpanka.CustomerCredentials cc, 2021F_patpanka.Customer c where cc.cid = c.cid and login = '$login'";


$credentialResult = mysqli_query($con, $credentialSQL);
$credentialNum = mysqli_num_rows($credentialResult);
$credentialRow = mysqli_fetch_array($credentialResult);

if ($credentialNum > 0) {
    if ($credentialRow["password"] == $password) {

        echo "welcome user " . $credentialRow["fName"];
    } else {


        header("location:login.html");
        echo "The login or username was entered incorrectly";
    }
} else {


    header("location:login.html");
    echo "The login or username was entered incorrectly";
}
