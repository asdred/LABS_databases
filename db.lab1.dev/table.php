<!DOCTYPE html!>
<html>
    <head>
        <meta charset="utf-8">
        <title>БД. Лабораторная 1.</title>
        <link type="text/css" rel="stylesheet" href="css/table.css"/>
        <link type="text/css" rel="stylesheet" href="css/index.css"/>
    </head>
    <body> 
    <nav id="menu">
        <a href="http://db.lab1.dev/">
            <button class="submit">На главную</button>
        </a>
        <a href="http://db.lab1.dev/search.php?t=<?php echo $_GET['t'] ?>">
            <button class="submit">Поиск</button>
        </a>
    </nav>
    <table class="simple-little-table">
        <thead>
            <tr>
                
    <?php
                
    // ПОДКЛЮЧЕНИЕ ЧЕРЕЗ PDO, позволяет избежать SQL-Инъекции
    //Данные доступа
                
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
    
    // Счётчик строк для постраничного вывода
    $row_count = 0;
        
    $table = $_GET['t'];
    $page = $_GET['p']; 
        
    ?>
        
    <script>
        function count(obj) {
            location.assign("http://db.lab1.dev/form_update.php?t=<?php echo $table ?>&i=" + obj.cells[0].innerText);
        }
    </script>

    <?php
    
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
    
    // Просчёт кол-ва страниц
    $rows_count_query = $dbh->query("SELECT Count({$id_name}) FROM {$table};");
    $rows_count_query->setFetchMode(PDO::FETCH_ASSOC);
    $rows_count = $rows_count_query->fetch()['count'];
    $page_count = $rows_count / 10; 

    $columns = $dbh->query("SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}'");
    $columns->setFetchMode(PDO::FETCH_ASSOC);
    
    $columns_array = array();

    while($col = $columns->fetch()) {
        //if ($col['column_name'] == 'deleted') continue;
        array_push($columns_array, $col['column_name']);
        
        echo '<th>' . $col['column_name'] . '</th>';
        /*
        if (strpos($col['column_name'], 'id') !== false or strpos($col['column_name'], 'deleted') !== false) {
                echo '<th hidden="true">' . $col['column_name'] . '</th>';
            } else {
                echo '<th>' . $col['column_name'] . '</th>';
            }
        */
    }
                            
    ?>
    
            </tr>
        </thead>
    <tbody>
        
    <?php

    // отладка
    //echo "SELECT " . implode(', ',$columns_array) . " FROM {$table}";
    
    $sth = $dbh->query("SELECT " . implode(', ',$columns_array) . " FROM {$table} ORDER BY {$id_name} LIMIT 10 OFFSET " . (($page * 10) - 10));
    $sth->setFetchMode(PDO::FETCH_ASSOC);  
  
    while($row = $sth->fetch()) {
        echo '<tr onclick="count(this)">';
        
        foreach ($columns_array as $column_name) {
            
            echo '<td>' . $row[$column_name] . '</td>';
            /*
            if (strpos($column_name, 'id') !== false or strpos($column_name, 'deleted') !== false) {
                echo '<td hidden="true">' . $row[$column_name] . '</td>';
            } else {
                echo '<td>' . $row[$column_name] . '</td>';
            }
            */
        }
        
        echo '</tr>';  
    }

    // Закрытие соединений
    $dbh = null;
        
    ?>

    </tbody>
    </table>
    
        <a href="http://db.lab1.dev/form_insert.php?t=<?php echo $table ?>">
            <button class="submit" type="submit" name="<?php echo $table ?>">Добавить</button><br>
        </a>
        <nav id="table-navigation">
        
    <?php
    
    if ($page > 1) {    
        echo '<a href="http://db.lab1.dev/table.php?t=' . $table . '&p=' . ($page - 1) . '">';
        echo '<button class=submit>Назад</button>';
        echo '</a>';
    }
    
    if ($page < $page_count) {
        echo '<a href="http://db.lab1.dev/table.php?t=' . $table . '&p=' . ($page + 1) . '">';
        echo '<button class=submit>Вперёд</button>';
        echo '</a>';
    }
       
    ?>
        
        </nav>    
    </body>
</html>