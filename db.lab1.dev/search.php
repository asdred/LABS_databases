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
            <select id="s">
                <option value="1">Наименование самого тяжелого товара</option>
                <option value="2">Названия складов, в которые перевозят товары все автомобили</option>
                <option value="3">Названия складов и марки автомобилей, у которых владелец один и тот же</option>
                <option value="4">Наименования товаров, которые не перевозит указанный автомобиль</option>
                <option value="5">Названия складов, в которые перевозятся все товары</option>
                <option value="6">Названия автомобилей, которые перевозят товары в те же склады, что и указанный автомобиль</option>
            </select>
            <p>и нажмите кнопку</p>
            <button onclick="select()">Показать</button>
            <p>или введите что-нибудь в поле ниже</p>
            <input type="text" oninput="search()"></input>
        </div>
        <div class="results">
            <p>Введите что-нибудь в поле выше</p>
        </div>
        <script>
            
            // Обработка кнопки
            
            function select() {
                var table = <?php echo "'" . $_GET['t'] . "'" ?>;
                var selected = $('#s').val();
                //var container = $('#container-search');
                
                if (selected == 4) {
                    //container.append('<button>testtest</button>');
                    //$('#s').after('<p>Укажите автомобиль здесь:</p><input type="text" oninput="run()"></input>');
                    var truck = prompt("Укажите автомобиль:");
                }
                if (selected == 6) {
                    var truck = prompt("Укажите номер автомобиля:");
                }
                
                $.ajax({
                    type: 'POST',
                    url: 'response.php',
                    data: 't=' + table + '&selected_query=' + selected + '&truck=' + truck,
                    success: function(data) {
                        $('.results').html(data);
                        }
                    });
            }
            
            // Обработка ввода в input
            
            function search() {
                var table = <?php echo "'" . $_GET['t'] . "'" ?>;
                var search_string = document.getElementsByTagName('input')[0].value;

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