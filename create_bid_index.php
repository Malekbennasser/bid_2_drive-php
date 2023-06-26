<?php

namespace View;

session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login_index.php');
    die();
}

require_once __DIR__ . "/Classes/CreateBid.php";

use Classes\CreateBid;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>create_bid</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bodyy">
    <?php require_once __DIR__ . "/Menu/nav.php"; ?>
    <?php CreateBid::show_car($_GET["id"]); ?>
    <div class="w-25">
        <form action="create_bid_index.php?id=<?php echo $_GET["id"]; ?>" method="POST" class="row g-1 mt-2">
            <div class="col">
                <!-- Bid input -->
                <div class="form-outline ">
                    <input type="number" id="bid" class="form-control" name="bid" min="1" required />
                    <label class="form-label" for="bid">Bid</label>
                </div>
            </div>
            <div class="col">
                <!-- Bid button -->
                <div class="form-outline">
                    <input class="btn btn-primary" type="submit" value="BID">
                </div>
            </div>
        </form>
        <div>




        </div>

    </div>






    <?php // if send a bid we display this
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $newBid = new CreateBid($_POST["bid"]);
        // creating bid and displaying it in same time
        $newBid->create_bid($_GET["id"], $_POST["bid"], $_SESSION["userid"]);
        // static page
    } else {
        CreateBid::show_bids($_GET["id"]);

        CreateBid::check_status($_GET["id"]);
    } ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>