<?php

/*
 * Name: Linh Huynh
 * Student ID: 015020837
 * Midterm 2
 * Date: 11/09/2021
 * 
 * sql query
 * 
 * 
 
CREATE DATABASE cs174;
USE cs174;

CREATE TABLE info
(
    id       int          NOT NULL AUTO_INCREMENT,
    name     varchar(255) NOT NULL,
    content  text         NOT NULL,
    username varchar(32)  NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE users123
(
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    email    varchar(255) NOT NULL,
    PRIMARY KEY (username)
);

*/

css();
echo "<br><br>";
print_table();

require_once 'connection.php';
$connection = new mysqli($hn, $un, $pw, $db);

if ($connection->connect_error) die("Connection Failed!");

// get data from user
if (
    isset($_POST['username']) &&
    isset($_POST['password']) &&
    isset($_POST['email'])
) {
    if (
        check_string($_POST['username']) == 1
        || !$_POST['password']
        || !$_POST['email']

    ) {
        echo "<p style='text-align:center'>All fields must be filled in!</p><br>";
    } else {
        $username = sanitizeMySQL($connection, 'username');
        $password = sanitizeMySQL($connection, 'password');
        $encryted_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = sanitizeMySQL($connection, 'email');

        add_user($connection, $username, $encryted_password, $email);
    }
} else {
    //echo "Name and Content must be filled!";
}

$connection->close();

// functions
function sanitizeString($var)
{
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}
function sanitizeMySQL($connection, $var)
{
    $var = $connection->real_escape_string($_POST[$var]);
    $var = sanitizeString($var);
    return $var;
}

function check_string($str)
{
    $regex = preg_match('/[@_!#$%^&*()<>?\/|}{~:]/i', $str);
    if ($str == '' || $regex == 1)
        return 1;
    else
        return 0;
}

function add_user($connection, $username, $password, $email)
{
    $query = "INSERT INTO users VALUES('$username', '$password', '$email')";
    $result = $connection->query($query);
    if (!$result) die("<p style='text-align:center'>Account Creation Failed.</p>");
    else echo "<p style='text-align:center'>Account Created!</p>";
}

function css()
{

    echo <<<_END
<style>
table,
th,
td {
    border: 1px solid black;
}

table {
    text-align: center;
    border-collapse: collapse;
    width: 350px;
    ;
    font-family: monospace;
    font-size: 14px;
    text-align: left;
    margin-left: auto;
    margin-right: auto;
}

th {
    background-color: #DBA40E;
    color: white;
    text-align: center;
}

#id,
#name {
    text-align: center;
}

tr:nth-child(even) {
    background-color: #f2f2f2
}
</style>
_END;
}

function print_table()
{
    echo <<<_END

<form id = "signup" class = "form" action="signup.php" method="post"  enctype="multipart/form-data">
<table>
<tr>
    <th colspan="2">Sign Up</th>
</tr>
<tr>
    <td>Username:</td>
    <td><input type="text" name ="username" id ="username"><br><small></small></td>
</tr>
<tr>
    <td>Password:</td>
    <td><input type="password" name ="password" id ="password"><br><small></small></td>
    
</tr>
<tr>
    <td>Email:</td>
    <td><input type="text" name ="email" id ="email"><br><small></small></td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input type="submit" name ="submit" value="Sign Up">
    </td>
</tr>
<tr>
    <td colspan="2 " align="center">
        Already a member? <a href="login.php">Log In</a>
    </td>
</tr>
</table>
</form>
<script type="text/javascript" src="signup.js"></script>
_END;
}