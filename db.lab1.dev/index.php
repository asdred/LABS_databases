<!DOCTYPE html!>
<html>
    <head>
        <meta charset="utf-8">
        <title>БД. Лабораторная 1.</title>
        <link type="text/css" rel="stylesheet" href="css/index.css"/>
    </head>
    <body>
        <center>
        <nav id="main_menu">
        <h3>Таблицы:</h3>
            
        <?php
        
        session_start();
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

            $tables = $dbh->query("select relname from pg_stat_user_tables order by relname;");
            $tables->setFetchMode(PDO::FETCH_ASSOC);

            while($t = $tables->fetch()) { 
                if ($t['relname'] == db_user) continue; ?>
                <a href="http://db.lab1.dev/table.php?t=<?php echo $t['relname'] ?>&p=1" >
                <button class="submit" id="index" type="submit" name="<?php echo $t['relname'] ?>"><?php echo $t['relname'] ?></button><br>
                </a>
            <?php    
            }

            $dbh = null;
        }
        
        ?>
            
        </nav>
        </center>    
    </body>
</html>