<?php

/* Example Config for MYSQL */
ini_set("display_errors","1");

include('PDO/cxpdo.php');
abstract class DBConnection
{
    protected $_db;
    private $_config = array();
//     $config['DATABSE_USER_NAME'] = 'root';
//     $config['DATABSE_PASSWORD'] = 'root';
//     $config['DATABASE_NAME'] = 'skillseeker';
//     $config['DATABASE_HOST'] = 'localhost';
//     $config['DATABASE_TYPE'] = 'mysql';
//     $config['DATABASE_PORT'] = null;
//     $config['DATABASE_PERSISTENT'] = true
    
    public function __construct ()
    {
        $this->_config['DATABSE_USER_NAME'] = 'root';
        $this->_config['DATABSE_PASSWORD'] = 'root';
        $this->_config['DATABASE_NAME'] = DB_COMMON;
        $this->_config['DATABASE_HOST'] = 'localhost';
        $this->_config['DATABASE_TYPE'] = 'mysql';
        $this->_config['DATABASE_PORT'] = null;
        $this->_config['DATABASE_PERSISTENT'] = true;
        $this->_db = db::instance($this->_config);
//         ECHO '<PRE>';
//         PRINT_R($THIS->_CONFIG);
//         DIE;

    }
}   

?>