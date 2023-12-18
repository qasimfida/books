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
		$book = json_decode(json_encode($this->taskModel->get($bookId)), true);
		$chapters = json_decode(json_encode($this->chapterModel->getChapter($bookId)), true);
		$sections = json_decode(json_encode($this->sectionModel->getSection($bookId)), true);



		if ($book !== false && $chapters !== false && $sections !== false) {
			// Combine both data sets into a single array
			$combinedChapters = [];

			foreach ($chapters as $chapter) {
				$combinedChapter = $chapter;
				$combinedChapter['sections'] = [];

				foreach ($sections as $section) {
					if ($section['chapter_id'] === $chapter['id']) {
						$combinedChapter['sections'][] = $section;
					}
				}

				$combinedChapters[] = $combinedChapter;
			}

			// Combine all data into a single array
			$combinedData = [
				"book" => $book,
				"chapters" => $combinedChapters,
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
