<?php 
require_once('Book.php');
class BookController {
    private $db;
    private $requestMethod;
    private $bookId;

    private $book;

    public function __construct($db, $requestMethod, $bookId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->bookId = $bookId;

        $this->book = new Book($db);
    }

    public function processRequest()
    {
     
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->bookId) {
                    $response = $this->getBook($this->bookId);
                }
                break;
            case 'POST':
                $response = $this->createBook();
                break;
            case 'PUT':
                $response = $this->updateBook($this->bookId);
                break;
            case 'DELETE':               
                $response = $this->deleteBook($this->bookId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        echo $response;   

    }

    private function getBook($id)
    {
        $result = $this->book->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['statusCode'] = 200;
        $response['message'] = 'Created Successfully';
        $response['body'] = json_encode($result);
        return json_encode($response);
    }

    private function createBook()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
       
        if (! $this->validateBook($input)) {
            return $this->unprocessableEntityResponse();
        }     
        $this->book->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['statusCode'] = 200;
        $response['message'] = 'Created Successfully';
        $response['body'] =  $input;

        return json_encode($response);
    }

    private function updateBook($id)
    {
     
        $result = $this->book->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateBook($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->book->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['statusCode'] = 200;
        $response['message'] = 'Updated Successfully';
        $response['body'] = null;
        return json_encode($response);
    }

    private function deleteBook($id)
    {
        $result = $this->book->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->book->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['statusCode'] = 200;
        $response['body'] = 'Deleted Successfully';
        return json_encode($response);
    }

    private function validateBook($input)
    {
        if (! isset($input['author_id'])) {
            return false;
        }
        if (! isset($input['title'])) {
            return false;
        }
        if (! isset($input['isbn'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['statusCode'] = 422;
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['statusCode'] = 404;
        $response['body'] = null;
        return json_encode($response);
    }
}