<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <link rel="stylesheet" href="css/Login_&_Registration.css">
<style>body {
 background-color: white; 
}

.forms_container{
    background-color: grey; 
    padding:150px;
  
}

    #login_form {
    display: block;
}

#registration_form {
    display: none;
}
</style></head> 
<body >
    <header>
    </header>
     
     <section class="main_content">
         <div class="forms_container">
            <div id="status" class="status" > default </div>
             <form id="registration_form" action=" php/registration.php" method="POST">
                <h1> Registration</h1>
         <div class="input_name">
        <input type="text" name="name" placeholder=" name" required>
        </div>
        <div class="input_surname">
            <input type="text" name="surname" placeholder="surname" required>
            </div>
            <div class="input_username">
                <input type="text" name="username" placeholder="username" required>
                </div>
         <div class="input_email">
        <input type="email" name="email" placeholder="email" required>
        </div>
        
        <div class="input_password">
        <input type="password" name="password" autocomplete="off" placeholder="Password" required>
        </div>
        
            <div class="input_age">
        <input type="int" name="age" placeholder="age" required>
        </div>
        <div class="input_height">
        <input type="number" name="height" placeholder="height in cm" required>
        </div>
        <div class="input_current_weight">
        <input type="weight" name=" weight" placeholder="current weight in kg" required>
        </div>
          <div class="input_target_weight">
        <input type="target_weight" name="target_weight" placeholder="target weight in kg" required>
        </div>
        
        <div class="input_health_Condition">
            <select type="tel" name="health_Condition" placeholder="Health Condition" required>
            <option value="Diabetes">Diabetic</option>
            <option value="Prediabetes">Pre_Diabetic</option>
        </select>
    </div>
           
         
            <div id="log_Link_container">
            <a href class="login_link" id="login_link"> Login</a>
        </div>
            <div class="button">
        <button type="submit">Register</button>
                </div>
          </form>
             
              <form id="login_form" action="php/login.php" method="POST">
                      <h1> Login</h1>
        
         <div class="input_email">
        <input type="email" name="email" placeholder="email" required>
        </div>
        
        <div class="input_password">
        <input type="password" name="password" autocomplete="on" placeholder="Password" required>
        </div>
        <div  id="reg_Link_container">
            <a href="#"  id="registration_link">registration</a>
        </div>

        
          <div class="button">
        <button type="submit">Login</button>
             </div>
            </form>
         </div>
        
       <script >
        document.addEventListener("DOMContentLoaded", () => {
          showStatus();
});
     /*
       function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
      }*/

function showStatus(status){
    var sessionValue = getCookie("status");
    
    document.getElementById('status').innerHTML = sessionValue;
}    
        document.getElementById('registration_link').addEventListener('click', function(event) {
        document.getElementById('login_form').style.display = 'none';
        document.getElementById('registration_form').style.display = 'block';
            });
       

        document.getElementById('login_link').addEventListener('click', function () {
        document.getElementById('reg_Link_container').style.display = 'none';
        document.getElementById('log_Link_container').style.display = 'block';
            });


    document.getElementById('registration_form').addEventListener('submit', function(event) {
  const formData = new FormData(e.target);
    
  const apiUrl = 'php/login.php'; 
      const DateTime = new Date().toISOString();
      
      
     
      const weight = parseFloat(document.getElementById('current_weight').value);
      const targetWeight = parseFloat(document.getElementById('target_weight').value);
  
    });


    document.getElementById('login_form').addEventListener('submit', async (e) => {
    e.preventDefault(); 
    
     const formData = new FormData(e.target);
    
  const apiUrl = 'php/login.php'; 

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            body: formData,
            credentials:'include'
             
        });

           
        const responseClone = response.clone(); 

        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
try{
        const data = await response.json(); 
        
if (data.success) {
        alert(data.message);
        window.location.href = 'P_logWeight.php';
    } else {
        alert(data.message);
    }

    
    }  catch (jsonError) {
            const errorText = await responseClone.text();
            console.error("Non-JSON response received:", errorText);
            throw new Error("Expected JSON but received non-JSON response.");
        }
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
}catch (error) {
        console.error("Error:", error);
}

});




</script>
     </section>
     </body>
     
</html>