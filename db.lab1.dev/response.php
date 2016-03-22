<table class="simple-little-table">
    <thead>
        <tr>
<?php
    if (isset($_POST['search_string'])) {
        
        $table = $_POST['t'];
        $search_string = $_POST['search_string'];
        
        if ($table == "product") {
            if (((int) $search_string) == 0) {
                $query = "
                SELECT * 
                FROM product 
                WHERE 
                    name LIKE '%{$search_string}%' 
                OR 
                    type LIKE '%{$search_string}%'
                ;";
            } else {
                $query = "
                SELECT * 
                FROM product 
                WHERE 
                    name LIKE '%{$search_string}%' 
                OR 
                    type LIKE '%{$search_string}%' 
                UNION ALL 
                SELECT * 
                FROM product 
                WHERE 
                    code={$search_string} 
                OR 
                    weight={$search_string}
                ;";
            }
        } elseif ($table == "shipment") {
            if (((int) $search_string) == 0) {
                $query = "
                SELECT 
                    id, 
                    product.name AS code_product, 
                    model AS number_auto, 
                    store.name AS number_store, 
                    amount 
                FROM product, 
                    shipment, 
                    store, 
                    truck 
                WHERE 
                    product.code=shipment.code_product 
                AND 
                    store.number=shipment.number_store 
                AND 
                    truck.number=shipment.number_auto 
                AND 
                    (
                        product.name LIKE '%{$search_string}%' 
                    OR 
                        model LIKE '%{$search_string}%' 
                    OR 
                        store.name LIKE '%{$search_string}%'
                    )";
            } else {
                $query = "
                SELECT 
                    id, 
                    product.name AS code_product, 
                    model as number_auto, 
                    store.name as number_store, 
                    amount 
                FROM 
                    product, 
                    shipment, 
                    store, 
                    truck 
                WHERE 
                    product.code=shipment.code_product 
                AND 
                    store.number=shipment.number_store 
                AND 
                    truck.number=shipment.number_auto 
                AND 
                    (
                        amount={$search_string} 
                    OR 
                        id={$search_string} 
                    OR 
                        product.name LIKE '%{$search_string}%' 
                    OR 
                        model LIKE '%{$search_string}%' 
                    OR 
                        store.name LIKE '%{$search_string}%'
                    )";
            }
        } elseif ($table == "store") {
            if (((int) $search_string) == 0) {
                $query = "
                SELECT * 
                FROM store 
                WHERE 
                    name LIKE '%{$search_string}%' 
                OR 
                    owner LIKE '%{$search_string}%'
                ;";
            } else {
                $query = "
                SELECT * 
                FROM store 
                WHERE 
                    name LIKE '%{$search_string}%' 
                OR 
                    owner LIKE '%{$search_string}%' 
                UNION ALL 
                SELECT * 
                FROM store 
                WHERE 
                    number={$search_string}
                ;";
            }
        } elseif ($table == "truck") {
            if (((int) $search_string) == 0) {
                $query = "
                SELECT * 
                FROM truck 
                WHERE 
                    model LIKE '%{$search_string}%' 
                OR 
                    owner LIKE '%{$search_string}%'
                ;";
            } else {
                $query = "SELECT * 
                FROM truck 
                WHERE 
                    model LIKE '%{$search_string}%' 
                OR 
                    owner LIKE '%{$search_string}%' 
                UNION ALL 
                SELECT * 
                FROM truck 
                WHERE 
                    number={$search_string} 
                OR 
                    capacity={$search_string}
                ;";
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