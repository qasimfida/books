<?php

# Set up routes
$routing = new Routing();

require_once('Middleware.php');
Middleware::handle();

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
$routing->put("section/:id", "SectionAPI::put");
$routing->delete("section/:id", "SectionAPI::delete");

//citation
$routing->post("citation/:book_id", "CitationAPI::post");//ok
$routing->get("citations/:id", "CitationAPI::getById");//ok this will be book_id
$routing->get("citations", "CitationAPI::get");//ok
$routing->get("citation/:citation_id", "CitationAPI::getById");//ok
$routing->put("citation/:citation_id", "CitationAPI::put");//ok
$routing->delete("citation/:citation_id", "CitationAPI::delete");//ok

//figure
$routing->post("figure/:book_id", "FigureAPI::post");//ok
$routing->get("figures/:id", "FigureAPI::getById");//ok
$routing->get("figures", "FigureAPI::get");//ok
$routing->get("figure/:figure_id", "FigureAPI::getById");//ok
$routing->put("figure/:figure_id", "FigureAPI::put");//ok
$routing->delete("figure/:figure_id", "FigureAPI::delete");//ok



// Adding CORS headers to all routes
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
