Setting up of Social Site:


XAMPP Introduction:

	For maintaining Database to store user's data, we need a server.
	XAMPP is one of an application which provides us local server.
	After installing XAMPP Application,
	1) 	We create a folder named after our database in C:/xampp/htdocs/ directory
		Eg: I have created an empty folder socialsite as C:/xampp/htdocs/socialsite
	2) 	Then we start the server by opening XAMPP Control Panel > Start Apache, Start MySQL.
	3)	Server is now hosted and available at localhost/phpmyadmin in your browser.
	4)	To run your file in server, name your main file as index.php or index.html in the database folder as C:/xampp/htdocs/socialsite/index.php
		When we access localhost/socialsite, our index file is executed.

------------------------------------------------------------------------------------------------------------------

Setting up Database:
	
	1)	To Link the database to the index file, a connection is established as shown in 'db.php' file.
	2)	Our required tables in the database are created as shown 'sqlCommands.sql' file.

------------------------------------------------------------------------------------------------------------------

Running the Website:

--