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
            <p>выберите что-нибудь здесь:</p>
            <select style="width: 80%" onchange="select()">
                <option>Наименование самого тяжелого товара</option>
                <option>Названия складов, в которые перевозят товары все автомобили</option>
                <option>Названия складов и марки автомобилей, у которых владелец один и тот же</option>
                <option>Наименования товаров, которые не перевозит указанный автомобиль</option>
                <option>Названия складов, в которые перевозятся все товары</option>
                <option>Названия автомобилей, которые перевозят товары в те же склады, что и указанный автомобиль</option>
            </select>
            <p>или введите что-нибудь в поле ниже</p>
            <input type="text" oninput="search()"></input>
        </div>
        <div class="results">
            <p>Введите что-нибудь в поле выше</p>
        </div>
        <script>
            function select() {
                alert("adas");   
            }
            
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