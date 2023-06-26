<?php

namespace Classes;


include_once __DIR__ . "/../DB/Database.php";


use Db\Database;
use PDO;


class CreateBid
{
    protected $bid;

    public function __construct($bid)
    {
        $this->bid = $bid;
    }


    // create and display in the same time function
    public function create_bid($car_id, $bid, $bidder_id)
    {
        // creating conection with database
        $dbh = Database::createDBConnection();
        $query = $dbh->prepare("SELECT * FROM `cars` WHERE `id` = ? ");
        $query->execute([$car_id]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result[0]['auction_end'] < date('Y-m-d H:i:s')) {

            echo '    <h3 class="alert alert-danger mt-3">You cant bid anymore since the auction ended.</h3>';
        } else {
            // select * from bids_history where $car_id = GET['id']
            $query = $dbh->prepare("SELECT * FROM `bids_history` WHERE `car_id` = ? ");
            $query->execute([$car_id]);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            // if count(array) = 0 means no bids
            if (count($result) === 0) {
                // if no bids for  car id
                // select from cars
                $query = $dbh->prepare("SELECT * FROM `cars` WHERE `id` = ? ");
                $query->execute([$car_id]);
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                // insert in history highest bid = price + bid
                $final_price = $result[0]['price'] + $bid;
                $query = $dbh->prepare("INSERT INTO `bids_history`(`car_id`,`bid`,`bidder_id`,`final_price`) VALUES (?,?,?,?)");
                $query->execute([$car_id, $bid, $bidder_id, $final_price]);
            } else {

                //insert in history  $final_price = $bid + "last final price";
                $last_bid = end($result);
                $final_price = $last_bid['final_price'] + $bid;
                $query = $dbh->prepare("INSERT INTO `bids_history`(`car_id`,`bid`,`bidder_id`,`final_price`) VALUES (?,?,?,?)");
                $query->execute([$car_id, $bid, $bidder_id, $final_price]);
            }
            // show bids
            self::show_bids($car_id);
        }
    }

    public static function check_status($car_id)
    {
        $dbh = Database::createDBConnection();
        $query = $dbh->prepare("SELECT * FROM `cars` WHERE `id` = ? ");
        $query->execute([$car_id]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result[0]['auction_end'] < date('Y-m-d H:i:s')) {
            echo '

                <h3 class="alert alert-danger">This auction has ended.</h3>

        ';
        }
    }
    // display the car
    public static function show_car($car_id)
    {
        $dbh = Database::createDBConnection();
        $query = $dbh->prepare("SELECT * FROM `cars` WHERE `id` = ? ");
        $query->execute([$car_id]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $element) {
            //condition to check the auction end
            $id = $element['id'];
            $url = "create_bid_index.php?id=" . urlencode($id);
            $base64Image = base64_encode($element['image']);

?>


            <div class="projcard-container">
                <a href=<?php echo $url; ?>>
                    <div class="projcard projcard-blue">
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
                    </div>
                </a>
            <?php }
    }

    //display function history
    public static function show_bids($car_id)
    {
        $dbh = Database::createDBConnection();
        $query = $dbh->prepare("SELECT * FROM `bids_history` WHERE `car_id` = ? ");
        $query->execute([$car_id]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) === 0) { ?>
                <div class="card mt-3">
                    <div class="card-body">

                        <h3>This auction has no bids yet.</h3>

                    </div>
                </div>

            <?php } else {
            //join
            $query = $dbh->prepare("SELECT 
            users.username,
            bids_history.bid,
            bids_history.final_price     
               FROM bids_history  
               INNER JOIN  cars ON bids_history.car_id = cars.id AND bids_history.car_id = $car_id 
               INNER JOIN users ON bids_history.bidder_id = users.id              
                   ");


            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            ?>


                <div class="card mt-3">
                    <div class="card-body">
                        <?php
                        $last = end($result);
                        ?>
                        <h3>Last bid was made by <?php echo $last['username'] ?></h3>
                        <h5>Amount : <?php echo $last['bid'] . " " . "$" ?> </h5>
                        <h5>Final price : <?php echo $last['final_price'] . " " . "$" ?> </h5>
                    </div>

                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <?php
                        echo '<h3>All bids</h3>';
                        foreach ($result as $value) {
                        ?>
                            <h5><?php echo $value['username'] ?> bided <?php echo $value['bid'] . " " . "$" ?> </h5>


                    <?php
                        }
                    }   ?>
                    </div>

                </div>
        <?php   }
}
