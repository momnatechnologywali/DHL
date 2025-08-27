<?php 
include 'db.php'; 
$id = intval($_GET['id']);
$sql = "SELECT a.*, c.name AS category FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.id = $id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Article not found.");
}
$article = $result->fetch_assoc();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO comments (article_id, name, comment, date) VALUES ($id, '$name', '$comment', '$date')";
    $conn->query($sql);
    header("Location: article.php?id=$id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $article['title']; ?> - CNN Clone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #cc0000; color: white; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        header h1 { margin: 0; font-size: 2.5em; }
        #searchForm { margin-top: 10px; }
        #search { padding: 10px; width: 300px; border: none; border-radius: 5px 0 0 5px; }
        #searchForm button { padding: 10px; background: #fff; color: #cc0000; border: none; border-radius: 0 5px 5px 0; cursor: pointer; }
        nav { background: #333; color: white; padding: 15px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; transition: color 0.3s; }
        nav a:hover { color: #cc0000; }
        #content { padding: 20px; max-width: 800px; margin: auto; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        #content h1 { color: #cc0000; }
        #content img { width: 100%; height: auto; border-radius: 10px; margin-bottom: 20px; }
        #content p { line-height: 1.6; }
        #related a { color: #cc0000; text-decoration: none; display: block; margin: 10px 0; }
        #comments { margin-top: 30px; }
        #comments p { border-bottom: 1px solid #ddd; padding: 10px 0; }
        form { margin-top: 20px; }
        form input, form textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        form button { padding: 10px 20px; background: #cc0000; color: white; border: none; border-radius: 5px; cursor: pointer; }
        @media (max-width: 600px) {
            #search { width: 200px; }
            nav a { display: block; margin: 10px 0; }
        }
    </style>
</head>
<body>
    <header>
        <h1>CNN News</h1>
        <form id="searchForm">
            <input type="text" id="search" placeholder="Search articles...">
            <button type="submit">Search</button>
        </form>
    </header>
    <nav>
        <?php
        $sql = "SELECT * FROM categories";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '<a href="#" onclick="redirectToCategory(\'' . $row["name"] . '\')">' . $row["name"] . '</a>';
        }
        ?>
    </nav>
    <section id="content">
        <h1><?php echo $article['title']; ?></h1>
        <p>By <?php echo $article['author']; ?> | <?php echo $article['publish_date']; ?> | <?php echo $article['category']; ?></p>
        <img src="<?php echo $article['image_url']; ?>" alt="Article Image">
        <p><?php echo nl2br($article['content']); ?></p>
        <h3>Related News</h3>
        <div id="related">
            <?php
            $sql = "SELECT id, title FROM articles WHERE category_id = " . $article['category_id'] . " AND id != $id LIMIT 3";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<a href="#" onclick="redirectToArticle(' . $row["id"] . ')">' . $row["title"] . '</a>';
            }
            ?>
        </div>
        <h3 id="comments">Comments</h3>
        <?php
        $sql = "SELECT * FROM comments WHERE article_id = $id ORDER BY date DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong> on ' . $row['date'] . ': ' . nl2br(htmlspecialchars($row['comment'])) . '</p>';
        }
        ?>
        <form method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <textarea name="comment" placeholder="Your Comment" required></textarea>
            <button type="submit">Post Comment</button>
        </form>
    </section>
    <script>
        function redirectToCategory(category) {
            location.href = 'category.php?category=' + encodeURIComponent(category);
        }
        function redirectToArticle(id) {
            location.href = 'article.php?id=' + id;
        }
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var q = document.getElementById('search').value;
            location.href = 'search.php?q=' + encodeURIComponent(q);
        });
    </script>
</body>
</html>
