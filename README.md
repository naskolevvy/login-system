# login-system

Simple login system with most of the basic functionality that can be expected

## Features

* Login
* Register
* Remember login credentials
* Forgotten password 
* Change password
* logout
* error handling

## Note

The file includes/dbHandler.inc.php contains the data base connection details - set them to whatever you need to be able to connect 

## Data base setup

To set up the same DB as used in this login do the following SQL commands: 

* Setup the Users table
~~~ sql
CREATE TABLE users(
  iduser int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username TEXT NOT NULL,
  email TEXT NOT NULL,
  password LONGTEXT NOT NULL
);
~~~

* Setup the remember_me table - used to enable the remember me functionality
~~~ sql
CREATE TABLE remember_me(
  id int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  hash_token VARCHAR(255) NOT NULL,
  hash_pass VARCHAR(255) NOT NULL,
  expireDate DATETIME NOT NULL
);
~~~

* Setup the pwdreset table - used to enable the forgotten password functionality
~~~ sql
CREATE TABLE pwdreset(
  pwdResetID int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  pwdResetEmail	TEXT NOT NULL,
  pwdResetSelector TEXT NOT NULL,
  pwdResetToken LONGTEXT NOT NULL,
  pwdResetExpires TEXT NOT NULL
);
~~~

You can of course change the names and the fields of the DB as you wish but you would have to account for any changes inside the files as well

I am also using XAMP MySQL and APACHE server to test this locally. The DB mangement system I am using is called phpmyadmin and can be found at localhost/phpmyadmin if you are using xamp as well
