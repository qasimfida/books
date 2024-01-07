<?php

class Citation extends Model
{

	function __construct()
	{
		$this->table_name = "Citations";
		$this->table_columns = ["id", "book_id", "citation_id", "citation_name"];
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
}
