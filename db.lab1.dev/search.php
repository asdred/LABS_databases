<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>БД. Лабораторная 1. Поиск</title>
        <link type="text/css" rel="stylesheet" href="css/table.css"/>
        <link type="text/css" rel="stylesheet" href="css/index.css"/>
        <link type="text/css" rel="stylesheet" href="css/search.css"/>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    </head>
    <body>
        <nav id="menu">
            <a href="http://db.lab1.dev/">
                <button class="submit">На главную</button>
            </a>
        </nav>
        <div id="container-search">
            <input type="text" oninput="search()"></input>
            <button onclick="run()">Найти</button>
        </div>
        <div class="results">
            <p>Ждём ответа</p>
        </div>
        <script>
            function search() {
                var search_string = document.getElementsByTagName('input')[0].value;
                var table = <?php echo "'" . $_GET['t'] . "'" ?>;

                $.ajax({
                    type: 'POST',
                    url: 'response.php',
                    data: 't=' + table + '&search_string=' + search_string,
                    success: function(data) {
                        $('.results').html(data);
                    }
                });
            }
        </script>
    </body>
</html>