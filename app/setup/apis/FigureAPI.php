<?php


class FigureAPI extends Api
{
	private $figureModel;
	function __construct()
	{
		$this->figureModel = $this->model("Figure");
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

		$book = json_decode(json_encode($this->figureModel->getFigureById($bookId)), false);

		if ($book !== false) {
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $book]);
		} else {
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => "error"]);
		}
	}

	public function generateImageName($originalName)
	{
		$path = "assets/images/";
		$date = date('YmdHis');
		$uniqueName = "{$path}book_{$originalName}.jpg";
		return $uniqueName;
	}
	public function post($request)
	{
		// Check if the request is an array and contains the required 'book_id'
		if (is_array($request) && isset($request['book_id'])) {
			$book_id = $request['book_id'];
		} else {
			echo json_encode(["success" => false, "error" => "Book ID not defined"]);
			return;
		}
	
		// Check if 'figure_id' is set in the POST request
		if (isset($_POST['figure_id'])) {
			$figure_name = $_POST['figure_name'] ?? '';
			$figure_id = $_POST['figure_id'];
			$figure_image = $_POST['figure_image'];
			$chapter_id = $_POST['chapter_id'] ?? null; // Use null coalescing operator for optional fields
	
			// Process the figure image if it's in the expected format
			if (strpos($figure_image, 'data:image') !== false) {
				$imageData = explode(',', $figure_image);
				$image = base64_decode($imageData[1]);
				$fileName = $this->generateImageName($figure_id);
				$filePath = $fileName;
				file_put_contents($filePath, $image);
				$figure_image = $fileName;
	
				if (!empty($figure_name)) {
					// Insert data into the model
					$result = $this->figureModel->insert([
						"figure_name" => $figure_name,
						"figure_id" => $figure_id,
						"book_id" => $book_id,
						"figure_image" => $figure_image,
						"chapter_id" => $chapter_id
					]);
	
					// Return the appropriate response
					header('Content-Type: application/json');
					if ($result !== false) {
						echo json_encode(["success" => true, "data" => $result]);
					} else {
						$error = $this->figureModel->getError();
						echo json_encode(["success" => false, "error" => $error]);
					}
				} else {
					echo json_encode(["success" => false, "error" => "Image file not uploaded or processed correctly"]);
				}
			} else {
				echo json_encode(["success" => false, "error" => "Figure Image Upload error"]);
			}
		} else {
			echo json_encode(["success" => false, "error" => "Figure Id not defined"]);
		}
	}

	public function put($id)
	{
		$getFigure = $this->figureModel->getFigureById($id);

		if (!$getFigure) {
			echo json_encode(["success" => false, "error" => "Figure not found"]);
			return;
		}
		$name = isset($_POST['figure_name']) && $_POST['figure_name'] !== '' ? $_POST['figure_name'] : $getFigure[0]->figure_name;
		$image = isset($_POST['figure_image']) && $_POST['figure_image'] !== '' ? $_POST['figure_image'] : $getFigure[0]->figure_image;


		$newData = [
			"figure_name" => $name,
			"figure_image" => $image,
			"figure_id" => $id,
		];


		try {
			$updateResult = $this->figureModel->updateFigure($newData, $id);
			if ($updateResult !== false) {
				echo json_encode(["success" => true, "data" => $updateResult]);
			} else {
				$error = $this->figureModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} catch (Exception $e) {
			echo json_encode(["success" => false, "error" => $e->getMessage()]);
		}
	}

	public function delete($bookId)
	{
		$getFigure = json_decode(json_encode($this->figureModel->getFigureById($bookId)), true);

		
		$image = $getFigure[0]->figure_image;
		$filePath =  $image;
		if (file_exists($filePath)) {
			if (unlink($filePath)) {
			} else {
				echo json_encode(["success" => false, "error" => "Failed to delete image file"]);
			}
		}
		if (empty($getFigure)) {
			echo json_encode(["success" => false, "error" => "No figure found with the Id identifier"]);
			return;
		}

		try {
			$updateResult = $this->figureModel->delete($bookId); // Pass the condition here

			if ($updateResult !== false) {
				echo json_encode(["success" => true, "message" => "figure deleted successfully"]);
			} else {
				$error = $this->figureModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} catch (Exception $e) {
			echo json_encode(["success" => false, "error" => $e->getMessage()]);
		}
	}
}
