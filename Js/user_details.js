
            let user_id = null;
            
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
            //document.getElementById("weight").value = `${userData.weight}`;
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
        //weight: document.getElementById("weight").value,
        health_Condition: document.getElementById("health_Condition").value,
        //weight_fluctuation: document.getElementById("weight_fluctuation").value,
    };

    console.log("Collected input values:", inputValues);
    await updateUserData(user_id, inputValues);
});

document.getElementById("milestoneButtonOn").addEventListener("click", () => {
    milestoneAlertONorOFF(user_id, 'on');
    alert("Milestone Alert is now ON");
});

document.getElementById("milestoneButtonOff").addEventListener("click", () => {
    milestoneAlertONorOFF(user_id, 'off');
    alert("Milestone Alert is now OFF");
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
}