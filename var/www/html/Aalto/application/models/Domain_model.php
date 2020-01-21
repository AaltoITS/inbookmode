<?php
class Domain_model extends MY_Model
{
    const DB_TABLE = 'domains';
    const DB_TABLE_PK = 'id';

    function __construct()
    {
        parent::__construct();
    }
}