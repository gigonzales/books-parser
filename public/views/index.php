<?php
use Command\Command;

$command = new Command();
$books = $command->getAllBooks();

$encodedBooks = addcslashes(json_encode($books), "'");
?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../style.css"/>
    <script src="../functions.js"></script>
    </head>
    <title>Books</title>
    <div class="searchDiv">
        <input class='search' id='searchBox' type="text" placeholder="Search" oninput='getBooks(<?php echo htmlspecialchars($encodedBooks) ?>)'/>
    </div>
    <table class="books" id="bookTable">
        <tr>
            <th>Author</th>
            <th>Book</th>
        </tr>
        <?php $delay = 1; foreach($books as $book) :?>
            <tr style="animation: <?php echo $delay+=0.1?>s ease-out 0s 1 slideInFromLeft;">
                <td> <?php echo $book['author'] ?></td>
                <td> <?php echo $book['book'] ?? "&lt;none&gt; (no books found)"?></td>
            </tr>
        <?php endforeach ?>
    </table>
</html>
