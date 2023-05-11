<?php
session_start();
require_once '../db/db_config.php';

if (!isset($_POST['course'])) {
    exit();
}

$type = $_POST['type'];
$department = $_POST['department'];
$course = $_POST['course'];
$batch = $_POST['batch'];
$employment = $_POST['employment'];
$gender = $_POST['gender'];
$civil = $_POST['civil'];
$location = $_POST['location'];
$where = '';


$fields = $_POST['wildcard'];

$fieldsArr = explode(",", $fields);

if ($fieldsArr[0] == "")
    array_shift($fieldsArr);

$where = 'WHERE birth_date IS NOT NULL';
$sqlFields = [];

foreach ($fieldsArr as $field) {
    if ($field == "department") {
        array_push($sqlFields, 'd.description as department');
        if ($department != '') $where .= " AND c.department_id = '$department'";
    } elseif ($field == "course") {
        array_push($sqlFields, 'c.description as course');
        if ($course != '') $where .= " AND u.course = '$course'";
    } elseif ($field == "batch") {
        array_push($sqlFields, 'u.batch');
        if ($batch != '') $where .= " AND u.batch = '$batch'";
    } elseif ($field == "employment status") {
        array_push($sqlFields, 'u.employment_status');
        if ($employment != '') {
            if ($employment != 2) $where .= " AND u.employment_status !=2";
            else  $where .= " AND u.employment_status = 2";
        }
    } elseif ($field == "gender") {
        array_push($sqlFields, 'u.gender');
        if ($gender != '') $where .= " AND u.gender = '$gender'";
    } elseif ($field == "civil status") {
        array_push($sqlFields, 'u.civil_status');
        if ($civil != '') $where .= " AND u.civil_status = '$civil'";
    } elseif ($field == "location") {
        array_push($sqlFields, "CONCAT(address_line, ', ' , muncity, ', ' , province) as location");
        if ($location != '') $where .= " AND province LIKE '%$location%'";
    } elseif ($field == "student_id") {
        array_push($sqlFields, "student_id");
    } elseif ($field == "name") {
        array_push($sqlFields, "CONCAT(u.first_name, ' ' , u.middle_name, ' ' , u.last_name , ' ' , u.extension_name) as name");
    } elseif ($field == "birth_date") {
        array_push($sqlFields, "birth_date");
    } elseif ($field == "contact") {
        array_push($sqlFields, "contact");
    } elseif ($field == "email") {
        array_push($sqlFields, "email");
    } elseif ($field == "position") {
        array_push($sqlFields, "current_position as position");
    } elseif ($field == "zip_code") {
        array_push($sqlFields, "zip_code");
    }
}

if ($type == 'registered') {
    $where .= ' AND is_verified = 1';
} elseif ($type == 'unverified') {
    $where .= ' AND is_verified = 0';
}


if (count($sqlFields) > 0) {
    $qq =  implode(', ', $sqlFields);
}

// SQL query to fetch data
$sql = "SELECT $qq FROM users u INNER JOIN courses c ON u.course = c.id INNER JOIN departments d ON c.department_id = d.id $where";

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
fputcsv($fp, ['AlUMNI INFORMATION - MASTER LIST']);
// Add the column names to the first row of the file
$header = array();
foreach ($columns as $column) {
    $header[] =  ucwords(str_replace('_', ' ', $column->name));
}
fputcsv($fp, $header);
// Add the data to the file
while ($row = mysqli_fetch_assoc($result)) {
    $row = array_map(function ($str, $index) {

        if ($index == 'employment_status') {
            switch ($str) {
                case 1:
                    return 'Employed';
                    break;
                case 2:
                    return 'Unemployed';
                    break;
                case 3:
                    return 'Self-Employed';
                    break;
            }
        } else if ($index == 'gender') {
            switch ($str) {
                case '1':
                    return 'Male';
                    break;
                case '2':
                    return 'Female';
                    break;
            }
        } else  if ($index == 'civil_status') {
            switch ($str) {
                case '1':
                    return 'Single';
                    break;
                case '2':
                    return 'Married';
                    break;
                case '3':
                    return 'Annuled';
                    break;
            }
        }
        if (
            $index != 'email'
        ) {
            return strtoupper($str);
        }

        return $str;
    }, $row, array_keys($row));
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
