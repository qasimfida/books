<?php

class Citation extends Model
{

	function __construct()
	{
		$this->table_name = "citations";
		$this->table_columns = ["id", "book_id","section_id",  "citation_id","chapter_id", "citation_name"];
	}

	public function getCitation($data)
	{

		$bookId = isset($data['id']) ? $data['id'] : $data['citation_id'];
		$columnToSearch = isset($data['id']) ? 'book_id' : 'id';

		$sql = "SELECT * FROM " . $this->table_name . " WHERE $columnToSearch = :book_id";

		$this->query($sql);

		$this->bind("book_id", $bookId);

		$this->data = $this->resultSet();
		$this->exist = ($this->rowCount() > 0);

		return $this->data;
	}
	public function getCitationById($data)
	{

		$citationId = isset($data['id']) ? $data['id'] : $data['citation_id'];	
		$columnToSearch = isset($data['id']) ? 'book_id' : 'citation_id';

		$sql = "SELECT * FROM " . $this->table_name . " WHERE $columnToSearch = :id";
		$this->query($sql);
		$this->bind("id", $citationId);
		$this->data = $this->resultSet();
		$this->exist = ($this->rowCount() > 0);

		return $this->data;
	}
	
	public function deleteCitation($data)
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
	public function updateCitation($data, $citation_id){
			
		$citationId = $citation_id['citation_id'];

		$sql = "UPDATE " . $this->table_name . " SET citation_name = :citation_name WHERE citation_id = :citation_id";
	
		$this->query($sql);
	
		$this->bind(":citation_name", $data['citation_name']);
		$this->bind(":citation_id", $citationId);
	
		$this->execute();
	
		$this->data = $this->getCitationById($citation_id);
		$this->exist = ($this->rowCount() > 0);
	
		return $this->data;
	}

	public function deleteCitationByType($data, $type)
	{


		$bookId = isset($data['id']) ? $data['id'] : "";
	
		$findType =  $type == "chapter" ? "chapter_id" : "section_id";
		
		$sql = "DELETE FROM " . $this->table_name . " WHERE $findType = :id";
	
		$this->query($sql);

		$this->bind("id", $bookId);

		$this->data = $this->resultSet();

		$this->exist = ($this->rowCount() > 0);
	
		return $this->data;
	}
}
