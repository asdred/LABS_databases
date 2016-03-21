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
        
        if (isset($_POST['ut'])) {
            
            $table = $_POST['ut'];
            
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

            $id = $_POST[$id_name];

            $columns = $dbh->query("SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}'");
            $columns->setFetchMode(PDO::FETCH_ASSOC);

            $set_columns_array = array();
            $values_array = array();

            while($col = $columns->fetch()) {

                if ($col['column_name'] == $id_name) continue;

                array_push($set_columns_array, $col['column_name'] . '=' . '?');

                if (isset($_POST[ $col['column_name'] ])) {
                    array_push($values_array, $_POST[ $col['column_name'] ]);
                } else {
                    array_push($values_array, 'false');
                }
            }

            $query = "UPDATE {$table} SET " . implode(', ',$set_columns_array) . " WHERE {$id_name}={$id};";
            
            $sth = $dbh->prepare($query);
            $sth->execute($values_array);
            
        } elseif (isset($_POST['dt'])) {
            
            $table = $_POST['dt'];

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

            $id = $_POST[$id_name];

            $query = "DELETE FROM {$table} WHERE {$id_name}={$id};";
            echo $query;
            
            $sth = $dbh->prepare($query);  
            $sth->execute($values_array);
        }

        $dbh = null;

        header("Location: http://db.lab1.dev/table.php?t={$table}&p=1");
?>