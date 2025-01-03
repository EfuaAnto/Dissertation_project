<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
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
        
         <div class="aside-L">
         <h1>User Details</h1>
         
         <div class="change_input_name">
             <h5>Name:</h5>
        <input type="text" name="name" id="name" placeholder="name" required>
        </div>

        <div class="change_input_name">
             <h5>Surname:</h5>
        <input type="text" name="surname" id="surname" placeholder="surname" required>
        </div>
        
         <div class="input_email">
             <h5>E-mail:</h5>
        <input type="email" name="email" id="email" placeholder="E-mail" required>
        </div>

        
        <div class="input_height">
             <h5>Height:</h5>
        <input type="number" name="height" id="height"  placeholder="height in metres" required>
        </div>
       
        <div class="Age">
             <h5>Age:</h5>
        <input type="Age" name="Age"  id="Age" placeholder="Age" required>
        </div><br>
        </div>
        <div class="aside-R">
            <div class="milestone alert">
            <lable>Milestone Alert</lable>
           
<button id="milestoneButtonOn">ON</button>
<button id="milestoneButtonOff">OFF</button>

</div>
</div>
       
             <div class="target_weight">
             <h5>Target Weight:</h5>
        <input type="weight" name="target_weight" id="target_weight"  placeholder="target_weight" required>
        </div>

        <h5>Health Condition:</h5>
        <div class="health_Condition">
            <input type="text" name="health_Condition"  id="health_Condition" placeholder="Health Condition" required>
            </div>
       
            <h5>Inactivity Reminder:</h5>
            <label for="inactivity_reminder">Days:</label>
            <select id="inactivity_reminder">
    <option value="1">1 Day</option>
    <option value="2">2 Days</option>
    <option value="3">3 Days</option>
</select> <br>
<br>
            
              <div class="button">
              
        <button  id="updateButton" >submit</button>
             </div>
             
      
            
         </section>
         </body>
         <script src="js/user_details.js"></script>
         </html>