<?php
header('Content-Type: application/json');

require 'connectionScript.php';
// Get the JSON input from the request
$data = json_decode(file_get_contents('php://input'), true);

// Ensure we handle missing data correctly
$filterType = isset($data['filterType']) ? $data['filterType'] : 'latest';  // Default to 'latest' if not set
$selectedValue = isset($data['selectedValue']) ? $data['selectedValue'] : '';  // Default to empty string if not set
$sortOrder = isset($data['sortOrder']) ? $data['sortOrder'] : 'latest';  // Default to 'latest' if not set

// Initialize SQL query
$query = "SELECT 
            DATE(date_time) AS full_date, 
            YEAR(date_time) AS year, 
            MONTH(date_time) AS month, 
            DAY(date_time) AS day,
            weight, 
            bodyMass_percentage 
          FROM weight_recorded";

// Apply filters
if ($filterType === 'year') {
    $query .= " WHERE YEAR(date_time) = '$selectedValue'";
} elseif ($filterType === 'month') {
    $query .= " WHERE DATE_FORMAT(date_time, '%Y-%m') = '$selectedValue'";
} elseif ($filterType === 'latest' || $filterType === 'oldest') {
    // Sort by full date
    $query .= " ORDER BY date_time " . ($sortOrder === 'oldest' ? 'ASC' : 'DESC');
}


// Execute the query
$result = $conn->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'full_date' => $row['full_date'], // Full date (Year-Month-Day)
            'year' => $row['year'],
            'month' => $row['month'],
            'day' => $row['day'],
            'weight' => $row['weight'],
            'bodyMass_percentage' => $row['bodyMass_percentage']
        ];
    }
} else {
    $data = [];  // No records found
}

$conn->close();

// Return the filtered data as JSON
echo json_encode($data);
?>
