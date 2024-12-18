<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weigth History</title>
    <link rel="stylesheet" href="css/Login_&_Registration.css">
    <style>
        body {
            margin: 0;
  padding: 0;
  box-sizing: border-box;
        }

        .main_content {
            display: flex;
  justify-content: center; /* Horizontally center content */
  align-items: center; /* Vertically center content */
  flex-direction: column; /* Stack children vertically */
  text-align: center;
  min-height: 80vh; /* Use at least 80% of the viewport height */
  padding: 20px;
  background: #f2f2f2;
  margin:auto; 
        }
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
            text-align: center;
        }
        table {
            background-color: #D3D3D3
            ;
        }
       #deleteLoggsBtn{
        color: white;
        margin-top: 5px;
        width: 100%;
        background-color: #333;
        padding-top: 8px;
        padding-bottom: 8px;
        padding-left: 15px;
        padding-right: 15px;
        display: block;
        cursor: pointer; 
            box-sizing: border-box;
            transition: background-color 0.3s, transform 0.2s;
    }
      
    #deleteLoggsBtn:hover {
            background-color: #555;
            color: #fff;
           
        }
        #deleteLoggsBtn:active {
            background-color: #222;
            transform: scale(0.95);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) inset;
        }
        #filterForm {
      display: flex;
      align-items: center; 
      gap: 10px;
      margin-bottom: 10px; 
    }

 
    #filterForm label {
      margin: 0 5px; 
    }
       
    </style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
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
<body>

     
     <section class="main_content">
     <h1>Weight History</h1>
     <form id="filterForm">
        <label for="filterForm">Sort By :</label>
<label for="yearSelect">Year:</label>
        <div id="yearDropdown">
            <select id="yearSelect" name="yearSelect">
                <!-- Year options will be dynamically populated -->
            </select>
        </div>

        <div id="monthDropdown">
            <label for="months1"> Month:</label>
            <select id="months1" name="months1">
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

        <button type="submit">Apply Filters</button>
    </form>

    <!-- Table to display data -->
    <table class="weight_history">
        <thead>
            <tr>
         
                <th>Day</th>
                <th>Month</th>
                <th>Year</th>
                <th>Weight</th>
                <th>Body Mass Percentage</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows will be inserted here  style="background:grey" -->
        </tbody>
    </table>
      <h1>Graphs</h1>
      <div id="graph_container">
<div id="line_charts">
<h3>Line Charts</h3>
<label for="Months2">Month:</label>
<select id="Months2">
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
<button id="dailyButton">Daily</button>
    <button id="monthlyButton">Monthly</button>
    <button id="yearlyButton">Yearly</button>

    <div class="chart" >  <canvas id="line_chart_weekly_weight" width="700px" height="300px" style ></canvas></div>
      <canvas id="line_chart_weekly_monthly" width="400" height="200"></canvas>
      <canvas id="line_chart_weekly_yearly" width="400" height="200"></canvas>
      </div>
        <div id="Bmi_tracking">
            <h3>BMI Tracking</h3>
            <div id="bmi_chart">
            <canvas id="pie_bmi_chart_weekly"></canvas>
            <canvas id="pie_bmi_chart_monthly"></canvas>
            <canvas id="pie_bmi_chart_yearly"></canvas>
            </div>
  
        </div>
       <!--comparing between averages  Group records using avergae function on weight and group by weekly etc..-->
        <div class="average_weight">
            <h3>Average Weight</h3>
            <div id="average_weight_chart">
            <canvas id="pie_average_weight_chart_weekly"></canvas>
            <canvas id="pie_average_weight_chart_monthly"></canvas>
            <canvas id="pie_average_weight_chart_yearly"></canvas>
            <!--OR-->
            <canvas id="horizontal_bar_average_weight_chart_weekly"></canvas>
            <canvas id="horizontal_bar_average_weight_chart_monthly"></canvas>
            <canvas id="horizontal_bar_average_weight_chart_yearly"></canvas>
            </div>

            <div id="scatter_chart_weight_fluctuation">
                <h3>Weight Fluctuation</h3>
                <canvas id="scatter_chart_weight_fluctuation_weekly"></canvas>
                <canvas id="scatter_chart_weight_fluctuation_monthly"></canvas>
                <canvas id="scatter_chart_weight_fluctuation_yearly"></canvas>
            </div>
            <div id="scatter_plott_user_weight_logging">
                <h3>User Weight Logging</h3>
                <canvas id="scatter_plott_user_weight_logging_weekly"></canvas>
                <canvas id="scatter_plott_user_weight_logging_monthly"></canvas>
                <canvas id="scatter_plott_user_weight_logging_yearly"></canvas>
            </div>
     
        </div>
      </div>
     </section>
     
     </body>
     <script>
         let user_id = null;

         const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        let chartInstance = null;
        let rawData = [];  

    document.addEventListener("DOMContentLoaded", () => {
            loginStatus();
            WeightLogData();
           
});
 
         async function loginStatus() {
    try {
        const response = await fetch('php/authentication.php', {
            credentials: 'include',
        });
        
        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
       
            user_id = data.user_id; 
            FetchUserWeightAndDateData(user_id);

        if (!data.authenticated) {
            window.location.href = 'P_Login_&_Registration.php';
        }
    } catch (error) {
        console.error("An error occurred:", error);
      window.location.href = 'P_Login_&_Registration.php';
    }
}

document.getElementById('ButtonLogout').addEventListener('click', ButtonLogout);

async function ButtonLogout() {
    try {
        const response = await fetch('php/logout.php', {
            method: 'POST', 
            credentials: 'include',
        });

        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
 window.location.href = 'P_Login_&_Registration.php';
        } else {
            alert(data.message || 'Failed to log out.');
        }
    } catch (error) {
        console.error('An error occurred during logout:', error);
        alert('An error occurred. Please try again.');
    }
}
async function WeightLogData() {
    fetch('php/listLoggs.php', {
        method: 'POST',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        displayWeightLog(data); 
    })
    .catch(error => {
        console.error("Error fetching data:", error);
    });   
}

/*function displayWeightLog(data) {
    const table = document.querySelector('.weight_history');
    table.innerHTML = `
        <tr>
            <th>Date And Time </th>
            <th>Weight</th>
            <th>Body Mass Percentage</th>
        </tr>
    `;

    data.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.date_time}</td>
            <td>${row.weight}</td>
            <td>${row.bodyMass_percentage}</td>

        `;
        table.appendChild(tr);
    });
    WeightLogData();
}*/



function fetchWeightLogs() {
    fetch('php/listLoggs.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
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
    table.innerHTML = "";
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
            <button id="deleteLoggsBtn" onclick="deleteLog(${row.user_id}, ${row.weight_id})">Delete</button>
        `;
        table.appendChild(tr);
    });
}
async function deleteLog(user_id,weight_id){
    if (!confirm("Are you sure you want to delete this weight log?")) {
        return;
    }
    const apiUr = 'php/deleteWeightLog.php';
    try {
        const response = await fetch(apiUr, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id, weight_id })
        });

        const responseClone = response.clone();

        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        let result;
         
        try {
            result = await response.json();
        } catch (jsonError) {
           
            const errorText = await responseClone.text();
            console.error("Non-JSON response received:", errorText);
            throw new Error("Expected JSON but received non-JSON response.");
        }

         if (result.error) {
            alert(result.error);
           
        } else if (result.message){
            alert("Lesson deleted successfully.");
            fetchWeightLogs();
        }
        
    }catch (error) {
        console.error("Error:", error);
        alert("An error occurred while trying to delete the lesson.");
    }
}

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

async function FetchUserWeightAndDateData(user_id,) {
    try {
        const response = await fetch('php/ChartWeightData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: user_id}),
        });

        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        console.log("Success:", result);
        updateChart('daily', 1);
         DailyLineChart(result);
    //MonthlyLineChart(result);
   // YearlyLineChart(result);

    } catch (error) {
        console.error("Error:", error);
    }
}
/*
function DailyLineChart(data) {
   
            const ctx = document.getElementById('line_chart_weekly_weight').getContext('2d');

          
            const labels = data.map(entry => entry.full_date);
            const weights = data.map(entry => parseFloat(entry.weight));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Daily Weight',
                        data: weights,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3
            }]
        },
        options: {
           responsive: false, // Disable responsiveness
  maintainAspectRatio: false,
          
        }
    });
}*/
/*
function updateChart(viewType, selectedMonth = null) {
            const ctx = document.getElementById('lineChart').getContext('2d');

            let labels = [], dataPoints = [];

            if (viewType === 'daily') {
                // Filter data for the selected month
                const filteredData = rawData.filter(row => row.month == selectedMonth);
                labels = filteredData.map(row => `${row.day} ${monthNames[row.month - 1]}`);
                dataPoints = filteredData.map(row => parseFloat(row.weight));
            } else if (viewType === 'monthly') {
                // Group data by month
                const monthlyData = {};
                rawData.forEach(row => {
                    const key = `${monthNames[row.month - 1]} ${row.year}`;
                    if (!monthlyData[key]) monthlyData[key] = [];
                    monthlyData[key].push(parseFloat(row.weight));
                });
                labels = Object.keys(monthlyData);
                dataPoints = Object.values(monthlyData).map(arr => 
                    arr.reduce((a, b) => a + b, 0) / arr.length);
            } else if (viewType === 'yearly') {
                // Group data by year
                const yearlyData = {};
                rawData.forEach(row => {
                    const year = row.year;
                    if (!yearlyData[year]) yearlyData[year] = [];
                    yearlyData[year].push(parseFloat(row.weight));
                });
                labels = Object.keys(yearlyData);
                dataPoints = Object.values(yearlyData).map(arr => 
                    arr.reduce((a, b) => a + b, 0) / arr.length);
            }

            // Destroy previous chart instance
            if (chartInstance) chartInstance.destroy();

            // Create new chart
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: viewType === 'daily' ? `Daily Weight (${monthNames[selectedMonth - 1]})` :
                               viewType === 'monthly' ? 'Monthly Average Weight' :
                               'Yearly Average Weight',
                        data: dataPoints,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Time' } },
                        y: { title: { display: true, text: 'Weight (kg)' } }
                    }
                }
            });
        }

        // Event Listeners
        document.getElementById('monthDropdown').addEventListener('change', (e) => {
            const selectedMonth = parseInt(e.target.value);
            updateChart('daily', selectedMonth);
        });

        document.getElementById('dailyButton').addEventListener('click', () => {
            const selectedMonth = parseInt(document.getElementById('monthDropdown').value);
            updateChart('daily', selectedMonth);
        });

        document.getElementById('monthlyButton').addEventListener('click', () => {
            updateChart('monthly');
        });

        document.getElementById('yearlyButton').addEventListener('click', () => {
            updateChart('yearly');
        });

        // Initialize with sample user ID
        document.addEventListener('DOMContentLoaded', () => {
            const userId = 1; // Replace with a dynamic user ID
            fetchData(userId);
        });
        */   
</script>
     </html>