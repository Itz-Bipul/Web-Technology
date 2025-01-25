<?php
    $conn = new mysqli("localhost", "root", "", "BOOK");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "SELECT id, title, author, yearofpublication, genre FROM books";
    $result = $conn->query($sql);
    $books = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    $conn->close();

    
    $tokens = [];
    if (file_exists('token.json')) {
        $json = file_get_contents('token.json');
        $tokens = json_decode($json, true) ?? [];
    }

    
    $usedTokens = [];
    if (file_exists('used_tokens.json')) {
        $json = file_get_contents('used_tokens.json');
        $usedTokens = json_decode($json, true) ?? [];
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="style.css">
        <title>Library Management</title>
        <script>
            function filterBooks() {
            // Get the search query
            const searchQuery = document.getElementById("searchBar").value.toLowerCase();
            const bookList = document.getElementById("bookList");
            const books = bookList.getElementsByClassName("book-item");

            // Loop through all book items and hide those that don't match the search query
            for (let i = 0; i < books.length; i++) {
                const bookText = books[i].textContent.toLowerCase();
                if (bookText.includes(searchQuery)) {
                     books[i].style.display = ""; // Show the book item
                } else {
                    books[i].style.display = "none"; // Hide the book item
                 }
            }
            }   
        </script>
    </head>
    <body>
        <main>
        <aside class="box3">
        <h2>Token Used</h2>
        <ul class="token-list">
            <?php foreach ($usedTokens as $used): ?>
                <li><strong><?php echo htmlspecialchars($used['token']); ?></strong></li>
            <?php endforeach; ?>
        </ul>
        </aside>
            <div>
                <section>
                <div class="box1">
                    <h2 class="header-title">All Database Books</h2>
                    <input 
                        type="text" 
                        id="searchBar" 
                        placeholder="Search books by title, author, or year..." 
                        onkeyup="filterBooks()" 
                        class="search-bar">
                        <ol id="bookList">
                            <?php foreach ($books as $book): ?>
                            <li class="book-item">
                                <?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']) . " at " . htmlspecialchars($book['yearofpublication']); ?>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>

                    <div class="box1">
                    <h2 class="header-title">Add or Remove Book</h2>
                        <form action="add_remove_book.php" method="post">
                            <input type="text" name="title" placeholder="Book Title" required>
                            <input type="text" name="author" placeholder="Author" required>
                            <input type="number" name="yearofpublication" placeholder="Year of Publication" required>
                            <input type="text" name="genre" placeholder="Genre" required>
                            <div class="form-buttons"> 
                                <button type="submit" name="action" value="add" id="buttonAdd"><b>Add Book</b></button>
                                <button type="submit" name="action" value="remove" id="buttonRemove"><b>Remove Book</b></button>
                            </div>
                        </form>
                    </div>
                    <div class="box1">
                    <h2 class="header-title">Edit Book Information</h2>
                        <form action="edit_book.php" method="post">
                            <input type="number" name="id" placeholder="Book ID" required>
                            <input type="text" name="title" placeholder="New Title">
                            <input type="text" name="author" placeholder="New Author">
                            <input type="number" name="yearofpublication" placeholder="New Year of Publication">
                            <input type="text" name="genre" placeholder="New Genre">
                            <div class="button-container">
                                 <button type="submit" id="buttonUpdate"><b>Update</b></button>
                            </div>
                        </form>
                    </div>
                </section>
                <section class="section2">
                    <div class="box2">
                        <img src="/LabTask/Picture/1.png">
                    </div>
                    <div class="box2">
                    <img src="/LabTask/Picture/2.png">

                    </div>
                    <div class="box2">
                    <img src="/LabTask/Picture/3.png">

                    </div>
                </section>

                <section class="section2">
                    <div class="box22a">
                        <form action="process.php" method="post">
                            <b>Student Name</b> 
                            <br><input type="text" placeholder="Student Full Name" name="studentname" id="studentname" required><br>
                            <b>Student ID</b>
                            <br><input type="text" placeholder="Student ID" name="studentid" id="studentID" required><br>
                            <b>Student Email</b>
                            <br><input type="email" placeholder="Student Email" name="email" id="email" required><br>
                            <label for="booktitle"><b>Select A Book : </b></label><br>
                            <select name="booktitle" id="booktitle" required>
                                <option value="" disabled selected>Select a Book</option>
                                <?php 
                                    foreach ($books as $book): 
                                ?>
                                <option value="<?php echo htmlspecialchars($book['title']); ?>">
                                    <?php echo htmlspecialchars($book['title']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select><br>
                            <b>Borrow date</b>
                            <br><input type="date" name="borrowdate" id="borrowdate" required><br>
                            <b>Return date</b>
                            <br><input type="date" name="returndate" id="returndate" required><br>
                            <b>Token</b>
                            <br><input type="text" placeholder="Token Number" name="token" id="token"><br>
                            <b>Fees</b>
                            <br><input type="text" placeholder="Fees" name="fees" id="fees"><br> <br><br>
                            <button type="submit" name="submit" id="button"><b>Submit</b></button>
                        </form>
                    </div>
                   

                        <?php
                    if (file_exists('token.json')) {
                        $tokens_json = file_get_contents('token.json');
                        $tokens = json_decode($tokens_json, true);
                        if ($tokens === null) {
                            echo "Error decoding JSON.";
                        }
                    } else {
                        echo "token.json file not found.";
                    }
                    ?>
                   
                    <div class="box22b">
                        <h3 style="text-align: center;">Available Tokens</h3>
                        <ul class="token-list">
                        <?php if (isset($tokens) && is_array($tokens)): ?>
                            <?php foreach ($tokens as $token): ?>
                                <?php if (isset($token['token'])): ?>
                                        <li><strong><?php echo htmlspecialchars($token['token']); ?></strong></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No tokens available or an error occurred while loading the tokens.</p>
                        <?php endif; ?>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="box3">
                <h2 style="text-align: center;">Library Details</h2>
                <hr>
                <Label><b>About</b></Label>
                <p>The Library, established in 1994, supports the academic and research needs of faculty, students, and staff. It has grown significantly, offering a rich collection of over 43,318 books, 1,72,000 e-books, 68,000 e-journals, and resources in various fields like Business, Science, Technology, and Social Sciences. The library operates an open system for AIUB students, allowing book and CD borrowing (excluding textbooks) for seven days using their student ID cards. With a seating capacity for 500+, it uses the "Library System," a software developed in-house, providing modern facilities for efficient library access and management.</p>
                <hr>
                <div class="social-icons">
                    <a href="https://www.facebook.com/aiub.edu" target="_blank">
                        <i class="fab fa-facebook"></i>
                    </a>                  
                    <a href="https://www.linkedin.com/school/aiubedu/" target="_blank">
                        <i class="fab fa-linkedin"></i>
                    </a> 
                </div>

                
            </div>
        </main>
    </body>
    </html>