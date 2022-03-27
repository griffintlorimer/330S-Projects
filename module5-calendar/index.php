<?php
ini_set("session.cookie_httponly", 1);

session_start();

// Will only appear if user is logged in
if (isset($_SESSION['username']) != null) {
  echo('<input type="hidden" id="secret-token" name="token" value=' . $_SESSION['token'] . '>');
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Calendar</title>
      <link
         rel="stylesheet"
         href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
         integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
         crossorigin="anonymous"
         />
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <!-- <link rel="stylesheet" href="./styles.css"> -->
      <style>
         /* General styling */
         #wrapper {
         display: flex;
         flex-direction: column;
         }
         #top-bar {
         height: 5vh;
         display: flex;
         }
         #rest-of-page {
         display: flex;
         }
         #event-section {
         flex: 2;
         height: 90vh;
         margin-left: 1vw;
         }
         #calendar-section {
         flex: 8;
         height: 90vh;
         }
         /* Top bar styling */
         #calendar-title {
         margin-left: 1vw;
         }
         #login-btn {
         margin-left: auto;
         margin-right: 1vw;
         height: 5vh;
         }
         /* Event styling */
         #events-title,
         #create-event-title,
         #update-event-title {
         text-align: center;
         vertical-align: middle;
         }
         p {
         margin-bottom: 0rem !important;
         }
         #create-events {
         padding: 1vh;
         height: 45vh;
         width: 40vw;
         overflow: scroll;
         padding-top: 2px;
         }
         #update-events {
         padding: 1vh;
         height: 48vh;
         width: 40vw;
         margin-bottom: 1vh;
         overflow: scroll;
         }
         #update-events {
         margin-top: 7vh;
         }
         .btn {
         margin-top: 1vh;
         }
         #delete-btn {
         padding: 0.1vw;
         width: 7.55vw;
         padding: 12px 16px;
         }
         #update-btn {
         padding: 0.1vw;
         width: 5vw;
         padding: 12px 16px;
         }
         /* Logout btn */
         #logout {
         margin-left: 95%;
         margin-top: 0%;
         }
         /* // calendar styling  */
         button {
         width: 75px;
         cursor: pointer;
         box-shadow: 0px 0px 2px gray;
         border: none;
         outline: none;
         padding: 5px;
         border-radius: 5px;
         color: white;
         }
         #header {
         padding: 10px;
         color: black;
         font-size: 22px;
         font-family: sans-serif;
         display: flex;
         justify-content: space-between;
         }
         #header button {
         background-color: #5265a3;
         }
         #container {
         width: 770px;
         }
         #weekdays {
         width: 100%;
         display: flex;
         color: #a02485;
         }
         #weekdays div {
         width: 100px;
         padding: 14px;
         }
         #calendar {
         width: 100%;
         margin: auto;
         display: flex;
         flex-wrap: wrap;
         }
         .day {
         width: 100px;
         padding: 10px;
         height: 130px;
         cursor: pointer;
         box-sizing: border-box;
         background-color: white;
         margin: 5px;
         box-shadow: 0px 0px 3px #cbd4c2;
         overflow-x: hidden; 
         overflow-y: auto;  
         }
         .day:hover {
         background-color: rgba(0, 0, 255, 0.185);
         }
         .wed {
         margin-left: 30px;
         }
         .thu {
         margin-left: 20px;
         }
         .tue {
         margin-left: 10px;
         }
         p.event-name {
         font-size: 10px;
         padding: 3%;
         /* background-color: #58bae4; */
         color: black;
         border-radius: 5px;
         max-height: 55px;
         overflow: hidden;
         margin: 4%;
         }
         p.event-date {
         font-size: 10px;
         margin: 2%;
         padding: 3%;
         }
         p.event-time {
         font-size: 10px;
         padding: 2px;
         /* background-color: #000066; */
         color: black;
         border-radius: 5px;
         max-height: 55px;
         overflow: hidden;
         margin: 2%;
         padding: 3%;
         }
         #category-form{
           margin-left: 50vw;
         }
      </style>
      <script>
         function logOut() {
             const pathToPhpFile = 'logout.php';
             fetch(pathToPhpFile, {
                 method: "GET",
             })
                 .then(res => res.json())
                 .then(response => console.log('Success:', JSON.stringify(response)))
                 .then(alert('YOU ARE NOT LOGGED IN'))
                 .catch(error => console.error('Error:', error))
                 $('option').replaceWith('');
                 document.getElementById('next').click();
                 document.getElementById('back').click();
         }
         function createEvent() {
             const name = document.getElementById("create-name").value;
             const date = document.getElementById("create-date").value;
             const time = document.getElementById("create-time").value;
             const category = document.getElementById("create-category").value;
             const au = document.getElementById("create-au").value;
             const ug = document.getElementById("create-ug").value;
             const token = document.getElementById("secret-token").value;
         
             const data = { 'name': name, 'date': date, 'time': time, 'category': category, 'additional_users': au, 'user_group': ug, 'token' : token};
             if (name == '' || date == '' || time == '') {
                 alert("PLEASE FILL IN NAME, DATE, AND TIME");
                 return;
             }
             fetch("create-event.php", {
                 method: 'POST',
                 body: JSON.stringify(data),
                 headers: {
                     'content-type': 'application/json'
                 }
             })
                 .then(response => response.json())
                 .then(data => {
                     if (data.success){
                     $("#event-to-update").append('<option id="' + data.name + '" value="' + data.name + '">' + data.name + '</option>');
                     // alert('SUCCESSFULLY CREATED ' + data.name + data.time + data.date);
                     let date = String(data.date);
                     let name = String(data.name);
                     let time = String(data.time);
         
                     let curr_month = $("#month").text();
                     let month_year = curr_month.split(' ');
                     const months = new Map();
                     months.set('January', '01');
                     months.set('February', '02');
                     months.set('March', '03');
                     months.set('April', '04');
                     months.set('May', '05');
                     months.set('June', '06');
                     months.set('July', '07');
                     months.set('August', '08');
                     months.set('Septembrt', '09');
                     months.set('October', '10');
                     months.set('Novembor', '11');
                     months.set('December', '12');
         
                     let day = String(date.substr(8, 2));
                     const month = months.get(month_year[0]);
                     const year_month = month_year[1] + '-' + month;
         
                         date = date.substr(0, 7);
                         if (date == year_month) {
                     
                       if (day[0] == '0') {
                           day = day[1];
                       };
                       $("#" + day).append('<p class="event-name">' + name + '</p>');
                       // $("#" + day).append('<p class="event-date">' + events[i][3] + '</p>');
                       $("#" + day).append('<p class="event-time">' + time + '</p>');
         
                     }
                 
                     
                     return;
                     }
                     // alert(data.message);
         
                 })
             .catch(err => alert(err));
             document.getElementById("create-name").value = '';
             document.getElementById("create-date").value = '';
             document.getElementById("create-time").value = '';
             document.getElementById("create-category").value = '';
             document.getElementById("create-au").value = '';
             document.getElementById("create-ug").value = '';
             return;
         
         }
         function getEventsForUser() {
             fetch("get-events.php", {
                 method: 'GET',
                 headers: {
                     'content-type': 'application/json'
                 }
             })
                 .then(response => response.json())
                 .then(data => {
                     if (!data.success) {
                         alert('YOU ARE NOT LOGGED IN');
                         return;
                     }
                     for (let i = 0; i < data.message.length; i++) {
                         $("#event-to-update").append('<option id="' + data.message[i][2] + '"  value="' + data.message[i][2] + '">' + data.message[i][2] + '</option>');
                     }
                 })
                 .catch(err => console.error(err));
         }
         
         function deleteEvent() {
             const name = document.getElementById("event-to-update").value;
             const token = document.getElementById("secret-token").value;
         
             const data = { 'name': name, 'token': token };
             fetch("delete-event.php", {
                 method: 'POST',
                 body: JSON.stringify(data),
                 headers: {
                     'content-type': 'application/json'
                 }
             })
                 .then(response => response.json())
                 .then(data => {
                   if(data.success){
                       $('.event-name').each( function (){
                         if ($(this).html() == data.name){
                           $(this).next().empty();
                           $(this).empty();
                         }
                       })
                     }})     
                 .catch(err => {
                     alert(err);
                     return;
                 });
             $("#" + (name)).replaceWith('');
         
           // reloadCalender();
         }
         
         function updateEvent() {
             const oldname = document.getElementById("event-to-update").value;
             const name = document.getElementById("update-name").value;
             const date = document.getElementById("update-date").value;
             const time = document.getElementById("update-time").value;
             const category = document.getElementById("update-category").value;
             const au = document.getElementById("update-au").value;
             const ug = document.getElementById("update-ug").value;
             const token = document.getElementById("secret-token").value;
         
         
             if (name == '') {
               name = oldname;
             }
         
             const data = { 'oldname': oldname, 'name': name, 'date': date, 'time': time, 'category': category, 'additional_users': au, 'user_group': ug, 'token': token };
             fetch("update-event.php", {
                 method: 'POST',
                 body: JSON.stringify(data),
                 headers: {
                     'content-type': 'application/json'
                 }
             })
                 .then(response => response.json())
                 .then(data => {
                     if(data.success){
                       $('.event-name').each( function (){
                         if ($(this).html() == oldname){
                           $(this).next().empty();
                           $(this).empty();
                         }
                       });
         
                     let date = String(data.date);
                     let name = String(data.name);
                     let time = String(data.time);
         
                     let curr_month = $("#month").text();
                     let month_year = curr_month.split(' ');
                     const months = new Map();
                     months.set('January', '01');
                     months.set('February', '02');
                     months.set('March', '03');
                     months.set('April', '04');
                     months.set('May', '05');
                     months.set('June', '06');
                     months.set('July', '07');
                     months.set('August', '08');
                     months.set('Septembrt', '09');
                     months.set('October', '10');
                     months.set('Novembor', '11');
                     months.set('December', '12');
         
                     let day = String(date.substr(8, 2));
                     const month = months.get(month_year[0]);
                     const year_month = month_year[1] + '-' + month;
         
                         date = date.substr(0, 7);
                         if (date == year_month) {
                     
                       if (day[0] == '0') {
                           day = day[1];
                       };
                       $("#" + day).append('<p class="event-name">' + name + '</p>');
                       $("#" + day).append('<p class="event-time">' + time + '</p>');
         
                     }
                 
                     
                     return;
                   }
                     
           
                 })
                 .catch(err => {
                     alert(err);
                     return;
                 });
             document.getElementById("update-name").value = '';
             document.getElementById("update-date").value = '';
             document.getElementById("update-time").value = '';
             document.getElementById("update-category").value = '';
             document.getElementById("update-au").value = '';
             document.getElementById("update-ug").value = '';
         
             $("#" + (oldname)).replaceWith('<option id="' + name + '"  value="' + name + '">' + name + '</option>');
             // reloadCalender();
         
         }
         
         function reloadCalender() {
           document.getElementById('next').click();
             document.getElementById('back').click();
         }
         
         function mapEventsOnCalendar() {
             fetch("get-events.php", {
                 method: 'GET',
                 headers: {
                     'content-type': 'application/json'
                 }
             })
                 .then(response => response.json())
                 .then(data => {
                     let curr_month = $("#month").text();
                     let month_year = curr_month.split(' ');
         
                     const months = new Map();
                     months.set('January', '01');
                     months.set('February', '02');
                     months.set('March', '03');
                     months.set('April', '04');
                     months.set('May', '05');
                     months.set('June', '06');
                     months.set('July', '07');
                     months.set('August', '08');
                     months.set('Septembrt', '09');
                     months.set('October', '10');
                     months.set('Novembor', '11');
                     months.set('December', '12');
         
                     const month = months.get(month_year[0]);
                     const year_month = month_year[1] + '-' + month;
         
                     const events = [];
                     for (let i = 0; i < data.message.length; i++) {
                       if (data.message == 'not logged in'){
                         return;
                       }
                         let date = data.message[i][3];
                         date = date.substr(0, 7);
                         if (date == year_month) {
                             events.push(data.message[i]);
                         }
                     }
                     for (let i = 0; i < events.length; i++) {
                         let day = String(events[i][3].substr(8, 2));
                         if (day[0] == '0') {
                             day = day[1];
                         };
                         // if ($("#" + day + ":contains('" + events[i][2] + "')" )) {
                         //     continue;
                         // }
         
                         // alert(day);
                         $("#" + day).append('<p class="event-name">' + events[i][2] + '</p>');
                         // $("#" + day).append('<p class="event-date">' + events[i][3] + '</p>');
                         $("#" + day).append('<p class="event-time">' + events[i][4] + '</p>');
         
                     }
         
                 })
                 .catch(err => console.log(err));
         }
         
         function comingFromLoginPage(){
           try {
            const name = document.getElementById("create-name").value;
             const date = document.getElementById("create-date").value;
             const time = document.getElementById("create-time").value;
             const category = document.getElementById("create-category").value;
             const au = document.getElementById("create-au").value;
             const ug = document.getElementById("create-ug").value;
             const token = document.getElementById("secret-token").value;
         
             const oldname1 = document.getElementById("event-to-update").value;
             const name1 = document.getElementById("update-name").value;
             const date1 = document.getElementById("update-date").value;
             const time1 = document.getElementById("update-time").value;
             const category1 = document.getElementById("update-category").value;
             const au1 = document.getElementById("update-au").value;
             const ug1 = document.getElementById("update-ug").value;
             const token1 = document.getElementById("secret-token").value;
         
             // document.getElementById('next').click();
             // document.getElementById('back').click();
         
         
           } catch (error) {
             // https://stackoverflow.com/questions/6985507/one-time-page-refresh-after-first-page-load
             if(!window.location.hash) {
           window.location = window.location + '#loaded';
           window.location.reload();
         }        
         }
         }
         
         function nextBack(){
             document.getElementById('next').click();
             document.getElementById('back').click();
         }
         function filterEvents() {

          const category = $('#category').val();
          if (category == ''){
            alert('fill in the category');
            return;
          }
          $('.event-name').each( function (){
              $(this).next().empty();
              $(this).empty();
          });

          fetch("get-events.php", {
                 method: 'GET',
                 headers: {
                     'content-type': 'application/json'
                 }
             })
                 .then(response => response.json())
                 .then(data => {
                     let curr_month = $("#month").text();
                     let month_year = curr_month.split(' ');
         
                     const months = new Map();
                     months.set('January', '01');
                     months.set('February', '02');
                     months.set('March', '03');
                     months.set('April', '04');
                     months.set('May', '05');
                     months.set('June', '06');
                     months.set('July', '07');
                     months.set('August', '08');
                     months.set('Septembrt', '09');
                     months.set('October', '10');
                     months.set('Novembor', '11');
                     months.set('December', '12');
         
                     const month = months.get(month_year[0]);
                     const year_month = month_year[1] + '-' + month;
         
                     const events = [];
                     for (let i = 0; i < data.message.length; i++) {
                       if (data.message == 'not logged in'){
                         return;
                       }
                         let date = data.message[i][3];
                         date = date.substr(0, 7);
                         if (date == year_month) {
                             events.push(data.message[i]);
                         }
                     }
                     for (let i = 0; i < events.length; i++) {
                         let day = String(events[i][3].substr(8, 2));
                         if (day[0] == '0') {
                             day = day[1];
                         };
                         if (events[i][5] == category){
                         $("#" + day).append('<p class="event-name">' + events[i][2] + '</p>');
                         // $("#" + day).append('<p class="event-date">' + events[i][3] + '</p>');
                         $("#" + day).append('<p class="event-time">' + events[i][4] + '</p>');
                         }
         
                     }
         
                 })
                 .catch(err => console.log(err));
         }
         
         document.addEventListener('DOMContentLoaded',function () {
         
         Buttons();
         calendars_();
         
             document.getElementById('create-event-btn').addEventListener('click', createEvent, false);
             document.getElementById("logout").addEventListener("click", logOut, false);
             document.getElementById("update-btn").addEventListener("click", updateEvent, false);
             document.getElementById("delete-btn").addEventListener("click", deleteEvent, false);
             document.getElementById("next").addEventListener("click", mapEventsOnCalendar, false);
             document.getElementById("back").addEventListener("click", mapEventsOnCalendar, false);
             document.getElementById("category-event-btn").addEventListener("click", filterEvents, false);
         
             getEventsForUser();
             mapEventsOnCalendar();
             comingFromLoginPage();
         
         });
      </script>
   </head>
   <body>
      <div id="wrapper">
         <!-- top bar div -->
         <div id="top-bar">
            <p class="h1" id="calendar-title">Calendar</p>
            <form action="login-page.html" method="post" id="login-btn">
               <input
                  type="submit"
                  value="login or register"
                  class="btn btn-primary"
                  />
            </form>
         </div>
         <!-- rest of the page -->
         <div id="rest-of-page">
            <!-- event section -->
            <div id="event-section">
               <div id="create-events" class="border border-dark rounded">
                  <p class="h6" id="create-event-title">Create a New Event</p>
                  <form id="create-event">
                     <p>
                        Name<input
                           type="text"
                           name="create-event-name"
                           class="form-control"
                           id="create-name"
                           />
                     </p>
                     <p>
                        Date<input
                           type="date"
                           name="create-event-date"
                           class="form-control"
                           id="create-date"
                           />
                     </p>
                     <p>
                        Time<input
                           type="time"
                           name="create-event-time"
                           class="form-control"
                           id="create-time"
                           />
                     </p>
                     <p>
                        Category (optional)<input
                           type="text"
                           name="create-event-category"
                           class="form-control"
                           id="create-category"
                           />
                     </p>
                     <p>
                        Additional Users, seperated by a space (optional)<input
                           type="text"
                           name="create-event-au"
                           class="form-control"
                           id="create-au"
                           />
                     </p>
                     <p>
                        User Groups (optional)<input
                           type="text"
                           name="create-event-ug"
                           class="form-control"
                           id="create-ug"
                           />
                     </p>
                     <button
                        class="btn btn-primary"
                        type="button"
                        id="create-event-btn"
                        >
                     Submit
                     </button>
                  </form>
               </div>
               <div id="update-events" class="border border-dark rounded">
                  <p class="h6" id="update-event-title">Update an Event</p>
                  <form id="update-event">
                     <select
                        class="form-select"
                        name="event"
                        id="event-to-update"
                        ></select>
                     <p>
                        Name<input
                           type="text"
                           name="update-event-name"
                           class="form-control"
                           id="update-name"
                           />
                     </p>
                     <p>
                        Date<input
                           type="date"
                           name="update-event-date"
                           class="form-control"
                           id="update-date"
                           />
                     </p>
                     <p>
                        Time<input
                           type="time"
                           name="update-event-time"
                           class="form-control"
                           id="update-time"
                           />
                     </p>
                     <p>
                        Category (optional)<input
                           type="text"
                           name="update-event-category"
                           class="form-control"
                           id="update-category"
                           />
                     </p>
                     <p>
                        Additional Users (optional)<input
                           type="text"
                           name="update-event-au"
                           class="form-control"
                           id="update-au"
                           />
                     </p>
                     <p>
                        User Groups (optional)<input
                           type="text"
                           name="update-event-ug"
                           class="form-control"
                           id="update-ug"
                           />
                     </p>
                     <button type="button" class="btn btn-primary" id="update-btn">
                     Submit
                     </button>
                     <button type="button" class="btn btn-primary" id="delete-btn">
                     Delete Event
                     </button>
                  </form>
               </div>
            </div>
            <!-- calendar section -->
            <div id="container">
               <div id="header">
                  <div id="month"></div>
                  <div>
                     <button id="back">Back</button>
                     <button id="next">Next</button>
                  </div>
               </div>
               <div id="weekdays">
                  <div>Sunday</div>
                  <div>Monday</div>
                  <div class="tue">Tuesday</div>
                  <div class="wed">Wednesday</div>
                  <div class="thu">Thursday</div>
                  <div>Friday</div>
                  <div>Saturday</div>
               </div>
               <div id="calendar"></div>
            </div>
         </div>
      </div>
      <!-- // closing div above for rest of the page  -->
      <!-- // closing div for wrapper  -->
      <script>
         let num = 0;
         // let clicked = null;
         
         const calendar = document.getElementById("calendar");
         const weekdays = [
           "Sunday",
           "Monday",
           "Tuesday",
           "Wednesday",
           "Thursday",
           "Friday",
           "Saturday",
         ];
         
         function calendars_() {
           const dt = new Date();
         
           if (num !== 0) {
             dt.setMonth(new Date().getMonth() + num);
           }
         
           // const day = dt.getDate();
           const month = dt.getMonth();
           const year = dt.getFullYear();
         
           const firstDay = new Date(year, month, 1);
           const daysMonth = new Date(year, month + 1, 0).getDate();
         
           const dateString = firstDay.toLocaleDateString("en-us", {
             weekday: "long",
             year: "numeric",
             month: "numeric",
             day: "numeric",
           });
         
           const Days = weekdays.indexOf(dateString.split(", ")[0]);
         
           document.getElementById("month").innerText = `${dt.toLocaleDateString(
             "en-us",
             { month: "long" }
           )} ${year}`;
         
           calendar.innerHTML = "";
         
           for (let i = 1; i <= Days + daysMonth; i++) {
             const dayBox = document.createElement("div");
             dayBox.classList.add("day");
         
             if (i > Days) {
               dayBox.innerText = i - Days;
               let i_str = String(i - Days);
               dayBox.setAttribute("id", i_str);
         
               dayBox.addEventListener("click", () => console.log("click"));
             }
         
             calendar.appendChild(dayBox);
           }
         }
         
         function Buttons() {
           document.getElementById("next").addEventListener("click", () => {
             num++;
             calendars_();
           });
         
           document.getElementById("back").addEventListener("click", () => {
             num--;
             calendars_();
           });
         }
      </script>
      <form id="category-form">
         Category<input
            type="text"
            name="category"
            id="category"
            />
         <button
            class="btn btn-primary"
            type="button"
            id="category-event-btn"
            >
         Submit
         </button>
      </form>
      <form>
         <button id="logout" type="button" class="btn btn-primary">Logout</button>
      </form>
   </body>
</html>