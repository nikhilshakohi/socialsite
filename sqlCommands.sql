/*Create main database*/
CREATE DATABASE socialsite;

/*Create Users Table*/
CREATE TABLE users ( 
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(256) NOT NULL , 
	email VARCHAR(256) NOT NULL , 
	phone BIGINT(20) NOT NULL , 
	firstName VARCHAR(256) NOT NULL , 
	lastName VARCHAR(256) NOT NULL , 
	password VARCHAR(256) NOT NULL
);
/*Connection status for creating friends*/
CREATE TABLE connections (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userA VARCHAR(256) NOT NULL,
	userB VARCHAR(256) NOT NULL,
	connectionStatus INT(11) DEFAULT 0
);
/*Posts table for storing Images, profile Pictures, captions*/
CREATE TABLE posts (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(256) NOT NULL,
	photo TEXT NOT NULL,
	caption LONGTEXT NOT NULL,
	type VARCHAR(256) NOT NULL,
	dateTimeUploaded datetime NOT NULL
);
/*Table for storing likes*/
CREATE TABLE likes (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	postId INT(11) NOT NULL,
	userId INT(11) NOT NULL,
	userFullName TEXT NOT NULL /*Just for Reference*/
);
/*Table for storing likes*/
CREATE TABLE comments (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	postId INT(11) NOT NULL,
	userId INT(11) NOT NULL,
	userFullName TEXT NOT NULL, /*Just for Reference*/
	comment LONGTEXT NOT NULL,
	commentDateTime datetime NOT NULL
);
/*Table for creating messages*/
CREATE TABLE messages (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	currentUserId INT(11) NOT NULL,
	friendUserId INT(11) NOT NULL,
	message LONGTEXT NOT NULL,
	readStatus INT(11) NOT NULL,
	messageDateTime datetime NOT NULL
);
/*Feedback Table*/
CREATE TABLE userFeedback(
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userId INT(11) NOT NULL,
	userFullName TEXT NOT NULL,
	userFeedback LONGTEXT NOT NULL
);

/*Privacy in posts table*/
ALTER TABLE posts ADD privacy VARCHAR (256) NOT NULL DEFAULT 'public'