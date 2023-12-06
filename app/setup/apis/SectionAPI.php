<?php

	/**
	 * Test class
	 */
	class SectionAPI extends Api{
		function __construct(){
			$this->taskModel = $this->model("section");
		}
		public function get(){
			$this->json([
				"message" => "You get this using " . $_SERVER['REQUEST_METHOD'] . " request"
			]);
		}

		public function post($request){
            if (is_array($request)) {
                $chapter_id = $request['chapter_id'];
            } elseif (is_object($request) && method_exists($request, 'getParam')) {
                $chapter_id = $request->getParam('chapter_id');
            }

			if(isset($_POST['section_title'])) {
				
				$section_title = $_POST['section_title'];
				$content = $_POST['content'];

				$result = $this->taskModel->insert([
					"section_title" => $section_title,
                    "content" => $content,
					"chapter_id" => $chapter_id

				]);
		
				header('Content-Type: application/json');
		
				if ($result !== false) {
					// Insertion successful
					echo json_encode(["success" => true, "data" => $result]);
				} else {
					// Insertion failed
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
				"id" => "The id is `$this->id`",
				"message" => "You're now accessing this method by DEFINED_METHOD."
			]);
		}

	}

?>