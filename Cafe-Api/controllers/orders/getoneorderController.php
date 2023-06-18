<?php

require('../../handle.php');


$database = new Database();

$imageDir = "/cafe_project/controllers/product/uploaded_img/";

$order_id = $_GET["id"];


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $result = $database->getrow('', "select OP.price, OP.quantity, O.date , O.status , O.userID ,OP.product_id
    
    FROM orders O

    INNER JOIN order_product OP

    ON O.orderID = OP.order_id

    where O.orderID=?
    ", [$order_id]);


    $rowNum =  $result->rowCount();
    $result =  $result->fetchALL(PDO::FETCH_ASSOC);

    if ($rowNum > 0) {

        $order_arr = [
            'order_date' => $result[0]['date'],
            'order_status' => $result[0]['status'],
        ];

        $order_arr['user'] = $database->getrow(
            'users',
            "SELECT name,email,room_number,ext,image FROM users WHERE id = ?",
            [
                $result[0]['userID'],
            ]
        )->fetchALL(PDO::FETCH_ASSOC)[0];

        $order_arr['price'] = 0;
        $order_arr['products'] = [];

        foreach ($result as $re) {
            // totall price 
            $order_arr['price'] += $re['price'] * $re['quantity'];
            // get products data  
            $product = $database->getrow(
                'products',
                "SELECT * FROM products WHERE product_id = ?",
                [
                    $re['product_id'],
                ]
            )->fetchALL(PDO::FETCH_ASSOC)[0];

            $product['price'] = $re['price'];
            $product['quantity'] = $re['quantity'];
            $product['image'] = isset($_SERVER['HTTPS']) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] . $imageDir . $product['image'];

            $order_arr['products'][] = $product;
        }

        echo json_encode($order_arr);
    } else {
        echo json_encode(["message" => "invaild id"]);
    }
} else {
    echo json_encode(["message" => "Invalid Method"]);
}
