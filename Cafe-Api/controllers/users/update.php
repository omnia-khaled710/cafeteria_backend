<?php
header('Access-Control-Allow-Methods: POST');
require('../../handle.php');


$db = new Database();

function getExtensionOfImage($id){
    $db = new Database();
    $rows = $db->getrow('', "select * from users where id = $id;")->fetchAll(PDO::FETCH_ASSOC);
    $image = $rows[0]['image'];
    $imgNameAtExtention = explode('_',$image)[1];
    $extention = end(explode('.', $imgNameAtExtention));
    return '.' . $extention;
}

function cleanData($data){
    return htmlspecialchars(strip_tags($data));
}

function renameImage($oldImage, $newImage){
    try{
        rename('./images/' . $oldImage, './images/' . $newImage);
    }
    catch(Exception){
        json_encode('image not found to be renamed');
    }
}



////////////////////////neeeds validation and password decription!!!!!!!!!!!!!!!!
//var_dump($_SERVER['REQUEST_METHOD']);
//var_dump();
if ($_SERVER["REQUEST_METHOD"] === 'POST'){
$emailFlag = true;
if ($_GET['id']) {
    $id = $_GET['id'];
    $data = json_decode(file_get_contents('php://input'));
    //////////////
    $rows = $db->getrow('', "select * from users where id = $id;")->fetchAll(PDO::FETCH_ASSOC);
    $name = $_POST['name']?:$rows[0]['name'];

    ///change image name if the user changed his email
    if($_POST['email']){
        $image = 'user_' . $_POST['email'] . getExtensionOfImage($id);
        $email = $_POST['email'];
        renameImage($rows[0]['image'], $image);
        ////this flage is to deny renaming the image again
        $emailFlag = false;
    }
    $password = password_hash(cleanData($_POST['password']), PASSWORD_DEFAULT)?:$rows[0]['password'];
    $room_number = $_POST['room_number']?:$rows[0]['room_number'];
    $ext = $_POST['ext']?:$rows[0]['ext'];
    //// createing new image if the user added image
    if($_FILES['image']){
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $extention = explode(".", $image);
        $extention = strtolower((end($extention)));
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
        move_uploaded_file($image_tmp, $image_folder);
        $image = $image_name;
    }
    /////if the user didn't change the email don not update image name
    else{
        if($emailFlag){
            $image = $rows[0]['image'];
        }
       
    }
  
    //var_dump(compact('name','email','password', 'room_number','ext', 'image'));

    //// Clean data
    // $name = htmlspecialchars(strip_tags($data->name));
    // $email = htmlspecialchars(strip_tags($data->email));
    // $password = htmlspecialchars(strip_tags($data->password));
    // $room_number = htmlspecialchars(strip_tags($data->room_number));
    // $ext = htmlspecialchars(strip_tags($data->ext));
    // $image = htmlspecialchars(strip_tags($data->image));


    $query1 = "update users
set name = '$name', email = '$email', password = '$password', room_number = '$room_number', ext = $ext, image = '$image'
where id = $id";


    $db->updateRow('', $query1);
    echo json_encode("updated");
} else {
    echo json_encode("please use id");
}
}
else{
    echo json_encode("wrong http method");
}