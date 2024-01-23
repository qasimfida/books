<?php

	/**
	 * Test class
	 */
	class SectionAPI extends Api{
		private $sectionModel;
		private $figureModel;
		private $citationModel;
		function __construct(){
			$this->sectionModel = $this->model("Section");
			$this->citationModel = $this->model("Citation");
			$this->figureModel = $this->model("Figure");
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
		
		public function getById($id)
		{
			$book = json_decode(json_encode($this->sectionModel->getSection($id)), true);
			
			if ($book !== false) {
				header('Content-Type: application/json');
				echo json_encode(["success" => true, "data" => $book]);
			} else {
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
					"book_id" => $book_id,

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
		

		public function put($id)
		{
		
			$getSections = $this->sectionModel->getSectionById($id);
			if (!$getSections) {
				echo json_encode(["success" => false, "error" => "Book not found"]);
				return;
			}

			$section_title = isset($_POST['section_title']) && $_POST['section_title'] !== '' ? $_POST['section_title'] : $getSections[0]->section_title;
			$content = isset($_POST['content']) && $_POST['content'] !== '' ? $_POST['content'] : $getSections[0]->content;

			
			$newData = [
				"section_title" => $section_title,
				"content" => $content,

			];

			$called_id = ['id' => $id];
		
			try {
				$updateResult = $this->sectionModel->update($newData, $called_id);
		
				if ($updateResult !== false) {
					echo json_encode(["success" => true, "data" => $updateResult]);
				} else {
					$error = $this->sectionModel->getError();
					echo json_encode(["success" => false, "error" => $error]);
				}
			} catch (Exception $e) {
				echo json_encode(["success" => false, "error" => $e->getMessage()]);
			}
		}

		public function delete($bookId)
		{
			$getSection = json_decode(json_encode($this->sectionModel->getSectionById($bookId)), true);
			$deleteFigure = json_decode(json_encode($this->figureModel->deleteFigureByType($bookId, $type="section")), true);
			$deleteCitation = json_decode(json_encode($this->citationModel->deleteCitationByType($bookId, $type="section")), true);
	
			if (empty($getSection)) {
				echo json_encode(["success" => false, "error" => "No Section found with the Id identifier"]);
				return;
			}
					
			try {
				$updateResult = $this->sectionModel->delete($bookId); // Pass the condition here

				if ($updateResult !== false || $deleteCitation || $deleteFigure ) {
					echo json_encode(["success" => true, "message" => "Section deleted successfully"]);
				} else {
					$error = $this->sectionModel->getError();
					echo json_encode(["success" => false, "error" => $error]);
				}
			} catch (Exception $e) {
				echo json_encode(["success" => false, "error" => $e->getMessage()]);
			}
	
		
		}
		

	}

?>