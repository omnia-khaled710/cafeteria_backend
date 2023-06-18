<?php
header('Access-Control-Allow-Methods: DELETE');
require('../../handle.php');



$database = new Database();

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$order_id = $_GET['id'];


if ("$uri.'?'.$order_id " && $_SERVER['REQUEST_METHOD'] == 'DELETE') {

  $deleteOrder = $database->deleteRow('order', "delete FROM orders where orderID=? ", [$order_id]);

  echo json_encode(['message' => "deleted successfully"]);
} else {
  echo json_encode(['message' => "invaild id"]);
}
