<?php
session_start();
require_once '../db/db_config.php';

if (!isset($_POST['course'])) {
    exit();
}

$course = $_POST['course'];
$batch = $_POST['batch'];
$employment = $_POST['employment'];
$where = '';
if ($course != '' || $batch != '' || $employment != '') {

    $where = 'WHERE 1 = 1';

    if ($course != '') $where .= " AND u.course = '$course'";
    if ($batch != '') $where .= " AND u.batch = '$batch'";
    if ($employment != '') $where .= " AND u.employment_status = '$employment'";
}

// SQL query to fetch data
$sql = "SELECT u.student_id, u.first_name, u.middle_name, u.last_name, u.extension_name, u.birth_date, u.address_line, u.muncity as municipality, u.province, u.contact, u.email, c.description as course, u.batch FROM users u INNER JOIN courses c ON u.course = c.id $where";

// Execute the query and store the result set
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Get the number of rows in the result set
$rows = mysqli_num_rows($result);

// Get the column names
$columns = mysqli_fetch_fields($result);

// File name
$filename = "Alma-Tech_Alumni_List.csv";

// Open the file for writing
$fp = fopen($filename, 'w');

// Add the column names to the first row of the file
$header = array();
foreach ($columns as $column) {
    $header[] = $column->name;
}
fputcsv($fp, $header);

// Add the data to the file
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($fp, $row);
}

// Close the file
fclose($fp);

// Free up the memory used by the result set
mysqli_free_result($result);

// Close the database connection
mysqli_close($conn);

// Set the appropriate headers
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=" . $filename);

// Read the file and send it to the output
readfile($filename);

// Remove the file from the server
unlink($filename);
