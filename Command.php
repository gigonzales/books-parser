<?php
namespace Command;

require_once('PDOConnection.php');
require('Helper.php');

use Traits\Helper;
use PDO;
use PDOConnection;
use PDOStatement;

class Command extends PDOStatement
{
    use Helper;
    private const TABLE_NAMES = [
        'books.books' => 'books.books',
        'books.authors' => 'books.authors'
    ];

    public function __construct() {
    }

    public function select($table, $criteria)
    {
        $table = self::TABLE_NAMES[$table];
        $connection = new PDOConnection();

        $bindings = [];
        $query = '';

        foreach ($criteria as $key => $value) {
            $binding = [];
            $binding['param'] = ':' . $key;
            $binding['field'] = $value;
            $query .= $key . '=:' . $key;
            $bindings[] = $binding;
        }

        $query = "select * from $table where $query";
        $statement = $connection->prepare($query);

        foreach ($bindings as $binding) {
            $statement->bindParam($binding['param'], $binding['field']);
        }

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($table, $criteria): void
    {
        $table = self::TABLE_NAMES[$table];
        $connection = new PDOConnection();

        $bindings = [];
        $query = '';
        $fields = '';
        foreach ($criteria as $key => $value) {
            $binding = [];
            $binding['param'] = ':' . $key;
            $binding['field'] = $value;
            $query .= ':' . $key . ',';
            $fields .= $key . ',';
            $bindings[] = $binding;
        }

        $fields = rtrim($fields, ',');
        $query = rtrim($query, ',');

        $query = "INSERT INTO $table ($fields) VALUES ($query)";
        $statement = $connection->prepare($query);

        foreach ($bindings as $binding) {
            $statement->bindParam($binding['param'], $binding['field']);
        }

        $statement->execute();

        $connection = null;
    }

    public function getAllBooks(): bool|array
    {
        $connection = new PDOConnection();

        $query = "select books.authors.name as author, books.books.name as book 
            from books.authors 
            left join books.books  
            on books.authors.id = books.books.author_id"
        ;

        $statement = $connection->prepare($query);

        $statement->execute();

        $books = $statement->fetchAll(PDO::FETCH_ASSOC);

        $connection = null;

        return $books;
    }

    public function insertOrUpdate($books)
    {
        foreach ($books as $book) {
            $author = $this->select('books.authors', ['name' => $book['author']]);

            if (!$author) {
                $this->insert('books.authors', ['name' => $book['author']]);

                $author = $this->select('books.authors', ['name' => $book['author']]);
            }

            $title = $this->select('books.books', ['name' => $book['name']]);

            if (!$title) {
                $this->insert('books.books', ['name' => $book['name'], 'author_id' => $author['id']]);
            }
        }
    }
}
