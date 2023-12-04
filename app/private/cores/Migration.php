<?php

class Model {
    protected $connection;
	public function __construct($host, $user, $pass, $dbName)
    {
        $this->connection = new mysqli($host, $user, $pass, $dbName);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function runMigration($sql)
    {
        $result = $this->connection->query($sql);

        if ($result === TRUE) {
            echo "Table created successfully";
        } else {
            echo "Error creating table: " . $this->connection->error;
        }

        return $result;
    }

    public function closeConnection()
    {
        $this->connection->close();
    }
}
class Book extends Model {
    public function createBooksTable() {
        $sql = "CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            book_title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255)
        )";
        $this->runMigration($sql);
    }
}

class Chapters extends Model {
    public function createChaptersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS chapters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            chapter_name VARCHAR(255) NOT NULL,
            book_id INT,
            FOREIGN KEY (book_id) REFERENCES books(book_id)
        )";
        $this->runMigration($sql);
    }
}

class Sections extends Model {
    public function createSectionsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS sections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            section_title VARCHAR(255) NOT NULL,
            content TEXT,
            chapter_id INT,
            FOREIGN KEY (chapter_id) REFERENCES chapters(id)
        )";
        $this->runMigration($sql);
    }
}

// Database configuration
// $clas = new Database();
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "books";

// Create instances of the Model class
$bookModel = new Book($dbHost, $dbUser, $dbPass, $dbName);
$chaptersModel = new Chapters($dbHost, $dbUser, $dbPass, $dbName);
$sectionsModel = new Sections($dbHost, $dbUser, $dbPass, $dbName);

// Run migrations
$bookModel->createBooksTable();
$chaptersModel->createChaptersTable();
$sectionsModel->createSectionsTable();

// Output result
if (!$bookModel->queryError && !$chaptersModel->queryError && !$sectionsModel->queryError) {
    echo "Tables created successfully: books, chapters, sections";
} else {
    echo "Error creating tables";
}

// Close database connections if necessary
$bookModel->closeConnection();
$chaptersModel->closeConnection();
$sectionsModel->closeConnection();
?>
