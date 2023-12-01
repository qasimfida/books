<?php 

	class Section extends Model{
		
		function __construct(){
			$this->table_name = "Sections";
		    $this->table_columns = ["id", "title",  "content","chapter_id"];
		}

	}

?>