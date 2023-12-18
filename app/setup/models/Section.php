<?php 

	class Section extends Model{
		
		function __construct(){
			$this->table_name = "Sections";
		    $this->table_columns = ["id", "section_title",  "content","chapter_id","book_id"];
		}

		public function getSection($data)
		{
			// var_dump($data);
			// die();
			$bookId = isset($data['id']) ? $data['id'] : 1;
		
			# Generate array
			$sql = "SELECT * FROM " . $this->table_name . " WHERE book_id = :book_id";
		
			# Pass query
			$this->query($sql);
		
			# Bind parameter
			$this->bind("book_id", $bookId);
		
			# Save data to global data holder object
			$this->data = $this->resultSet();
			$this->exist = ($this->rowCount() > 0);
		
			return $this->data;
		}
	}
// 	public function getById($bookId)
// {
// 	$book = json_decode(json_encode($this->taskModel->get($bookId)), true);
// 	$chapters = json_decode(json_encode($this->chapterModel->getChapter($bookId)), true);
// 	$sections = json_decode(json_encode($this->sectionModel->getSection($bookId)), true);
	
//     if ($book !== false && $chapters !== false && $sections !== false) {
//         $combinedChapters = [];

//         foreach ($chapters as $chapter) {
//             $combinedChapter = $chapter;
//             $combinedChapter['sections'] = [];

//             foreach ($sections as $section) {
//                 if ($section['chapter_id'] === $chapter['id']) {
//                     $combinedChapter['sections'][] = $section;
//                 }
//             }

//             $combinedChapters[] = $combinedChapter;
//         }

//         // Combine all data into a single array
//         $combinedData = [
//             "book" => $book,
//             "chapters" => $combinedChapters,
//         ];

//         // Return the combined data as JSON
//         header('Content-Type: application/json');
//         echo json_encode(["success" => true, "data" => $combinedData]);
//     } else {
//         // If either the book, chapter, or section is not found, return an error
//         header('Content-Type: application/json');
//         echo json_encode(["success" => false, "error" => "error"]);
//     }
// }
?>