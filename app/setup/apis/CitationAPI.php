<?php

/**
 * Test class
 */
class CitationAPI extends Api
{
	private $citationModal;
	private $bookModel;
	private $chapterModel;
	private $sectionModel;
	function __construct()
	{
		$this->citationModal = $this->model("Citation");
		$this->bookModel = $this->model("Book");
		$this->chapterModel = $this->model("Chapter");
		$this->sectionModel = $this->model("Section");
	}
	public function get()
	{
		// Check if book_id is set in the request
		$books = $this->citationModal->selectAll();


		// Check if any books were found
		if ($books !== false) {
			// Return the books as JSON
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $books]);
		} else {
			// If no books found, return an error
			$error = $this->citationModal->getError();
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => $error]);
		}
	}
	
	public function getById($bookId)
	{
	
		$book = json_decode(json_encode($this->citationModal->getCitation($bookId)), false);
		

		if ($book !== false) {
			// Combine both data sets into a single array
			$combinedChapters = [];

			// Combine all data into a single array
			$combinedData = [
				"citations" => $book,
			];

			// Return the combined data as JSON
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $combinedData]);
		} else {
			// If either the book or chapter is not found, return an error
			// $error = $book !== false ? $this->bookModel->getError() : $this->chapterModel->getError();

			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => "error"]);
		}
	}




	public function post($request){
		if (is_array($request)) {
			$book_id = $request['book_id'];
		}

		if(isset($_POST['citation_name']) && isset($_POST['citation_id'])) {
			
			$citation_name = $_POST['citation_name'];
			$citation_id = $_POST['citation_id'];
			
			$result = $this->citationModal->insert([
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
			"message" => "You're now accessing this method by DEFINED_METHOD."
		]);
	}
}
