<?php

namespace app;

use app\models\Model;
use app\View;

class Controller extends Router
{

    protected $model;
    protected $view;

    public function __construct() {

        $this->model = new Model();
        $this->view = new View($this->model);

        $action = $this->route($this); // Раут по юрлу генерирует экшн контроллера. Так как в проекте всего
                                  // один контроллер, раут генерирует только названия экшнов, но не контроллеров

        $this->$action(); // Вызываем сгенерированный экшн
        $this->view->template = 'basic'; // Задаем шаблон по умолчанию
        $this->view->openTemplate();

    }

    protected function actionIndex() {

        $this->view->content = 'index';
        $this->view->widgets = ['navbar' => ['buttons']];

    }

    protected function actionTasksNew() {

        $this->view->widgets = ['navbar' => ['back']];

        if ($_POST) {

            $validateArray = [
                ['name' => ['email', 'Email'], 'data' => $_POST['email'], 'rules' => ['empty', 'email']],
                ['name' => ['author', 'Имя'], 'data' => $_POST['author'], 'rules' => ['empty', 'name']],
                ['name' => ['task', 'Задание'], 'data' => $_POST['task'], 'rules' => ['empty', 'size'], 'params' => ['size' => ['min' => 10, 'max' => '100']]]
            ];

            if ($this->model->validation($validateArray)) {
                if ($this->model->saveNewTask()) {
                    $message = 'Задание успешно создано!';
                    $messageType = 'success';
                    header('Location: /index.php?messages='.$message.'&messageType='.$messageType);
                }
                else {
                    $this->view->content = 'new_task';
                }
            }
            else {
                $this->view->content = 'new_task';

                if (!empty($this->model->errorList) && is_array($this->model->errorList)) {
                    foreach ($this->model->errorList as $field => $error) {
                        $this->view->messages[] = ['type' => 'danger', 'message' => $error];
                    }
                }
            }
        }
        else {
            $this->view->content = 'new_task';
        }

    }

    public function actionLoginIndex() {

        if ($_POST) {

            $validateArray = [
                ['name' => ['login', 'Логин'], 'data' => $_POST['login'], 'rules' => ['empty', 'login']],
                ['name' => ['pass', 'Пароль'], 'data' => $_POST['pass'], 'rules' => ['empty']]
            ];

            if ($this->model->validation($validateArray)) {

                $this->view->messages[] = $this->model->auth($_POST['login'], $_POST['pass']);

            }
            else {
                if (!empty($this->model->errorList) && is_array($this->model->errorList)) {
                    foreach ($this->model->errorList as $field => $error) {
                        $this->view->messages[] = ['type' => 'danger', 'message' => $error];
                    }
                }
            }

            $this->view->content = 'login';

        }
        else {

            $this->view->content = 'login';

        }
    }

    public function actionError404() {

        $this->view->content = 'error404';

        header("HTTP/1.0 404 Not Found");

    }

}