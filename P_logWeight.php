<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Log</title>
    <link rel="stylesheet" href="css/Login_&_Registration.css">
    <style>
        body {
            background-color: white;
        }

        #Weight_log {
            display: block;
            padding:50px;
        }

        .date_&_time {
            display: block;
            padding: 150px;
        }

        .progress_bar {
            width: 100%;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress {
            height: 20px;
            background-color:rgba(9, 9, 10, 0.68);
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
     
     <section class="main_content">
     <h5>Progress bar:</h5>
        <p>Current weight: <span id="current_weight">0</span> kg</p>
        <div class="progress_bar">
            <div class="progress" id="progress" style="width: 0%;"></div>
        </div>
        <p>Target weight: <span id="target_weight">0</span> kg</p>
        <div id="percentageMessage">
            <p id="percentageMessageText"></p>
    

       </div>
       <form id="Weight_log" >
        
        <label for="weight"><h1>Log Weight:</h1></label>
        <input type="number" id="weight" name=" weight" placeholder=" log weight" required min="1" max="1000" step= "0.01" value="00">
        <div class = "date_&_time">
        <input type ="datetime-local" id ="date_time" name = "date_time" placeholder = "Date And Time">
        <input type="hidden" name="user_id" value="10">
        </div>
      <input type="submit">
      </form>
      
      
    
    </section>

         </body>
         <script>
           document.addEventListener("DOMContentLoaded", () => {
            loginStatus();
            defaultDate();
            progressBar();
          
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

        if (!data.authenticated) {
            window.location.href = 'P_Login_&_Registration.php';
        }
    } catch (error) {
        console.error("An error occurred:", error);
      window.location.href = 'P_Login_&_Registration.php';
    }
}
    document.getElementById('Weight_log').addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            try {
                const response = await fetch('php/logWeight.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    const errorBody = await response.text();
                    console.error("Error Response Body:", errorBody);
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                 progressBar();
            } catch (error) {
                console.error('Error logging weight:', error);
            }
        });
   
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
 
function defaultDate() {
    const userInput = document.getElementById("date_time");
            if (!userInput.value) {
                const today = new Date(); 
        const currentDate = today.toISOString().slice(0, 10);
        const hours = today.getHours().toString().padStart(2, '0');
        const minutes = today.getMinutes().toString().padStart(2, '0');
        const currentTime = hours + ':' + minutes;
        const dateTimeValue = currentDate + 'T' + currentTime;
        console.log(dateTimeValue);
        document.getElementById('date_time').value = dateTimeValue;
            }
        }
       
        async function progressBar() {
            try {
                const response = await fetch('php/progressBar.php');
                
                if (!response.ok) {
                    const errorBody = await response.text();
                    console.error("Error Response Body:", errorBody);
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                document.getElementById('current_weight').textContent = data.current_weight;
                document.getElementById('target_weight').textContent = data.target_weight;
                document.getElementById('progress').style.width = data.progress + '%';
                ProgressMessage(data.progress);
            } catch (error) {
                console.error('Error fetching progress data:', error);
            }
        }   
        
        function ProgressMessage(progress) {
            const messageElement = document.getElementById('percentageMessageText');
            let percentage = `You're at ${progress.toFixed(0)}%. `;
            if(progress >= 100) {
                percentage += "You Did It! Set your next goal in the user page";
            } else if (progress >= 80) {
                percentage += "Almost there!";
            } else if (progress >= 50) {
                percentage += "Just halfway there !";
            } else if (progress >= 20) {
                percentage += "Good progress!";
            } else {
                percentage += "lets start!";
            }
            messageElement.textContent = percentage;
        }
</script>
         </html>