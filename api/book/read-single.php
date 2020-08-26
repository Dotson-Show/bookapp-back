<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Book.php';

//Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

//Instantiate book object
$book = new Book($db);

$book_id = isset($_GET['id']) ? $_GET['id'] : die();

$bookResults = $book->readSingle($book_id);
$rowCount = $bookResults->rowCount();

//Check if any book
if ($rowCount == 1) {
    $booksArray = array();
    $booksArray['data'] = array();

    $row = $bookResults->fetch(PDO::FETCH_ASSOC);
    extract($row);

    $book_item = array(
        'id' => $id,
        'name' => $name,
        'isbn' => $isbn,
        'authors' => $authors,
        'numOfPages' => $numOfPages,
        // 'comments' => $comments,
        // 'characters' => $characters,
        'yearPublished' => $yearPublished
    );

    //Push to Data array
    array_push($booksArray['data'], $book_item);

    //Turn to json and output
    echo json_encode($booksArray);
} else {
    //No Books
    echo json_encode(
        array('message' => 'No Books Found')
    );
}