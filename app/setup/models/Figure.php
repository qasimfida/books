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
		public function getFigureById($data)
	{
		$figureId = isset($data['id']) ? $data['id'] : $data['figure_id'];	
		
		$columnToSearch = isset($data['id']) ? 'book_id' : 'figure_id';

		$sql = "SELECT * FROM " . $this->table_name . " WHERE $columnToSearch = :figure_id";
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
		public function updateFigure($data, $figure_id){
			
			$figureId = $figure_id['figure_id'];

			$sql = "UPDATE " . $this->table_name . " SET figure_name = :figure_name, figure_image = :figure_image WHERE figure_id = :figure_id";
		
			$this->query($sql);
		
			$this->bind(":figure_name", $data['figure_name']);
			$this->bind(":figure_image", $data['figure_image']);
			$this->bind(":figure_id", $figureId);
		
			$this->execute();
		
			$this->data = $this->getFigureById($figure_id);
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
	}

?>