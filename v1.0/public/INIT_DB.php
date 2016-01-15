<?php

echo 'Initializing the Database...';

$mode 	= file_get_contents('../mode.php');
$config = include("../app/config/{$mode}.php");
$config = $config['db'];

echo '<hr>MODE : ' . $mode;
echo '<br>CONFIG : ' . $config;
echo '<hr>';

$connection = mysqli_connect("localhost",$config['username'],$config['password'],$config['name']);

if (mysqli_connect_errno()){ echo "Failed to connect to MySQL: " . mysqli_connect_error(); }
echo "Connected successfully<hr>";

$tbl_users = "CREATE TABLE IF NOT EXISTS users (
              id INT(11) NOT NULL AUTO_INCREMENT,
			  username VARCHAR(20) NOT NULL,
			  first_name VARCHAR(50),
			  last_name VARCHAR(50),
			  email VARCHAR(255) NOT NULL,
			  password VARCHAR(255) NOT NULL,
			  gender ENUM('m','f') NULL,
			  job VARCHAR(255),
			  club VARCHAR(255),
			  tags VARCHAR(255),
			  city VARCHAR(255),
			  logins INT(11) DEFAULT 0,
			  active INT(1),
			  active_hash VARCHAR(255),
			  recover_hash VARCHAR(255),
			  remember_identifier VARCHAR(255),
			  remember_token VARCHAR(255),
			  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  updated_at TIMESTAMP,
			  status INT(1) DEFAULT 1,
			  avatar VARCHAR(255) NULL,
			  ip VARCHAR(255) NULL,
			
              PRIMARY KEY (id),
			  UNIQUE KEY username (username,email)
             )";

$tbl_users_permissions = "CREATE TABLE IF NOT EXISTS users_permissions (
						  id INT(11) NOT NULL AUTO_INCREMENT,
						  user_id INT(11) NOT NULL,
						  is_admin INT(1) NULL,
						  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  			  updated_at TIMESTAMP,
			  			  PRIMARY KEY (id)
						)";

$tbl_posts = "CREATE TABLE IF NOT EXISTS posts (
              id INT(11) NOT NULL AUTO_INCREMENT,
              url VARCHAR(255) NOT NULL,
              title VARCHAR(255) NOT NULL,
              subtitle VARCHAR(255) NULL,
              newsgroup VARCHAR(255) NULL,
              author INT(11) NULL,
              audience TEXT NULL,
              image VARCHAR(255) NULL,
              body TEXT NULL,
              symbol VARCHAR(255) NULL,
              footer VARCHAR(255) NULL,
              tags VARCHAR(255) NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  updated_at TIMESTAMP,
			  status INT(1) DEFAULT 1,
			  likes TEXT,
			  views INT(11) DEFAULT 0,
			  favorite TEXT NULL,
			  
			  PRIMARY KEY (id),
			  UNIQUE KEY url (url)
            )";

$tbl_comments = "CREATE TABLE IF NOT EXISTS comments (
              id INT(11) NOT NULL AUTO_INCREMENT,
              post_id INT(11),
              user_id INT(11),
              body TEXT NULL,
              
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  updated_at TIMESTAMP,
			  status INT(1) DEFAULT 1,
			  
			  PRIMARY KEY (id)
            )";

$tbl_images = "CREATE TABLE IF NOT EXISTS images (
              id INT(11) NOT NULL AUTO_INCREMENT,
              url VARCHAR(255) NOT NULL,
              filename VARCHAR(255) NOT NULL,
              description VARCHAR(255) NULL,
              slug VARCHAR(255) NOT NULL,
              
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  updated_at TIMESTAMP,
			  status INT(1) DEFAULT 1,
			  
			  PRIMARY KEY (id)
            )";

if ($connection->query($tbl_users) === TRUE) {
    echo "Table <b>users</b> created successfully<hr>";
} else {
    echo "Error creating table: " . $connection->error;
}

if ($connection->query($tbl_posts) === TRUE) {
    echo "Table <b>posts</b> created successfully<hr>";
} else {
    echo "Error creating table: " . $connection->error;
}

if ($connection->query($tbl_users_permissions) === TRUE) {
    echo "Table <b>users_permissions</b> created successfully<hr>";
} else {
    echo "Error creating table: " . $connection->error;
}
            
if ($connection->query($tbl_comments) === TRUE) {
    echo "Table <b>comments</b> created successfully<hr>";
} else {
    echo "Error creating table: " . $connection->error;
}

if ($connection->query($tbl_images) === TRUE) {
    echo "Table <b>images</b> created successfully<hr>";
} else {
    echo "Error creating table: " . $connection->error;
}

$connection->close();

?>