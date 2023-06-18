<?php
require('../../handle.php');
header('Access-Control-Allow-Methods: GET');

$db = new Database();



if ($_SERVER["REQUEST_METHOD"] === 'GET'){
        $query = "SELECT usr.name user_name, ord.date, ord_prod.quantity, prod.name, prod.price 
        FROM php_project.users as usr inner join orders as ord
        on usr.id = ord.userID
        inner join php_project.order_product as ord_prod
        on ord_prod.order_id = ord.orderID
        inner join php_project.products as prod 
        on prod.product_id = ord_prod.product_id
        ;";
        $queryTotal = "select date, sum(price*quantity) total
        from php_project.orders 
        inner join order_product 
        on orders.orderID = order_product.order_id
        group by orders.date;";
        $rows = ($db->getrows('', $query))->fetchAll(PDO::FETCH_ASSOC);
        $rows2 = ($db->getrows('', $queryTotal))->fetchAll(PDO::FETCH_ASSOC);

        for($i =0; $i< count($rows); $i++){
           for($j=0; $j< count($rows2); $j++){
            if($rows2[$j]["date"]==$rows[$i]["date"] ){
                $rows[$i]["total"]=$rows2[$j]["total"]; 
            }
           }
        }
     

        //var_dump($rows);

        //var_dump($rows2);

        $json = json_encode($rows);
       echo $json;
    
}

else{
    echo json_encode("wrong http method");
}