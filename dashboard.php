<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - QR Code Attendance System</title>
  <link rel="icon" type="image/png" href="./images/red_qr_favicon.png">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Righteous&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap');

    body {
        margin: 0;
        padding: 40px 20px;
        background: linear-gradient(45deg, rgba(0,212,255,1) 0%, rgba(11,3,45,1) 100%);
        background-attachment: fixed; /* This ensures the background stays fixed while scrolling */
        background-size: cover; /* Ensures the gradient covers the entire screen */
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        display: flex;
        justify-content: center;
        min-height: 100vh; /* Ensures the body height covers the full viewport */
    }

    .container-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        max-height: 100px;
        max-width: 1200px;
        width: 100%;
    }

    .container {
        flex: 1 1 300px;
        max-width: 350px;
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        background-color: rgba(17, 25, 40, 0.25);
        border-radius: 12px;
        border: 5px solid rgba(255, 0, 0, 0.206);
        padding: 24px;
        display: flex;
        margin-top: 70px;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .banner-image {
        width: 100%;
        height: 200px;
        background-size: contain; /* Ensures the entire image is visible */
        background-position: center;
        border-radius: 12px;
        /* Remove the border line */
        margin-bottom: 20px;
        background-repeat: no-repeat;
        }

    h1 {
      font-family: 'Righteous', sans-serif;
      color: rgba(255,255,255,0.98);
      text-transform: uppercase;
      font-size: 1.6rem;
      margin: 0 0 10px;
    }

    p {
      color: #fff;
      font-size: 0.8rem;
      line-height: 150%;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin: 0 0 20px;
    }

    .button-wrapper {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .btn {
      border: none;
      padding: 10px 20px;
      border-radius: 24px;
      font-size: 0.8rem;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .outline {
      background: transparent;
      color: white;
      border: 5px solid red;
    }

    .outline:hover {
      transform: scale(1.1);
      color: rgba(255, 255, 255, 0.9);
      border-color: rgba(255, 255, 255, 0.9);
    }

    .fill {
      background: rgba(0, 83, 24, 0.9);
      color: rgba(255, 255, 255, 0.95);
      font-weight: bold;
    }

    .fill:hover {
      transform: scale(1.1);
      filter: drop-shadow(0 10px 5px rgba(0,0,0,0.125));
    }

    .filter-button-wrapper {
      display: flex;
      justify-content: center;
      margin-top: 05vh;
    }

    .logout-button-wrapper {
      display: flex;
      justify-content: center;
      margin-top: 05vh;
    }

    .icon {
      width: 20px;
      height: 20px;
    }

    .logout-icon {
      width: 30px;
      height: 30px;
    }

    /* Responsive grid behavior */
    @media (max-width: 1024px) {
      .container {
        flex: 1 1 45%;
      }
    }

    @media (max-width: 600px) {
      .container {
        flex: 1 1 100%;
      }
    }



.filter-form input[type="submit"] {
   width: 50%;
  padding: 5px;  
  color: #ffffff;
  cursor: pointer;
  background-color: #03C04A;
}
  .container {
    background-color: rgba(255, 255, 255, 0.3);
    display: flex;
    justify-content: center;
}
.filter-form input[type="text"],
.filter-form input[type="datetime-local"] {
    width: 80%;
    padding: 12px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    box-sizing: border-box;
    background-color: #00ff8090;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.filter-form input[type="number"] {
    width: 100px; 
    padding: 5px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    box-sizing: border-box;
    background-color: #00ff8090;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
}
.popup-link{
  display:flex;
  flex-wrap:wrap;
}

.popup-link a{
    background: #333;
    color: #fff;
    padding: 10px 30px;
    border-radius: 5px;
    font-size:17px;
    cursor:pointer;
    margin:20px;
    text-decoration:none;
}

.popup-container {
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease-in-out;
    transform: scale(1.3);
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(21, 17, 17, 0.61);
    display: flex;
    align-items: center;
    justify-content: center;
}
.popup-content {
    background-color:  linear-gradient(45deg, rgba(0,212,255,1) 0%, rgba(11,3,45,1) 100%);
    padding: 20px;
    border: 10px solid #888;
    width: 90%;
    max-width: 450px; /* More responsive */
    border-radius: 10px;
    text-align: center; /* Optional: centers inner text */
}

.popup-content p{
    font-size: 17px;
    padding: 10px;
    line-height: 20px;
}
.popup-content a.close {
    color: #ffffff;
    border: 5px solid;              /* Thinner border for better circle look */
    border-radius: 50%;             /* Makes it a perfect circle */
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    float: right;
    font-size: 35px;
    font-weight: bold;
    background: transparent;
    text-decoration: none;
    margin: -10px -10px 0 0;        /* Adjust position slightly */
    cursor: pointer;
}


.popup-content a.close:hover{
  color:#f00;
}

.popup-content span:hover,
.popup-content span:focus {
    color: #B0FC38;
    text-decoration: none;
    cursor: pointer;
}

.popup-container:target{
  visibility: visible;
  opacity: 1;
  transform: scale(1);
}

.popup-container h3{
  margin:10px;
}

.popup-style{
  transform: skewY(180deg);
   transition: all 0.7s ease-in-out;
}

.popup-style:target{
 transform: skewY(0deg);

 }

  </style>
</head>
<body>

  <div class="container-wrapper">
    
    <div class="container">
      <div class="banner-image" style="background-image: url('./images/new_user.png');"></div>
      <h1>Register User</h1>
      <p>Add new members to the system quickly.</p>
      <div class="button-wrapper"> 
        <button class="btn outline" onclick="alert('Add new members to the system quickly...')">Details</button>

        <button class="btn fill" onclick="window.location.href='./new_registration.php'">Register</button>
      </div>
    </div>

    <div class="container">
      <div class="banner-image" style="background-image: url('./images/users.png');"></div>
      <h1>View Users</h1>
      <p>Browse and manage user data.</p>
      <div class="button-wrapper"> 
        <button class="btn outline" onclick="alert('Browse and manage user data...')">Details</button>

        <button class="btn fill" onclick="window.location.href='user.php'">Manage</button>
      </div>
    </div>

    <div class="container">
      <div class="banner-image" style="background-image: url('./images/view_list.png');"></div>
      <h1>Attendance</h1>
      <p>View and track attendance records.</p>
      <div class="button-wrapper"> 
        <button class="btn outline" onclick="alert('View and track attendance records...')">Details</button>

        <button class="btn fill" onclick="window.location.href='attendance_list.php'">View List</button>
      </div>
    </div>

<div class="filter-button-wrapper">
  <a href="#popup7"><button class="btn outline" style="font-size: 1.6rem;">
    <img src="./images/filter-icon.png" alt="Filter Icon" class="icon" />
    Filter
  </button></a>
</div>

<div class="logout-button-wrapper">
  <button class="btn fill" style="font-size: 1.8rem;"  onclick="window.location.href='logout.php'">
    <img src="./images/logout-icon.png" alt="Logout Icon" class="logout-icon" />
    LogOut
  </button>
</div>

</div>

<!--  Filter pop-up  -->

<div id="popup7" class="popup-container popup-style">
  <div class="popup-content">
    <a href="#" class="close">&times;</a>
    <div id="filterContainer" class="container">
        <div class="filter-form">
            <h2>Apply Filter</h2>
            <form id="filterForm" method="post" action="filter.php">
                <input type="text" id="name" name="name" placeholder="Name"><br>
                Sem : <input type="number" id="student_sem" name="student_sem" min="1" max="6" placeholder="Semester"><br>
                <input type="submit" value="Filter">
            </form>
        </div>
    </div>
  </div>
</div>


</body>
</html>
