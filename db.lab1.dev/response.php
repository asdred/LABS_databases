<table class="simple-little-table">
    <thead>
        <tr>
<?php
    if (isset($_POST['search_string'])) {
        
        $table = $_POST['t'];
        $search_string = $_POST['search_string'];
        
        if ($table == "product") {
            if (((int) $search_string) == 0) {
                $query = "SELECT * FROM product WHERE name LIKE '%{$search_string}%' or type LIKE '%{$search_string}%';";
            } else {
                $query = "SELECT * FROM product WHERE name LIKE '%{$search_string}%' or type LIKE '%{$search_string}%' UNION ALL SELECT * FROM product WHERE code={$search_string} or weight={$search_string};";
            }
        } elseif ($table == "shipment") {
            if (((int) $search_string) == 0) {
                $query = "select id, product.name as code_product, model as number_auto, store.name as number_store, amount from product, shipment, store, truck 
where product.code=shipment.code_product and store.number=shipment.number_store and truck.number=shipment.number_auto and (product.name LIKE '%{$search_string}%' or model LIKE '%{$search_string}%' or store.name LIKE '%{$search_string}%')";
            } else {
                $query = "select id, product.name as code_product, model as number_auto, store.name as number_store, amount from product, shipment, store, truck 
where product.code=shipment.code_product and store.number=shipment.number_store and truck.number=shipment.number_auto and (amount={$search_string} or id={$search_string} or product.name LIKE '%{$search_string}%' or model LIKE '%{$search_string}%' or store.name LIKE '%{$search_string}%')";
            }
        } elseif ($table == "store") {
            if (((int) $search_string) == 0) {
                $query = "SELECT * FROM store WHERE name LIKE '%{$search_string}%' or owner LIKE '%{$search_string}%';";
            } else {
                $query = "SELECT * FROM store WHERE name LIKE '%{$search_string}%' or owner LIKE '%{$search_string}%' UNION ALL SELECT * FROM product WHERE number={$search_string};";
            }
        } elseif ($table == "truck") {
            if (((int) $search_string) == 0) {
                $query = "SELECT * FROM truck WHERE model LIKE '%{$search_string}%' or owner LIKE '%{$search_string}%';";
            } else {
                $query = "SELECT * FROM truck WHERE model LIKE '%{$search_string}%' or owner LIKE '%{$search_string}%' UNION ALL SELECT * FROM product WHERE number={$search_string} or capacity={$search_string};";
            }
        }
    
        //echo $query;
    
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

    $columns = $dbh->query("SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}'");
    $columns->setFetchMode(PDO::FETCH_ASSOC);
    
    $columns_array = array();

    while($col = $columns->fetch()) {
        array_push($columns_array, $col['column_name']);
        
        echo '<th>' . $col['column_name'] . '</th>';
    }
                            
?>
                
        </tr>
    </thead>
    <tbody>
    
<?php
    
        $sth = $dbh->query($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);  

        while($row = $sth->fetch()) {
            echo '<tr>';

            foreach ($columns_array as $column_name) {
                echo '<td>' . $row[$column_name] . '</td>';
            }

            echo '</tr>';  
        }

        $dbh = null;
    }
?>
    
    </tbody>
</table>