<?php

namespace admin;

use app\models\Admin;
use app\Router;
use app\View;

class Controller extends Router
{

    protected $model;
    protected $view;

    public function __construct() {

        $this->model = new Admin();
        $this->view = new View($this->model);

        if (!$this->model->loginControl()) {
            $this->logout();
            exit();
        }

        $action = $this->route($this); // Раут по юрлу генерирует экшн контроллера. Так как в проекте всего
                                       // один контроллер, раут генерирует только названия экшнов, но не контроллеров

        $this->$action(); // Вызываем сгенерированный экшн
        $this->view->template = 'basic'; // Задаем шаблон по умолчанию
        $this->view->openTemplate();

    }

    public function logout() {

        $_SESSION['login'] = ''; unset($_SESSION['login']);
        $_SESSION['pass'] = ''; unset($_SESSION['pass']);

        header('Location: /login');

    }

    public function actionAdminIndex() {

        $this->view->content = 'admin_index';
        $this->view->widgets = ['navbar' => ['admin']];

    }

    public function actionAdminStatusChangeIndex() {

        if (!empty($_GET['status_id']) && !empty($_GET['change_to'])) {
            if ($this->model->changeStatus($_GET['status_id'], $_GET['change_to'])) {
                $this->view->messages[] = ['type' => 'success', 'message' => 'Статус задания успешно изменен!'];
            }
        }

        $this->view->content = 'admin_index';
        $this->view->widgets = ['navbar' => ['admin']];
    }

    public function actionAdminLogout() {

        $this->logout();

        header('Location: ../login');

    }

    public function actionError404() {

        $this->view->content = 'error404';

        header("HTTP/1.0 404 Not Found");

    }

    public function actionAdminTaskEdit() {

        $this->view->widgets = ['navbar' => ['back']];

        if ($_POST) {

            $validateArray = [
                ['name' => ['email', 'Email'], 'data' => $_POST['email'], 'rules' => ['empty', 'email']],
                ['name' => ['author', 'Имя'], 'data' => $_POST['author'], 'rules' => ['empty', 'name']],
                ['name' => ['task', 'Задание'], 'data' => $_POST['task'], 'rules' => ['empty', 'size'], 'params' => ['size' => ['min' => 10, 'max' => '100']]]
            ];

            if ($this->model->validation($validateArray)) {
                if ($this->model->saveEditTask($_POST)) {
                    $this->view->messages[] = ['type' => 'success', 'message' => 'Задание успешно отредактировано!'];
                }
                $this->view->content = 'edit_task';
            }
            else {
                $this->view->content = 'edit_task';

                if (!empty($this->model->errorList) && is_array($this->model->errorList)) {
                    foreach ($this->model->errorList as $field => $error) {
                        $this->view->messages[] = ['type' => 'danger', 'message' => $error];
                    }
                }
            }
        }
        else {
            $this->view->content = 'edit_task';
        }

    }

}