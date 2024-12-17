<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Log</title>
    <link rel="stylesheet" href="css/Login_&_Registration.css">
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
       <form action=" http://localhost/Dissertation_project/php/logWeight.php" method="POST" >
         
       
         <div class="Weight_log">
            <label for="weight"><h1>Log Weight:</h1></label>
        <input type="number" id="weight" name=" weight" placeholder=" log weight" required min="1" max="1000" value="00">
        </div>
  
  <div class = "date_&_time">
      <input type ="datetime-local" id ="date_time" name = "date_time" placeholder = "Date And Time">
      <input type="hidden" name="user_id" value="10">
      </div>
      <input type="submit">
       </form>
    
             <h5>Progress bar:</h5>current weight
       <div class="progress_bar">
           
       </div>target weight
        
         
         </section>
         </body>
         <script>
           document.addEventListener("DOMContentLoaded", () => {
            loginStatus();
            defaultDate();
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
       
</script>
         </html>