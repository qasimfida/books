<?php 

	class Chapter extends Model{
		
		function __construct(){
			$this->table_name = "Chapter";
		    $this->table_columns = ["id", "chapter_name",  "book_id"];
		}

	}

?>