<?php
require('../../handle.php');

header('Access-Control-Allow-Methods: POST');


$db = new Database();





if ($_SERVER["REQUEST_METHOD"] === 'POST'){

$data = json_decode(file_get_contents('php://input'));

// Clean data
// $name = htmlspecialchars(strip_tags($data->name));
// $email = htmlspecialchars(strip_tags($data->email));
// $password = htmlspecialchars(strip_tags($data->password));
// $password = password_hash($password, PASSWORD_BCRYPT);
// $room_number = htmlspecialchars(strip_tags($data->room_number));
// $ext = htmlspecialchars(strip_tags($data->ext));
// $image = htmlspecialchars(strip_tags($data->image));

// $password = password_hash($password, PASSWORD_DEFAULT);

// $p_name =  $_POST['name'];
// $p_price = $_POST['price'];
// $p_quantity = $_POST['quantity'];
// $p_description = $_POST['description'];
// $p_image = $_FILES['image']['name'];
// $p_image_tmp_name = $_FILES['image']['tmp_name'];

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
$room_number = $_POST['room_number'];
$ext = $_POST['ext'];
$image = $_FILES['image']['name'];
$image_tmp = $_FILES['image']['tmp_name'];



$extention = explode(".", $image);
$extention = strtolower((end($extention)));

$image_name = "user_" . $email . '.' . $extention;

$image_folder = "./images/" . $image_name;

$imgAllowedExtention = ["png", "jpg", "jpeg"];

if (!(in_array($extention, $imgAllowedExtention))) {
    echo json_encode([
      'message' => "this in not allowed extention"
    ]);
    exit();
  }

  //var_dump('http' . '://' . $_SERVER['HTTP_HOST'].$path_relative. $image_folder);
  move_uploaded_file($image_tmp, $image_folder);
  $image = $image_name;

$query = "insert into users
(name,email, password, room_number, ext, image)       
 values
(\"$name\", \"$email\", \"$password\", \"$room_number\", $ext, \"$image\");";

if($db->insertRow('', $query)){
    echo json_encode("done") ;
} 
else{
   echo json_encode("error");
} 

}
else{
    echo json_encode("wrong http method");
}