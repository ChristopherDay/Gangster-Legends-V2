# Gangster-Legends-V2

Gangster Legends v2 is a open source PBBG game engine written in PHP using a MySQL backend.

# Requirements

- PHP 5.6.X or higher
- MySQL 5.5 or higher

# How to install

1. Extract files to your webserver
2. Navigate to GL v2 on your server in a web browser i.e. `http://127.0.0.1/Gangster-Legends-V2`
3. Follow instructions

# How To Manualy Install

1. Extract files to your webserver
2. Create a new database and a user
3. Import `install/schema.sql` and `install/data.sql` to your MySQL Database
4. Open up `dbconn.php` and alter the connection string with your MySQL username, password and database name
5. The game should now be made with some sample data
6. You can login with the email `Admin@yourgame.co.uk` and the password `adminPass`
7. Go to your profile and change your password

# Example

A demo can be found at http://glscript.cdcoding.com/demo/

The email is `Admin@yourgame.co.uk` and the password is `adminPass` 
