<?php

/**

* Sql CRUD(Select Inser Update Delete etc) All fnctions are Defined over here and which is exted bye the model (Akhil 1/9/2015).

* This Alse extends CI_Model of Codeignitor so that all the codeignitor property would also be called when u call MY_Model.

*/

class MY_Model extends CI_Model

{

	const DB_TABLE = 'abstract';

	const DB_TABLE_PK = 'abstract';



	/*public function __construct()

    {

        parent::__construct();

        $this->select_query = '';

        self::$db = &get_instance()->db;

    }*/



	/*delete*/

	function delete($where_array = Null, $where_in_array = Null)

	{

		if (!empty($where_array)) {

			foreach ($where_array as $key => $row) {

				$this->db->where($key, $row);

			}

		}

		if (!empty($where_in_array)) {

			foreach ($where_in_array as $key => $row) {

				$this->db->where_in($key, $row);

			}

		}

  		//$this->db->delete($this::DB_TABLE);

  		return $this->db->delete($this::DB_TABLE);

	}



	/*insert*/

	function insert($insert_data)

	{

		$this->db->insert($this::DB_TABLE, $insert_data);

		return $this->db->insert_id();

	}



	/*New Insert Using created instance*/

	function new_insert()

	{

		$this->db->insert($this::DB_TABLE, $this);

		return $this->db->insert_id();

	}



	/*insert in batch*/

	function insert_batch($insert_data)

	{

		//$this->db->insert_batch($this::DB_TABLE, $insert_data);

		return $this->db->insert_batch($this::DB_TABLE, $insert_data);

	}



	/*Update*/

	function update($update_data, $where_array, $where_in_array = Null)

	{

		if (!empty($where_array)) {

			foreach ($where_array as $key => $row) {

				$this->db->where($key, $row);

			}

		}

		if (!empty($where_in_array)) {

			foreach ($where_in_array as $key => $row) {

				$this->db->where_in($key, $row);

			}

		}

		$this->db->update($this::DB_TABLE, $update_data); 

		return $this->db->affected_rows();

	}



	/*get values from the table by giving id (single row)*/

	function get_details_id($id, $columns = '*')

	{

		$this->db->select($columns);

		$this->db->where($this::DB_TABLE_PK, $id);

		return $this->db->get($this::DB_TABLE)->row();

	}



	/*get detils of data from the table by giving condition (single row)*/

	function get_details($where_array, $where_in_array, $columns = '*')

	{

		if ($columns == 'PK') {

			$this->db->select($this::DB_TABLE_PK);

		} else {

			$this->db->select($columns);

		}

		if (!empty($where_array)) {

			foreach ($where_array as $key => $row) {

				$this->db->where($key, $row);

			}

		}

		if (!empty($where_in_array)) {

			foreach ($where_in_array as $key => $row) {

				$this->db->where_in($key, $row);

			}

		}	

		$result= $this->db->get($this::DB_TABLE)->row();

		return $result;

	}	



	/*Get Details of list of data from the table

	$columns = 'PK'-Primary Key; 'this'-get columns from instance created in model; '*'-default, get all columns.

	$order_by = array('title' => value, 'order' => value);

	$signle_array = 1- get result in single-D array format(pass only one column or PK).

	*/

	function get_details_list($where_array = Null, $where_in_array = Null, $columns = '*', $order_by = Null, $signle_array = 0, $limit = 0)

	{

		if ($columns == 'PK') {

			$this->db->select($this::DB_TABLE_PK);

		} 

		elseif ($columns == 'this') {

			$new_columns = array();

		 	foreach ($this as $key => $row) {

		 		$new_columns[] = $this::DB_TABLE.'.'.$key.' as '.$this::DB_TABLE.'_'.$key;

		 	}



		 	if (!empty($new_columns)) {

		 		$this->db->select($new_columns);

		 	}

		 	else {

		 		$this->db->select('*');

		 	}

		}

		else 

		{

			$this->db->select($columns);

		}

		if (!empty($where_array)) {

			foreach ($where_array as $key => $row) {

				$this->db->where($key, $row);

			}

		}

		if (!empty($where_in_array)) {

			foreach ($where_in_array as $key => $row) {

				$this->db->where_in($key, $row);

			}

		}

		if (!empty($order_by)) {

			$this->db->order_by($order_by['title'], $order_by['order']); 

		}

		if (isset($limit) && $limit > 0) {

			$this->db->limit($limit);

		}

		if ($signle_array == 0) {

			return $this->db->get($this::DB_TABLE)->result();

		}

		else {

			$data = $this->db->get($this::DB_TABLE)->result();



			$formated_array = array();

			if ($columns == 'PK') {

				$pk = $this::DB_TABLE_PK;

				foreach ($data as $key => $row) {

					$formated_array[] = $row->$pk;

				}

			} else {

				foreach ($data as $key => $row) {

					$formated_array[] = $row->$columns[0];

				}

			}

			return $formated_array;

		}	

	}



	

}

?>