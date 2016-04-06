<meta charset="utf-8">
<form action="" method="post">
    <input name="number" type="number"></input>
    <button name="add" value="1">Добавить</button>
</form>
<p>
<?php
    function generateString(){
        $chars = 'абвгдеёжзиклмнопрстуфхцчшщыэюя';
        $string = '';
        for ($i = 0; $i < rand(3, 8); $i++) {
            $string .= mb_substr($chars, rand(0, 29), 1, "UTF-8");
        }
        return $string;
    }
    
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
        
        $types = array("песок", "жидкость", "стройматериал", "пиломатериал", "металлоконструкция");
        
        for($i = 0; $i < $_POST['number']; $i++) {
        
            $name_rand_val = generateString();
            $weight_rand_val = rand(500,2000);
            $type_rand_val = $types[rand(0,4)];
            
            //echo $name_rand_val." ";
            //echo $weight_rand_val." ";
            //echo $type_rand_val." "."<br>";
            
            $query = "INSERT INTO product (name, weight, type) VALUES ('{$name_rand_val}', {$weight_rand_val}, '{$type_rand_val}');";
            
            $sth = $dbh->prepare($query);  
            $sth->execute();
        }

        $dbh = null;
    }
?>
</p>