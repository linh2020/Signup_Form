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

require_once 'connection.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection Failed!");

echo "<h1 class='center'>Homepage</h1>";

session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];

    print_table($username, $email);
    add_name_content($conn, $username);
    show_content($conn, $username);
} else {
    echo "<div class='center'>
            Please <a href='login.php'>click here</a> to Log in.<br><br>
            Please <a href='signup.php'>click here</a> to Sign up.
            </div>";
}

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

function check_file($var)
{
    if (
        $_FILES[$var]['name'] == "" or
        $_FILES[$var]['size'] == 0 or
        $_FILES[$var]['type'] != "text/plain"
    )
        return 1;
    else
        return 0;
}

function read_content($var)
{
    $name = $_FILES[$var]['name'];
    $data = file_get_contents($name);
    $data = sanitizeString($data);
    return $data;
}

function destroy_session_and_data()
{
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
}

function add_name_content($conn, $username)
{
    if (isset($_POST['name']) && isset($_FILES['content'])) {
        if (check_string($_POST['name']) == 1 or check_file('content') == 1)
            echo "Field name and content cannot be empty. They accept string and text file only please!.Example: World, San Jose, etc. <br>";
        else {
            $name = sanitizeMySQL($conn, 'name');
            $content = read_content('content');
            $query = "INSERT INTO info (id,name,content,username) VALUES ('','$name','$content','$username')";
            $result = $conn->query($query);
            if (!$result) die("Insert Failed!");
        }
    } else {
        // echo "Name and Content must be filled!";
    }
}

function show_content($conn, $username)
{
    $query = "SELECT * FROM info WHERE username = '$username'";
    $result = $conn->query($query);
    if (!$result) die("Wrong Table!");
    $rows = $result->num_rows;

    echo <<<_END
    <br>
    <table id="info">
    <tr>
        <th colspan ="3">Information</th>
    </tr>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Content</th>
    </tr>    
    _END;

    echo '<br>';

    for ($j = 0; $j < $rows; ++$j) {

        $result->data_seek($j);
        $id = $result->fetch_assoc()['id'];
        echo '<tr><td id ="id">' . $id . '</td>';

        $result->data_seek($j);
        $name = $result->fetch_assoc()['name'];
        echo '<td id ="name">' . $name . '</td>';

        $result->data_seek($j);
        $org_content = $result->fetch_assoc()['content'];
        $content_lt3 = $org_content;
        $number_line = substr_count($org_content, "\r");
        $str_linebreak =  explode("\n", $org_content);

        read_remaining_content();

        echo '<td id ="content">';

        if ($number_line < 3) {
            echo  '<pre>' . $content_lt3 . '</pre>';
        } else {
            echo "$str_linebreak[0] <br> $str_linebreak[1] <br> $str_linebreak[2] <br>";
            $remain_str = readFull($str_linebreak, $number_line);

            echo <<<_END
            <span id=$id></span>
            <br>
            <input type ='button' onclick='read_remaining_content($id,"$remain_str")' value='Read Full'>
            _END;
        }

        echo '</td></tr>';
    }

    echo <<<_END
        </table>
        <br>
        <a href="logout.php">Click here to Log out</a>
    _END;

    $result->close();
    $conn->close();
}

function read_remaining_content()
{
    echo "
    <script>
    function read_remaining_content(val,content) {
        var str ='';
        str += content;
        document.getElementById(val).innerHTML = str;
    }
    </script>";
}

function readFull($str_linebreak, $number_line)
{
    $str = '';
    for ($i = 3; $i <= $number_line; $i++) {
        $str .= $str_linebreak[$i];
    }
    $str = preg_replace("/<br>|\n|\r/", "<br>", $str);
    $str .= '<br>';
    return $str;
}

function print_table($username, $email)
{
    echo <<<_END

        Welcome back $username<br>       	
        Your email: $email<br><br>
        
        <form action="index.php" method="post"  enctype="multipart/form-data">
            <table id="add">
                <tr>
                    <th colspan="2">Add Information</th>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type="text" name="name"></td>
                </tr>
                <tr>
                    <td>Content</td>
                    <td><input type="file" name="content"></td>
                </tr>        
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="submit" value="Add Record"></td>
                </tr>
            </table>
        </form>
    _END;
}

function css()
{
    echo <<<_END
<style>
table, th, td {
    border: 1px solid black;
}
table#info {
border-collapse: collapse;
width: 100%;
font-family: monospace;
font-size: 14px;
text-align: left;
}

table#add {
    border-collapse: collapse;
    font-family: monospace;
    font-size: 14px;
    text-align: left;
    }

th {
background-color: #DBA40E;
color: white;
text-align:center;
}
#id, #name{
    text-align:center;
    width:5%;
}
#name{
    text-align:center;
    width:10%;
}
tr:nth-child(even){
    background-color: #f2f2f2;
}

.center {
    text-align: center;    
}

#content{
    padding: 5px;
}
</style>

_END;
}
