<?php

/**
 * FMVC - File manager 
 *
 * DOCUMENTATION
 *	1. Init the File class and pass the location to be uploaded
 *  2. (Optional) edit initialize public variables
 *  3. Call the check method passing the array of files for `CHECKING`
 *  4. Call the upload method passing the array of files for `UPLOADING`
 */
class File
{

	private $DEFAULT_location = "";
	public $result = false;
	public $files = [];

	/**
	 * File constructor
	 * @param `location` setup the default location for the upload
	 **/
	public function __construct($location = null)
	{

		// Set the active location of the uploading files
		if ($location != null) {
			$this->DEFAULT_location = $location;
		} else {
			$projectRoot = dirname(__DIR__); // Assuming this file is in a subdirectory of your project
			$this->DEFAULT_location = $projectRoot . "/public/assets/images/";

			// Create the directory if it doesn't exist
			if (!is_dir($this->DEFAULT_location)) {
				mkdir($this->DEFAULT_location, 0777, true);
			}
		}
	}

	/**
	 * Error Checking then handle
	 * @param array `files` contains all the files
	 * @param string `file_name` contain the id or the key of the said image
	 * @param boolean $upload
	 * @return array `array` contains all the returned parameters
	 **/
	public function handle($files, $file_name = "", $upload = false)
	{

		# Organize files of the multiple uploads from the $_FILES
		$allowed_type = ["png", "jpeg", "jpg", "docx", "pdf", "ppt"];

		# Organize `multiple` uploads
		if (is_array($files['name'])) {
			$files = $this->organize($files);

			# Organize `single` upload
		} else {
			// Store, reset, and update
			$temp = $files;
			$files = [];
			array_push($files, $temp);
		}

		# Return if no value
		if ($files[0]['name'] == '') {
			return "No file is uploaded";
		}

		for ($i = 0, $l = count($files); $i < $l; $i++) {
			# Check for the Extension name
			$type = explode('.', $files[$i]['name']);
			$type = strtolower(end($type));

			if (!in_array($type, $allowed_type)) {
				return "Invalid file type";
			}

			# Check for error
			if ($files[$i]['error'] == 1) {
				return "Corrupted file";
			}

			# Generate the actual name using the date
			$date = date('YmdHis');
			$actual_name = $file_name . "_" . $date . "." . $type;

			# Merge the files data and the actual name array
			$files[$i] = array_merge($files[$i], ["actual_name" => $actual_name]);
		}

		# Check for upload, or return `true` if no error
		if ($upload) {
			$this->files = $files;
			return $this->upload($files);
		} else {
			$this->files = $files;
			return $files;
		}
	}

	/**
	 * Organize the files
	 * @param array $files files to organize
	 * @return array
	 **/
	public function organize($files)
	{

		// Returning array
		$returning = [];

		// Loop every file
		for ($i = 0, $l = count($files['name']); $i < $l; $i++) {
			$inner = [
				'name' => $files['name'][$i],
				'type' => $files['type'][$i],
				'size' => $files['size'][$i],
				'tmp_name' => $files['tmp_name'][$i],
				'error' => $files['error'][$i]
			];

			array_push($returning, $inner);
		}

		// Return the new data
		return $returning;
	}

	/** 
	 * File uploading 
	 * @param `files` contains all the files
	 * @return bool
	 **/
	/**
	 * File uploading 
	 * @param array $files Contains all the files
	 * @return bool
	 */
	public function upload($files)
	{
		// Upload every image
		for ($i = 0, $l = count($files); $i < $l; $i++) {
			$sourcePath = $files[$i]['tmp_name'];
			$destinationPath = $this->DEFAULT_location . $files[$i]['actual_name'];

			// Check if the source file exists
			if (!file_exists($sourcePath)) {
				$this->result = false;
				return false;
			}

			// Check if the destination directory exists, create it if not
			$destinationDirectory = dirname($destinationPath);
			if (!is_dir($destinationDirectory)) {
				mkdir($destinationDirectory, 0777, true);
			}

			// Move to the upload folder
			$success = move_uploaded_file($sourcePath, $destinationPath);

			if (!$success) {
				$this->result = false;
				return false;
			}
		}

		// Return flag
		$this->result = true;
		return true;
	}
}
