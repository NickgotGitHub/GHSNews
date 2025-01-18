<?php
// create_article.php

// Check if the form was submitted
if (isset($_POST['submit_article'])) {
    
    // 1. Handle Uploaded Image
    // ---------------------------------------------------------
    $uploadedImagePath = '';
    if (!empty($_FILES['article_image']['name'])) {
        $targetDir = "images/";
        // Example: generate a unique name like "img_1673295294.jpg"
        $fileName = "img_" . time() . "_" . basename($_FILES["article_image"]["name"]);
        $targetFile = $targetDir . $fileName;
        
        if (move_uploaded_file($_FILES["article_image"]["tmp_name"], $targetFile)) {
            // Successfully uploaded
            $uploadedImagePath = $targetFile;  // something like "images/img_1673295294.jpg"
        } else {
            echo "<p>Image upload failed.</p>";
        }
    }

    // 2. Collect Form Data
    // ---------------------------------------------------------
    $articleTitle   = $_POST['article_title'] ?? 'Untitled';
    $articleAuthor  = $_POST['article_author'] ?? 'Unknown Author';
    $articleDate    = $_POST['article_date'] ?? date('Y-m-d H:i'); // fallback to "now"
    $articleContent = $_POST['article_body'] ?? '';

    // 3. Build the Final HTML Using Your Template
    // ---------------------------------------------------------
    // NOTE: Below is a simplified example that uses a "template" approach
    //       You can incorporate your entire news-article HTML structure
    //       and just insert the user data in the right places.

    $newArticleHtml = <<<EOT
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="The latest international news from Frivizn, featuring top stories...">
    <meta name="author" content="dukibu">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World | Frivizn News</title>

    <link href="https://fonts.googleapis.com/css?family=Exo+2:400,700|Open+Sans&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<div class="content-wrap">
    <!-- Header -->
    <header role="banner">
        <!-- ... your existing header / nav code ... -->
    </header>

    <!-- Main -->
    <main class="main-article" role="main">
        <article class="article">
            <h1>{$articleTitle}</h1>
            <p><small>{$articleDate}</small><br>By {$articleAuthor}</p>
            <div class="headline"></div>
            
            <!-- If there was an image uploaded, show it -->
EOT;

    // Only insert the <figure> if image was uploaded
    if (!empty($uploadedImagePath)) {
        $newArticleHtml .= <<<EOT

            <figure>
                <img src="{$uploadedImagePath}" alt="Article Image">
                <figcaption></figcaption>
            </figure>

EOT;
    }

    // Insert the user’s WYSIWYG content
    $newArticleHtml .= <<<EOT
            {$articleContent}
        </article>
    </main>

    <!-- Sidebar -->
    <aside class="sidebar" role="complementary">
        <!-- ... your existing sidebar code ... -->
    </aside>

    <!-- Footer -->
    <footer role="contentinfo">
        <!-- ... your existing footer code ... -->
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="js/main.js"></script>
</body>
</html>
EOT;

    // 4. Save the Article as an HTML File
    // ---------------------------------------------------------
    // e.g. "article_20250109_12345.html"
    $uniqueName = "article_" . date('Ymd_His') . ".html";
    $filePath   = __DIR__ . "/articles/" . $uniqueName; 
    // Make sure "articles/" folder exists and is writable

    file_put_contents($filePath, $newArticleHtml);

    echo "<p>Article created! <a href='articles/{$uniqueName}' target='_blank'>View Article</a></p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create New Article</title>

    <!-- TinyMCE (WYSIWYG) from CDN -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#article_body',
        plugins: 'lists link image preview',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | preview'
      });
    </script>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 20px;
      }
      label {
        display: block;
        margin-top: 10px;
      }
      input[type="text"], input[type="date"], textarea {
        width: 100%;
        max-width: 600px;
      }
      .btn {
        margin-top: 15px;
        padding: 10px 20px;
        display: inline-block;
        background: #3B5998;
        color: #fff;
        border: none;
        cursor: pointer;
      }
      .btn:hover {
        background: #2b4370;
      }
    </style>
</head>
<body>

<h1>Create New Article</h1>

<form action="create_article.php" method="post" enctype="multipart/form-data">
    <label for="article_title">Article Title:</label>
    <input type="text" name="article_title" id="article_title" required>

    <label for="article_author">Author:</label>
    <input type="text" name="article_author" id="article_author" required>

    <label for="article_date">Date/Time:</label>
    <!-- You can also auto-generate the date/time in your PHP code instead -->
    <input type="text" name="article_date" id="article_date" placeholder="e.g. Tuesday, Jan. 8, 12:00h" required>

    <label for="article_image">Upload Image:</label>
    <input type="file" name="article_image" id="article_image" accept="image/*">

    <label for="article_body">Article Content:</label>
    <textarea name="article_body" id="article_body" rows="15"></textarea>

    <button type="submit" name="submit_article" class="btn">Publish Article</button>
</form>

</body>
</html>
