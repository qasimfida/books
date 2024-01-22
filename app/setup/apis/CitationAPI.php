<?php

/**
 * Citation class
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
		
		$book = json_decode(json_encode($this->citationModel->getCitationById($bookId)), false);

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
			$chapter_id = $_POST['chapter_id'];

			$result = $this->citationModel->insert([
				"citation_name" => $citation_name,
				"citation_id" => $citation_id,
				"book_id" => $book_id,
				"chapter_id"=>$chapter_id

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


	public function delete($bookId)
	{
		$getCitation = json_decode(json_encode($this->citationModel->getCitationById($bookId)), true);

		if (empty($getCitation)) {
			echo json_encode(["success" => false, "error" => "No Citation found with the Id identifier"]);
			return;
		}
				
		try {
			$updateResult = $this->citationModel->delete($bookId); // Pass the condition here

			if ($updateResult !== false) {
				echo json_encode(["success" => true, "message" => "Citation deleted successfully"]);
			} else {
				$error = $this->citationModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} catch (Exception $e) {
			echo json_encode(["success" => false, "error" => $e->getMessage()]);
		}

	
	}
	public function put($id)
	{
		$getCitation = $this->citationModel->getCitationById($id);

		if (!$getCitation) {
			echo json_encode(["success" => false, "error" => "Citation not found"]);
			return;
		}
		$name = isset($_POST['citation_name']) && $_POST['citation_name'] !== '' ? $_POST['citation_name'] : $getCitation[0]->citation_name;


		$newData = [
			"citation_name" => $name,
			"citation_id" => $id,
		];

		
		try{
			$updateResult = $this->citationModel->updateCitation($newData, $id);
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
}
