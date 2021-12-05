<?php
if (isset($_COOKIE["userFirstName"]) && isset($_COOKIE["userLastName"]) && isset($_COOKIE["userCustomerID"])) {
    header("Location: landing.php");
    die();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])  && isset($_POST["password"])) {
    include "dbconfig.php";  // hold db credentials

    // connection to the database
    $con = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname)
        or die("<br> Cannot connect to DB: $dbname on $host " . mysqli_connect_error());

    // default time zone set to NY
    date_default_timezone_set('America/New_York');

    // get username from user 
    if (isset($_POST["username"]) and isset($_POST["password"])) {
        // get the login from the user
        $login = mysqli_real_escape_string($con, $_POST["username"]);

        // get the password form user 
        $password = mysqli_real_escape_string($con, $_POST["password"]);
    }

    // sql statement to check login with the customer table
    $credentialSQL = "select * FROM 2021F_patpanka.CustomerCredentials cc, 2021F_patpanka.Customer c where cc.cid = c.cid and login = '$login'";

    // connections to the database
    $credentialResult = mysqli_query($con, $credentialSQL);
    $credentialNum = mysqli_num_rows($credentialResult);
    $credentialRow = mysqli_fetch_array($credentialResult);

    // sql statement to check the login with the admin table 
    $adminCredentialSQL = "select * FROM 2021F_patpanka.AdminCredentials ac, 2021F_patpanka.Admin a where ac.aid = a.aid and login = '$login'";
    // connections to the database
    $adminCredentialResult = mysqli_query($con, $adminCredentialSQL);
    $adminCredentialNum = mysqli_num_rows($adminCredentialResult);
    $adminCredentialRow = mysqli_fetch_array($adminCredentialResult);

    // check to see if the query results are coming from the admin table or user table 
    // based on the output the user is directed to the proper page admin or user 
    if ($credentialNum > 0) {
        // validating password for users
        if ($credentialRow["password"] == $password) {

            // set cookies on sucessfull login
            $userFirstName = $credentialRow["fName"];
            $userLastName = $credentialRow["lName"];
            $userCid = $credentialRow["cid"];


            setcookie("userFirstName", $userFirstName, time() + 3600, "/");
            setcookie("userLastName", $userLastName, time() + 3600, "/");
            setcookie("userCustomerID", $userCid,  time() + 3600, "/");

            header("location:store.php");
        } else {

            header("location: login.html");
        }
    } else if ($adminCredentialNum > 0) {
        // validating password for admin
        if ($adminCredentialRow["password"] == $password) {

            // set cookies on sucessfull loginß
            $adminFirstName = $adminCredentialRow["fname"];
            $adminLastName = $adminCredentialRow["lName"];
            $adminAid = $adminCredentialRow["aid"];


            setcookie("adminFirstName", $adminFirstName, time() + 3600, "/");
            setcookie("adminLastName", $adminLastName, time() + 3600, "/");
            setcookie("adminID", $adminAid,  time() + 3600, "/");

            header("location: admin/adminPage.php");
        } else {
            header("location: login.html");
        }
    } else {


        header("location: login.html");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="./images/Logo.ico" />
    <title>Login - Lost on The Rack</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="./public/css/style.css" />
    <link rel="stylesheet" href="./public/css/form.css" />
</head>

<body class="d-flex flex-column justify-content-between">
    <main class="container-fluid">
        <div class="row justify-content-center justify-content-lg-between">
            <section class="
                        auth-content
                        offset-lg-1 offset-xl-1
                        col col-sm-12 col-md-8 col-lg-5 col-xl-5
                    ">
                <div class="img-container w-25 mx-auto">
                    <a href="landing.php">
                        <img class="img-fluid" src="./public/images/Logo.ico" alt="Lost On The Rack Logo" />
                    </a>
                </div>
                <h1 class="text-center display-4 mt-2 mb-5">Log In</h1>
                <form class="auth-form needs-validation" action="login.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" autofocus required />
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">
                            Username must not be blank!
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">
                            Password must not be blank!
                        </div>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-success" type="submit">
                            Sign Up!
                        </button>
                    </div>
                    <span class="d-block mt-2">
                        Don't have an account?
                        <a class="auth-link text-decoration-none" href="signup.php">Sign Up!</a>
                    </span>
                </form>
            </section>
            <section class="
                        sidebar
                        min-vh-100
                        col-lg-5 col-xl-4
                        d-none d-lg-flex
                        justify-content-center
                    ">
                <a href="landing.php">
                    <img src="./public/images/rack_image.png" alt="Lost On The Rack Logo" class="img-fluid align-self-start" />
                </a>
            </section>
        </div>
    </main>
</body>

</html>