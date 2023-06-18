<?php

require('../../handle.php');

$database = new Database();

header('Access-Control-Allow-Methods: PATCH');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
if ($uri && $_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    $order_id = $_GET['id'];

    $status = $data->status;

    if (!((isset($status)) && !empty($status))) {
        echo json_encode(
            [
                'message' => 'status is required'
            ]
        );
    } else if ($status !== 'done' && $status !== 'delivered' && $status !== 'processing') {
        echo json_encode(
            [
                'message' => "Status must be done or delivered or processing"
            ]
        );
    } else {
        $updateOrder = $database->updateRow(
            'orders',
            "update orders set  status=? where orderID=?",
            [

                $status,
                $order_id
            ]
        );
        echo json_encode(["message" => "order Updated Successfully "]);
    }
} else {
    echo json_encode(["message" => "Error in update "]);
}
