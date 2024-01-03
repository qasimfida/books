<?php

class Book extends Model
{

	function __construct()
	{
		$this->table_name = "books";
		$this->table_columns = ["id", "book_title", "author", "description", "image"];
	}
	public function getById($data)
	{
		$bookId = isset($data['id']) ? $data['id'] : $data['book_id'];

		$sql = "SELECT * FROM " . $this->table_name . " WHERE id = :book_id";

		$this->query($sql);

		$this->bind("book_id", $bookId);

		$this->data = $this->resultSet();
		$this->exist = ($this->rowCount() > 0);

		return $this->data;
	}
	public function deleteByBookId($data)
	{
		$bookId = isset($data['id']) ? $data['id'] : $data['id'];

		// Build the DELETE query
		$sql = "DELETE FROM " . $this->table_name . " WHERE id = :book_id";

		// Execute the query
		$this->query($sql);
		$this->bind("book_id", $bookId);
		$this->execute();

		// Return true if at least one row was deleted, otherwise false
		return $this->rowCount() > 0;
	}
}
