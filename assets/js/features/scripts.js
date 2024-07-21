

window.addEventListener("DOMContentLoaded", function() {
  const currentPage = window.location.pathname;

  if (currentPage === "../../admin/user-manage.html") {
    // JavaScript code for specific-page1.html
    // Add your code here
    console.log("This code will execute on specific-page1.html");
  } else if (currentPage === "../../admin/applications-pending.html") {
    // JavaScript code for specific-page2.html
    // Add your code here
    console.log("This code will execute on specific-page2.html");
  } else {
    // JavaScript code for other pages
    // Add your code here
    console.log("This code will execute on other pages");
  }
});