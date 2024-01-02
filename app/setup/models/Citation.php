<?php 

	class Citation extends Model{
		
		function __construct(){
			$this->table_name = "Citations";
		    $this->table_columns = ["id", "book_id","citation_id","citation_name"];
		}

		public function getCitation($data)
		{
			
			$bookId = isset($data['id']) ? $data['id'] :  $data['book_id'] ;
		
			$sql = "SELECT * FROM " . $this->table_name . " WHERE book_id = :book_id";
		
			$this->query($sql);
		
			$this->bind("book_id", $bookId);
		
			$this->data = $this->resultSet();
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
	}

?>