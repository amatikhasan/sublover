<?php
include 'db.php'; // include database connection
include 'ChromePhp.php';
// Directory containing subtitle files
$directory = __DIR__ . "/files";

// Read all files in the directory
$files = scandir($directory);

// Array to keep track of movies we've added
$moviesAdded = [];

foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        // Example filename: "1883-first-season_bengali-2657846.zip"
        $nameParts = explode('_', $file);

        $movieName = ucwords(str_replace('-', ' ', $nameParts[0])); // Converts '1883-first-season' to '1883 First Season'
        if (!array_key_exists($movieName, $moviesAdded)) {
            $existingId = checkMovieExists($conn, $movieName);
            if ($existingId === false) {
            // Insert movie name into subtitles table
            $subtitle_language="Bangla";
            $stmt = $conn->prepare("INSERT INTO subtitles (movie_name, subtitle_language) VALUES (?,?)");
            $stmt->bind_param("ss", $movieName, $subtitle_language);
            $stmt->execute();
            $subtitleId = $stmt->insert_id;
            $moviesAdded[$movieName] = $subtitleId;
            } else {
                $subtitleId = $existingId;
                $moviesAdded[$movieName] = $subtitleId;
            }
        } else {
            $subtitleId = $moviesAdded[$movieName];
        }

        // Prepare the release version and file path
        $releaseName = str_replace('.zip', '', $nameParts[1]); // Removes the .zip and gets the version
        $releaseName = $movieName.'_'.$releaseName;
        $filePath = $directory . '/' . $file;

        // Check if the release already exists
        if (!checkReleaseExists($conn, $subtitleId, $releaseName)) {
            $stmt = $conn->prepare("INSERT INTO releases (subtitle_id, release_name, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $subtitleId, $releaseName, $filePath);
            $stmt->execute();
        } else {
        }
    }
}

function checkMovieExists($conn, $movieName) {
    $stmt = $conn->prepare("SELECT id FROM subtitles WHERE movie_name = ?");
    $stmt->bind_param("s", $movieName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id']; // return existing movie ID
    }
    return false; // no existing entry found
}

function checkReleaseExists($conn, $subtitleId, $releaseName) {
    $stmt = $conn->prepare("SELECT id FROM releases WHERE subtitle_id = ? AND release_name = ?");
    $stmt->bind_param("is", $subtitleId, $releaseName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;  // Existing release found
    }
    return false;  // No existing release found
}


echo "Data import complete.";

/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function debug_to_console($data, $context = 'Debug in Console') {

    // Buffering to solve problems frameworks, like header() in this and not a solid return.
    ob_start();

    $output  = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output  = sprintf('<script>%s</script>', $output);

    echo $output;
}
?>
