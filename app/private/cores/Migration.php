<?php

class Migration {
    protected $connection;
    public $queryError;

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
            $this->queryError = false;

        } else {
            echo "Error creating table: " . $this->connection->error;
            $this->queryError = true;
        }

        return $result;
    }

    public function closeConnection()
    {
        $this->connection->close();
    }
}
class Books extends Migration {
    public function createTable() {
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

class Chapters extends Migration {
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS chapters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            chapter_name VARCHAR(255) NOT NULL,
            book_id INT,
            FOREIGN KEY (book_id) REFERENCES books(id)
        )";
        $this->runMigration($sql);
    }
}

class Sections extends Migration {
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS sections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            section_title VARCHAR(255) NOT NULL,
            content TEXT,
            chapter_id INT,
            FOREIGN KEY (chapter_id) REFERENCES chapters(id),
            book_id INT,
            FOREIGN KEY (book_id) REFERENCES books(id)
        )";
        $this->runMigration($sql);
    }
}

class Citation extends Migration {
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS citations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            citation_name VARCHAR(255) NOT NULL,
            citation_id VARCHAR(255) NOT NULL,
            book_id INT,
            chapter_id INT,
            FOREIGN KEY (book_id) REFERENCES books(id)
            -- FOREIGN KEY (chapter_id) REFERENCES chapters(id)
        )";
        $this->runMigration($sql);
    }
}
class Figure extends Migration {
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS Figures (
            id INT AUTO_INCREMENT PRIMARY KEY,
            figure_name VARCHAR(255) NOT NULL,
            figure_id VARCHAR(255) NOT NULL,
            figure_image VARCHAR(255),
            book_id INT,
            chapter_id INT,
            FOREIGN KEY (book_id) REFERENCES books(id)
            -- FOREIGN KEY (chapter_id) REFERENCES chapters(id)
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

$booksmigration = new Books($dbHost, $dbUser, $dbPass, $dbName);
$chaptersmigration = new Chapters($dbHost, $dbUser, $dbPass, $dbName);
$sectionsmigration = new Sections($dbHost, $dbUser, $dbPass, $dbName);
$citationmigration = new Citation($dbHost, $dbUser, $dbPass, $dbName);
$figuremigration = new Figure($dbHost, $dbUser, $dbPass, $dbName);

// Run migrations
$booksmigration->createTable();
$chaptersmigration->createTable();
$sectionsmigration->createTable();
$citationmigration->createTable();
$figuremigration->createTable();

// Output result
$tables = ['books', 'chapters', 'sections', 'citation', 'figure'];
$errorFlag = false;

foreach ($tables as $table) {
    $migration = new $table($dbHost, $dbUser, $dbPass, $dbName);
    if ($migration->queryError) {
        $errorFlag = true;
        echo "Error creating table: $table\n";
    }
    $migration->closeConnection();
}

if (!$errorFlag) {
    echo "Tables created successfully: " . implode(', ', $tables);
} else {
    echo "Error creating tables";
}
?>