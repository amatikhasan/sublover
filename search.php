<?php
include 'db.php';  // Ensure your DB connection settings are correct

$search = $_GET['q'];
if (!empty($search)) {
    $query = $conn->prepare("SELECT * FROM subtitles WHERE movie_name LIKE ?");
    $likeSearch = '%' . $search . '%';
    $query->bind_param("s", $likeSearch);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        // Define a placeholder image URL
        $placeholderUrl = 'asset/placeholder.jpg';
        // Check if the 'poster_url' is not empty and assign it, otherwise use the placeholder
        $imageUrl = !empty($subtitle['poster_url']) ? $subtitle['poster_url'] : $placeholderUrl;

        echo "<div class='search-item text-center pl-5'>
                  <img src=$imageUrl alt='Movie Poster' class='search-img'>
                  <a href='detail.php?id=" . $row['id'] . "' class='search-link'>" . htmlspecialchars($row['movie_name']) . "</a>
              </div>";
    }
}
?>
