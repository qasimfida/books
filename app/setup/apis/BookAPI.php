<?php
error_reporting(0);
ini_set('display_errors', 0);
/**
 * Test class
 */
class BookAPI extends Api
{
	private $bookModel;
	private $chapterModel;
	private $sectionModel;
	private $citationModel;
	private $figureModel;

	function __construct()
	{
		$this->bookModel = $this->model("Book");
		$this->chapterModel = $this->model("Chapter");
		$this->sectionModel = $this->model("Section");
		$this->citationModel = $this->model("Citation");
		$this->figureModel = $this->model("Figure");
	}
	public function get()
	{
		// Check if book_id is set in the request
		$books = $this->bookModel->selectAll();


		// Check if any books were found
		if ($books !== false) {
			// Return the books as JSON
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $books]);
		} else {
			// If no books found, return an error
			$error = $this->bookModel->getError();
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => $error]);
		}
	}
	public function getById($bookId)
	{
		$book = json_decode(json_encode($this->bookModel->get($bookId)), true);
		$chapters = json_decode(json_encode($this->chapterModel->getChapter($bookId)), true);
		$sections = json_decode(json_encode($this->sectionModel->getSection($bookId)), true);
		$citations = json_decode(json_encode($this->citationModel->getCitation($bookId)), true);
		$figures = json_decode(json_encode($this->figureModel->getFigure($bookId)), true);


		if ($book !== false && $chapters !== false && $sections !== false) {
			// Combine both data sets into a single array
			$combinedChapters = [];

			foreach ($chapters as $chapter) {
				$combinedChapter = $chapter;
				$combinedChapter['sections'] = [];

				foreach ($sections as $section) {
					if ($section['chapter_id'] === $chapter['id']) {
						$combinedChapter['sections'][] = $section;
					}
				}

				$combinedChapters[] = $combinedChapter;
			}
			// Combine all data into a single array
			$combinedData = [
				"book" => $book,
				"chapters" => $combinedChapters,
				"citations" => $citations,
				"figure" => $figures

			];

			// Return the combined data as JSON
			header('Content-Type: application/json');
			echo json_encode(["success" => true, "data" => $combinedData]);
		} else {
			header('Content-Type: application/json');
			echo json_encode(["success" => false, "error" => "error"]);
		}
	}

	public function generateUniqueFileName($originalName)
	{
		$path = "public/assets/images/";
		$extension = pathinfo($originalName, PATHINFO_EXTENSION);
		$date = date('YmdHis');
		$uniqueName = "{$path}book_{$date}.{$extension}";
		return $uniqueName;
	}

	public function post()
	{

		if (isset($_POST['book_title'], $_POST['author'])) {
			$book_title = $_POST['book_title'];
			$author = $_POST['author'];
			$description = isset($_POST['description']) ? $_POST['description'] : '';
			// $fileHandler = new File("assets/images/");
			// $fileName = @$_FILES['image']['name'];
			$image = $_POST['image'];

			if (strpos($image, 'data:image') !== false) {

			
				$imageData = explode(',', $image);
				$image = base64_decode($imageData[1]);

				// Generate a unique file name
				$fileName = $this->generateImageName("book_");

				$filePath =  $fileName;
				// Save the image to the specified path
				file_put_contents($filePath, $image);
				$image = $fileName;


				if (!empty($image)) {
					$result = $this->bookModel->insert([
						"book_title" => $book_title,
						"author" => $author,
						"description" => $description,
						"image" => $image
					]);

					header('Content-Type: application/json');

					if ($result !== false) {
						echo json_encode(["success" => true, "data" => $result]);
						return;
					} else {
						$error = $this->bookModel->getError();
						echo json_encode(["success" => false, "error" => $error]);
					}
				} else {
					echo json_encode(["success" => false, "error" => "Image file not uploaded or processed correctly"]);
				}
			} else {
				// $error = is_string(imag) ? $uploadedFiles : "File upload failed";
				echo json_encode(["success" => false, "error" => "File upload failed"]);
			}
		}
	}
	public function generateImageName($originalName)
	{
		$path = "assets/images/";
		$date = date('YmdHis');
		$uniqueName = "{$path}book_{$date}.jpg";
		return $uniqueName;
	}

	function base64_to_jpeg($base64_string, $output_file)
	{
		$ifp = fopen($output_file, 'wb');
		$data = explode(',', $base64_string);
		fwrite($ifp, base64_decode($data[1]));
		fclose($ifp);
		return $output_file;
	}

		public function put($id)
		{
			$existingData = $this->bookModel->getById($id);

			if (!$existingData) {
				echo json_encode(["success" => false, "error" => "Book not found"]);
				return;
			}

			$book_title = isset($_POST['book_title']) && $_POST['book_title'] !== '' ? $_POST['book_title'] : $existingData[0]->book_title;
			$author = isset($_POST['author']) && $_POST['author'] !== '' ? $_POST['author'] : $existingData[0]->author;
			$description = isset($_POST['description']) && $_POST['description'] !== '' ? $_POST['description'] : $existingData[0]->description;
			$image = $_POST['image'];

			// Check if the image is in base64 format
			if (strpos($image, 'data:image') !== false) {
				$imageData = explode(',', $image);
				$image = base64_decode($imageData[1]);

				// Generate a unique file name
				$fileName = $this->generateImageName("book_");

				$filePath =  $fileName;
				// Save the image to the specified path
				file_put_contents($filePath, $image);
				$image = $fileName;
			} else {
				// If the image is not in base64 format, use the existing value
				$image = $existingData[0]->image;
			}

			$newData = [
				"book_title" => $book_title,
				"author" => $author,
				"description" => $description,
				"image" => $image
			];

			$called_id = ['id' => $id];

			$updateResult = $this->bookModel->update($newData, $called_id);

			if ($updateResult !== false) {
				echo json_encode(["success" => true, "data" => $updateResult]);
				return;
			} else {
				$error = $this->bookModel->getError();
				echo json_encode(["success" => false, "error" => $error]);
			}
		}







	public function delete($bookId)
	{


		$booksToDelete = json_decode(json_encode($this->bookModel->deleteByBookId($bookId)), true);
		$chapterModel = json_decode(json_encode($this->chapterModel->deleteChapter($bookId)), true);
		$sectionModel = json_decode(json_encode($this->sectionModel->deleteSection($bookId)), true);
		$citationModel = json_decode(json_encode($this->citationModel->deleteCitation($bookId)), true);
		$figureModel = json_decode(json_encode($this->figureModel->deleteFigure($bookId)), true);




		if (empty($booksToDelete || $chapterModel || $sectionModel || $citationModel || $figureModel)) {
			echo json_encode(["success" => false, "error" => "No books found with the specified identifier"]);
			return;
		}

		echo json_encode(["success" => true, "message" => "All books with the specified identifier deleted successfully"]);
	}

	public function foobar()
	{
		$this->json([
			"message" => "You're now accessing this method by DEFINED_METHOD."
		]);
	}
}
