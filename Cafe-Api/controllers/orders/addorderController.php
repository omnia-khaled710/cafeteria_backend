<?php

require('../../handle.php');



$database = new Database();


$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
if ($uri && $_SERVER['REQUEST_METHOD'] == 'POST') {


    $data = json_decode(file_get_contents("php://input"), TRUE);

    $userID = $data['userID'];
    $products = $data['products'];



    // insert in order table
    $addOrder = $database->insertRow('orders', "insert into orders (userId,date) values 
    (?,?)", [
        $userID,
        date("Y-m-d H:i:s"),

    ]);
    // get last order  
    $get_id = $database->getrows('orders', "select MAX(orderID) from orders");

    $order_id = $get_id->fetch(PDO::FETCH_COLUMN);
    // get one product 
    foreach ($products as $value) {
        // get product data
        $product = $database->getrow(
            'products',
            "SELECT * FROM products WHERE product_id = ?",
            [
                $value['id'],
            ]
        )->fetchALL(PDO::FETCH_ASSOC)[0];
        // insert in order_product table  
        $database->insertRow('order_product', "insert into order_product (order_id,product_id,price,quantity) values 
    (?,?,?,?)", [
            $order_id,
            $value['id'],
            $product['price'],
            $value['quantity']
        ]);

        // decrease product quantity after make order
        $database->updateRow(
            'products',
            'update products set quantity = ?-? where product_id =?',
            [$product['quantity'], $value['quantity'], $value['id']]
        );
    }
    echo json_encode(['message' => "Added order successfully"]);
} else {
    echo json_encode(['message' => "Error creating"]);
}
