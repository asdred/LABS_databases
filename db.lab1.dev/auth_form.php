<!DOCTYPE html>
<html>
    <head>
        <title>lab1</title>
        <meta charset="utf-8">
        <link type="text/css" rel="stylesheet" href="css/index.css"/>
        <link type="text/css" rel="stylesheet" href="css/form.css"/>
    </head>
    <body>
        <?php
            session_start();
            session_destroy();
        ?>
        <div id="form">
            <form class="insert_form" action="auth.php" method="post">
                <ul>
                    <li>
                        <label for="login">Логин</label>
                        <input name="login" placeholder="abcdef@ghik.mn" required="" type="text" maxlength="20">
                    </li>
                    <li>
                        <label for="password">Пароль</label>
                        <input name="password" required="" type="password" maxlength="20">
                    </li>
                    <li>
                        <button class="submit" type="submit" name="" value="">Войти</button>
                    </li>
                </ul>
            </form>
        </div>
    </body>
</html>