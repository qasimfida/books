<?php 

	class Book extends Model{
		
		function __construct(){
			$this->table_name = "books";
		    $this->table_columns = ["id", "book_title", "author", "description", "image"];
		}
		
		
	}

?>