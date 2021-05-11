<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>Books</h1>
        <form id="bookForm">
            <input type="hidden" id="book_id">
            <div class="row">
                <div class="col-5 form-group">
                    <label for="book_name">Book Name</label>
                    <input type="text" class="form-control" id="book_name" name="book_name" placeholder="Book Name">
                </div>
                <div class="col-5 form-group">
                    <label for="author">Author</label>
                    <input type="text" class="form-control" id="author" name="author" placeholder="Author">
                </div>
            </div>
            <div class="row">
                <div class="col-5 form-group">
                    <label for="isbn">ISBN Number</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" placeholder="ISBN Number">
                </div>
                <div class="col-5 form-group">
                    <label for="release_date">Release Date</label>
                    <input type="date" class="form-control" id="release_date" name="release_date">
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="formSubmit">Save</button>
            <button type="button" class="btn btn-secondary" style="display:none;" id="formUpdate">Update</button>
            <input type="reset" class="btn btn-default" value="clear">
        </form>
        <hr />
        <table id="mytable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>ISBN Number</th>
                    <th>Author</th>
                    <th>Release Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script>
    var formatDate = function(date) {
        date = new Date(date);
        return date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
    }
    $(document).ready(function() {
        loadData();
    });

    function loadData() {
        $.ajax({
            url: 'http://localhost/shoppinal/api.php/books',
            method: 'GET',
            success: function(response) {
                $('#mytable tbody').empty();
                var row = '';
                var books = response.books;
                books.forEach(book => {
                    row += '<tr><td>' + book.title + '</td><td>' + book.isbn + '</td><td>' +
                        book.author + '</td><td>' + formatDate(book.release_date) +
                        '</td><td><button type="button" class="btn btn-primary" onclick="editBook(' +
                        book.id +
                        ')"><i class="fas fa-pen" aria-hidden="true"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="deleteBook(' +
                        book.id +
                        ')"><i class="fas fa-trash" aria-hidden="true"></i></button></td></tr>';
                });
                $('#mytable tbody').append(row);
            }
        });
    }
    $('#formSubmit').click(function() {
        var data = {
            "title": $("#book_name").val(),
            "author": $("#author").val(),
            "isbn": $("#isbn").val(),
            "release_date": $("#release_date").val()
        };
        $.ajax({
            url: 'http://localhost/shoppinal/api.php/books/store',
            method: 'POST',
            data: data,
            success: function(response) {
                alert(response.message);
                loadData();
            },
            error: function(error) {
                var msg = '';
                $.each(error.responseJSON.errors, function(key, value) {
                    $.each(value, function(key, value) {
                        msg += value;
                    });
                });
                alert(msg);
            }
        });
    });

    function editBook(id) {
        $.ajax({
            url: 'http://localhost/shoppinal/api.php/books/get?id=' + id,
            method: 'GET',
            success: function(response) {
                console.log(response)
                $('#book_id').val(response.book.id);
                $("#book_name").val(response.book.title);
                $("#author").val(response.book.author);
                $("#isbn").val(response.book.isbn);
                $("#release_date").val(response.book.release_date);
                $('#formSubmit').css('display', 'none');
                $('#formUpdate').css('display', 'inline');
            },
            error: function(error) {
                console.log(error)
            }
        });
    }
    $('#formUpdate').click(function() {
        var data = {
            "id": $("#book_id").val(),
            "title": $("#book_name").val(),
            "author": $("#author").val(),
            "isbn": $("#isbn").val(),
            "release_date": $("#release_date").val()
        };
        $.ajax({
            url: 'http://localhost/shoppinal/api.php/books/update',
            method: 'POST',
            data: data,
            success: function(response) {
                alert(response.message);
                $('#bookForm').trigger("reset");
                $('#formSubmit').css('display', 'inline');
                $('#formUpdate').css('display', 'none');
                loadData();
            },
            error: function(error) {
                var msg = '';
                $.each(error.responseJSON.errors, function(key, value) {
                    $.each(value, function(key, value) {
                        msg += value;
                    });
                });
                alert(msg);
            }
        });
    });

    function deleteBook(id) {
        $.ajax({
            url: 'http://localhost/shoppinal/api.php/books/delete?id=' + id,
            method: 'GET',
            success: function(response) {
                alert(response.message);
                loadData();
            },
            error: function(error) {
                console.log(error)
            }
        });
    }
    </script>
</body>

</html>