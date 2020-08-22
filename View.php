<?php

namespace app;

class View
{

    public $template;
    public $content;
    public $messages;
    public $model;
    public $widgets;

    public function __construct($model) {

        $this->model = $model;

        if($_GET['messages']) {
            $this->messages[] = ['type' => $_GET['messageType'], 'message' => $_GET['messages']];
        }

    }

    public function openTemplate() {

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/templates/'.$this->template.'/template.php'))
            include_once $_SERVER['DOCUMENT_ROOT'].'/templates/'.$this->template.'/template.php';

    }

    public function getContent() {

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/views/'.$this->content.'.php'))
            include_once $_SERVER['DOCUMENT_ROOT'].'/views/'.$this->content.'.php';

    }

    public function getMessages() {

        if (!empty($this->messages) && is_array($this->messages)) {
            foreach ($this->messages as $msg) {
                echo '<div class="alert alert-'.$msg['type'].'" role="alert">'.$msg['message'].'</div>';
                // $type - 'danger', 'success', 'info'
            }
        }

    }

    public function getWidgets($position) {

        if ($this->widgets[$position]) {
            foreach ($this->widgets[$position] as $widget) {
                include 'widgets/'.$widget.'.php';
            }
        }

    }

    public function pagination($itemsOnPage, $allItems, $pageNumber, $viewStr, $otherparamstr = '') {

        /* $itemsOnPage - количество выводимых записей на странице
         * $allItems - количество всех записей
         * $pageNumber - номер текущей страницы
         * $viewStr - количество отображаемых страниц.
         * Например, если 3, то пагинация будет иметь вид: 1,2,3,...,9, или 1,...,4,5,6,...,9
         * $otherparamstr - дополнительные параметры, которые нужно передать при листании страниц
         * */


        $uri = '/'.trim(str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']), '/');
        $allstr = ceil($allItems/$itemsOnPage); //Количество всех страниц

        if ($allstr > 1) {

            echo '<nav aria-label="Page navigation example">';
            echo '<ul class="pagination">';

            if ($allstr <= $viewStr) {
                for ($j = 1; $j <= $allstr; $j++) {
                    if ($j == $pageNumber) echo '<li class="page-item"><span class="page-link">'.$j.'</span></li>'; else echo '<li class="page-item"><a href="'.$uri.'?page='.($j).$otherparamstr.'" class="page-link">'.$j.'</a></li>';
                }
            }
            else {

                $botNum = $pageNumber - floor($viewStr/2);

                if ($botNum < 1) $botNum = 1;
                if ($botNum > $allstr-$viewStr+1) $botNum = $allstr-$viewStr+1;

                if ($botNum == 2)
                    echo '<li class="page-item">
                            <a href="'.$uri.'?page=1'.$otherparamstr.'" class="page-link">1</a>
                          </li>';
                elseif ($botNum > 2)
                    echo '<li class="page-item">
                            <a href="'.$uri.'?page=1'.$otherparamstr.'" class="page-link">1</a>
                          </li>
                          <li class="page-item">
                            <a href="'.$uri.'?page='.($pageNumber-1).$otherparamstr.'" class="page-link">...</a>
                          </li>';

                for ($j = $botNum; $j <= $botNum+$viewStr-1; $j++) {

                    if ($j == $pageNumber)
                        echo '<li class="page-item">
                                <span class="page-link">'.$j.'</span>
                              </li>';
                    else
                        echo '<li class="page-item">
                                <a href="'.$uri.'?page='.($j).$otherparamstr.'" class="page-link">'.$j.'</a>
                              </li>';

                }

                if ($botNum == $allstr-$viewStr)
                    echo '<li class="page-item">
                            <a href="'.$uri.'?page='.$allstr.$otherparamstr.'" class="page-link">'.$allstr.'</a>
                          </li>';
                elseif ($botNum < $allstr-$viewStr)
                    echo '<li class="page-item">
                            <a href="'.$uri.'?page='.($pageNumber-1).$otherparamstr.'" class="page-link">...</a>
                          </li>
                          <li class="page-item">
                            <a href="'.$uri.'?page='.$allstr.$otherparamstr.'" class="page-link">'.$allstr.'</a>
                          </li>';

            }

            echo '</ul>';
            echo '</nav>';

        }

    }

}