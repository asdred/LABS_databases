<?php

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

        $table = $_POST['t'];
        
        // разные названия у первичных ключей
        if ($table == "product") {
            $id_name = "code";
        } elseif ($table == "shipment") {
            $id_name = "id";
        } elseif ($table == "store") {
            $id_name = "number";
        } elseif ($table == "truck") {
            $id_name = "number";
        }
            
        $columns = $dbh->query("SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}'");
        $columns->setFetchMode(PDO::FETCH_ASSOC);
    
        $columns_array = array();
        $values_array = array();
        $placeholders = array();

        while($col = $columns->fetch()) {
            
            if ($col['column_name'] == $id_name or $col['column_name'] == 'deleted') continue;
            
            array_push($columns_array, $col['column_name']);
            array_push($values_array, $_POST[ $col['column_name'] ]);
            array_push($placeholders, '?');
        }

        $query = "INSERT INTO {$table} (" . implode(', ',$columns_array) . ") VALUES (" . implode(', ',$placeholders) . ");";
        
        $sth = $dbh->prepare($query);  
        $sth->execute($values_array);

        $dbh = null;

        header("Location: http://db.lab1.dev/table.php?t={$table}&p=1");
?>