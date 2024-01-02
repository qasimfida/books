<?php

	/**
	 * Test class
	 */
	class SectionAPI extends Api{
		private $sectionModel;
		function __construct(){
			$this->sectionModel = $this->model("section");
		}
		public function get()
		{
			// Check if book_id is set in the request
			$books = $this->sectionModel->selectAll();
	
	
			// Check if any books were found
			if ($books !== false) {
				// Return the books as JSON
				header('Content-Type: application/json');
				echo json_encode(["success" => true, "data" => $books]);
			} else {
				// If no books found, return an error
				$error = $this->sectionModel->getError();
				header('Content-Type: application/json');
				echo json_encode(["success" => false, "error" => $error]);
			}
		}
		
		public function getById($bookId)
		{
		
			$book = json_decode(json_encode($this->sectionModel->getSection($bookId)), false);
			
	
			if ($book !== false) {
				// Combine both data sets into a single array
				$combinedChapters = [];
	
				// Combine all data into a single array
				$combinedData = [
					"sections" => $book,
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
                $chapter_id = $request['chapter_id'];
				$book_id = $request['book_id'];
            } elseif (is_object($request) && method_exists($request, 'getParam')) {
                $chapter_id = $request->getParam('chapter_id');
				$book_id = $request->getParam('book_id');

            }

			if(isset($_POST['section_title'])) {
				
				$section_title = $_POST['section_title'];
				$content = $_POST['content'];

				$result = $this->sectionModel->insert([
					"section_title" => $section_title,
                    "content" => $content,
					"chapter_id" => $chapter_id,
					"book_id" => $book_id

				]);
		
				header('Content-Type: application/json');
		
				if ($result !== false) {
					echo json_encode(["success" => true, "data" => $result]);
				} else {
                    echo json_encode(["failed" => false, "error" => "cha 'section_title' or 'content' in the request"]);

				}
			} else {
				// Show an error if 'section_title' or 'content' keys are missing
				echo json_encode(["success" => false, "error" => "Missing 'section_title' or 'content' in the request"]);
			}
		}
		

		public function put(){
			$this->json([
				"message" => "Updating something using " . $_SERVER['REQUEST_METHOD'] . " request?",
				"request" => $this->request
			]);
		}

		public function delete(){
			$this->json([
				"message" => "You're now scrubbing something using " . $_SERVER["REQUEST_METHOD"] . " request"
			]);
		}

		public function foobar(){
			$this->json([
				"message" => "You're now accessing this method by DEFINED_METHOD."
			]);
		}

	}

?>