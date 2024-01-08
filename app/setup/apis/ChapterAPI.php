<?php

/**
 * Test class
 */
class ChapterAPI extends Api
{
	private $chapterModel;
	private $bookModel;
	function __construct()
	{
		$this->chapterModel = $this->model("Chapter");
		$this->bookModel = $this->model("Book");
	}
	public function get()
	{
		// Check if book_id is set in the request
		$books = $this->chapterModel->selectAll();

		if ($books !== false) {
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $books]);
		} else {
			$error = $this->chapterModel->getError();
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => $error]);
		}
	}
	public function getById($bookId)
	{


		$chaptersById = json_decode(json_encode($this->chapterModel->getChapter($bookId)), true);

		if ($chaptersById !== false) {
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $chaptersById]);
		} else {
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => "error"]);
		}
	}
	public function post($request)
	{
		if (is_array($request)) {
			$book_id = $request['book_id'];
		} elseif (is_object($request) && method_exists($request, 'getParam')) {
			$book_id = $request->getParam('book_id');
		}


		if (isset($_POST['chapter_name'])) {

			$author = $_POST['chapter_name'];
			$result = $this->chapterModel->insert([
				"chapter_name" => $author,
				"book_id" => $book_id

			]);

			header('Content-Type: application/json');

			if ($result !== false) {
				// Insertion successful
				echo json_encode(["success" => true, "data" => $result]);
			} else {
				// Insertion failed
				echo json_encode(["failed" => false, "error" => "cha 'title' or 'content' in the request"]);
			}
		} else {
			// Show an error if 'title' or 'content' keys are missing
			echo json_encode(["success" => false, "error" => "Missing 'title' or 'content' in the request"]);
		}
	}


	public function put($id)
	{

		$chaptersById = $this->chapterModel->getChapterById($id);
		if (!$chaptersById) {
			echo json_encode(["success" => false, "error" => "Book not found"]);
			return;
		}

		$chapter_name = isset($_POST['chapter_name']) && $_POST['chapter_name'] !== '' ? $_POST['chapter_name'] : $chaptersById[0]->chapter_name;


		$newData = [
			"chapter_name" => $chapter_name,
		];

		$called_id = ['id' => $id];

		try {
			$updateResult = $this->chapterModel->update($newData, $called_id);

			if ($updateResult !== false) {
				echo json_encode(["success" => true, "data" => $updateResult]);
			} else {
				$error = $this->chapterModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} catch (Exception $e) {
			echo json_encode(["success" => false, "error" => $e->getMessage()]);
		}
	}

	public function delete($bookId)
	{
		$getChapter = json_decode(json_encode($this->chapterModel->getChapterById($bookId)), true);

		if (empty($getChapter)) {
			echo json_encode(["success" => false, "error" => "No chapter found with the Id identifier"]);
			return;
		}
				
		try {
			$updateResult = $this->chapterModel->delete($bookId); // Pass the condition here

			if ($updateResult !== false) {
				echo json_encode(["success" => true, "message" => "Chapter deleted successfully"]);
			} else {
				$error = $this->chapterModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} catch (Exception $e) {
			echo json_encode(["success" => false, "error" => $e->getMessage()]);
		}

	
	}
}
