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

if (isset($_POST['submit'])) {
    $un_temp = mysql_entities_fix_string($connection, $_POST['username']);
    $pw_temp = mysql_entities_fix_string($connection, $_POST['password']);

    authenticate($connection, $un_temp, $pw_temp);
} else {
    header('WWW-Authenticate: Basic realm="Restricted Section“');
    header('HTTP/1.0 401 Unauthorized');
    die("<p style='text-align:center'>Please enter your username and password</p>");
}

$connection->close();

function mysql_entities_fix_string($connection, $string)
{
    return htmlentities(mysql_fix_string($connection, $string));
}
function mysql_fix_string($connection, $string)
{
    $string = stripslashes($string);
    return $connection->real_escape_string($string);
}

function destroy_session_and_data()
{
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
}

function authenticate($connection, $un_temp, $pw_temp)
{
    $query = "SELECT * FROM users WHERE username='$un_temp'";
    $result = $connection->query($query);

    if (!$result)
        die($connection->error);
    elseif ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->close();

        if (password_verify($pw_temp, $row[1])) {
            session_start();
            $_SESSION['username'] = $un_temp;
            $_SESSION['email'] = $row[2];

            echo <<<_END
            <div class ='center'>            
            <h2>Hi $row[0], you are now logged in</h2>
            </div>
            _END;

            die("<p class='center'><a href=index.php>Click here to Homepage</a></p>");
        } else die("<p class='center'>Invalid username or password</p>");
    } else die("<p class='center'>Invalid username or password</p>");
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

.center {
    text-align: center;    
  }

  </style>
_END;
}

function print_table()
{
    echo <<<_END
<form id ="login" class ="login" action="login.php" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <th colspan="2">Log In</th>
            </tr>
            <tr>
                <td>
                    Username:
                </td>
                <td>
                    <input type="text" name="username" id="username" /><br><small></small>
                </td>
            </tr>
            <tr>
                <td>
                    Password:
                </td>
                <td>
                    <input type="password" name="password" id="password" /><br><small></small>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name ="submit" value="Log In" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    New user? <a href="signup.php">Sign Up</a>
                </td>               
            </tr>
        </table>
    </form>
    <script type="text/javascript" src="login.js"></script>
_END;
}
