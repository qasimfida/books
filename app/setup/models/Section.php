<?php

class Section extends Model
{

	function __construct()
	{
		$this->table_name = "Sections";
		$this->table_columns = ["id", "section_title",  "content", "chapter_id", "book_id"];
	}

	public function getSection($data)
	{
		$bookId = isset($data['id']) ? $data['id'] : $data['section_id'];
		$columnToSearch = isset($data['id']) ? 'book_id' : 'id';

		$sql = "SELECT * FROM " . $this->table_name . " WHERE $columnToSearch = :book_id";

		$this->query($sql);

		$this->bind("book_id", $bookId);

		$this->data = $this->resultSet();
		$this->exist = ($this->rowCount() > 0);

		return $this->data;
	}
	public function getSectionById($sectionId)
	{
		$sectionId = isset($sectionId['id']) ? $sectionId['id'] : "";	
		$sql = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
		$this->query($sql);
		$this->bind("id", $sectionId);
		$this->data = $this->resultSet();
		$this->exist = ($this->rowCount() > 0);

		return $this->data;
	}
	
	public function deleteSection($data)
	{
		$bookId = isset($data['id']) ? $data['id'] : $data['book_id'];

		// Build the DELETE query
		$sql = "DELETE FROM " . $this->table_name . " WHERE book_id = :book_id";

		// Execute the query
		$this->query($sql);
		$this->bind("book_id", $bookId);
		$this->execute();

		// Return true if at least one row was deleted, otherwise false
		return $this->rowCount() > 0;
	}
}
