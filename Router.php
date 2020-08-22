<?php

namespace app;

class Router
{
    protected function route($controller) {

        $uri = trim(str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']), '/');

        if (empty($uri) || $uri == 'index.php') {
            $action = 'index';
        }
        else {
            $uriParts = explode('/', strtolower($uri));

            if (is_array($uriParts)) {

                $action = $uriParts[0];
                unset($uriParts[0]);

                foreach ($uriParts as $part) {
                    $action .= ucfirst($part);
                }

                if ($k = strripos($action,'.')) {
                    $action = substr($action, 0, $k);
                }
                else {
                    $action .= 'Index';
                }
            }
        }

        $methodsList = get_class_methods($controller); // Взял все методы, для поиска в них того,
                                                       // что сгенерирован раутом

        if (in_array('action'.ucfirst($action), $methodsList)) {
            $method = 'action'.ucfirst($action);
        }
        else {
            $method = 'actionError404'; // Экшн 404 ошибки
        }

        return $method;
    }
}