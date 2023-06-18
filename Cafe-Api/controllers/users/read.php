<?php
require('../../handle.php');
header('Access-Control-Allow-Methods: GET');

$db = new Database();
//change this link according to your path
$path_link = "http://localhost/cafe_project/controllers/users/images/";


if ($_SERVER["REQUEST_METHOD"] === 'GET') {

    if ($_GET['id']) {
        $id = $_GET['id'];
        $rows = $db->getrow('', "select * from users where id = $id;")->fetchAll(PDO::FETCH_ASSOC);
        $json = json_encode($rows);

        echo $json;
    } else {

        $rows = ($db->getrows('', 'select * from users;'))->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $row['image'] = $path_link . $row['image'];
        }

        $json = json_encode($rows);
        echo $json;
    }
} else {
    echo json_encode("wrong http method");
}
