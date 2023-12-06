<?php 

	class Chapter extends Model{
		
		function __construct(){
			$this->table_name = "chapters";
		    $this->table_columns = ["id", "chapter_name",  "book_id"];
		}
		public function getChapter($data)
		{
			$bookId = isset($data['id']) ? $data['id'] : 1;
		
			# Generate array
			$sql = "SELECT * FROM " . $this->table_name . " WHERE book_id = :book_id";
		
			# Pass query
			$this->query($sql);
		
			# Bind parameter
			$this->bind("book_id", $bookId);
		
			# Save data to global data holder object
			$this->data = $this->resultSet();
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
		
	}

?>