
<!DOCTYPE html>
      <!-- html presets , metadata ,character encoding,language.-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title  -->
    <title>Assesment Dashboard</title>
    <!-- Link to  dashboard css file  -->
    <link rel="stylesheet" href="../admin_web/css/dashboard.css">
</head>
<body>
        <!-- container for the admin pannel navigation bar    -->
<div class="con-grid">
    <header class="header">
        <h1>Assesment Dashboard</h1>
     </header>
         <!--Links for the pages in admin panel  as well as a logout button -->
      <div id="admin-sidebar">
        <ul class="nav_button">
            <li class="navbar_item"><div class="link"><a href="../admin_web/home_dashboard.html"><span>Home</span></a></div></li>
            <li class="navbar_item"><div class="link"><a href="../admin_web/lesson_dashboard.html"><span>Lessons</span></a></div></li>
              <li class="navbar_item"><div class="link"><a href="../admin_web/Assesment_page.html"><span>Assessment</span></a></div></li>
                        <li  class="navbar_item"><div  id="logoutbtn" class="link"><a><span>Logout</span></a></div></li>
         <li class="navbar_item"><div class="link"><a href="../admin_web/user_dashboard.html"><span>User</span></a></div></li>
        </ul>
    </div>
    

    <!--upload form  to upload  data to database using javascript to send data .
    using table script to make the form more uniform withouth needing css
    making sure all fields have ids names and are the correct type  of input field -->
    <div class="main-content">
        <form id="dashboard_form" class="form_style" enctype="multipart/form-data">
            
            <table class="dashboard_tbl">
                
    <tr><th>
    <label for="lesson_id">Lesson ID:</label>
    </th><td>
    <input type="number" id="lesson_id" name="lesson_id" ><br>
    </td></tr>
                <tr><th>
                    
    <label for="question_text">Question:</label>
    </th><td colspan="2">
    <input type="text" id="question_text" name="question_text" ><br>
    </td></tr>
     
      <tr><th>
    <label for="option_a">option_a:</label>
      </th><td>
   <input type="text" id="option_a" name="option_a"><br>
 </td></tr>
 
  <tr><th>
    <label for="option_b">option_b:</label>
    </th><td>
    <input type="text" id="option_b" name="option_b" ><br>
 </td></tr>
 
 <tr><th>
    <label for="option_c">option_c:</label>
    </th><td>
    <input type="text" id="option_c" name="option_c" ><br>
 </td></tr>
 <tr><th>
    <label for="option_d">option_d:</label>
    </th><td>
    <input type="text" id="option_d" name="option_d" ><br>
 </td></tr>
  <tr><th><label for="correct_option">Correct Option:</label></th>
            <td><input type="text" id="correct_option" name="correct_option"></td></tr>
 <tr> <td colspan=2><br>
     <!-- depending on button clicked different actions are performed trough javascript.Regardles of the button pressed form data is submitted   -->
    <button type="submit" name="action" value="add" onclick="setAction('add')">Add Question</button>
<button type="submit" name="action" value="search" onclick="setAction('search')">Search Question</button>
<button type="submit" name="action" value="list" onclick="setAction('list')">List Questions</button>
      </td></tr>
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
//on content load the function to check if user is  authorized to access page also listing the question data on the page 
document.addEventListener('DOMContentLoaded', () => {
    checkifloggedin();
    reloadQuestionList(); 
      
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
        apiUrl = './Assesment_pages/AddQuestion.php';
    } else if (action === 'list') {
        apiUrl = './Assesment_pages/listQuestions.php';
    } else if (action === 'search') {
        apiUrl = './Assesment_pages/searchQuestion.php';
    }
    
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
//if there is an error with connection the error will be shown trough console status erro page or text to user
        if (!response.ok) {
            const errorBody = await responseClone.text();
            console.error("Error Response Body:", errorBody);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
// Parse the response JSON data
        const data = await response.json(); 

      //the mesages displayed depending on the action fulfilled 
        if (action === 'add') {
            // a message will be displayed relaying if it was succesful or not then the list will be reloaded with the changes
            alert(data.message || data.error); 
             reloadQuestionList();
             //if the actions where seatch and list the function displayQuestions is triggered with specific data
        } else if (action === 'search' || action === 'list') {
            displayQuestions(data); 
        }//catch error  for try function
    } catch (error) {
        console.error("Error:", error);
        alert("cant process request.");
    }
});
//function to reload database data 
async function reloadQuestionList() {
    fetch('./Assesment_pages/listQuestions.php', {
        method: 'POST',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        displayQuestions(data); 
    })
    .catch(error => {
        console.error("Error fetching lessons:", error);
        alert("An error occurred while fetching lessons.");
    });
}
//function to display data within a table form can be used by all the fucntions to display specific data
    async function displayQuestions(data) {
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
             <th>question_text</th>
                <th>Option a</th>
                <th>Option b</th>
                <th>Option c</th>
                <th>Option d</th>
                <th>Correct option</th>
                 <th>Action</th>
            </tr>
        `;
     //function to display dynamically all relevant elements within the database
     //two buttons are created and displayed next to each lesson element they are linked to delete and edit function 
        data.forEach((lesson) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${lesson.lesson_id}</td>
                <td>${lesson.question_text}</td>
                <td>${lesson.option_a}</td>
                <td>${lesson.option_b}</td>
                <td>${lesson.option_c}</td>
                <td>${lesson.option_d}</td>
                <td>${lesson.correct_option}</td>
          <td>
                     <button onclick="editQuestionElement(${lesson.lesson_id}, ${lesson.questions_id})">Edit</button>
        <button onclick="deleteQuestions(${lesson.lesson_id}, ${lesson.questions_id})">Delete</button>
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

//function to delete questions  it asks for confirmation before php file is called
async function deleteQuestions(lesson_id,questions_id){
    if (!confirm("Are you sure you want to delete this question?")) {
        return;
    }//giving path for fetch request and converting data into json and setting content type to JSON
    const apiUr = './Assesment_pages/deleteQuestions.php';
    try {
        const response = await fetch(apiUr, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ lesson_id, questions_id })
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
            //if no error alert with confirmation message plus reloading the question list to update
        } else if (result.message){
            alert("Lesson deleted successfully.");
            reloadQuestionList();
        }
        //error handling for api or response and will give alert and will log error if anythingh found 
    }catch (error) {
        console.error("Error:", error);
        alert("An error occurred while trying to delete the lesson.");
    }
}
//function to edit question elements following the same principle of calling api parsing text and error handling
async function editQuestionElement(lesson_id, questions_id) {
    const apiUrl = './Assesment_pages/retrieveQuestions.php';
    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
             headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ lesson_id, questions_id })
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
        form.id = 'editQuestionElement';
        form.style.maxWidth = '200px';
        form.style.margin = '0 auto';
        form.style.border = '1px solid black';
        form.style.padding = '20px';
        form.style.borderRadius = '5px';

      //creating the elements of the form and appending them to the form
      //lesson id is displayed as a text field therefore not editable
        const lessonIdDisplay = document.createElement('p');
        lessonIdDisplay.textContent = `Lesson ID: ${lesson.lesson_id || ''}`;
        form.appendChild(lessonIdDisplay);
        
       
        
        // question_text ,input field so editable 
        const label_question_text = document.createElement('label');
        label_question_text.htmlFor = 'edit_question_text';
        label_question_text.textContent = 'question_text:';
        form.appendChild(label_question_text);

        const input_question_text = document.createElement('textarea');
        input_question_text.type = 'text';
        input_question_text.id = 'edit_question_text'; 
        input_question_text.value = lesson.question_text || '';
        input_question_text.name = 'question_text';
        form.appendChild(input_question_text);
       
           // option_a  ,input field so editable 
        const label_option_a = document.createElement('label');
        label_option_a.htmlFor = 'edit_option_a';
        label_option_a.textContent = 'option_a:';
        form.appendChild(label_option_a);

        const input_option_a = document.createElement('textarea');
        input_option_a.type = 'text';
        input_option_a.id = 'edit_option_a'; 
        input_option_a.value = lesson.option_a || '';
        input_option_a.name = 'option_a';
        form.appendChild(input_option_a);
       
          // option_b  ,input field so editable 
        const label_option_b = document.createElement('label');
        label_option_b.htmlFor = 'edit_option_b';
        label_option_b.textContent = 'option_b:';
        form.appendChild(label_option_b);

        const input_option_b = document.createElement('textarea');
        input_option_b.type = 'text';
        input_option_b.id = 'edit_option_b'; 
        input_option_b.value = lesson.option_b || '';
        input_option_b.name = 'option_b';
        form.appendChild(input_option_b);
        
       // option_c  ,input field so editable 
        const label_option_c = document.createElement('label');
        label_option_c.htmlFor = 'edit_option_c';
        label_option_c.textContent = 'option_c:';
        form.appendChild(label_option_c);

        const input_option_c = document.createElement('textarea');
        input_option_c.type = 'text';
        input_option_c.id = 'edit_option_c'; 
        input_option_c.value = lesson.option_c || '';
        input_option_c.name = 'option_c';
        form.appendChild(input_option_c);
        
         // option_c , input field so editable 
        const label_option_d = document.createElement('label');
        label_option_d.htmlFor = 'edit_option_d';
        label_option_d.textContent = 'option_d:';
        form.appendChild(label_option_d);

        const input_option_d = document.createElement('textarea');
        input_option_d.type = 'text';
        input_option_d.id = 'edit_option_d'; 
        input_option_d.value = lesson.option_d || '';
        input_option_d.name = 'option_d';
        form.appendChild(input_option_d);
        
        // correct_option , input field so editable 
        const label_correct_option = document.createElement('label');
        label_correct_option.htmlFor = 'edit_correct_option';
        label_correct_option.textContent = 'correct_option:';
        form.appendChild(label_correct_option);

        const input_correct_option = document.createElement('textarea');
        input_correct_option.type = 'text';
        input_correct_option.id = 'edit_correct_option'; 
        input_correct_option.value = lesson.correct_option || '';
        input_correct_option.name = 'correct_option';
        form.appendChild(input_correct_option);
      
        
         // Save Button  wich calls the saveUpdateQuestionElement function on click 
        const save_Button = document.createElement('button');
        save_Button.type = 'button';
        save_Button.textContent = 'Save';
        save_Button.onclick = () => saveUpdateQuestionElement(lesson_id, questions_id);
        save_Button.style.cursor = 'pointer';
        form.appendChild(save_Button);

      // cancelButton wich calls cancel function
        const cancel_Button = document.createElement('button');
        cancel_Button.type = 'button';
        cancel_Button.textContent = 'Cancel';
        cancel_Button.onclick = cancelButton;
        cancel_Button.style.cursor = 'pointer';
        form.appendChild(cancel_Button);
        
        
        //when triggered it clears the form and reloads the question list 
        function cancelButton() {
            document.querySelector('.preview').innerHTML = ''; 
            reloadQuestionList(); 
        }

        //save button for updating elements 
  async function saveUpdateQuestionElement(lesson_id, questions_id) {
    const form = document.getElementById('editLessonElement');
    const formData = new FormData();
// retrieves the HTML element to be edited and adds it to the  form .
        formData.append("lesson_id", lesson_id);
        formData.append("questions_id", questions_id);
        formData.append("question_text", document.getElementById('edit_question_text').value);
        formData.append("option_a", document.getElementById('edit_option_a').value);
        formData.append("option_b", document.getElementById('edit_option_b').value);
        formData.append("option_c", document.getElementById('edit_option_c').value);
        formData.append("option_d", document.getElementById('edit_option_d').value);
        formData.append("correct_option", document.getElementById('edit_correct_option').value);
       


        
    //fetch api to update the element
            const updateApiUrl = './Assesment_pages/updateQuestions.php';
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
              //if no error alert with confirmation message plus reloading the question list to update

        } else {
            alert("Question  updated successfully.");
            reloadQuestionList();
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



</script>

</body>
</html>


<?php
//start session connects to database script
session_start();
header('Content-Type: application/json');
require 'db_connection.php';
//check for database connection if not error is sent to user
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
//making sure script processes post requests only 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieving json raw input data from the request body
    $input = file_get_contents("php://input");
    // Decoding the JSON input into a PHP associative array
    $data = json_decode($input, true);
    
  //extracting input data from JSON request and asigning it variables
    $lesson_id =  $data['lesson_id'];
    $questions_id =  $data['questions_id'];
   
    //validate to make sure lesson id and question id  was included otherwise error  will be sent to user and the script will stop here.
 
  if (!isset($data['questions_id']) || !isset($data['lesson_id'])) {
    echo json_encode(["error" => "Missing questions_id or lesson_id in request data."]);
    exit;
}
  // prepared sql query to dynamically delete   
    $query = "DELETE FROM questions_tbl WHERE lesson_id=? AND questions_id=?";
    //SQL statement preparing the statement 
    $stmt = $conn->prepare($query);
    //binding parameters to the sql statement
    $stmt->bind_param("ii", $lesson_id,$questions_id);
//executing the statement with different json alert depending if succesful or not
    if ($stmt->execute()) {
        echo json_encode(["message" => "question deleted successfully."]);
    } else {
        echo json_encode(["error" => "Failed to delete question: " . $stmt->error]);
    }
   //closing the statement
    $stmt->close();
} else {
    //if the request method is not Post it will trow a 405 status and network message  
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]); 
}
//closing the connection 
$conn->close();
?>
