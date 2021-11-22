<?php

include "dbconfig.php";  // hold db credentials

// connection to the database
$con = mysqli_connect($host,$username,$dbpassword,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

// default time zone set to NY
date_default_timezone_set('America/New_York');

// get username from user 
if(isset($_POST["username"]) and isset($_POST["password"]))
{
    // get the login from the user
	$login = mysqli_real_escape_string($con,$_POST["username"]);

    // get the password form user 
	$password= mysqli_real_escape_string($con,$_POST["password"]);	
}

// sql statement to check login with the customer table
$credentialSQL = "select * FROM 2021F_patpanka.CustomerCredentials cc, 2021F_patpanka.Customer c where cc.cid = c.cid and login = '$login'";

        // connections to the database
        $credentialResult = mysqli_query($con, $credentialSQL); 
        $credentialNum = mysqli_num_rows($credentialResult);
        $credentialRow = mysqli_fetch_array($credentialResult); 

// sql statement to check the login with the admin table 
$adminCredentialSQL= "select * FROM 2021F_patpanka.AdminCredentials ac, 2021F_patpanka.Admin a where ac.aid = a.aid and login = '$login'";
        // connections to the database
        $adminCredentialResult = mysqli_query($con, $adminCredentialSQL); 
        $adminCredentialNum = mysqli_num_rows($adminCredentialResult);
        $adminCredentialRow = mysqli_fetch_array($adminCredentialResult); 

        // check to see if the query results are coming from the admin table or user table 
        // based on the output the user is directed to the proper page admin or user 
        if($credentialNum > 0)
        {
            // validating password for users
            if($credentialRow["password"] == $password)
            {

                // set cookies on sucessfull login
                $userFirstName= $credentialRow["fName"];
                $userLastName= $credentialRow["lName"];
                $userCid = $credentialRow["cid"];


                setcookie("FirstName", $userFirstName, time() + 3600, "/");
                setcookie("LastName", $userLastName, time() + 3600, "/");
                setcookie("customerID" , $userCid,  time() + 3600, "/");

                header("location:store.php");
               
            }
            else
            {

                header("location: login.html");
        
            }
        }
        else if ($adminCredentialNum > 0)
        {
            // validating password for admin
            if($adminCredentialRow["password"] == $password)
            {
               
                // set cookies on sucessfull loginß
                $userFirstName= $adminCredentialRow["fName"];
                $userLastName= $adminCredentialRow["lName"];
                $userAid = $adminCredentialRow["aid"];


                setcookie("FirstName", $userFirstName, time() + 3600, "/");
                setcookie("LastName", $userLastName, time() + 3600, "/");
                setcookie("customerID" , $useraid,  time() + 3600, "/");

                header("location: adminPage.php");

            }
            else 
            {
                header("location: login.html");
            }

        }
        else
        {

        
                header("location: login.html");
        
        }

?> 
