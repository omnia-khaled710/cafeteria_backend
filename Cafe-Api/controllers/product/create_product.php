<?php

// Headers 
require('../../handle.php');
header('Access-Control-Allow-Mehods:POST');

$db = new Database();


if ($_SERVER["REQUEST_METHOD"] === 'POST') {

  $p_name =  $_POST['name'];
  $p_price = $_POST['price'];
  $p_quantity = $_POST['quantity'];
  $p_description = $_POST['description'];
  $p_image = $_FILES['image']['name'];
  $p_image_tmp_name = $_FILES['image']['tmp_name'];


  $p_categories = $_POST['categories'];

  $extention = explode(".", $p_image);
  $extention = strtolower((end($extention)));

  $image_name = "product_" . time() . '.' . $extention;


  $p_image_folder = "./uploaded_img/" . $image_name;

  $imgAllowedExtention = ["png", "jpg", "jpeg"];



  if (!((isset($p_name)) && !empty($p_name))) {
    echo json_encode(
      [
        'message' => 'product name is required'
      ]
    );
    exit();
  }
  if (!preg_match("/^[a-zA-Z ]*$/", $p_name)) {
    echo json_encode(
      [
        'message' => "Only alphabets and white space are allowed"
      ]
    );
    exit();
  }
  if (!preg_match("/^\d{1,5}$|(?=^.{1,5}$)^\d+\.\d{0,2}$/", $p_price)) {
    echo json_encode(
      [
        'message' => "Only number or decimail number are allowed"
      ]
    );
    exit();
  }


  if (!(in_array($extention, $imgAllowedExtention))) {
    echo json_encode([
      'message' => "this in not allowed extention"
    ]);
    exit();
  }

  if (!preg_match("/^[0-9]*$/", $p_quantity)) {
    echo json_encode([
      'message' => "Only numeric value is allowed."
    ]);
    exit();
  }
}


$result = $db->insertRow('products', 'insert into products (name,price,quantity,description,image)
    
    values(?,?,?,?,?)', [$p_name, $p_price, $p_quantity, $p_description, $image_name]);

if ($result) {


  move_uploaded_file($p_image_tmp_name, $p_image_folder);

  $insert_row = $db->getrows('products', "select MAX(product_id) from products");



  $product_id = $insert_row->fetch(PDO::FETCH_COLUMN);




  foreach ($p_categories as $category)
    $db->insertRow('category_product', "insert into category_product (product_id,category_id)
         values(?,?)", [$product_id, $category]);

  echo json_encode([
    'message' => "created successful"
  ]);
};
