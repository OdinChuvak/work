<?php

namespace app;  //

Class DB {

    public $dbconnect;

    public function __construct($dbhost = '127.0.0.1', $dbname = 'beejee', $dbuser = 'mysql', $dbpass = 'mysql') {

        $this->dbconnect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        //die('<pre>'.print_r($this->dbconnect, true).'</pre>');

        mysqli_set_charset($this->dbconnect, "utf8");

        return $this->dbconnect;

    }

    public function sqlQuery($sql) {

         return mysqli_query($this->dbconnect, $sql);

    }

    public function selectSqlQuery($sql) {

        $select = $this->sqlQuery($sql);

        return mysqli_fetch_all($select, MYSQLI_ASSOC);

    }

}

?>