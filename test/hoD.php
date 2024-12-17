<!DOCTYPE html>
    <!-- html presets , metadata ,character encoding,language.-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- page title  -->
    <title>Home Dashboard</title>
        <!-- Link to  dashboard css file  -->
    <link rel="stylesheet" href="../admin_web/css/dashboard.css">
   
</head>
<body>
     <!-- container for the admin pannel navigation bar    -->
<div class="con-grid">
    <header class="header">
        <h1>Home Dashboard</h1>
    </header>
        <!--Links for the pages in admin panel  as well as a logout button -->
     <aside id="admin-sidebar">
        <ul class="nav_button">
            <li class="navbar_item"><div class="link"><a href="../admin_web/home_dashboard.html"><span>Home</span></a></div></li>
            <li class="navbar_item"><div class="link"><a href="../admin_web/lesson_dashboard.html"><span>Lessons</span></a></div></li>
              <li class="navbar_item"><div class="link"><a href="../admin_web/Assesment_page.html"><span>Assessment</span></a></div></li>
            <li  class="navbar_item"><div  id="logoutbtn" class="link"><a><span>Logout</span></a></div></li>
         <li class="navbar_item"><div class="link"><a href="../admin_web/user_dashboard.html"><span>User</span></a></div></li>
        </ul>
    </aside>


    <div class="main-content">

  
           
     
      <!--upload form  to upload  data to database using javascript to send data .
    using table script to make the form more uniform withouth needing css
    making sure all fields have ids names and are the correct type  of input field -->
         
            <form id="dashboard_form" class="form_style" enctype="multipart/form-data">
             
            <table class="dashboard_tbl">
               
               
                    <tr>
                          <!-- field for displaying programme summary  -->
                        <th><label for="programme_summary"> Program Summary:</label></th>
                        <td><textarea id="programme_summary" name="programme_summary" rows="20" cols="50"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <!-- This will update the programme summary by triggering a javascript function  -->
                          <button type="button" onclick="updateProgrammeSummary(1)">Submit</button>

                    </tr>
             
                    <tr>
                        <th><label for="lesson_number">Lesson number:</label></th>
                        <td><input type="number" id="lesson_number" name="lesson_number"></td>
                    </tr>
                    <tr>
                        <th><label for="L_title">Lesson Title:</label></th>
                        <td><input type="text" id="L_title" name="L_title"></td>
                    </tr>
                    <tr>
                        <th><label for="lesson_summary">Lesson Summary:</label></th>
                        <td><textarea id="lesson_summary" name="lesson_summary"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <!-- depending on button clicked different actions are performed trough javascript.Regardles of the button pressed form data is submitted   -->
                        <button type="submit" name="action" value="add" onclick="setAction('add')">Add Lesson Summary</button>
                        <button type="submit" name="action" value="search" onclick="setAction('search')">Search Lessons</button>
                        <button type="submit" name="action" value="list" onclick="setAction('list')">List Summary</button>
                           
                        </td>
                    </tr>
                </table>
            </form>
           
        </div>
            <!-- Preview div to list database entries and show changes in real time -->
        <div style="margin: 20px;" class="preview">
    
</div>
    </div>
<script>
let action = '';

function setAction(value) {
    action = value;
}
//on content load the function to check if user is  authorized to access page also listing the  data on the page and loading the programme summary .
document.addEventListener('DOMContentLoaded', () => {
    checkifloggedin();
     loadProgrammeSummary();
    reloadSummaryList(); 
   
});

  //asynchronus function to check if user has logged in if they have not logged in they will be redirected  to the login page where they can login. and some error reporting 
async function checkifloggedin() {
    try {
        const response = await fetch('./Login_pages/authenticationAdmin.php', {
            credentials: 'include',
        });
        
        if (!response.ok) {
            const errorBody = await response.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.authenticated) {
            console.error("Not authenticated:", data.message || 'Authentication required.');
            window.location.href = 'loginAdmin.html';
        }
        console.log("Authenticated");
    } catch (error) {
        console.error("An error occurred:", error);
        window.location.href = 'loginAdmin.html';
    }
}
//this logout button  retrieves the logout php function wich destroys the session data then if this is succesful it will redirect user to login page if not they will get error
document.getElementById('logoutbtn').addEventListener('click', logoutbtn);

async function logoutbtn() {
    try {
        const response = await fetch('./Login_pages/logout.php', {
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
 window.location.href = 'loginAdmin.html';
        } else {
            alert(data.message || 'Failed to log out.');
        }
    } catch (error) {
        console.error('An error occurred during logout:', error);
        alert('An error occurred. Please try again.');
    }
}
 

/* This section firstly prevents form reload after submission then
gathers the form submission data into FormData this data is sent 
out for processing using js to dynamically handle different actions on submission 
 */

document.getElementById('dashboard_form').addEventListener('submit', async (e) => {
    e.preventDefault(); 
    

    const formData = new FormData(e.target);
    
    let apiUrl = '';
    if (action === 'add') {
        apiUrl = './Home_pages/AddLessonSummary.php';
    } else if (action === 'list') {
        apiUrl = './Home_pages/listLessonSummary.php';
    } else if (action === 'search') {
        apiUrl = './Home_pages/searchLessonSummary.php';
    } 
    
        console.log("Action:", action); 
    console.log("API URL:", apiUrl);
//if the action is not set user will be alerted with alert function and exit
    if (!apiUrl) {
        alert("Action not set.");
        return;
    }

   //once the url has been set a post request is sent to the php file with all the data entered in the form 
  try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            body: formData
        });
//cloning the rsponse mainly a debugging line
        const responseClone = response.clone(); 
//if there is an error with connection the error will be shown trough console status error page or text to user
 
        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
// Parse the response JSON data
 
        const data = await response.json(); 

        //the mesages displays depending on the action fulfilled 
        if (action === 'add') {
            // a message will be displayed relaying if it was succesful or not then the list will be reloaded with the changes
            alert(data.message || data.error); 
             reloadSummaryList();
             //if the actions where seatch and list the function displaySummaries is triggered with specific data
        } else if (action === 'search' || action === 'list') {
            displaySummaries(data); 
             //if the actions where search and list the  display function is triggered with specific data
        }else if (action === 'submit'){ 
        alert(data.message || data.error);
         updateProgrammeSummary();
        } //catch error  for try function
    } catch (error) {
        console.error("Error:", error);
        alert("cant process request.");
    }
});//function to reload database data 
async function reloadSummaryList() {
    fetch('./Home_pages/listLessonSummary.php', {
        method: 'POST',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        displaySummaries(data); 
    })
    .catch(error => {
        console.error("Error fetching lessons:", error);
        alert("An error occurred while fetching lessons.");
    });
}
//function to display data within a table form can be used by all the fucntions to display specific data
 
 async function displaySummaries(data) {
    const preview = document.querySelector('.preview');
    preview.innerHTML = ''; 
//data is checked to make sure its in the right format then table is created to display data received 

    if (Array.isArray(data) && data.length > 0) {
        const table = document.createElement('table');
        table.classList.add('dashboard_tbl');
        table.style.color = 'black';
           //column names for table just created 
        table.innerHTML = `
        
            <tr>
                <th>Lesson Number</th>
             <th>Lesson Title</th>
                <th>Lesson Summary</th>
                 <th>Action</th>
            </tr>
        `;
     //function to display dynamically all relevant elements within the database
     //two buttons are created and displayed next to each lesson element they are linked to delete and edit function 
      
        data.forEach((lesson) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${lesson.lesson_number}</td>
                <td>${lesson.L_title}</td>
                <td>${lesson.lesson_summary}</td>
              
          <td>
                     <button onclick="editSummaryElement(${lesson.L_Summary_Id}, ${lesson.lesson_number})">Edit</button>
        <button onclick="deleteSummaries(${lesson.L_Summary_Id}, ${lesson.lesson_number})">Delete</button>
                </td>
            `;
            //appending all elements created to the page preview div
            table.appendChild(row);
        });
        preview.appendChild(table);
    } else {
         preview.textContent = "No lessons found.";
    }
}
//function to delete elements  it asks for confirmation before php file is called

async function deleteSummaries(L_Summary_Id,lesson_number){
    if (!confirm("Are you sure you want to delete this lesson?")) {
        return;
    }//giving path for fetch request and converting data into json and setting content type to JSON
   
    const apiUr = './Home_pages/deleteLessonSummary.php';
    try {
        const response = await fetch(apiUr, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ L_Summary_Id, lesson_number })
        });
//clone response given
        const responseClone = response.clone();
// errors reporting if any issues found
        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
//creating variable to store result data 
        let result;
         //parsing response as json one more time to avoid errors 
        try {
            result = await response.json();
        } catch (jsonError) {
              //if its not a json response it will give error by showing the response or other methods of error reporting
            const errorText = await responseClone.text();
            console.error("Non-JSON response received:", errorText);
            throw new Error("Expected JSON but received non-JSON response.");
        }
//checking for any more errors and alert with error message 
         if (result.error) {
            alert(result.error);
            //if no error alert with confirmation message plus reloading the display list to update
        } else if (result.message){
            alert("Lesson deleted successfully.");
            reloadSummaryList();
        }//error handling for api or response and will give alert and will log error if anythingh found 
    }catch (error) {
        console.error("Error:", error);
        alert("An error occurred while trying to delete the lesson.");
    }
}
//function to edit elements following the same principle of calling api parsing text and error handling

async function editSummaryElement(L_Summary_Id, lesson_number) {
    const apiUrl = './Home_pages/retrieveLessonSummary.php';
    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
             headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ L_Summary_Id, lesson_number })
        });

        const responseClone = response.clone();

        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        let lesson;
        try {
            lesson = await response.json();
        } catch (jsonError) {
            const errorText = await responseClone.text();
            console.error("Non-JSON response received:", errorText);
            throw new Error("Expected JSON but received non-JSON response.");
        }

        if (lesson.error) {
            alert(lesson.error);
            return;
        }
        console.log("Retrieved lesson data:", lesson);
        //selecting and clearing list div so edit form can be viewed
        const preview = document.querySelector('.preview');
        preview.innerHTML = ''; 
//creating the form
        const form = document.createElement('form');
        form.id = 'editSummaryElement';
        form.style.maxWidth = '200px';
        form.style.margin = '0 auto';
        form.style.border = '1px solid black';
        form.style.padding = '20px';
        form.style.borderRadius = '5px';

        
        // lesson_number ,input field so editable 
        const label_lesson_number = document.createElement('label');
        label_lesson_number.htmlFor = 'edit_lesson_number';
        label_lesson_number.textContent = 'lesson_number:';
        form.appendChild(label_lesson_number);

        const input_lesson_number = document.createElement('input');
        input_lesson_number.type = 'text';
        input_lesson_number.id = 'edit_lesson_number'; 
        input_lesson_number.value = lesson.lesson_number || '';
        input_lesson_number.name = 'lesson_number';
        form.appendChild(input_lesson_number);
       
       
           // L_title ,input field so editable 
        const label_L_title = document.createElement('label');
        label_L_title.htmlFor = 'edit_L_title';
        label_L_title.textContent = 'L_title:';
        form.appendChild(label_L_title);

        const input_L_title = document.createElement('textarea');
        input_L_title.type = 'text';
        input_L_title.id = 'edit_L_title'; 
        input_L_title.value = lesson.L_title || '';
        input_L_title.name = 'L_title';
        form.appendChild(input_L_title);
       
          // lesson_summary ,input field so editable 
        const label_lesson_summary = document.createElement('label');
        label_lesson_summary.htmlFor = 'edit_lesson_summary';
        label_lesson_summary.textContent = 'lesson_summary:';
        form.appendChild(label_lesson_summary);

        const input_lesson_summary = document.createElement('textarea');
        input_lesson_summary.type = 'text';
        input_lesson_summary.id = 'edit_lesson_summary'; 
        input_lesson_summary.value = lesson.lesson_summary || '';
        input_lesson_summary.name = 'lesson_summary';
        form.appendChild(input_lesson_summary);
        
       
      
        
         // Save Button wich calls the saveUpdateButton function on click 
        const save_Button = document.createElement('button');
        save_Button.type = 'button';
        save_Button.textContent = 'Save';
        save_Button.onclick = () => saveUpdateButton(L_Summary_Id, lesson_number);
        save_Button.style.cursor = 'pointer';
        form.appendChild(save_Button);

        // Cancel Button  wich calls cancelButton function on click 
        const cancel_Button = document.createElement('button');
        cancel_Button.type = 'button';
        cancel_Button.textContent = 'Cancel';
        cancel_Button.onclick = cancelButton;
        cancel_Button.style.cursor = 'pointer';
        form.appendChild(cancel_Button);
        
        
             //when triggered it clears the form and reloads the displayed list 
        function cancelButton() {
            document.querySelector('.preview').innerHTML = ''; 
            reloadSummaryList(); 
        }

        //save button for updating elements
  async function saveUpdateButton(L_Summary_Id, lesson_number) {
    const form = document.getElementById('editSummaryElement');
    const formData = new FormData();
    // retrieves the HTML element to be edited and adds it to the  form .
        formData.append("L_Summary_Id", L_Summary_Id);
        formData.append("lesson_number", document.getElementById('edit_lesson_number').value);
        formData.append("L_title", document.getElementById('edit_L_title').value);
        formData.append("lesson_summary", document.getElementById('edit_lesson_summary').value);
     //fetch api to update the element
            const updateApiUrl = './Home_pages/updateLessonSummary.php';
            try {
                const response = await fetch(updateApiUrl, {
                    method: 'POST',
                    body: formData
                });
//clone response given for debugging
                const responseClone = response.clone();
// errors reporting if any issues found
                if (!response.ok) {
                    const errorBody = await responseClone.text();
                    console.error("Error Response Body:", errorBody);
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
//creating variable to store result data 
        let lesson;
        try {
             //parsing response as json one more time to avoid errors 
            lesson = await response.json();
        } catch (jsonError) {
             //if its not a json response it will give error by showing the response or other methods of error reporting
            const errorText = await responseClone.text();
            console.error("Non-JSON response received:", errorText);
            throw new Error("Expected JSON but received non-JSON response.");
        }
//checking for any more errors and alert with error message 
                if (lesson.error) {
            alert(lesson.error);
             //if no error alert with confirmation message plus reloading the summary list to update

        } else {
            alert("Lesson summary  updated successfully.");
            reloadSummaryList();
            document.querySelector('.preview').innerHTML = ''; 
        }
    } catch (error) {
                console.error("Error:", error);
                alert("An error occurred while processing the request.");
            }
        }

        preview.appendChild(form);
        
      
//error handling for api or response and will give alert and will log error if anythingh found 
   
    } catch (error) {
        console.error("Error:", error);
    }
}
//async function to load and display programme summary within the container  created
async function loadProgrammeSummary(homepage_id) {
 try{
       //fetch api 
        const response = await fetch('./Home_pages/retrieveProgrammeSummary.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            //hardcoding the value as the program summary is always the same field updated.
            body: JSON.stringify({ homepage_id: 1 })
        });
        //clone response given for debugging
        const responseClone = response.clone();
// errors reporting if any issues found
        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
//creating variable to store result data 
        let summary;
        try {
             //parsing response as json one more time to avoid errors 
            summary = await response.json();
            document.getElementById("programme_summary").value = `${summary.programme_summary}`;
        } catch (jsonError) {
             //if its not a json response it will give error by showing the response or other methods of error reporting
           
            const errorText = await responseClone.text();
            console.error("Non-JSON response received:", errorText);
            throw new Error("Expected JSON but received non-JSON response.");
        }
        //checking for any more errors and alert with error message 

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
}catch (error) {
        console.error("Error:", error);
}
}

async function updateProgrammeSummary(homepage_id) {
      const programme_summary = document.getElementById("programme_summary").value;
 try{
   
        const response = await fetch('./Home_pages/updateProgrammeSummary.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ homepage_id: homepage_id,
            programme_summary: programme_summary})
        });
        
        const responseClone = response.clone();
 console.log("Server Response:", responseClone); 
        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

     if (!response.ok) {
            const errorBody = await response.text(); // Retrieve text response for debugging
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // parsing JSON response to prevent catch triggers
        let result;
        try {
            result = await response.json();
            console.log(" Response:", result); 
        } catch (jsonError) {
            //if its not a json response it will give error by showing the response or other methods of error reporting
            console.error("Failed to parse JSON response:", jsonError);
            throw new Error("Unexpected server response format (not valid JSON).");
        }

      //alert message to users for succes  
        if (result && result.message) {
            alert(result.message); 
            //updating old programme summary with new updated value
            document.getElementById("programme_summary").value = programme_summary;
           //if there is an error it will be alerted to user
        } else if (result && result.error) {
            alert(result.error); 
           // if there are any unexpected errors
        } else {
            console.error("Unexpected response:", result);
            alert("An unexpected error occurred.");
        }//catch function to find any excution errors 
    } catch (error) {
        console.error("Error updating programme summary:", error);
        alert("An error occurred while updating the programme summary.");
    }
}

document.addEventListener('DOMContentLoaded', () => {
     loadProgrammeSummary();
    reloadSummaryList(); 
   
});
</script>
</body>
</html>       

