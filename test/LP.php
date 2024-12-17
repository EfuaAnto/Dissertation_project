Login.html	<!DOCTYPE html>
<html lang="en">
<head>
    <title>Homepage</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href='../css/login.css'>
</head>
<body>
<!-- login and registration form containers-->
    <section id="container">
        <div class="form_container active" id="login_form">
        <div class="L-form ">
            <!--login form -->
                <h1> Login</h1>
         <form action="login.php" method="post">
             <div class="input_name">
        <input type="text" name="L_username" placeholder="Username" id="L_username" autocomplete="username"  required>
        </div>
        <div class="input_password">
        <input type="password" name="L_password" id="L_password"  placeholder="Password" autocomplete="current-password" required>
        </div>
        <div class="signup">
            <a href="#" class="sign-up_link" id="RegistrationLink">Registration</a>
          </div>
          <div class="button">
        <button type="submit">Login</button>
        </div>
   
    </form>
    </div>
    </div>
      <!--registration form -->
       <div class="form_container " id="registration_form">
        <div class="R-form ">
                <h1> Registration</h1>
         <form >
             <div class="input_name">
        <input type="text" name="R_username" placeholder="Username" id="R_username"  autocomplete="username" required>
        </div>
        <div class="input_password">
        <input type="password" name="R_password" placeholder="Password" id="R_password" autocomplete="current-password" required>
        </div>
        <div class="signup">
            <a href="#" class="sign-up_link" id="LoginLink">Login</a>
          </div>
          <div class="button">
            <button type="submit">register</button> 
        </div>
        
    </form>
    </div>
    </div>
    </section>
 <script>
 //event listener to toggle to registration  form view
  document.getElementById('RegistrationLink').addEventListener('click', function () {
    console.log("Registration link clicked.");
    document.getElementById('login_form').style.display = 'none';
    document.getElementById('registration_form').style.display = 'block';
});
 //event listener to togle between registration and login pages
document.getElementById('LoginLink').addEventListener('click', function () {
    console.log("Login link clicked.");
    document.getElementById('registration_form').style.display = 'none';
    document.getElementById('login_form').style.display = 'block';
});
  
  
 //asyncronush function to register new user to the table
document.getElementById('registration_form').addEventListener('submit', async (e) => {
    e.preventDefault(); 
   
   //gathers formdata into a variable 
     const formData = new FormData(e.target);
    //setting fetch api url
  const apiUrl = '../php/Login_pages/registration.php'; 
//once the url has been set a post request is sent to the php file with all the data entered in the form 
    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            body: formData,
             credentials:'include'
        });

       //cloning the rsponse mainly a debugging line
        const responseClone = response.clone(); 
//if there is an error with connection the error will be shown trough console status error page or text to user
 

        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
try{// Parse the response JSON data
        const data = await response.json(); 
if (data.success) {
    //once registration is finished user are directed to homepage 
        alert(data.message);
        window.location.href = '../html/HomePage.html';
    } else {
        alert(data.message);
    }

//catch error  for try function
    }  catch (jsonError) {
         //if its not a json response it will give error by showing the response or other methods of error reporting
            const errorText = await responseClone.text();
            console.error("Non-JSON response received:", errorText);
            throw new Error("Expected JSON but received non-JSON response.");
        }
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }//try function catch statement 
}catch (error) {//n error occurred while trying to delete the lesson.
        console.error("Error:", error);
}

});

 //asyncronush function to  logs in login data
document.getElementById('login_form').addEventListener('submit', async (e) => {
    e.preventDefault(); 
    
     const formData = new FormData(e.target);
    
  const apiUrl = '../php/Login_pages/login.php'; 

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            body: formData,
            credentials:'include'
             
        });

        const responseClone = response.clone(); 
//if its not a json response it will give error by showing the response or other methods of error reporting
           
        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
try{
        const data = await response.json(); 
if (data.success) {//if login succeful  user is redirected to login page
        alert(data.message);
        window.location.href = '../html/HomePage.html';
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
</body>
</html>
