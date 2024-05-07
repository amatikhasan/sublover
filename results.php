<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php';
include 'db.php'; // include database connection
?>
<body>
<div class="container mt-3">
    <?php
    //get search param
    $param = $_GET['q']; // Get the subtitle ID from the URL

    $limit = 10; // Number of entries per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    $result = $conn->query("SELECT * FROM subtitles WHERE movie_name LIKE '%$param%' LIMIT $start, $limit");

    echo "<div class='row row-cols-1 row-cols-md-5 g-4'>"; // Setting up grid for 5 items per row

    while ($subtitle = $result->fetch_assoc()) {

        $encodedQuery = urlencode($subtitle['movie_name']." movie/series poster");
        $url = "http://images.google.com/images?um=1&hl=en&safe=active&nfpr=1&q=".$encodedQuery; // Replace with your actual URL
        $firstImageSrc = "";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // For HTTPS requests, if necessary
        $html = curl_exec($curl);
        curl_close($curl);

        if (!$html) {
            //die("Failed to retrieve content.");
        }else{
            //die($html);

            $doc = new DOMDocument();
            libxml_use_internal_errors(true); // Suppress warnings for invalid HTML
            $doc->loadHTML($html);
            libxml_clear_errors(); // Clear errors due to malformed HTML

            $xpath = new DOMXPath($doc);

            // XPath to find the first <img> element in the nested table structure
            $query = "//table[@class='GpQGbf']//img";
            $images = $xpath->query($query);

            if ($images->length > 0) {
                $firstImageSrc = $images[0]->getAttribute('src');
                //echo "First image source URL: " . $firstImageSrc;
            } else {
                //echo "No images found.";
            }
        }

        // Define a placeholder image URL
        $placeholderUrl = 'asset/placeholder.jpg';
        // Check if the 'poster_url' is not empty and assign it, otherwise use the placeholder
        $imageUrl = !empty($subtitle['poster_url']) ? $subtitle['poster_url'] : $firstImageSrc;
        $imageUrl = !empty($firstImageSrc) ? $firstImageSrc : $placeholderUrl;

        echo "<div class='col'>
                  <div class='card mb-3' style='width: 100%;'>
                      <a href='detail.php?id={$subtitle['id']}' class='btn btn-dark p-0'>
                          <img src=$imageUrl class='card-img-top' alt='Movie Poster'>
                          <div class='card-body'>
                              <h5 class='card-title'>{$subtitle['movie_name']}</h5>
                              <p class='card-text'>Subtitle language: <strong><i>{$subtitle['subtitle_language']}</i></strong></p>
                          </div>
                      </a>
                  </div>
              </div>";
    }

    echo "</div>"; // Close row
    ?>


    <div class="m-2">
        <?php
        $total_result = $conn->query("SELECT COUNT(id) AS id FROM subtitles WHERE movie_name LIKE '%$param%'")->fetch_assoc()['id'];
        $total_pages = ceil($total_result / $limit);
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page from URL, default is 1

        echo "<nav aria-label='Page navigation example'>";
        echo "<ul class='pagination justify-content-center'>"; // Center pagination

        echo "<li class='page-item " . ($current_page == 1 ? 'disabled' : '') . "'><a class='page-link' href='results.php?q=$param&page=1'>&laquo;</a></li>";
        // Previous Button
        $prev_page = $current_page - 1;
        echo "<li class='page-item " . ($current_page == 1 ? 'disabled' : '') . "'><a class='page-link' href='results.php?q=$param&page=" . max($prev_page, 1) . "'>&lt;</a></li>";

        // Numbered Links: Calculate the range to display
        $start = max(1, $current_page - 2);
        $end = min($total_pages, $current_page + 2);

        if ($current_page - 2 < 1) {
            $end = min($total_pages, 1 + 4);
        }

        if ($current_page + 2 > $total_pages) {
            $start = max(1, $total_pages - 4);
        }

        for ($i = $start; $i <= $end; $i++) {
            echo "<li class='page-item " . ($i == $current_page ? 'active' : '') . "'><a class='page-link' href='results.php?q=$param&page=$i'>$i</a></li>";
        }

        // Next Button
        $next_page = $current_page + 1;
        echo "<li class='page-item " . ($current_page == $total_pages ? 'disabled' : '') . "'><a class='page-link' href='results.php?q=$param&page=" . min($next_page, $total_pages) . "'>&gt;</a></li>";
        echo "<li class='page-item " . ($current_page == $total_pages ? 'disabled' : '') . "'><a class='page-link' href='results.php?q=$param&page=$total_pages'>&raquo;</a></li>";

        echo "</ul>";
        echo "</nav>";
        ?>
    </div>

</div>

<?php include 'footer.php'; ?>
</body>
</html>
