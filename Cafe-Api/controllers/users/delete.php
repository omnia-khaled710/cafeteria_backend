<?php
header('Access-Control-Allow-Methods: DELETE');
require('../../handle.php');


$db = new Database();

function deleteImage($id){
    $db = new Database();
    $rows = $db->getrow('', "select * from users where id = $id;")->fetchAll(PDO::FETCH_ASSOC);
    $image = $rows[0]['image'];
    
    try{
        unlink("images/$image");
    }
    catch(Exception){
        json_encode("name of image not found");
        die();
    }
}

// unlink("images/user_nam1e1@email.com.jpg");


////////////////////////neeeds validation and password decription!!!!!!!!!!!!!!!!
//var_dump($_SERVER['REQUEST_METHOD']);
//var_dump();
if ($_SERVER["REQUEST_METHOD"] === 'DELETE'){

if ($_GET['id']) {
    $id = $_GET['id'];
    deleteImage($id);

    // $query = "insert into users
    // (name,email, password, room_number, ext, image)       
    //  values
    // (\"$name\", \"$email\", \"$password\", \"$room_number\", $ext, \"$image\");";

    $query1 = "delete from users where id = $id";

    $db->deleteRow('', $query1);
    echo json_encode('row deleted successfully');
} else {
    echo json_encode("please use id");
}
}
else{
    echo json_encode("wrong http method");
}