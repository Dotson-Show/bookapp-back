<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Book.php';
include_once '../../models/Comment.php';

//Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

//Instantiate comment object
$comment = new Comment($db);

//Instantiate book object
$book = new Book($db);
$bookResults = $book->read();
$rowCount = $bookResults->rowCount();

//Check if any book
if ($rowCount > 0) {
    $booksArray = array();
    $booksArray['data'] = array();

    while ($row = $bookResults->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $book_id = $id;
        $commentCount = $comment->getCommentCount($book_id); //CGet the comment count from Comment

        $book_item = array(
            'id' => $id,
            'name' => $name,
            'isbn' => $isbn,
            'authors' => $authors,
            'numOfPages' => $numOfPages,
            'commentCount' => $commentCount,
            'comments' => $comment->getComments($book_id), //Get a list of comments from Comments in reversed chronological order
            // 'characters' => $characters,
            'yearPublished' => $yearPublished
        );

        //Push to Data array
        array_push($booksArray['data'], $book_item);
    }

    //Turn to json and output
    echo json_encode($booksArray);
} else {
    //No Books
    echo json_encode(
        array('message' => 'No Books Found')
    );
}