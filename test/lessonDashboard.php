<!DOCTYPE html>
    <!-- html presets , metadata ,character encoding,language.-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- page title  -->
    <title> Lesson Dashboard</title>
           <!-- Link to  dashboard css file  -->
    <link rel="stylesheet" href="../admin_web/css/dashboard.css">
</head>
<body>
      <!-- container for the admin pannel navigation bar    -->
<div class="con-grid">
    <header class="header">
        <h1>Lesson Dashboard</h1>
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
    making sure all fields have ids names and are the correct type  of input field  -->
        <form id="dashboard_form" class="form_style" enctype="multipart/form-data">
            
            <table class="dashboard_tbl">
                
                <tr><th>
    <label for="lesson_id">Lesson ID:</label>
    </th><td>
    <input type="number" id="lesson_id" name="lesson_id" ><br>
    </td></tr>
     
      <tr><th>
    <label for="branch">Branch:</label>
      </th><td>
    <input type="number" id="branch" name="branch" ><br>
 </td></tr>
 
  <tr><th>
    <label for="leaf">Leaf:</label>
    </th><td>
    <input type="number" id="leaf" name="leaf" ><br>
 </td></tr>
 
 <tr><th>
    <label for="subleaf">Subleaf:</label>
    </th><td>
    <input type="number" id="subleaf" name="subleaf" ><br>
 </td></tr>
 
  <tr><th>
    <label for="type">Type:</label>
    </th><td>
    <input type="text" id="type" name="type" ><br>
     </td></tr>
     
     
<tr><th>
    <label for="source_path">Source Path:<br> (../images/fileName )</label>
</th><td>
    <input type="text" id="source_path" name="source_path"><br>
</td></tr>

      
     <tr><th>
    <label for="html_text">HTML Text:</label>
    </th><td>
    <textarea id="html_text" name="html_text" ></textarea><br>
 </td></tr>
 <tr> <td colspan=2>
      <!-- depending on button clicked different actions are performed trough javascript.Regardles of the button pressed form data is submitted   -->
    <button type="submit" name="action" value="add" onclick="setAction('add')">Add</button>
<button type="submit" name="action" value="search" onclick="setAction('search')">Search Lessons</button>
<button type="submit" name="action" value="list" onclick="setAction('list')">List Lessons</button>

   
     <!-- form for immage upload using form action-->
      </td></tr>
      </table>
</form>

<div>
    <form action="./lesson_pages/imageUpload.php" method="POST" encType="multipart/form-data">
    <input type="file" id="uploadForm" name='uploadForm'><br>
    <input class='hidden' id="submit" name="submit" type="submit" value="submit">
</form> 
  
    </div>
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
//on content load the function to check if user is  authorized to access page also listing the  data on the page  .
document.addEventListener('DOMContentLoaded', () => {
    reloadLessons(); 
    checkifloggedin();
      
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
        apiUrl = './lesson_pages/addNewLessonElement.php';
    } else if (action === 'search') {
        apiUrl = './lesson_pages/searchLesson.php';
    } else if (action === 'list') {
        apiUrl = './lesson_pages/listLessons.php';
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
             reloadLessons();
              //if the actions where search and list the function displayLessons is triggered with specific data
        } else if (action === 'search' || action === 'list') {
            displayLessons(data); 
        } //catch error  for try function
    } catch (error) {
        console.error("Error:", error);
        alert("cant process request.");
    }
});//function to reload database data 
async function reloadLessons() {
    fetch('./lesson_pages/listLessons.php', {
        method: 'POST',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        displayLessons(data); 
    })
    .catch(error => {
        console.error("Error fetching lessons:", error);
        alert("An error occurred while fetching lessons.");
    });
}
//function to display data within a table form can be used by all the fucntions to display specific data
 
async function displayLessons(data) {
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
                <th>Lesson ID</th>
                <th>Branch</th>
                <th>Leaf</th>
                <th>Subleaf</th>
                <th>Type</th>
                <th>Source Path</th>
                <th>HTML Text</th>
                 <th>Action</th>
            </tr>
        `;
     //function to display dynamically all relevant elements within the database
     //two buttons are created and displayed next to each lesson element they are linked to delete and edit function 
     
        data.forEach((lesson) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${lesson.lesson_id}</td>
                <td>${lesson.branch}</td>
                <td>${lesson.leaf}</td>
                <td>${lesson.subleaf}</td>
                <td>${lesson.type}</td>
                <td>${lesson.source_path}</td>
                <td>${lesson.html_text}</td>
          <td>
                     <button onclick="editLessonElement(${lesson.lesson_id}, ${lesson.branch}, ${lesson.leaf}, ${lesson.subleaf})">Edit</button>
                    <button onclick="deleteLessonElement(${lesson.lesson_id}, ${lesson.branch}, ${lesson.leaf}, ${lesson.subleaf})">Delete</button>
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


async function deleteLessonElement(lesson_id, branch, leaf, subleaf){
    if (!confirm("Are you sure you want to delete this lesson?")) {
        return;
    }
    const apiUr = './lesson_pages/deleteLessons.php';
    try {//giving path for fetch request and converting data into json and setting content type to JSON
        const response = await fetch(apiUr, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ lesson_id, branch, leaf, subleaf })
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
        let lesson;
        //parsing response as json one more time to avoid errors 
        try {
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
       //if no error alert with confirmation message plus reloading the display list to update
       } else if (lesson.message){
            alert("Lesson deleted successfully.");
            reloadLessons();
        } //error handling for api or response and will give alert and will log error if anythingh found 
    }catch (error) {
        console.error("Error:", error);
        alert("An error occurred while trying to delete the lesson.");
    }
}
//function to edit elements following the same principle of calling api parsing text and error handling

async function editLessonElement(lesson_id, branch, leaf, subleaf) {
    const apiUrl = './lesson_pages/retrieveLesson.php';
    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
             headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ lesson_id, branch, leaf, subleaf })
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
        const preview = document.querySelector('.preview');
        preview.innerHTML = ''; 
 //selecting and clearing list div so edit form can be viewed
 
 //creating the form
        const form = document.createElement('form');
        form.id = 'editLessonElement';
        form.style.maxWidth = '200px';
        form.style.margin = '0 auto';
        form.style.border = '1px solid black';
        form.style.padding = '20px';
        form.style.borderRadius = '5px';

      //creating the elements of the form and appending them to the form
      //composite keys: lesson_id ,branch ,leaf ,subleaf are displayed as a text field therefore not editable
        const lessonIdDisplay = document.createElement('p');
        lessonIdDisplay.textContent = `Lesson ID: ${lesson.lesson_id || ''}`;
        form.appendChild(lessonIdDisplay);
        
        
        const branchDisplay = document.createElement('p');
        branchDisplay.textContent = `Branch: ${lesson.branch || ''}`;
        form.appendChild(branchDisplay);
        
        
        const leafDisplay = document.createElement('p');
        leafDisplay.textContent = `Leaf: ${lesson.leaf || ''}`;
        form.appendChild(leafDisplay);
        
        
        const subleafDisplay = document.createElement('p');
        subleafDisplay.textContent = `Subleaf: ${lesson.subleaf || ''}`;
        form.appendChild(subleafDisplay);

        // Type ,input field so editable 
        const label_Type = document.createElement('label');
        label_Type.htmlFor = 'edit_Type';
        label_Type.textContent = 'Type:';
        form.appendChild(label_Type);

        const input_Type = document.createElement('input');
        input_Type.type = 'text';
        input_Type.id = 'edit_Type'; 
        input_Type.value = lesson.type || '';
        input_Type.name = 'Type';
        form.appendChild(input_Type);

        // Source Path,input field so editable 
        const label_Source_Path = document.createElement('label');
        label_Source_Path.htmlFor = 'edit_Source_Path';
        label_Source_Path.textContent = 'Source_Path:';
        form.appendChild(label_Source_Path);

        const input_Source_Path = document.createElement('input');
        input_Source_Path.type = 'text';
        input_Source_Path.id = 'edit_Source_Path'; 
        input_Source_Path.value = lesson.source_path || '';
        input_Source_Path.name = 'Source_Path';
        form.appendChild(input_Source_Path);

        // HTML Text ,input field so editable 
        const label_HTML_Text = document.createElement('label');
        label_HTML_Text.htmlFor = 'edit_HTML_Text';
        label_HTML_Text.textContent = 'HTML_Text:';
        form.appendChild(label_HTML_Text);

        const input_HTML_Text = document.createElement('textarea');
        input_HTML_Text.type = 'text';
        input_HTML_Text.id = 'edit_HTML_Text';
        input_HTML_Text.value = lesson.html_text || '';
        input_HTML_Text.name = 'HTML_Text';
        form.appendChild(input_HTML_Text);

        // Save Button wich calls the saveUpdateLessonElement function on click 
        const save_Button = document.createElement('button');
        save_Button.type = 'button';
        save_Button.textContent = 'Save';
        save_Button.onclick = () => saveUpdateLessonElement(lesson_id, branch, leaf, subleaf);
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
            reloadLessons(); 
        }
 //save button for updating elements
async function saveUpdateLessonElement(lesson_id, branch, leaf, subleaf) {
    const form = document.getElementById('editLessonElement');
    const formData = new FormData();
      // retrieves the HTML element to be edited and adds it to the  form .
        formData.append("lesson_id", lesson_id);
        formData.append("branch", branch);
        formData.append("leaf", leaf);
        formData.append("subleaf", subleaf);
        formData.append("type", document.getElementById('edit_Type').value);
        formData.append("source_path", document.getElementById('edit_Source_Path').value);
        formData.append("html_text", document.getElementById('edit_HTML_Text').value);
        
     console.log("FormData being sent:", {
        lesson_id,
        branch,
        leaf,
        subleaf,
        type: document.getElementById('edit_Type').value,
        source_path: document.getElementById('edit_Source_Path').value,
        html_text: document.getElementById('edit_HTML_Text').value
    });
    
    //fetch api to update the element
            const updateApiUrl = './lesson_pages/updateLesson.php';
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
             //if no error alert with confirmation message plus reloading the  list to update
        } else {
            alert("Lesson updated successfully.");
            reloadLessons();
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

document.addEventListener('DOMContentLoaded', () => {
    reloadLessons(); 
});


</script>

</body>
</html>
