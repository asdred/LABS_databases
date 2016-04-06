<!DOCTYPE html!>
<html>
    <head>
        <meta charset="utf-8">
        <title>БД. Лабораторная 1.</title>
        <link type="text/css" rel="stylesheet" href="css/table.css"/>
        <link type="text/css" rel="stylesheet" href="css/index.css"/>
    </head>
    <body>
        
    <?php session_start(); ?>
        
    <nav id="menu">
        <div>
            Пользователь: <?php echo $_SESSION['userlogin'] . " | " ?>
            Группа: <?php echo $_SESSION['usergroup'] . " | " ?>
            <a href="http://db.lab1.dev/auth_form.php">Выйти</a>
        </div>
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
    if (!isset($_SESSION['userlogin'])) {
            header("Location: http://db.lab1.dev/auth_form.php");
    } else {
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

    if ($_SESSION['usergroup'] != "guest" && $_SESSION['usergroup'] != "user") {
    ?>
        
    <script>
        function count(obj) {
            location.assign("http://db.lab1.dev/form_update.php?t=<?php echo $table ?>&i=" + obj.cells[0].innerText);
        }
    </script>

    <?php
    }
    
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

        if ($table == 'shipment') {
            $columns_array = array("name", "model", "name", "amount");
            foreach ($columns_array as $column_name) {
                echo '<th>' . $column_name . '</th>';
            }
        } else {
            $columns = $dbh->query("SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}'");
            $columns->setFetchMode(PDO::FETCH_ASSOC);

            $columns_array = array();

            while($col = $columns->fetch()) {
                array_push($columns_array, $col['column_name']);
                echo '<th>' . $col['column_name'] . '</th>';
            }
        }
                            
    ?>
    
            </tr>
        </thead>
    <tbody>
        
    <?php

        // отладка
        if ($table == 'shipment') {
            $sth = $dbh->query("select product.name, model, store.name, amount from product, shipment, store, truck where product.code=shipment.code_product and store.number=shipment.number_store and truck.number=shipment.number_auto LIMIT 10 OFFSET " . (($page * 10) - 10));
        } else {
            $sth = $dbh->query("SELECT " . implode(', ',$columns_array) . " FROM {$table} ORDER BY {$id_name} LIMIT 10 OFFSET " . (($page * 10) - 10)); 
        }
        $sth->setFetchMode(PDO::FETCH_ASSOC);  

        while($row = $sth->fetch()) {
            echo '<tr onclick="count(this)">';

            foreach ($columns_array as $column_name) {
                echo '<td>' . $row[$column_name] . '</td>';
            }

            echo '</tr>';  
        }

        // Закрытие соединений
        $dbh = null;
        
        echo '</tbody>';
        echo '</table>';

    if ($_SESSION['usergroup'] != "guest") {    
    ?>

            <a href="http://db.lab1.dev/form_insert.php?t=<?php echo $table ?>">
                <button class="submit" type="submit" name="<?php echo $table ?>">Добавить</button><br>
            </a>

    <?php
    }
        echo '<nav id="table-navigation">';
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
    }
    ?>
        
        </nav>    
    </body>
</html>