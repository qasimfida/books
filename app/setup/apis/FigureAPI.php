<?php

	
	class FigureAPI extends Api{
		private $figureModel;
		function __construct(){
			$this->figureModel = $this->model("figure");
		}
		public function get()
		{
			$books = $this->figureModel->selectAll();

			if ($books !== false) {
				header('Content-Type: application/json');
				echo json_encode(["success" => true, "data" => $books]);
			} else {
				$error = $this->figureModel->getError();
				header('Content-Type: application/json');
				echo json_encode(["success" => false, "error" => $error]);
			}
		}
		
		public function getById($bookId)
		{
		
			$book = json_decode(json_encode($this->figureModel->getFigure($bookId)), false);
			
	
			if ($book !== false) {
				// Combine both data sets into a single array
				$combinedChapters = [];
	
				// Combine all data into a single array
				$combinedData = [
					"figures" => $book,
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
		public function generateUniqueFileName($originalName)
		{
			$path = "public/assets/images/";
			$extension = pathinfo($originalName, PATHINFO_EXTENSION);
			$date = date('YmdHis');
			$uniqueName = "{$path}figure_{$date}.{$extension}";
			return $uniqueName;
		}

		public function post($request){
			
			if (is_array($request)) {
				$book_id = $request['book_id'];
			}
	
			if(isset($_POST['figure_name']) && isset($_POST['figure_id'])) {

				$figure_name = $_POST['figure_name'];
				$figure_id = $_POST['figure_id'];

				$fileHandler = new File("assets/images/");
				$fileName = @$_FILES['figure_image']['name'];
	
	
				$uploadedFiles = @$fileHandler->handle($_FILES['figure_image'], "figure", true);
				if ($fileHandler->result && !empty($uploadedFiles)) {
					$image = $this->generateUniqueFileName($fileName);
	
	
					if (!empty($image)) {
						$result = $this->figureModel->insert([
							"figure_name" => $figure_name,
							"figure_id" => $figure_id,
							"figure_image" => $image,
							"book_id"=> $book_id
						]);
	
						header('Content-Type: application/json');
	
						if ($result !== false) {
							echo json_encode(["success" => true, "data" => $result]);
							return; 
						} else {
							$error = $this->figureModel->getError();
							echo json_encode(["success" => false, "error" => $error]);
						}
					} else {
						echo json_encode(["success" => false, "error" => "Image file not uploaded or processed correctly"]);
					}
				} else {
					$error = is_string($uploadedFiles) ? $uploadedFiles : "File upload failed";
					echo json_encode(["success" => false, "error" => $error]);
				}
			}
		}
	}


?>