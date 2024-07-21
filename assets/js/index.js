// Function to update the time every second
function updateTime() {
  // Get the current time
  var currentTime = new Date();
  var time = currentTime.toLocaleTimeString();
  // Get the current date
  var currentDate = new Date();
  var options = { weekday: 'short', month: 'long', day: 'numeric' };
  var date = currentDate.toLocaleDateString(undefined, options);

  // Update the time element
  document.getElementById("current-time").textContent = time;
  document.getElementById("current-date").textContent = date;
}

// Update the time initially
updateTime();

// Update the time every second
setInterval(updateTime, 1000);

//same password(password and confirm password)
  const passwordInput = document.getElementById('user-pass');
  const confirmPasswordInput = document.getElementById('user-pass-confirm');

  confirmPasswordInput.addEventListener('input', function() {
    
    if (passwordInput.value !== confirmPasswordInput.value) {
      confirmPasswordInput.setCustomValidity('Passwords do not match');
      
    } else {
      confirmPasswordInput.setCustomValidity('');
      
    }
  });

  

  
 
  




 

  
 
   
   
  



