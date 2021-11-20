<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["fname"]) && isset($_POST["lname"])) {
    require "./dbconfig.php";
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $fName = $_POST["fname"];
    $lName = $_POST["lname"];
    $phoneNumber = $_POST["phoneNumber"];

    $con = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
    if ($con->connect_error) {
        die("Connection failed" . $con->connect_error);
    }

    $stmt = $con->prepare("INSERT INTO Customer(fName, lName, email, phoneNumber) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $fName, $lName, $email, $phoneNumber);
    $stmt->execute();
    $stmt->close();

    $stmt = $con->prepare("SELECT cid FROM Customer WHERE email= ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo $row["cid"];
        $stmt = $con->prepare("INSERT INTO CustomerCredentials(cid, login, password) VALUES (?,?,?)");
        $stmt->bind_param("iss", $row["cid"], $username, $password);
        $stmt->execute();
    }

    header("Location: index.html");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="./images/Logo.ico" />
    <title>Login | Lost on The Rack</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="./public/css/style.css" />
</head>

<body class="min-vh-100 d-flex flex-column justify-content-between">

    <main class="container">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-12 col-md-10 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center">Sign Up</h2>
                        <form class="needs-validation mb-2" action="signup.php" method="POST" novalidate>
                            <div class="mb-3">

                                <label for="fname" class="form-label">First name</label>
                                <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" autofocus required />
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    First name must not be blank!
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="lname" class="form-label">Last name</label>
                                <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" autofocus required />
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    Last name must not be blank!
                                </div>
                            </div>
                            <div class="mb-3">

                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter Phone Number" autofocus required />
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    Phone Number must not be blank!
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" autofocus required />
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    Username must not be blank!
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required />
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    Email must not be left blank
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    Password must not be blank!
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-success" type="submit">
                                    Sign Up!
                                </button>
                            </div>
                        </form>
                        <span>
                            Already have an account?
                            <a class="card-link text-decoration-none" href="login.php">Login!</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>