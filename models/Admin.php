<?php

namespace app\models;

use app\DB;

class Admin extends Model
{
    public function loginControl() {

        $sql = "SELECT COUNT(*) as k FROM `administrators` WHERE `login` = '".$_SESSION['login']."' AND `pass` = '".$_SESSION['pass']."';";

        return $this->selectSqlQuery($sql)[0]['k'];

    }

    public function changeStatus($taskId, $changeTo) {

        $sql = "UPDATE `tasks` SET `status` = '".$changeTo."' WHERE `id` = ".$taskId.";";

        if ($this->sqlQuery($sql)) return true;

    }

    public function getTask($task_id) {

        $sql = "SELECT * FROM `tasks` WHERE `id` = ".$task_id.';';

        return $this->selectSqlQuery($sql)[0];

    }

    public function saveEditTask($task) {

        $sql = "UPDATE `tasks` SET `author` = '".$task['author']."', `task` = '".htmlspecialchars(trim($task['task']), ENT_QUOTES)."', `email` = '".$task['email']."', remark = 'Отредактировано администратором' WHERE `id` = ".$task['task_id'].";";

        if ($this->sqlQuery($sql)) return true;

    }

}