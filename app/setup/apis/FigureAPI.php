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
	public function put($id)
		{
		
			$getSections = $this->figureModel->getFigureById($id);
			var_dump($getSections);
			if (!$getSections) {
				echo json_encode(["success" => false, "error" => "Figure not found"]);
				return;
			}

			$figure_name = isset($_POST['figure_name']) && $_POST['figure_name'] !== '' ? $_POST['figure_name'] : $getSections[0]->figure_name;
			$figure_id = isset($_POST['figure_id']) && $_POST['figure_id'] !== '' ? $_POST['figure_id'] : $getSections[0]->figure_id;

			
			$newData = [
				"figure_name" => $figure_name,
				"figure_id" => $figure_id,

			];

			$called_id = ['id' => $id];
		
			try {
				$updateResult = $this->figureModel->update($newData, $called_id);
		
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
