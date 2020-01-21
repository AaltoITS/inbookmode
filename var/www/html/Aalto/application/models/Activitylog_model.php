<?php
class Activitylog_model extends MY_Model
{
    const DB_TABLE = 'activity_log';
    const DB_TABLE_PK = 'id';

    function __construct()
    {
        parent::__construct();
    }
}