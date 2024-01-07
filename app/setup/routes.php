<?php
	
	# Set up routes
	$routing = new Routing();

	require_once('Middleware.php');
	Middleware::handle();

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
	$routing->get("chapters/:id", "SectionAPI::getById");
	$routing->get("chapters", "ChapterAPI::get");
	$routing->get("chapter/:chapter_id", "ChapterAPI::getById");
	$routing->put("chapter/:id", "ChapterAPI::put");
	$routing->delete("chapter/:id", "ChapterAPI::delete");
	
	//section
	$routing->post("book/:book_id/chapter/:chapter_id", "SectionAPI::post");
	$routing->get("sections/:id", "SectionAPI::getById");
	$routing->get("sections", "SectionAPI::get");
	$routing->get("section/:section_id", "SectionAPI::getById");
	$routing->put("section/:id", "SectionAPIAPI::put");
	$routing->delete("/:id", "SectionAPIAPI::delete");

	//citation
	$routing->post("citation/:book_id", "CitationAPI::post");
	$routing->get("citations/:id", "CitationAPI::getById");
	$routing->get("citations", "CitationAPI::get");
	$routing->get("citation/:citation_id", "CitationAPI::getById");
	$routing->put("/:id", "CitationAPIAPI::put");
	$routing->delete("/:id", "CitationAPIAPI::delete");
	
	//figure
	$routing->post("figure/:book_id", "FigureAPI::post");
	$routing->get("figures/:id", "FigureAPI::getById");
	$routing->get("figures", "FigureAPI::get");
	$routing->get("figure/:figure_id", "FigureAPI::getById");
	$routing->put("figure/:id", "FigureAPIAPI::put");
	$routing->delete("figure/:id", "FigureAPIAPI::delete");



	// Adding CORS headers to all routes
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
	header("Access-Control-Allow-Headers: Content-Type");
	header("Access-Control-Allow-Credentials: true");


?>