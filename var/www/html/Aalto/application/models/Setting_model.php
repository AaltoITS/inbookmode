<?php
class Setting_model extends MY_Model
{
    const DB_TABLE = 'settings';
    const DB_TABLE_PK = 'id';

    function __construct()
    {
        parent::__construct();
    }
}