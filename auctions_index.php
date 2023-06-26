<?php

namespace View;

session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login_index.php');
    die();
}

include_once __DIR__ . "/DB/Database.php";

use Db\Database;
use PDO;

$dbh = Database::createDBConnection();
$query = $dbh->prepare("SELECT * FROM `cars` 
        ");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);

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
    <h1>Auctions</h1>
    <div class="projcard-container">
        <?php foreach ($result as $element) {
            //condition to check the auction end
            $id = $element['id'];
            $url = "create_bid_index.php?id=" . urlencode($id);
            $base64Image = base64_encode($element['image']);
        ?>


            <div class="projcard projcard-blue">
                <a href=<?php echo $url; ?>>

                    <div class="projcard-innerbox">
                        <?php echo '<img class="projcard-img" src="data:image/jpeg;base64,' . $base64Image . '" alt="Image" />'; ?>

                        <div class="projcard-textbox">


                            <div class="projcard-title"><?php echo $element['make']; ?>
                                <?php echo $element['model']; ?>
                            </div>
                            <div class="projcard-subtitle">
                                Power : <?php echo $element['power']; ?>
                                Year : <?php echo $element['year']; ?>


                            </div>
                            <div class="projcard-bar"> </div>
                            <div class="projcard-description">
                                
                                <?php echo $element['description']; ?></div>


                            <span id="<?php echo $element['id'] ?>">
                                <script>
                                    function updateTimer() {
                                        var targetDate = new Date("<?php echo  $element['auction_end']; ?>");
                                        var currentDate = new Date();
                                        var remainingTime = targetDate.getTime() - currentDate.getTime();

                                        var days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

                                        document.getElementById("<?php echo $element['id'] ?>").innerHTML = "End in: " + days + " days, " + hours + " H, " + minutes + " M, " + seconds + " S ";
                                        if (remainingTime <= 0) {
                                            clearInterval();
                                            document.getElementById("<?php echo $element['id'] ?>").innerHTML = "Auction expired!";
                                        }
                                    };


                                    setInterval(updateTimer, 1000);
                                </script>
                            </span>
                            <div class="projcard-tagbox">
                                <span class="projcard-tag">Price : <?php echo $element['price'] . " " . "$"; ?></span>
                                <span class="projcard-tag">Auction_start : <?php echo $element['auction_start']; ?></span>
                                <span class="projcard-tag">Auction_end : <?php echo $element['auction_end']; ?></span>

                            </div>
                        </div>
                    </div>
                </a>
            </div>

        <?php } ?>

    </div>







    <!-- Pills content -->
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>




</body>

</html>