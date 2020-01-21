<?php
class Book_model extends MY_Model
{
    const DB_TABLE = 'books';
    const DB_TABLE_PK = 'id';

    function __construct()
    {
        parent::__construct();
    }
}