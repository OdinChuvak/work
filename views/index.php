<?php

    if ($_GET['page']) $page = $_GET['page']; else $page = 1; //Номер страницы для пагинации

    //Для сортировки
    if ($_GET['sort_method'] == 'ASC') $sort_method = 'DESC'; else $sort_method = 'ASC';

    if (!empty($_GET['sort_field'])) {
        $orderBy = 'ORDER BY '.$_GET['sort_field'].' '.$_GET['sort_method'];
    }
    else {
        $orderBy = 'ORDER BY id DESC';
    }

    //Для пагинации
    $itemsOnPage = 3;
    $sql = "SELECT COUNT(*) as k FROM tasks";
    $allItems = $this->model->selectSqlQuery($sql)[0]['k'];

    $sql = "SELECT * FROM tasks ".$orderBy." LIMIT ".(($page-1)*$itemsOnPage).", ".$itemsOnPage;
    $openItems = $this->model->selectSqlQuery($sql);

?>
<h1>Список заданий</h1>
<?php $this->getWidgets('navbar'); ?>
<table class="table table-hover">
    <tr>
        <th>#</th>
        <th>Задание</a></th>
        <th><a href="/index.php?page=<?=$page; ?>&sort_field=author&sort_method=<?=$sort_method; ?>">Автор</a></th>
        <th><a href="/index.php?page=<?=$page; ?>&sort_field=email&sort_method=<?=$sort_method; ?>">Email</a></th>
        <th><a href="/index.php?page=<?=$page; ?>&sort_field=status&sort_method=<?=$sort_method; ?>">Статус</a></th>
    </tr>
    <?php

        $k = ($page-1)*$itemsOnPage + 1;

        if ($openItems) {
            foreach ($openItems as $item) {
                echo '<tr>
                        <td width="30"><span class="increment">'.$k++.'</span></td>
                        <td width="400">'.$item['task'].'<br /><small class="remark">'.$item['remark'].'</small></td>
                        <td width="150">'.$item['author'].'</td>
                        <td width="230">'.$item['email'].'</td>
                        <td><span class="task-status status-'.$item['status'].'">'.$item['status'].'</span></td>
                    </tr>';
            }
        }

    ?>
</table>
<?php $this->pagination($itemsOnPage, $allItems, $page, $viewStr = 4, '&sort_field='.$_GET['sort_field'].'&sort_method='.$_GET['sort_method']); ?>
