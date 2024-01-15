<?php 

	class Figure extends Model{
		
		function __construct(){
			$this->table_name = "Figures";
		    $this->table_columns = ["id", "book_id","chapter_id","figure_name","figure_id", "figure_image"];
		}

		public function getFigure($data)
		{
			
			$bookId = isset($data['id']) ? $data['id'] : $data['figure_id'];
			$columnToSearch = isset($data['id']) ? 'book_id' : 'id';
			$sql = "SELECT * FROM " . $this->table_name . " WHERE $columnToSearch = :book_id";
		
			$this->query($sql);
			$this->bind("book_id", $bookId);
		
			$this->data = $this->resultSet();
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
		public function getFigureById($figureId)
	{
		$figureId = $figureId['figure_id'] ;
		
		$sql = "SELECT * FROM " . $this->table_name . " WHERE figure_id = :figure_id";
		$this->query($sql);
		$this->bind("figure_id", $figureId);
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