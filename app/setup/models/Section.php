<?php 

	class Section extends Model{
		
		function __construct(){
			$this->table_name = "Sections";
		    $this->table_columns = ["id", "section_title",  "content","chapter_id"];
		}

		public function getSection($data)
		{
			$bookId = isset($data['id']) ? $data['id'] : 1;
		
			# Generate array
			$sql = "SELECT * FROM " . $this->table_name . " WHERE chapter_id = :chapter_id";
		
			# Pass query
			$this->query($sql);
		
			# Bind parameter
			$this->bind("chapter_id", $bookId);
		
			# Save data to global data holder object
			$this->data = $this->resultSet();
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
	}

?>