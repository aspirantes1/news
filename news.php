<?php

$connection = new mysqli('127.0.0.1', 'root', '');

$prodarr = [];

 // Поверка, есть ли GET запрос
if (isset($_GET['get3'])) {
     // Если да то переменной $pageno присваиваем его
     $pageno = $_GET['get3'];
 } else { // Иначе
     // Присваиваем $pageno один
     $pageno = 1;
 }

 if ($_GET['get2'] == 'all') {
     $categ = 'all';
 } else {
     $categ = $_GET['get2'];
 }
// Назначаем количество данных на одной странице
$size_page = 5;
// Вычисляем с какого объекта начать выводить
$offset = ($pageno-1) * $size_page;

//если категория для всех, делаем запрос для всех, если выбрана отдельная категория, делаем запрос для отдельной категории
if ($categ == 'all') {
    $query = $connection->query("SELECT * FROM bdb.news ORDER BY id DESC LIMIT $offset, $size_page");
    $result = $connection->query("SELECT COUNT(*) FROM bdb.news");
} else {
    $query = $connection->query("SELECT * FROM bdb.news WHERE category = $categ ORDER BY id DESC LIMIT $offset, $size_page");
    $result = $connection->query("SELECT COUNT(*) FROM bdb.news WHERE category = $categ");
}
//Получаем количство страниц для пагнации
$total_rows = mysqli_fetch_array($result)[0];
// Вычисляем количество страниц
$total_pages = ceil($total_rows / $size_page);
?>
          <p>Выберите категорию...</p>
          <a href="https://yamal.shop/newsread/all">All</a>
          <?php 
//делаем отдельный запрос для таблицы категории          
          $query2 = $connection->query("SELECT * FROM bdb.news ORDER BY id DESC LIMIT $offset, $size_page");
          foreach ($query2 as $prod) {
               if(!in_array($prod['category'],$prodarr)) {
                    $prodarr[]=$prod['category'];?>
                    <a href="https://yamal.shop//newsread/<?php echo $prod['category'].'/'.$pageno;?>"><?php echo $prod['category'];}}?></a>
<?php
//Выводим результат
 foreach ($query as $prod) { 
          ?>
               <div class='news'>
                    <p>Название: <?php echo $prod['title'];?></p>
                    <p>Категория: <?php echo $prod['category'];?></p>
                    <p>Автор: <?php echo $prod['author'];?> </p>
                    <p>Новость: <?php echo $prod['news'];?> </p>
                    <p>Создана: <?php echo $prod['publishedAt'];?> </p>
                    -------------------------------------------
               </div>
<?php    
}
//Закрываем запросы
                        $prodarr=[];
                        $query->close();
                        $query2->close();?>


<!-- Выводим ссылки для пагнации -->

<ul>
    <li><a href="https://yamal.shop/newsread/<?php echo $categ;?>/1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno <= 1){ echo 'https://yamal.shop/newsread/'.$categ.'/'.$pageno; } else { echo "https://yamal.shop/newsread/$categ/".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'https://yamal.shop/newsread/all'; } ?>">
        <a href="<?php if($pageno >= $total_pages){ echo 'https://yamal.shop/newsread/'.$categ.'/'.$pageno; } else { echo "https://yamal.shop/newsread/$categ/".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a href="https://yamal.shop/newsread/<?php echo $categ.'/'.$total_pages; ?>">Last</a></li>
</ul>

<?php
// Создаём форму для записи новостей
$connection = new mysqli('127.0.0.1', 'root', '');

$query = $connection->query("SELECT * FROM bdb.news");

$_SESSION['strok'] = $query->num_rows;
$prodarr = [];

?>

<div class='news'>         
     <input id="title" type="text" name="texts2" placeholder="Тема..." autocomplete="off" required>
     <select name="category" id="category">
          <option>Выберите категорию...</option>
          <?php foreach ($query as $prod) {
               if(!in_array($prod['category'],$prodarr)) {
                    $prodarr[]=$prod['category'];?>
                    <option><?php echo $prod['category'];?></option>
               <?php }} $prodarr=[];
                        $query->close();?>
     </select>
     <input id="author" type="text" name="texts2" placeholder="Автор..." autocomplete="off" required>
     <textarea id="news" type="text" name="texts" placeholder="Новость..." autocomplete="off" required></textarea>
     <button name="createnews">Создать</button>
</div> 
