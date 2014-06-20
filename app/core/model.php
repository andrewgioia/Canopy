<?php

class Model {

    protected $_db;

    public function __construct(){
        // Connect to PDO here.
        $this->_db = new Database();
    }
}