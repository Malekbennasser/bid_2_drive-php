<?php

namespace Classes;



include_once __DIR__ . "/../DB/Database.php";

use Db\Database;


class CreateAuction
{
    protected $make;
    protected $model;
    protected $power;
    protected $year;
    protected $description;
    protected $price;
    protected $auction_start;
    protected $auction_end;
    protected $image;


    public function __construct($make, $model, $power, $year, $description,  $price,  $auction_start, $auction_end, $image)
    {
        $this->make = $make;
        $this->model = $model;
        $this->power = $power;
        $this->year = $year;
        $this->description = $description;
        $this->price = $price;
        $this->auction_start = $auction_start;
        $this->auction_end = $auction_end;
        $this->image = $image;
    }


    public function create_auction($make, $model, $power, $year, $description, $seller_id, $price, $auction_start, $auction_end, $image)
    {
        $dbh = Database::createDBConnection();

        //insert in cars
        $query = $dbh->prepare("INSERT 
            INTO `cars` (`make`, `model`, `power`,`year`,`description`,`seller_id`,`price`, `auction_start` , `auction_end`,`image`) VALUES (?,?,?,?,?,?,?,?,?,?) 
            ");
        $image_data = file_get_contents($image);

        $query->execute([$make, $model, $power, $year, $description, $seller_id, $price, $auction_start, $auction_end, $image_data]);
    }
}
// var_export
