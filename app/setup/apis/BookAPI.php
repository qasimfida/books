<?php

	/**
	 * Test class
	 */
	class BookAPI extends Api{
		function __construct(){
			$this->taskModel = $this->model("Book");
		}
		public function get(){
			$this->json([
				"message" => "You get this using " . $_SERVER['REQUEST_METHOD'] . " request"
			]);
		}

		// public function post(){
		// 	$title = $_POST['title'];
		// 	$author = $_POST['content'];
		
		// 	$result = $this->taskModel->insert([
		// 		"title" => $title,
		// 		"content" => $author
		// 	]);
		
		// 	header('Content-Type: application/json');
		
		// 	if ($result !== false) {
		// 		// Insertion successful
		// 		echo json_encode(["success" => true, "data" => $result]);
		// 	} else {
		// 		// Insertion failed
		// 		$error = $this->taskModel->getError(); // Assuming your model has a method to get the last error
		// 		echo json_encode(["success" => false, "error" => $error]);
		// 	}
		// }
		
		public function post(){
			
			// Check if the 'title' and 'content' keys exist in the $_POST array
			if(isset($_POST['title'], $_POST['content'])) {
				$title = $_POST['title'];
				$author = $_POST['content'];
		
				$result = $this->taskModel->insert([
					"title" => $title,
					"content" => $author
				]);
		
				header('Content-Type: application/json');
		
				if ($result !== false) {
					// Insertion successful
					echo json_encode(["success" => true, "data" => $result]);
				} else {
					// Insertion failed
					$error = $this->taskModel->getError(); // Assuming your model has a method to get the last error
					echo json_encode(["success" => false, "error" => $error]);
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