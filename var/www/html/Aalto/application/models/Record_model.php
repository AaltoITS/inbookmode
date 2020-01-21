<?php
class Record_model extends MY_Model {

    const DB_TABLE = 'record';
	const DB_TABLE_PK = 'Record_Id';
	
	function __construct() {
		parent::__construct();
	}
}