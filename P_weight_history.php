<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weigth History</title>
    <link rel="stylesheet" href="css/weightHistory.css">
    <style>
       
       
 
    </style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
     
<section class="main_content">
<div id="weightHistoryTable" style="display: none;">
    <h1>Weight History</h1>
    <table>
        <tr>
            <td colspan="3">
                <button id="nextButton">Graph View</button>
            </td>
        </tr>
        <tr>
            <th>
                <h4>Filter By:</h4>
            </th>
            <th>
                <label for="yearSelect">Year:</label>
                <div id="yearDropdown">
                    <select id="yearSelect" name="yearSelect">
                        <option value="0000">All Years</option>
                    </select>
                </div>
            </th>
            <th>
                <label for="months1">Month:</label>
                <div id="monthDropdown">
                    <select id="months1" name="months1">
                        <option value="00">All Months</option>
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
            </th>
        </tr>
        <tr>
            <td colspan="3">
                <button type="submit" id="applyFilters" onclick="filterData(user_id)">Apply Filters</button>
            </td>
        </tr>
    </table>

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
      
        </tbody>
    </table>
</div>

<div id="weightGraph" style="display: block;">
        <h1 class="graphTitle" >Graphs</h1>
        <button id="backButton">Weight History Table</button> 
       
        <h3>Your Weight Over Time</h3>
            <h4  for = "filter-buttons" style="font-size: 1.2rem; font-weight: bold;">Filter By:</h4>
    <div id="filter-buttons">
    
        <button id="weeklyButton " onclick="FetchUserWeightAndDateData(user_id ,'weekly')">Weekly</button>
        <button id="monthlyButton" onclick="FetchUserWeightAndDateData(user_id ,'monthly')">Monthly</button>
        <button id="yearlyButton" onclick="FetchUserWeightAndDateData(user_id ,'yearly')">Yearly</button>

    </div>
  
    <div id="chart-container">   
  
        <canvas id="lineChart" width="700px" height="300px"   ></canvas>
    </div>

        <h4>Last 8 Weeks of BMI Average</h4>
    <div id="chart-container">
            <canvas id="bmi_chart" width="700px" height="300px" ></canvas>
    </div>
    
</div>


  
     </section>
     
     </body>
     <script>
         let user_id= null;
    document.addEventListener("DOMContentLoaded", () => {
            loginStatus();
            WeightLogData();
            getUserId();  
        
           
});
 
async function getUserId() {
    try {
        const response = await fetch('php/user_id.php');
        
        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
       console.log("user ID ", data.user_id);
       return data.user_id;
        
    
    } catch (error) {
        console.error("An error occurred:", error);
      return null;
    }
 }


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
            populateYearDropdown(user_id);
            FetchUserBMIData(user_id);

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


function displayWeightLog(data) {
    const table = document.querySelector('.weight_history').getElementsByTagName('tbody')[0];
    table.innerHTML = "";
    data.forEach(row => {
        const tr = document.createElement('tr');
        
        
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
            WeightLogData();
        }
        
    }catch (error) {
        console.error("Error:", error);
        alert("An error occurred while trying to delete the lesson.");
    }
}

async function  filterData(user_id) {

var yearSelect = document.getElementById("yearSelect");
var monthSelect = document.getElementById("months1");
const weight_history = document.querySelector('.weight_history').getElementsByTagName('tbody')[0];

    var selectedYear = yearSelect.value;
    var selectedMonth = monthSelect.value;


    console.log("Selected Year:", selectedYear);
    console.log("Selected Month:", selectedMonth);

    
    try {
        const response = await fetch('php/filterTblData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',},
            body: JSON.stringify({ user_id:user_id, year: selectedYear,
                month: selectedMonth}),
        });

        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log("Success:", data);
        displayWeightLog(data);
    } catch (error) {
        console.error("Error:", error);
        


}
    
}
async function populateYearDropdown(user_id) {
    const yearSelect = document.getElementById("yearSelect");
   
    try {
        const response = await fetch('php/PopulateDropdownList.php', {
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
        let years;
        try{ years = await response.json();
        console.log("Success:", years);

            } catch (jsonError) {
                const errorText = await response.text();
                console.error("Non-JSON response received:", errorText);
                throw new Error("Expected JSON but received non-JSON response.");
            }
  
    
        years.forEach(item => {
            const option1 = document.createElement("option");
            option1.value = item.year; 
            option1.textContent = item.year;
            yearSelect.appendChild(option1);

          
        });
    } catch (error) {
        console.error("Error:", error);
    }
}

async function FetchUserWeightAndDateData(user_id,view = "weekly") {
    
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

        const data = await response.json();
        console.log("Success chart data :", data);
       
        updateChart(data, view);
    } catch (error) {
        console.error("Error:", error);
    }
}





let chartView = null;
  

function updateChart(data, view){
    console.log("update Chart Data:", data);
    console.log("update Chart View:", view);
    switch(view){
        case 'weekly':
            WeeklyLineChart(data);
            break;
        case 'monthly':
            MonthlyLineChart(data);
            break;
        case 'yearly':
            YearlyLineChart(data);
            break;
        default:
            console.log("No data found",view);
            break;
    }
}
function WeeklyLineChart(data) {
    const today = new Date();
    const currentWeek = Math.ceil(today.getDate() / 7);
    const currentMonth = today.getMonth() + 1;
    const currentYear = today.getFullYear();
    console.log("Raw Data:", data);
   

    const startOfWeek = new Date(today.setDate(today.getDate() - 7));
    const endOfWeek = new Date(today.setDate(today.getDate() + 6));
console.log("Start of Week:", startOfWeek);
console.log("End of Week:", endOfWeek);
    const weeklyData = data.filter(row => {
        const date = new Date(row.full_date);
        return date >= startOfWeek && date <= endOfWeek;
    });
    console.log("Filtered Weekly Data:", weeklyData);

    const labels = [];
    const values = [];

    for (let d = new Date(startOfWeek); d <= endOfWeek; d.setDate(d.getDate() + 1)) {
        const dateString = `${d.getDate()} ${d.toLocaleString('default', { month: 'long' })}`;
        labels.push(dateString);

        const entry = weeklyData.find(row => {
            const date = new Date(row.full_date);
            return date.getDate() === d.getDate() && date.getMonth() === d.getMonth() && date.getFullYear() === d.getFullYear();
        });

        values.push(entry ? parseFloat(entry.weight) : null);
    }

    renderChart(labels, values, "Weekly Data");
}


function MonthlyLineChart(data) {
    const today = new Date();
    const currentMonth = today.getMonth() + 1;
    const currentYear = today.getFullYear();

    const monthlyData = data.filter(row => {
        const date = new Date(row.full_date);
        return date.getFullYear() === currentYear && date.getMonth() + 1 === currentMonth;
    });
    console.log("Filtered Monthly Data:", monthlyData);

    const labels = [];
    const values = [];

    const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();

    for (let day = 1; day <= daysInMonth; day++) {
        const dateString = `${day} ${today.toLocaleString('default', { month: 'long' })}`;
        labels.push(dateString);

        const entry = monthlyData.find(row => {
            const date = new Date(row.full_date);
            return date.getDate() === day && date.getMonth() + 1 === currentMonth && date.getFullYear() === currentYear;
        });

        values.push(entry ? parseFloat(entry.weight) : null);
    }

    
    const currentMonthName = today.toLocaleString('default', { month: 'long' });

    renderChart(labels, values, `Monthly Data of ${currentMonthName}`);
}

function YearlyLineChart(data) {
    const today = new Date();
    const currentYear = today.getFullYear();

    const yearlyData = data.filter(row => {
        const date = new Date(row.full_date);
        return date.getFullYear() === currentYear;
    });
    console.log("Filtered Yearly Data:", yearlyData);

    const labels = [];
    const values = [];

    for (let month = 0; month < 12; month++) {
        const dateString = new Date(currentYear, month).toLocaleString('default', { month: 'long' });
        labels.push(dateString);

        const monthlyEntries = yearlyData.filter(row => {
            const date = new Date(row.full_date);
            return date.getMonth() === month;
        });

        const averageWeight = monthlyEntries.length > 0
            ? monthlyEntries.reduce((sum, row) => sum + parseFloat(row.weight), 0) / monthlyEntries.length
            : null;

        values.push(averageWeight);
    }


renderChart(labels, values, ` Yearly Data of ${currentYear}`);

}

function renderChart(labels, values, title) {
    if (chartView) {
        chartView.destroy();
    }
    const ctx = document.getElementById('lineChart').getContext('2d');
    chartView = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: title,
                    data: values,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true, // Enable responsiveness
            maintainAspectRatio: false, // Allow the chart to adjust its aspect ratio
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'date'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'weight'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    display: true 
                },
                tooltip: {
                    enabled: true, 
                }
            }
        }
    });
}

async function FetchUserBMIData(user_id) {
    
    try {
        const response = await fetch('php/calculateUserBMI.php', {
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

      
        const data = await response.json();
        console.log("Success BMI chart data :", data);
       
        renderBMIChart(data);
    } catch (error) {
        console.error("Error:", error);
    }
}

function renderBMIChart(data) {
    const ctx = document.getElementById('bmi_chart').getContext('2d');
    const labels = Object.keys(data.weeklyBmi).map((week, index) => `Week ${index + 1}`);
   
    const values = Object.values(data.weeklyBmi);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Weekly BMI',
                data: values,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2
            }]
        },
        options: {
            
           
            
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Week'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'BMI'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    display: true
                },
                tooltip: {
                    enabled: true
                }
             
               
            }

        }
    });
}
       
    
      
        document.getElementById('nextButton').addEventListener('click', function(event) {
        document.getElementById('weightHistoryTable').style.display = 'none';
        document.getElementById('weightGraph').style.display = 'block';
            });
       

        document.getElementById('backButton').addEventListener('click', function () {
        document.getElementById('weightGraph').style.display = 'none';
        document.getElementById('weightHistoryTable').style.display = 'block';
            });
</script>
     </html>