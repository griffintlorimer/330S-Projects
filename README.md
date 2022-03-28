# 330S Projects
These are the projects that I worked on for Rapid Prototyping and Creative Programming CSE 330 at Washington University in St. Louis.  The course is still in progress

## Calendar
The [calendar](http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m5/group/#loaded) was created with HTML, Bootstrap CSS, and JavaScript on the frontend, php on the backend, and a mySQL database.  All JS client side requests use the AJAX technique.  Users can create an account with a password, the username and encrypted password are stored in the DB.  Users can then create, update, and delete events.  All the data regarding the events is stored in the database.  Users also have the ability to add other users to their event, which causes the event to show on the other users calendar.  They can also create a group for their account can belong to and tag events as belonging to the group which allows all the users in the group to see the event.  They can also add categories to the events and filter by them.  The website is protected against SQL injections, protected against XSS attacks, and CSRF tokens are passed between HTTP requests. 

## News Website
The [news website](http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m3/group/homepage.php) was created using HTML and Bootstrap CSS on the frontend, php on the backend, and a mySQL database.  Users can create an account with a password, the username and encrypted password are stored in the DB.  Users can also create articles, which are all stored in the database and users have the option to modify and delete their own articles.  Users can also comment and like articles as well as filter the articles based on an author.  All data about the articles is stored in the database.  The website is protected against SQL injections and CSRF tokens are passed between HTTP requests. 

## File Sharing Website
The [file sharing website ](http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m2login.html)
uses HTML and pure CSS for the front-end and php on the backend.  Users can create an account with a username, login, upload files, view/download files, and delete them.  All files and user information is stored on an Amazon EC2 instance.
