<?php

class Chapter extends Model
{

	function __construct()
	{
		$this->table_name = "chapters";
		$this->table_columns = ["id", "chapter_name",  "book_id"];
	}
	public function getChapter($data)
	{
		$bookId = isset($data['id']) ? $data['id'] : $data['chapter_id'];
		$columnToSearch = isset($data['id']) ? 'book_id' : 'id';

		$sql = "SELECT * FROM " . $this->table_name . " WHERE $columnToSearch = :book_id";
		$this->query($sql);

		$this->bind("book_id", $bookId);

		$this->data = $this->resultSet();
		$this->exist = ($this->rowCount() > 0);

		return $this->data;
	}
	public function deleteChapter($data)
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
