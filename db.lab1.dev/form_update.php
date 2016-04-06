<!DOCTYPE html>
<html>
    <head>
        <title>lab1</title>
        <meta charset="utf-8">
        <link type="text/css" rel="stylesheet" href="css/index.css"/>
        <link type="text/css" rel="stylesheet" href="css/form.css"/>
    </head>
    <body>
        <nav id="menu">
        <a href="http://db.lab1.dev/table.php?t=<?php echo $_GET['t'] ?>&p=1">
            <button class="submit">Назад</button>
        </a>
        </nav>
        <div id="form">
            <form class="insert_form" action="update.php" method="post">
                <ul>
                    
                <?php
                
                session_start();
                
                if (!isset($_SESSION['userlogin'])) {
                    header("Location: http://db.lab1.dev/auth_form.php");
                } elseif ($_SESSION['usergroup'] == "guest" || $_SESSION['usergroup'] == "user") {
                    header("HTTP/1.1 403 Unauthorized" );
                    echo "Отказано в доступе";
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

                    $table = $_GET['t'];
                    $id = $_GET['i'];

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

                    $columns = $dbh->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = '{$table}'");
                    $columns->setFetchMode(PDO::FETCH_ASSOC);

                    $columns_array = array();
                    $types_array = array();

                    while($col = $columns->fetch()) {
                    
                    // Начало не универсального кода
                    /*
                    if($col['column_name'] == 'dealer_id') { 
                        $dealers = $dbh->query("SELECT id, name FROM dealers WHERE deleted=false");
                        $dealers->setFetchMode(PDO::FETCH_ASSOC);
                    }
                    
                    if($col['column_name'] == 'id') {
                        echo '<li>';
                        echo '<input type="hidden" name="id" value="' . $id . '">';
                        echo '</li>';
                        continue;
                    } elseif ($col['column_name'] == 'deleted') continue;
                    else if ($col['column_name'] == 'dealer_id') {
                        
                        $selected_dealer_query = $dbh->query("SELECT dealer_id FROM cars WHERE id={$id}");
                        $selected_dealer_query->setFetchMode(PDO::FETCH_ASSOC);
                        $selected_dealer_id = $selected_dealer_query->fetch()['dealer_id'];
                        
                        echo '<li>';
                        echo '<label for="' . $col['column_name'] . '">' . $col['column_name'] . '</label>';
                        echo '<select name="dealer_id">';
                        while($dealer = $dealers->fetch()) {
                            if ($dealer['id'] == $selected_dealer_id) {
                                echo '<option selected value="' . $dealer['id'] . '">' . $dealer['name'] . '</option>'; 
                            } else {
                                echo '<option value="' . $dealer['id'] . '">' . $dealer['name'] . '</option>';
                            }
                        }
                        echo '</select>';
                        echo '</li>';
                        continue; 
                    }
                    */
                    // Конец не универсального кода
                    
                        array_push($columns_array, $col['column_name']);
                        array_push($types_array, $col['data_type']);

                        if($col['data_type'] == 'character varying') $input_type = 'text';
                        else if ($col['data_type'] == 'integer') $input_type = 'number';

                        echo '<li>';
                        echo '<label for="' . $col['column_name'] . '">' . $col['column_name'] . '</label>';
                        echo '<input id="' . $col['column_name'] . '" type="' . $input_type . '" name="' . $col['column_name'] . '" placeholder="' . $col['column_name']. '" required/>';
                        echo '<span class="form_hint">Proper format "' . $col['data_type'] . '"</span>';
                        echo '</li>';
                    }

                    $values = $dbh->query("SELECT " . implode(', ',$columns_array) . " FROM " . $table . " WHERE {$id_name}=" . $id);
                    $values->setFetchMode(PDO::FETCH_ASSOC);

                    $val = $values->fetch();
                    foreach($columns_array as $column_name) {
                        echo '<script>';
                        echo "document.getElementById('" . $column_name . "').value = '" . $val[ $column_name ] . "';";
                        echo '</script>';
                    }

                    $dbh = null;

                    // Не универсальный код

                    ?>
                    <li>
                        <button class="submit" type="submit" name="ut" value="<?php echo $table ?>">Изменить</button>
                    </li>
                    <?php if ($_SESSION['usergroup'] != "user") { ?> 
                    <li>
                        <button id="del" class="submit" type="submit" name="dt" value="<?php echo $table ?>">Удалить</button>
                    </li> 
                    <?php } 
                    
                    }
                    
                    ?>
                </ul>
            </form>
        </div>
    </body>
</html>