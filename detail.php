<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php';
include 'db.php'; // include database connection
?>
<body>
<div class="container mt-3 mb-3">
    <?php

    $id = $_GET['id']; // Get the subtitle ID from the URL

    // Fetch subtitle data
    $query = $conn->prepare("SELECT * FROM subtitles WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $subtitle = $result->fetch_assoc();

    // Fetch releases data
    $releaseQuery = $conn->prepare("SELECT * FROM releases WHERE subtitle_id = ?");
    $releaseQuery->bind_param("i", $id);
    $releaseQuery->execute();
    $releasesResult = $releaseQuery->get_result();
    ?>
    <div class="d-flex justify-content-center align-items-center text-center m-4">
        <?php
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
        // Display subtitle details
        echo "<div class='card text-light bg-dark m-3' style='width: 18rem;'>
                    <img src=$imageUrl alt='Movie Poster'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$subtitle['movie_name']}</h5>
                        <p class='card-text'>Subtitle language: <strong><i>{$subtitle['subtitle_language']}</i></strong></p>
                    </div>
                </div>";
        ?>
    </div>
    <!-- Display release data in a table -->
    <div class="text-center mb-5">
        <h5>Releases</h5>
        <table class="table mt-2 table-striped">
            <tbody>
            <?php while ($release = $releasesResult->fetch_assoc()) { ?>
                <tr>
                    <td class="align-middle"><i><?php echo htmlspecialchars($release['release_name']); ?></i></td>
                    <td class="align-middle"><a href='<?php echo $release['file_path']; ?>' download class='btn btn-success'>Download</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
<?php include 'footer.php'; ?>
</html>
