<?php
	
	# Set up routes
	$routing = new Routing();

	require_once('Middleware.php');
	Middleware::handle();


	# Default
	$routing->get("/", "TestAPI::get");

	/**
	 * Auto routing `REQUEST_METHOD`
	 * This is equal to
	 * $route->get("url", 	"class::get");
	 * $route->post("url", 	"class::post");
	 * $route->put("url", 	"class::put");
	 * $route->delete("url", class::delete");
	 */
	$routing->auto("test", "TestAPI");

	/** With parameter/s URL */
	$routing->get("foobar/:id", "TestAPI::foobar");

	/** Authentication Test */
	$routing->post("auth/login", "AuthAPI::login");
	$routing->post("auth/register", "AuthAPI::register");
	$routing->post("auth/check", "AuthAPI::check");
	$routing->post("auth/logout", "AuthAPI::logout");
	
	//books
	$routing->post("books", "BookAPI::post");
	$routing->put("books/:id", "BookAPI::put");
	$routing->get("books", "BookAPI::get");
	$routing->get("books/:id", "BookAPI::getById");
	$routing->delete("book/:id", "BookAPI::delete");

	//chapter
	$routing->post("chapter/:book_id", "ChapterAPI::post");
	$routing->get("chapters", "ChapterAPI::get");
	$routing->get("chapter/:book_id", "ChapterAPI::getById");
	
	
	//section
	$routing->post("book/:book_id/chapter/:chapter_id", "SectionAPI::post");
	$routing->get("sections", "SectionAPI::get");
	$routing->get("section/:book_id", "SectionAPI::getById");

	//citation
	$routing->post("figure/:book_id", "CitationAPI::post");
	$routing->get("citations", "CitationAPI::get");
	$routing->get("citation/:book_id", "CitationAPI::getById");

	//figure
	$routing->post("figure/:book_id", "FigureAPI::post");
	$routing->get("figures", "FigureAPI::get");
	$routing->get("figures/:book_id", "FigureAPI::getById");




	// Adding CORS headers to all routes
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
	header("Access-Control-Allow-Headers: Content-Type");
	header("Access-Control-Allow-Credentials: true");


?>