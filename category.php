<?php 
include 'db.php'; 
$category = mysqli_real_escape_string($conn, $_GET['category']);
$sql = "SELECT id FROM categories WHERE name='$category'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Category not found.");
}
$row = $result->fetch_assoc();
$cat_id = $row['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $category; ?> - CNN Clone</title>
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
        section { padding: 20px; }
        h2 { color: #cc0000; border-bottom: 2px solid #cc0000; padding-bottom: 10px; }
        .article-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .article { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .article:hover { transform: translateY(-5px); }
        .article img { width: 100%; height: 200px; object-fit: cover; }
        .article div { padding: 15px; }
        .article h3 { margin: 0 0 10px; font-size: 1.2em; }
        .article h3 a { color: #cc0000; text-decoration: none; }
        .article p { font-size: 0.9em; color: #666; }
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
    <section>
        <h2><?php echo $category; ?> News</h2>
        <div class="article-container">
            <?php
            $sql = "SELECT id, title, summary, image_url FROM articles WHERE category_id = $cat_id";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<div class="article"><img src="' . $row["image_url"] . '" alt="Image"><div><h3><a href="#" onclick="redirectToArticle(' . $row["id"] . ')">' . $row["title"] . '</a></h3><p>' . $row["summary"] . '</p></div></div>';
            }
            ?>
        </div>
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
