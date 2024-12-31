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
        <!--
        <div class="input_weight">
             <h5>Weight:</h5>
        <input type="weight" name="weight"  id="weight" placeholder="weight in kg " required>
        </div>
-->
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
        
        <!-- <div class="weight_fluctuation">
             <h5>Weigth Change Threshold:</h5>
        <input type="percentage" name="weight_fluctuation" id="weight_fluctuation" placeholder="weight_fluctuation" required>
        </div>-->
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
                <!-- <button type="submit" onclick="updateUserData(user_id)" >submit</button>  -->
        <button  id="updateButton" >submit</button>
             </div>
             
      
            
         </section>
         </body>
         <script src="js/user_details.js">
         /*   let user_id = null;
            
  document.addEventListener("DOMContentLoaded", () => {
            loginStatus();
           

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
    
        loadUserData(user_id);
      
       
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
async function loadUserData(user_id) {
    try {
        const response = await fetch('php/displayUserDetails.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: user_id }),
        });

        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const details = await response.json();
        console.log("Parsed JSON:", details);

        if (details.success) {
            const userData = details.data;
            
            document.getElementById("name").value = `${userData.name}`;
            document.getElementById("surname").value = `${userData.surname}`;
            document.getElementById("email").value = `${userData.email}`;
            document.getElementById("height").value = `${userData.height}`;
            document.getElementById("Age").value = `${userData.age}`;
            document.getElementById("target_weight").value = `${userData.target_weight}`;
            document.getElementById("weight").value = `${userData.weight}`;
            document.getElementById("health_Condition").value = `${userData.health_Condition}`;
            //document.getElementById("weight_fluctuation").value = `${userData.weight_fluctuation}`;
            document.getElementById("inactivity_reminder").value = `${userData.inactivity_reminder}`;

} else {
    console.error("Element with id 'inactivity_reminder' not found in the DOM.");
}
       
    } catch (error) {
        console.error("Error:", error);
    }
}

document.getElementById("updateButton").addEventListener("click", async () => {

    const inputValues = {
        inactivity_reminder: document.getElementById("inactivity_reminder").value,
        name: document.getElementById("name").value,
        surname: document.getElementById("surname").value,
        email: document.getElementById("email").value,
        height: document.getElementById("height").value,
        Age: document.getElementById("Age").value,
        target_weight: document.getElementById("target_weight").value,
        weight: document.getElementById("weight").value,
        health_Condition: document.getElementById("health_Condition").value,
        //weight_fluctuation: document.getElementById("weight_fluctuation").value,
    };

    console.log("Collected input values:", inputValues);
    await updateUserData(user_id, inputValues);
});

document.getElementById("milestoneButtonOn").addEventListener("click", () => {
    milestoneAlertONorOFF(user_id, 'on');
});

document.getElementById("milestoneButtonOff").addEventListener("click", () => {
    milestoneAlertONorOFF(user_id, 'off');
});

async function updateUserData(userId, input) {
    try {
        const updateData = { user_id: userId, ...input };
        console.log( updateData);

        const response = await fetch('php/updateUserDetails.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(updateData),
        });

        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        console.log("Update success:", result);

        if (result.message) {
            alert(result.message);

           
            Object.entries(input).forEach(([key, value]) => {
                const element = document.getElementById(key);
                if (element) element.value = value;
            });
        } else if (result.error) {
            alert(result.error);
        } else {
            console.error("Unexpected response format:", result);
            alert("An unexpected error occurred.");
        }
    } catch (error) {
        console.error("Error in updateUserData:", error);
        alert("An error occurred while updating user data.");
    }
}



 
async function milestoneAlertONorOFF(user_id, milestone_alert) {
    try {
        const response = await fetch('php/milestoneAlert.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: user_id, milestone_alert: milestone_alert }),
        });

        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        console.log("Success:", result);

    } catch (error) {
        console.error("Error:", error);
    }
}*/
</script>
         </html>