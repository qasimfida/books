<?php

/**
 * Test class
 */
class CitationAPI extends Api
{
	private $citationModel;
	private $bookModel;
	private $chapterModel;
	private $sectionModel;
	function __construct()
	{
		$this->citationModel = $this->model("Citation");
		$this->bookModel = $this->model("Book");
		$this->chapterModel = $this->model("Chapter");
		$this->sectionModel = $this->model("Section");
	}
	public function get()
	{
		// Check if book_id is set in the request
		$books = $this->citationModel->selectAll();


		// Check if any books were found
		if ($books !== false) {
			// Return the books as JSON
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $books]);
		} else {
			// If no books found, return an error
			$error = $this->citationModel->getError();
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => $error]);
		}
	}

	public function getById($bookId)
	{
		$book = json_decode(json_encode($this->citationModel->getCitation($bookId)), false);

		if ($book !== false) {
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $book]);
		} else {
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => "error"]);
		}
	}




	public function post($request)
	{
		if (is_array($request)) {
			$book_id = $request['book_id'];
		}

		if (isset($_POST['citation_name']) && isset($_POST['citation_id'])) {

			$citation_name = $_POST['citation_name'];
			$citation_id = $_POST['citation_id'];

			$result = $this->citationModel->insert([
				"citation_name" => $citation_name,
				"citation_id" => $citation_id,
				"book_id" => $book_id

			]);

			header('Content-Type: application/json');

			if ($result !== false) {
				echo json_encode(["success" => true, "data" => $result]);
			} else {
				echo json_encode(["failed" => false, "error" => " 'citation_id' or 'citation_name' in the request"]);
			}
		} else {
			echo json_encode(["success" => false, "error" => "Missing 'citation_id' or 'citation_name' in the request"]);
		}
	}


	public function put($id)
	{

		$getSections = $this->citationModel->getCitationById($id);
		if (!$getSections) {
			echo json_encode(["success" => false, "error" => "Book not found"]);
			return;
		}

		$citation_name = isset($_POST['citation_name']) && $_POST['citation_name'] !== '' ? $_POST['citation_name'] : $getSections[0]->citation_name;
		$citation_id = isset($_POST['citation_id']) && $_POST['citation_id'] !== '' ? $_POST['citation_id'] : $getSections[0]->citation_id;


		$newData = [
			"citation_name" => $citation_name,
			"citation_id" => $citation_id,

		];

		$called_id = ['id' => $id];

		try {
			$updateResult = $this->citationModel->update($newData, $called_id);

			if ($updateResult !== false) {
				echo json_encode(["success" => true, "data" => $updateResult]);
			} else {
				$error = $this->citationModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} catch (Exception $e) {
			echo json_encode(["success" => false, "error" => $e->getMessage()]);
		}
	}

	public function delete($bookId)
	{
		$getCitation = json_decode(json_encode($this->citationModel->getCitationById($bookId)), true);

		if (empty($getCitation)) {
			echo json_encode(["success" => false, "error" => "No chapter found with the Id identifier"]);
			return;
		}
				
		try {
			$updateResult = $this->citationModel->delete($bookId); // Pass the condition here

			var_dump($updateResult);
			if ($updateResult !== false) {
				echo json_encode(["success" => true, "message" => "Chapter deleted successfully"]);
			} else {
				$error = $this->citationModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} catch (Exception $e) {
			echo json_encode(["success" => false, "error" => $e->getMessage()]);
		}

	
	}
}
