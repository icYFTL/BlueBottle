<?php

class MySQLConn
{
public $mysql;

function __construct()
{
    $this->mysql = new mysqli("82.202.227.174", "user7180_root", "Frdf231968123", "user7180_bluebottle");
}
}

?>