<?php


// Headers 
require('../../handle.php');
header('Access-Control-Allow-Mehods:GET');

$db = new Database();

$imageDir = "/cafe_project/controllers/product/uploaded_img/";

$result = $db->getrows('products', "select * from products");


$rowNum = $result->rowCount();

if ($rowNum > 0) {
    // product array 
    $product_arr = [];
    $product_arr['data'] = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $product_item = [
            'p_id' => $product_id,
            'p_name' => $name,
            'p_quantity' => $quantity,
            'price' => $price,
            'p_description' => $description,
            'p_image' => isset($_SERVER['HTTPS']) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] . $imageDir . $image
        ];

        array_push($product_arr['data'], $product_item);
    }
    // echo json_encode(["data" =>$product_arr]);
    echo json_encode($product_arr);
} else {
    echo json_encode(
        [
            'message' => 'no products found'
        ]
    );
}
