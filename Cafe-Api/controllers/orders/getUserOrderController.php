<?php

require('../../handle.php');
require('../../cors.php');



$database = new Database();

$imageDir = "/cafe_project/controllers/product/uploaded_img/";
$user_id = $_GET["id"];
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

if ($uri && $_SERVER['REQUEST_METHOD'] == 'GET') {
    // get all order 
    $stmts = $database->getrow('', "select * FROM orders where userID=?", [$user_id])->fetchAll(PDO::FETCH_ASSOC);

    $orders = [];

    foreach ($stmts as $order) {

        $products = $database->getrows('order_product', "SELECT product_id,price,quantity FROM order_product
        where order_id = " . $order['orderID'])->fetchAll(PDO::FETCH_ASSOC);

        $order['products'] = [];
        $order['price'] = 0;

        foreach ($products as $product) {
            // get product data

            $db_product = $database->getrows('', "select * from products where product_id=" . $product['product_id'])
                ->fetchAll(PDO::FETCH_ASSOC)[0];


            $db_product['price'] = $product['price'];
            $db_product['quantity'] = $product['quantity'];
            $db_product['image'] = isset($_SERVER['HTTPS']) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] . $imageDir . $db_product['image'];

            // get totall order price
            $order['price'] += $product['price'] *  $product['quantity'];
            $order['products'][] = $db_product;
        }
        // get user data
        $order['user'] = $database->getrow(
            'users',
            "SELECT name,email,room_number,ext,image FROM users WHERE id = " .
                $order['userID']
        )->fetchALL(PDO::FETCH_ASSOC)[0];
        $orders[] = $order;
    }

    echo json_encode(["data" => $orders]);
} else {

    echo json_encode(["message" => "failed to get all orders"]);
}
