<?php

namespace app\models;

use app\DB;

class Model extends DB
{
    public $errorList;

    public function validation($validationArray) {

        $ok = true;

        if (!empty($validationArray) && is_array($validationArray)) {

            foreach ($validationArray as $validateField) {

                foreach ($validateField['rules'] as $rule ) {

                    switch ($rule) {
                        case 'email':
                            if (!filter_var($validateField['data'], FILTER_VALIDATE_EMAIL)) {
                                $ok = false;
                                $message = 'Поле "' . $validateField['name'][1] . '" заполнено некорректно!';
                            }
                            break;
                        case 'name':
                            if (!preg_match('/^[a-zA-Zа-яёА-ЯЁ\s\-]+$/u', $validateField['data'])) {
                                $ok = false;
                                $message = 'Поле "' . $validateField['name'][1] . '" заполнено некорректно!';
                            }
                            break;
                        case 'empty':
                            if (!$validateField['data']) {
                                $ok = false;
                                $message = 'Поле "' . $validateField['name'][1] . '" является обязательным для заполнения!';
                            }
                            break;
                        case 'size':
                            if (strlen($validateField['data']) < $validateField['params']['size']['min'] ||
                                strlen($validateField['data']) > $validateField['params']['size']['max']) {
                                $ok = false;
                                $message = 'Размер текста поля "' . $validateField['name'][1] . '" не должен 
                                быть меньше '.$validateField['params']['size']['min'].' или 
                                больше '.$validateField['params']['size']['max'].' символов! У вас - '.strlen($validateField['data']);
                            }
                            break;
                    }

                    if ($message) {
                        $this->errorList[$validateField['name'][0]] = $message;
                        $message = '';
                        break;
                    }

                }
            }
        }
        else {
            $this->errorList[] = ['global' => 'Не указаны или некорректно заданы правила валидации!'];
            $ok = false;
        }

        return $ok;
    }

    public function saveNewTask() {

        $sql = "INSERT INTO `tasks` VALUES (0, '".htmlspecialchars($_POST['task'], ENT_QUOTES)."', '".$_POST['author']."', '".$_POST['email']."', 'waits', '".date("Y-m-d H:i:s")."', '')";

        if ($this->sqlQuery($sql)) return true;

    }

    public function auth($login, $pass) {

        $sql = "SELECT * FROM `administrators` WHERE `login` = '".$login."';";
        $admin = $this->selectSqlQuery($sql);

        if ($admin) {

            $insertPass = $admin[0]['salt'].'_'.$_POST['pass'];

            for ($i = 0; $i <= 8; $i++) {
                if (md5($insertPass) == $admin[0]['pass']) {

                    $_SESSION['login'] = $admin[0]['login'];
                    $_SESSION['pass'] = $admin[0]['pass'];

                    header('Location: /admin/index.php');

                    break;
                }
                else {
                    if ($i != 8) {
                        $insertPass = md5($insertPass);
                    }
                    else {
                        return ['type' => 'danger', 'message' => 'Неверный пароль!'];
                    }
                }
            }

        }
        else {

            return $errorList[] = ['type' => 'danger', 'message' => 'Пользователя с такими данными не существует!'];

        }

        //echo '<pre>'.print_r($admin, true).'</pre>';

    }

}