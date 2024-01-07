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
		$book = json_decode(json_encode($this->figureModel->getFigure($bookId)), false);

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
			$result = $this->figureModel->insert([
				"figure_name" => $figure_name,
				"figure_id" => $figure_id,
				"book_id" => $book_id
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
}
