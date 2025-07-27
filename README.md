# 🟢 AnonnymChat – Secure & Anonymous Chat App
AnonnymChat is a simple yet powerful anonymous chat application built with PHP and MySQL.
It allows users to create accounts, log in securely, and chat anonymously with others while keeping data private.

🚀 Features
🔐 Secure Authentication – Passwords are hashed for safety.

💬 Anonymous Chatting – Users chat without revealing personal details.

🗑️ Message Control – Users can delete only their own messages.

📱 Responsive UI – Works smoothly on mobile and desktop.

🎨 Modern Design – Clean, dark-themed interface with cool effects.

📂 Project Structure

/ (root)

 ├── db.php  
 # Database connection
 ├── login.php 
 # Login page
 ├── register.php 
 # Registration page
 ├── chat.php    
 # Chat room
 ├── all.css   
 # Stylesheet
 └── README.md
 
🛠 Database Setup
Run this SQL to create the database and required tables:

CREATE DATABASE chatapp;

USE chatapp;

CREATE TABLE allusers (

    id INT AUTO_INCREMENT PRIMARY KEY,
    
    username VARCHAR(100) UNIQUE NOT NULL,
    
    password VARCHAR(250) NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
);

CREATE TABLE allmsgs (

    id INT AUTO_INCREMENT PRIMARY KEY,
    
    userid INT NOT NULL,
    
    msgs TEXT NOT NULL,
     
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (userid) REFERENCES allusers(id) ON DELETE CASCADE
    
);

⚡ Installation
Clone this repository or download it as a ZIP.

Import the provided SQL into your MySQL database.

Update db.php with your database credentials.

Upload the project to your hosting server (e.g., InfinityFree).

Open the project URL and start chatting anonymously!

🖼 Screenshots
Add screenshots of your login and chat pages here.

🌐 Live Demo
👉 https://nemochat.wuaze.com/index.php

📜 License
This project is open-source and free to use.

