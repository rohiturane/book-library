<?php
require('./dist/database.php');
$GLOBALS = array(
    'con' => database_connection()
);

class Books
{
    private $con;
    public function __construct() {
        global $GLOBALS;
        $this->con =& $GLOBALS['con'];
    }
    public function index()
    {
        $listOfBook=[];
        $books = mysqli_query($this->con, "SELECT * FROM `books`");
        while($res = mysqli_fetch_assoc($books)){
            $listOfBook[]=$res;
        }
        echo json_encode(['books'=>$listOfBook]);
    }
    public function store($params)
    {
        $validateData = ['title','author','isbn'];
        foreach($validateData as $validate)
        {
            if(!array_key_exists($validate, $params) && empty($params[$validate]))
            {
                $errors[$validate][] = $validate.' is required';
            }
        }
        if(!empty($errors)) {
            http_response_code(422);
            echo json_encode(['errors'=> $errors]);
        }
        $title = mysqli_real_escape_string($this->con, $params['title']);
        $author = mysqli_real_escape_string($this->con, $params['author']);
        $isbn = $params['isbn'];
        $release_date = date('Y-m-d', strtotime($params['release_date']));
        $created_at = date('Y-m-d H:i:s');
        $book = mysqli_query($this->con, "INSERT INTO books(title,isbn,author,release_date,created_at,updated_at) VALUES('".$title."','".$isbn."', '".$author."', '".$release_date."','$created_at','$created_at')");
        if(!$book) {
            echo json_encode(['message'=>'Failed to save book']);
        } else {
            echo json_encode(['message'=>'Book save successfully']);
        }
    }
    public function get($params)
    {
        $id = $params['id'];
        $book = mysqli_query($this->con, "select * from books where id=".$id);
        header('Content-type: application/json');
        echo json_encode(['book'=>mysqli_fetch_assoc($book)]);

    }
    public function delete($params)
    {
        $id = $params['id'];
        $book = mysqli_query($this->con, "delete from books where id=".$id);
        if($book) {
            echo json_encode(['message'=>'Book deleted Successfully']);
        } else {
            echo json_encode(['message'=>'Failed to delete book']);
        }
    }
    public function update($params)
    {
        $validateData = ['title','author','isbn'];
        foreach($validateData as $validate)
        {
            if(!array_key_exists($validate, $params) && empty($params[$validate]))
            {
                $errors[$validate][] = $validate.' is required';
            }
        }
        if(!empty($errors)) {
            http_response_code(422);
            echo json_encode(['errors'=> $errors]);
        }
        $title = mysqli_real_escape_string($this->con, $params['title']);
        $author = mysqli_real_escape_string($this->con, $params['author']);
        $isbn = $params['isbn'];
        $id=$params['id'];
        $release_date = date('Y-m-d', strtotime($params['release_date']));
        $created_at = date('Y-m-d H:i:s');

        $book = mysqli_query($this->con, "UPDATE `books` SET `title`='$title',`isbn`='$isbn',`author`='$author',`release_date`='$release_date',`updated_at`='$created_at' WHERE id=".$id);
        if(!$book) {
            echo json_encode(['message'=>'Failed to update book']);
        } else {
            echo json_encode(['message'=>'Book update successfully']);
        }
    }
}
?>