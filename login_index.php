<?php

namespace View;

require_once __DIR__ . "/Classes/Login.php";

use Classes\Login;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid2DRive</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
</head>


<body class="bodyy">
    <?php
    require_once __DIR__ . "/Menu/nav.php"
    ?>
    <div class="container">
        <h1 class="mb-3">Login</h1>
        <?php if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $newLogin = new Login(
                $_POST["username"],
                $_POST["password"],
            );
            $newLogin->login(
                $_POST["username"]
            );
        } ?>
        <form action="login_index.php" method="POST">

            <!-- Email input -->
            <div class="form-outline mb-4">
                <input type="text" id="form3Example2" class="form-control" name="username" required />
                <label class="form-label" for="form3Example2">Username</label>
            </div>
            <!-- Password input -->
            <div class="form-outline mb-4">
                <input type="password" id="form3Example4" class="form-control" name="password" required />
                <label class="form-label" for="form3Example4">Password</label>
            </div>


            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">LOGIN</button>

        </form>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>