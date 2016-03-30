<meta charset="utf-8">
<form action="" method="post">
    <input name="number" type="number"></input>
    <button name="add" value="1">Добавить</button>
</form>
<p>
<?php
    if($_POST['number'] != "" && $_POST['add'] == "1") {
        
        try {
            $user = 'postgres';  
            $pass = 'admin';  
            $host = 'localhost';  
            $db='databases';  
            $dbh = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
        } catch (PDOException $e) {  
            echo "Error!: " . $e->getMessage() . "<br/>";  
            die();  
        }
        
        for($i = 0; $i < $_POST['number']; $i++) {
        
            $code_product_rand_val = rand(1,10);
            $number_auto_rand_val = rand(1,10);
            $number_store_rand_val = rand(1,10);
            $amount_rand_val = rand(1,20);
            
            //echo $code_product_rand_val." ";
            //echo $number_auto_rand_val." ";
            //echo $number_store_rand_val." ";
            //echo $amount_rand_val." "."<br>";
            
            $query = "INSERT INTO shipment (code_product, number_auto, number_store, amount) VALUES ({$code_product_rand_val}, {$number_auto_rand_val}, {$number_store_rand_val}, {$amount_rand_val});";
            //echo ($i+1) . " запись добавлена<br>";
        
            $sth = $dbh->prepare($query);  
            $sth->execute();
        }

        $dbh = null;
    }
?>
</p>