function getBooks(books)
{
    const search = document.getElementById('searchBox').value.toLowerCase();
    const filteredBooks = books.filter(book => book['author'].toLowerCase().indexOf(search) > -1);

    let bookTable = document.getElementById('bookTable');

    bookTable.innerHTML = '';

    let trHead = document.createElement('tr');
    let thAuthor = document.createElement('th');
    thAuthor.innerHTML = 'Author';
    let thBook = document.createElement('th');
    thBook.innerHTML = 'Book';

    trHead.appendChild(thAuthor);
    trHead.appendChild(thBook);
    bookTable.appendChild(trHead);

    let delay = 1;
    filteredBooks.forEach(function (book) {
        let tr = document.createElement('tr');
        tr.style.animation = `${delay+=0.1}s ease-out 0s 1 slideInFromLeft`
        let author = document.createElement('td');
        author.innerHTML = book['author'];
        let name = document.createElement('td')
        name.innerHTML = book['book'] ?? "&lt;none&gt; (no books found)";

        tr.appendChild(author);
        tr.appendChild(name);

        bookTable.appendChild(tr)
    });
}
