<?php

    spl_autoload_register(function($className) {

        $class_path_array = explode('\\', $className);
        //Если пространство имен начинается с app, подключаем из корня (удаляем конструкцию app из uri)
        if ($class_path_array[0] == 'app') unset($class_path_array[0]);

        require_once implode(DIRECTORY_SEPARATOR, $class_path_array).'.php';

    });

?>