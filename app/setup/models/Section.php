<?php 

	class Section extends Model{
		
		function __construct(){
			$this->table_name = "Sections";
		    $this->table_columns = ["id", "section_title",  "content","chapter_id","book_id"];
		}

		public function getSection($data)
		{

			$bookId = isset($data['id']) ? $data['id'] : $data['book_id'];
		
			$sql = "SELECT * FROM " . $this->table_name . " WHERE book_id = :book_id";
		
			$this->query($sql);
		
			$this->bind("book_id", $bookId);
		
			$this->data = $this->resultSet();
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
	}
?>