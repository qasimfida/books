<?php 

	class Book extends Model{
		
		function __construct(){
			$this->table_name = "books";
		    $this->table_columns = ["book_id", "title", "content"];
		}

	}

?>