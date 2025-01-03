<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weigth History</title>
    <link rel="stylesheet" href='css/Login_&_Registration.css'>
    <style>
      table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color:#333 ;
            color: white;
            padding: 10px;
            border: 1px solid #ddd;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        table {
            background-color: #D3D3D3
            ;
        }
       
    </style>
</head>
<body>
<header>
    <nav class="navbar">
            <div class="nav-left">
                <button class="nav-button" onclick="window.location.href='P_logWeight.php';">Dashboard</button>
                <button class="nav-button" onclick="window.location.href='P_weight_history.php';">History</button>
    
            </div>
            <div class="nav-center">
            <button class="nav-button" id="ButtonLogout">Logout</button>
               
                <button class="nav-button" onclick="window.location.href='P_user_details.php';">User</button>
                
                </div>
            
        </nav>
    </header>
  
    <h1>Weight History</h1>

    <!-- Filter Form -->
    <form id="filterForm">
        <label for="filterType">Filter By:</label>
        <select id="filterType" name="filterType">
       <!-- <option value="latest">Latest</option>
            <option value="oldest">Oldest</option> -->
            <option value="year">Year</option>
            <option value="month">Month</option>
        </select>

        <div id="yearDropdown" style="display: none;">
            <select id="yearSelect" name="yearSelect">
                <!-- Year options will be dynamically populated -->
            </select>
        </div>

        <div id="monthDropdown" style="display: none;">
            <select id="monthSelect" name="monthSelect">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>

        <div id="sortOrderDropdown" style="display: none;">
            <label for="sortOrder">Sort Order:</label>
            <select id="sortOrder" name="sortOrder">
                <option value="latest">Latest</option>
                <option value="oldest">Oldest</option>
            </select>
        </div>

        <button type="submit">Apply Filters</button>
    </form>

    <!-- Table to display data -->
    <table class="weight_history">
        <thead>
            <tr>
         
                <th>day</th>
                <th>month</th>
                <th>year</th>
                <th>weight</th>
                <th>body Mass percentage</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows will be inserted here -->
        </tbody>
    </table>

    <script>
       document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const filterType = document.getElementById('filterType').value;
    let selectedValue = '';
    const sortOrder = document.getElementById('sortOrder').value;

    // Get selected value based on filter type (year, month, etc.)
    if (filterType === 'year') {
        selectedValue = document.getElementById('yearSelect').value;
    } else if (filterType === 'month') {
        selectedValue = document.getElementById('monthSelect').value;
    }

    fetchWeightLogs(filterType, selectedValue, sortOrder);
});

// Function to fetch filtered weight logs based on filter criteria
function fetchWeightLogs(filterType, selectedValue, sortOrder) {
    fetch('php/listLogsCOPY.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            filterType: filterType,
            selectedValue: selectedValue,
            sortOrder: sortOrder
        })
    })
    .then(response => response.json())
    .then(data => {
        displayWeightLog(data);
    })
    .catch(error => {
        console.error("Error fetching data:", error);
    });
}

function displayWeightLog(data) {
    const table = document.querySelector('.weight_history').getElementsByTagName('tbody')[0];
    table.innerHTML = ''; // Clear previous data

    data.forEach(row => {
        const tr = document.createElement('tr');
        
        // Extract month, day, and year from the full date
        const month = new Date(row.full_date).toLocaleString('default', { month: 'long' });
        const year = row.year;
        const day = row.day;

        tr.innerHTML = `
            <td>${day}</td>
            <td>${month}</td>
            <td>${year}</td>
            <td>${row.weight}</td>
            <td>${row.bodyMass_percentage}</td>
        `;
        table.appendChild(tr);
    });
}

// Show/hide year, month, and sort order dropdown based on selected filter type
function toggleFilterOptions() {
    const filterType = document.getElementById('filterType').value;

    // Show year/month dropdowns based on filter type
    if (filterType === 'year') {
        document.getElementById('yearDropdown').style.display = 'block';
        document.getElementById('monthDropdown').style.display = 'none';
        document.getElementById('sortOrderDropdown').style.display = 'block'; // Show Sort Order
    } else if (filterType === 'month') {
        document.getElementById('monthDropdown').style.display = 'block';
        document.getElementById('yearDropdown').style.display = 'none';
        document.getElementById('sortOrderDropdown').style.display = 'block'; // Show Sort Order
    } else {
        document.getElementById('yearDropdown').style.display = 'none';
        document.getElementById('monthDropdown').style.display = 'none';
        document.getElementById('sortOrderDropdown').style.display = 'none'; // Hide Sort Order
    }
}

document.getElementById('filterType').addEventListener('change', toggleFilterOptions);

// Populate year dropdown dynamically when the page loads
function populateYearDropdown() {
    const yearSelect = document.getElementById('yearSelect');
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= currentYear - 10; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }
}

window.onload = populateYearDropdown;

    </script>
</body>
</html>