<?php

$serverName = "LAPTOP-LSFR3CIB\SQLEXPRESS01";
$connectionOptions = [
    "Database" => "webapp",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn == false) {
    die(print_r(sqlsrv_errors(), true));
}

// Get the search query, selected radio button value, and current page
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$reportSelection = isset($_GET['report']) ? $_GET['report'] : '';
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 10;
$offset = ($currentPage - 1) * $recordsPerPage;

// Modify the query based on the selected report type and search query
$sql = "SELECT t.TITLE_ID, t.TITLE_NAME, t.PROGRAM, a.LAST_NAME, a.FIRST_NAME, 
               adv.FIRST_NAME AS ADVISER_FIRST_NAME, adv.LAST_NAME AS ADVISER_LAST_NAME
        FROM TITLE t
        JOIN AUTHOR a ON t.TITLE_ID = a.TITLE_ID
        LEFT JOIN ADVISER adv ON t.TITLE_ID = adv.TITLE_ID
        WHERE a.AUTHOR_ID = (
            SELECT MIN(a2.AUTHOR_ID)
            FROM AUTHOR a2 
            WHERE a2.TITLE_ID = t.TITLE_ID
        )";

if ($reportSelection === "title.php" && $searchQuery) {
    $sql .= " AND t.TITLE_NAME LIKE ?";
    $params = array('%' . $searchQuery . '%');
} elseif ($reportSelection === "program.php" && $searchQuery) {
    $sql .= " AND t.PROGRAM LIKE ?";
    $params = array('%' . $searchQuery . '%');
} elseif ($reportSelection === "authorln.php" && $searchQuery) {
    $sql .= " AND (a.LAST_NAME LIKE ? OR a.FIRST_NAME LIKE ?)";
    $params = array('%' . $searchQuery . '%', '%' . $searchQuery . '%');
} elseif ($reportSelection === "adviserln.php" && $searchQuery) {
    $sql .= " AND (adv.LAST_NAME LIKE ? OR adv.FIRST_NAME LIKE ?)";
    $params = array('%' . $searchQuery . '%', '%' . $searchQuery . '%');
} else {
    $params = array();
}

// Add pagination logic
$sql .= " ORDER BY t.TITLE_ID OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params[] = $offset;
$params[] = $recordsPerPage;

$result = sqlsrv_query($conn, $sql, $params);

// Query for total record count
$countSql = "SELECT COUNT(*) AS totalcount
             FROM TITLE t
             JOIN AUTHOR a ON t.TITLE_ID = a.TITLE_ID
             LEFT JOIN ADVISER adv ON t.TITLE_ID = adv.TITLE_ID
             WHERE a.AUTHOR_ID = (
                 SELECT MIN(a2.AUTHOR_ID)
                 FROM AUTHOR a2
                 WHERE a2.TITLE_ID = t.TITLE_ID
             )";

$countParams = []; // Separate parameter array for count query

if ($reportSelection === "title.php" && $searchQuery) {
    $countSql .= " AND t.TITLE_NAME LIKE ?";
    $countParams[] = '%' . $searchQuery . '%';
} elseif ($reportSelection === "program.php" && $searchQuery) {
    $countSql .= " AND t.PROGRAM LIKE ?";
    $countParams[] = '%' . $searchQuery . '%';
} elseif ($reportSelection === "authorln.php" && $searchQuery) {
    $countSql .= " AND (a.LAST_NAME LIKE ? OR a.FIRST_NAME LIKE ?)";
    $countParams[] = '%' . $searchQuery . '%';
    $countParams[] = '%' . $searchQuery . '%';
} elseif ($reportSelection === "adviserln.php" && $searchQuery) {
    $countSql .= " AND (adv.LAST_NAME LIKE ? OR adv.FIRST_NAME LIKE ?)";
    $countParams[] = '%' . $searchQuery . '%';
    $countParams[] = '%' . $searchQuery . '%';
}

// Execute the total count query
$countResult = sqlsrv_query($conn, $countSql, $countParams);

if ($countResult === false) {
    die(print_r(sqlsrv_errors(), true)); // Debugging: Display SQL Server errors
}

$countRow = sqlsrv_fetch_array($countResult);
$totalcount = $countRow['totalcount']; // Extract the total count

// Calculate total pages
$totalPages = ceil($totalcount / $recordsPerPage);


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="icon" href="image/favicon.png" type="image/png">
    <title>my.DLSU-D Thesis</title>
    <style>
        body {
    margin: 0;
    padding: 0;
    text-align: center;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
    background-image: url('image/login.jpg'); 
    background-size: cover;
    background-color: rgba(3, 45, 27, 0.53); /*greenish overlay */
    background-blend-mode: overlay; /* Blends image with the background color */
    color: #333;
    height: 125vh; /* Ensures the body takes up the full viewport height */

    
}

h1 {
            margin-top: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            color: #2f7a2f; /*for headings */
            font-family: 'Roboto', sans-serif;
            font-weight: normal;
            font-size: 40px; /*heading font size */

        }

        h3, h4, label {
    color: #4aaf51; 
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
    font-size: 16px; 
}





table {
    width: 80%;
    margin: 30px auto;
    border-collapse: collapse;

    background-color: rgba(255, 255, 255, 0.9); /* Set a solid background color for the table */
    border-radius: 10px; /* Rounded corners for the table */
    animation: popIn 0.5s ease-in-out;
}

th, td {
    padding: 10px;
    
    text-align: center;
    border: 1px solid #ddd;
}

th {
    background-color: #888;
    color: white;
}

td {
    background-color: #f9f9f9; /* Lighter background color for table rows */
}


.search-container {
    display: flex;
    justify-content: center;  /* Aligns items horizontally in the center */
    align-items: center;      /* Aligns items vertically in the center */
    gap: 10px;                /* Adds space between search bar and button */
    margin-top: 20px;
    width: 100%;              /* Ensure container takes full width */
}

/* Search Bar Styling */
.search-bar {
    padding: 10px;
    font-size: 16px;
    border-radius: 10px;
    border: 1px solid #006747;
    background-color: rgba(255, 255, 255, 0.7);
    width: 250px;  /* Set a fixed width for consistency */
    max-width: 300px;
    flex-grow: 1;   /* Allow it to take available space in the container */
}

/* Search Button Styling */
.search-button {
    background-color: #4aaf51;  /* Dark green button */
    color: white;
    padding: 12px 30px;  /* Adjust padding */
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Add hover effect */
.search-button:hover {
    background-color: #3e9a42;  /* Darker green when hovered */
}




/* Styling radio buttons */
.report-radio {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
    color: white;
}

.report-radio input[type="radio"] {
    accent-color: #006747; /* Change radio button accent color to match the La Salle green */
    cursor: pointer;
    width: 20px;
    height: 20px;
}

.report-radio label {
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.report-radio input[type="radio"]:checked + label {
    font-weight: normal;
    color: RED; /* Highlight selected option */
}

@keyframes popIn {
    0% { transform: scale(0); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

/* Dashboard Button */
button {
    font-family: 'Roboto', sans-serif;
    font-size: 16px;
    padding: 20px;
    width: 250px;
    margin: 30px auto;
    border-radius: 12px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    transform: translateY(10px);
    animation: popIn 0.5s ease-in-out;
    background-color: #4aaf51; /* color of the button */
    color: white; /* button text color */
    display: block; /* Make the button a block element */
    cursor: pointer;
}

/* Hover effect */
button:hover {
    color: #000000; /* nag iiba kulay kapag natutok don sa button*/
}


/* Total Records Box */
.total-count-box {
    font-size: 16px;
            padding: 20px;
            width: 250px;
            margin: 30px auto;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            transform: translateY(10px);
            animation: popIn 0.5s ease-in-out;
            font-family: 'Roboto', sans-serif;
            background-color: #4aaf51; /*color of the button */
            color: white; /* button color*/
            display: block; /* Make the button a block element */
}

/* Pop-in animation */
@keyframes popIn {
    0% { transform: scale(0); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

.pagination {
    margin: 20px 0;
    text-align: center;
}

.pagination .btn {
    margin: 0 5px;
    padding: 10px 15px;
    background-color: #4aaf51;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.pagination .btn:hover {
    background-color: #003e3e;
}

.pagination .btn.active {
    background-color: #004e3c;
    font-weight: bold;
}

.mooter {
    
    color: white;
    bottom: 0;
    width: 100%;
    text-align: center;

}



</style>
</head>

  <script>

     // Function to handle the search and update the URL
     function updateURL() {
            var selectedReport = document.querySelector('input[name="report"]:checked');
            var searchQuery = document.getElementById("search").value;
            var url = window.location.pathname + '?report=' + selectedReport.value + '&search=' + encodeURIComponent(searchQuery);
            window.history.pushState({ path: url }, '', url);
            loadResults(); // Reload the results based on the updated URL
        }

        // Function to reload the results dynamically after the search
        function loadResults() {
            var selectedReport = document.querySelector('input[name="report"]:checked');
            var searchQuery = document.getElementById("search").value;
            var form = document.createElement("form");
            form.method = "GET";
            form.action = window.location.pathname;
            
            var inputReport = document.createElement("input");
            inputReport.type = "hidden";
            inputReport.name = "report";
            inputReport.value = selectedReport.value;
            form.appendChild(inputReport);

            var inputSearch = document.createElement("input");
            inputSearch.type = "hidden";
            inputSearch.name = "search";
            inputSearch.value = searchQuery;
            form.appendChild(inputSearch);

            document.body.appendChild(form);
            form.submit();
        }
        
// Function to handle the search button click event
function handleSearchButtonClick() {
    var searchQuery = document.getElementById("search").value;
    updateURL(); // Update URL based on search
    loadResults(); // Load results after the search is made
    updatePlaceholder(); // Update the placeholder based on selected radio
}

// Function to update the placeholder based on selected radio button
function updatePlaceholder() {
    // Get the selected radio button
    const selectedFilter = document.querySelector('input[name="report"]:checked');
    
    // Get the search input element
    const searchInput = document.getElementById("search");

    // Debugging log to check placeholder update process
    console.log("Updating placeholder...");

    // Update the placeholder based on the selected radio button value
    if (selectedFilter) {
        switch (selectedFilter.value) {
            case "report.php":
                searchInput.placeholder = "Search...";
                break;
            case "title.php":
                searchInput.placeholder = "Search the Thesis name eg.PaddyScan";
                break;
            case "program.php":
                searchInput.placeholder = "Search the Program name eg.CPE";
                break;
            case "authorln.php":
                searchInput.placeholder = "Search the Author name eg.Juan";
                break;
            case "adviserln.php":
                searchInput.placeholder = "Search the Adviser name eg.Juan";
                break;
            default:
                searchInput.placeholder = "Search...";
        }
    }
}

// Attach event listeners on window load
window.onload = function () {
    var searchButton = document.getElementById("searchButton");
    searchButton.addEventListener('click', function() {
        handleSearchButtonClick(); // Trigger the search button functionality
        updatePlaceholder(); // Ensure placeholder is updated
    });

    var radios = document.querySelectorAll('input[name="report"]');
    radios.forEach(function (radio) {
        radio.addEventListener('change', function() {
            updateURL(); // Update the URL when a radio button changes
            updatePlaceholder(); // Update the placeholder when the radio button changes
        });
    });

    // Ensure placeholder is set when page loads initially
    updatePlaceholder();
};


  </script>
  </head>
</body>

<h1 style="text-align: center; color: white;" >DLSU-D CEAT Research</h1>

<div class="search-container">
        <input type="text" id="search" class="search-bar" placeholder="Search..." value="<?php echo $searchQuery; ?>">
        <inout type="submit" id="searchButton" class="search-button">Search</button>
    </div>
<!-- Radio Buttons for Filters -->
     <!-- Radio buttons to select a report -->
     <div class="report-radio">
        <input type="radio" id="report1" name="report" value="report.php"  onclick="updatePlaceholder()"class="radio-label" <?php echo ($reportSelection == "report.php") ? 'checked' : ''; ?>>
        <label for="report1">All</label>

        <input type="radio" id="report2" name="report" value="title.php"  onclick="updatePlaceholder()"class="radio-label" <?php echo ($reportSelection == "title.php") ? 'checked' : ''; ?>>
        <label for="report2">Title</label>

        <input type="radio" id="report3" name="report" value="program.php"  onclick="updatePlaceholder()"class="radio-label" <?php echo ($reportSelection == "program.php") ? 'checked' : ''; ?>>
        <label for="report3">Program</label>

        <input type="radio" id="report4" name="report" value="authorln.php" onclick="updatePlaceholder()" class="radio-label" <?php echo ($reportSelection == "authorln.php") ? 'checked' : ''; ?>>
        <label for="report4">Author</label>

        <input type="radio" id="report5" name="report" value="adviserln.php"  onclick="updatePlaceholder()"class="radio-label" <?php echo ($reportSelection == "adviserln.php") ? 'checked' : ''; ?>>
        <label for="report5">Adviser</label>
    </div>

   <!-- Table for displaying results -->
   <table>
        <thead>
            <?php
            // Output column headers based on selected report
            if ($reportSelection == "report.php" || $reportSelection == "title.php") {
                echo '<tr><th>TitleID</th><th>Title</th><th>Program</th><th>Author Last Name</th><th>Author First Name</th></tr>';
            } elseif ($reportSelection == "program.php") {
                echo '<tr><th>Program</th><th>TitleID</th><th>Title</th><th>Author Last Name</th><th>Author First Name</th></tr>';
            } elseif ($reportSelection == "authorln.php") {
                echo '<tr><th>Author First Name</th><th>Author Last Name</th><th>TitleID</th><th>Title</th></tr>';
            } elseif ($reportSelection == "adviserln.php") {
                echo '<tr><th>Adviser First Name</th><th>Adviser Last Name</th><th>TitleID</th><th>Title</th></tr>';
            }
            ?>
        </thead>
        <tbody>
            <?php
            while ($rows = sqlsrv_fetch_array($result)) {
                if ($reportSelection == "report.php" || $reportSelection == "title.php") {
                    echo '<tr>
                            <td>' . $rows['TITLE_ID'] . '</td>
                            <td>' . $rows['TITLE_NAME'] . '</td>
                            <td>' . $rows['PROGRAM'] . '</td>
                            <td>' . $rows['LAST_NAME'] . '</td>
                            <td>' . $rows['FIRST_NAME'] . '</td>
                          </tr>';
                } elseif ($reportSelection == "program.php") {
                    echo '<tr>
                            <td>' . $rows['PROGRAM'] . '</td>
                            <td>' . $rows['TITLE_ID'] . '</td>
                            <td>' . $rows['TITLE_NAME'] . '</td>
                            <td>' . $rows['LAST_NAME'] . '</td>
                            <td>' . $rows['FIRST_NAME'] . '</td>
                          </tr>';
                } elseif ($reportSelection == "authorln.php") {
                    echo '<tr>
                            <td>' . $rows['FIRST_NAME'] . '</td>
                            <td>' . $rows['LAST_NAME'] . '</td>
                            <td>' . $rows['TITLE_ID'] . '</td>
                            <td>' . $rows['TITLE_NAME'] . '</td>
                          </tr>';
                } elseif ($reportSelection == "adviserln.php") {
                    echo '<tr>
                            <td>' . $rows['ADVISER_FIRST_NAME'] . '</td>
                            <td>' . $rows['ADVISER_LAST_NAME'] . '</td>
                            <td>' . $rows['TITLE_ID'] . '</td>
                            <td>' . $rows['TITLE_NAME'] . '</td>
                          </tr>';
                }
            }
            ?>
        </tbody>
    </table>
    <div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="?report=<?php echo $reportSelection; ?>&search=<?php echo $searchQuery; ?>&page=<?php echo $currentPage - 1; ?>" class="btn">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?report=<?php echo $reportSelection; ?>&search=<?php echo $searchQuery; ?>&page=<?php echo $i; ?>" class="btn <?php echo ($i == $currentPage) ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPages): ?>
        <a href="?report=<?php echo $reportSelection; ?>&search=<?php echo $searchQuery; ?>&page=<?php echo $currentPage + 1; ?>" class="btn">Next</a>
        <?php endif; ?>
</div>
    

<div class="total-count-box">
    <?php echo $offset + 1; ?> to <?php echo min($offset + $recordsPerPage, $totalcount); ?> of <?php echo $totalcount; ?> Records
</div>
<!-- Display total count of results -->
<div class="total-count-box">
    Total Records: <?php echo $totalcount; ?>
</div><!-- Dashboard Button -->

    

    <button onClick="window.location.href='admindashboard.php'">Go Back</button>
    <div class="mooter">
    <h3 style="text-align: center; color: white;">© Copyright 2024 De La Salle University - Dasmariñas</h3>
    </div>
</body>

</html>
