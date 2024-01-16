<?php


class FigureAPI extends Api
{
	private $figureModel;
	function __construct()
	{
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
		$book = json_decode(json_encode($this->figureModel->getFigureById($bookId)), false);

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
		if (isset($_POST['figure_id'])) {
			$figure_name = $_POST['figure_name'];
			$figure_id = $_POST['figure_id'];
			$figure_image = $_POST['figure_image'];

			$result = $this->figureModel->insert([
				"figure_name" => $figure_name,
				"figure_id" => $figure_id,
				"book_id" => $book_id,
				"figure_image" => $figure_image
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

		
		try{
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

		if (empty($getFigure)) {
			echo json_encode(["success" => false, "error" => "No chapter found with the Id identifier"]);
			return;
		}

		try {
			$updateResult = $this->figureModel->delete($bookId); // Pass the condition here

			if ($updateResult !== false) {
				echo json_encode(["success" => true, "message" => "Chapter deleted successfully"]);
			} else {
				$error = $this->figureModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		} catch (Exception $e) {
			echo json_encode(["success" => false, "error" => $e->getMessage()]);
		}
	}
}
