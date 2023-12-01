<?php

	/**
	 * Test class
	 */
	class ChapterAPI extends Api{
		function __construct(){
			$this->taskModel = $this->model("Chapter");
		}
		public function get(){
			$this->json([
				"message" => "You get this using " . $_SERVER['REQUEST_METHOD'] . " request"
			]);
		}

		public function post($request){
            if (is_array($request)) {
                $book_id = $request['book_id'];
            } elseif (is_object($request) && method_exists($request, 'getParam')) {
                $book_id = $request->getParam('book_id');
            }

			if(isset($_POST['chapter_name'])) {
				
				$author = $_POST['chapter_name'];
				$result = $this->taskModel->insert([
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
				"id" => "The id is `$this->id`",
				"message" => "You're now accessing this method by DEFINED_METHOD."
			]);
		}

	}

?>