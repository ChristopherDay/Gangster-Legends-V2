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
6. Register a new user account
7. Open up phpMyAdmin and go to the `users` table and edit the `U_userLevel` from `1` to `2`

# Example

A demo can be found at http://demo.glscript.net/ 

**After registering you will become an admin automatically, this is only on the demo site.**