<?php 

	class Figure extends Model{
		
		function __construct(){
			$this->table_name = "Figures";
		    $this->table_columns = ["id", "book_id","chapter_id","figure_name","figure_image","figure_id"];
		}

		public function getFigure($data)
		{

			$bookId = isset($data['id']) ? $data['id'] : $data['book_id'];
		
			$sql = "SELECT * FROM " . $this->table_name . " WHERE book_id = :book_id";
		
			$this->query($sql);
		
			# Bind parameter
			$this->bind("book_id", $bookId);
		
			$this->data = $this->resultSet();
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
		public function deleteFigure($data)
		{

			$bookId = isset($data['id']) ? $data['id'] : $data['book_id'];
		
			$sql = "DELETE FROM " . $this->table_name . " WHERE book_id = :book_id";
		
			$this->query($sql);
		
			# Bind parameter
			$this->bind("book_id", $bookId);
		
			$this->data = $this->resultSet();
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
	}

?>