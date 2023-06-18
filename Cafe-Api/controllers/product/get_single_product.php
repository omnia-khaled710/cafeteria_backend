<?php


require('../../handle.php');


$imageDir = "/cafe_project/admin/controllers/product/uploaded_img/";

$db = new Database();
if($_SERVER["REQUEST_METHOD"]=== 'GET'){


$data=json_decode(file_get_contents("php://input"),TRUE); 

$product_id=$_GET['id'];

$result = $db->getrow('products','select * from products where product_id = ?',[$product_id]);

   $rowNum = $result->rowCount();
if($rowNum > 0){
    $result =  $result->fetch(PDO::FETCH_ASSOC);
    $product_arr = [
        'p_id'=>$result['product_id'],
        'p_name'=>$result['name'],
        'p_quantity'=>$result['quantity'],
        'p_price'=>$result['price'],
        'p_description'=>$result['description'],
        'p_image'=>isset($_SERVER['HTTPS'])?'https':'http'.'://'.$_SERVER['HTTP_HOST'].$imageDir.$result['image']
    ];
      echo json_encode($product_arr);
}
}else{
    echo json_encode([
    'error'=>"wrong http method"
   ]);  
}

?>

