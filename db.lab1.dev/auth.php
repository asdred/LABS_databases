<?php
/*
    if(isset($_POST['login']) && isset($_POST['password'])) {
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
        
        $login = $_POST['login'];
        $pswd = $_POST['password'];
        $user = $dbh->prepare("select id, usergroup from db_user where login=? and password=?;")->execute(array($login,$pswd));
        //echo "select id, usergroup from db_user where login='{$login}' and password='{$pswd}';";
        $user->setFetchMode(PDO::FETCH_ASSOC);
            
        session_start();
        $_SESSION['userid'] = $user['id'];
        $_SESSION['usergroup'] = $user['usergroup'];
            
        echo $_SESSION['userid'] . " " . $_SESSION['usergroup'];
        
    }*/
    
    if(isset($_POST['login']) && isset($_POST['password'])) {
        
        $login = $_POST['login'];
        $pswd = $_POST['password'];
        
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

        //$user = $dbh->query("select id, usergroup from db_user where login='{$login}' and password='{$pswd}';");
        $user = $dbh->prepare("select login, usergroup from db_user where login=? and password=?;");
        $user->bindParam(1, $login);
        $user->bindParam(2, $pswd);
        $user->execute();
        $user->setFetchMode(PDO::FETCH_ASSOC);
        $user = $user->fetch();
        
        session_start();
        $_SESSION['userlogin'] = $user['login'];
        $_SESSION['usergroup'] = $user['usergroup'];
            
        header("Location: http://db.lab1.dev/");
        }

?>