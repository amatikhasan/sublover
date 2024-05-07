<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subtitle Download</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/site.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark p-2">
    <div class="container-fluid m-2">
        <a class="navbar-brand" href="index.php"><img src="asset/logo.png" alt="logo" width="120px" height="100%"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
            </ul>
            <!-- Search Form at the end -->
            <form class="d-flex" role="search" action="results.php" method="get">
                <input class="form-control me-2" type="search" placeholder="Search Subtitles" aria-label="Search" id="searchInput" name="q">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<div id="searchResults" class="search-results text-center"></div>

<script>
    document.getElementById('searchInput').addEventListener('input', function(e) {
        e.preventDefault();
        var searchText = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'search.php?q=' + encodeURIComponent(searchText), true);
        xhr.onload = function() {
            if (this.status == 200 && this.responseText !== "") {
                var resultsDiv = document.getElementById('searchResults');
                resultsDiv.innerHTML = this.responseText;
                resultsDiv.style.display = 'block'; // Show the results
            } else {
                document.getElementById('searchResults').style.display = 'none'; // Hide the results if empty
            }
        }
        xhr.send();
    });
</script>
