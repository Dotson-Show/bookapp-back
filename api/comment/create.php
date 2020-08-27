<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Origin,Content-Type,Access-Control-Allow-Methods, Authorization,x-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Book.php';
include_once '../../models/Comment.php';

//Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

//Instantiate book object
$comment = new Comment($db);

// //Get the single book id
// $book_id = isset($_GET['id']) ? $_GET['id'] : die();

// Get the raw data
$data = json_decode(file_get_contents("php://input"));

$comment->book_id = $data->book_id;
$comment->title = $data->title; 
$comment->content = $data->content;

// Create the post
if ($comment->createComment($comment->book_id, $comment->title, $comment->content)) {
    echo json_encode(
        array('message' => 'Comment Posted')
    );
} else {
    echo json_encode(
        array('message' => 'Comment Not Posted')
    );
}