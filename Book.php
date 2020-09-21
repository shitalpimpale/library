<?php
class Book {
    public $author_id;
    public $title;
    public $releaseDate;
    public $isbn;
    
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function find($id)
    {       
        $statement = "
            SELECT 
                id,
                author_id,
                title,
                release_date,
                isbn
            FROM
                tbl_book
            WHERE id = $id;
        ";

        try {            
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }    
    }

    public function insert(Array $input)
    {
        $time = strtotime($input['releaseDate']);
        $release_date = date('Y-m-d',$time);

        $statement = "
            INSERT INTO tbl_book 
                (author_id, title, release_date, isbn)
            VALUES
                (:author_id, :title, :release_date, :isbn);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'author_id' => $input['author_id'],
                'title'  => $input['title'],
                'release_date' => $release_date ?? null,
                'isbn' => $input['isbn'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            return false;
        }    
    }

    public function update($id, Array $input)
    {
        $time = strtotime($input['releaseDate']);
        $release_date = date('Y-m-d',$time);


        $statement = "
            UPDATE tbl_book
            SET 
                author_id = :author_id,
                title  = :title,
                isbn = :isbn,
                release_date = :release_date
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'author_id' => $input['author_id'],
                'title'  => $input['title'],
                'isbn' => $input['isbn'],
                'release_date' => $release_date,
            ));
            return 1;
        } catch (\PDOException $e) {
            return false;
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM tbl_book
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            return false;
        }    
    }
  }