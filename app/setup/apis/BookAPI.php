<?php

/**
 * Test class
 */
class BookAPI extends Api
{
	function __construct()
	{
		$this->taskModel = $this->model("Book");
		$this->chapterModel = $this->model("Chapter");
		$this->sectionModel = $this->model("Section");

	}
	public function get()
	{
		// Check if book_id is set in the request
		$books = $this->taskModel->selectAll();


		// Check if any books were found
		if ($books !== false) {
			// Return the books as JSON
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $books]);
		} else {
			// If no books found, return an error
			$error = $this->taskModel->getError();
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => $error]);
		}
	}
	public function getById($bookId)
	{
		$book = $this->taskModel->get($bookId);
		$chapter = $this->chapterModel->getChapter($bookId);
		$section = $this->sectionModel->getSection($bookId);

		if ($book !== false && $chapter !== false && $section !== false) {
			// Combine both data sets into a single array
			$combinedData = [
				"book" => $book,
				"chapter" => $chapter,
				"section" => $section,

			];

			// Return the combined data as JSON
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $combinedData]);
		} else {
			// If either the book or chapter is not found, return an error
			// $error = $book !== false ? $this->taskModel->getError() : $this->chapterModel->getError();

			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => "error"]);
		}
	}


	public function post()
	{
		if (isset($_POST['book_title'], $_POST['author'])) {
			$book_title = $_POST['book_title'];
			$author = $_POST['author'];
			$description = $_POST['description'];
			$image = $_POST['image'];

			$result = $this->taskModel->insert([
				"book_title" => $book_title,
				"author" => $author,
				"description" => $description,
				"image" => $image
			]);

			header('Content-Type: application/json');

			if ($result !== false) {
				echo json_encode(["success" => true, "data" => $result]);
			} else {
				$error = $this->taskModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} else {
			echo json_encode(["success" => false, "error" => "Missing 'title' or 'content' in the request"]);
		}
	}


	public function put()
	{
		$this->json([
			"message" => "Updating something using " . $_SERVER['REQUEST_METHOD'] . " request?",
			"request" => $this->request
		]);
	}

	public function delete()
	{
		$this->json([
			"message" => "You're now scrubbing something using " . $_SERVER["REQUEST_METHOD"] . " request"
		]);
	}

	public function foobar()
	{
		$this->json([
			"id" => "The id is `$this->id`",
			"message" => "You're now accessing this method by DEFINED_METHOD."
		]);
	}
}
