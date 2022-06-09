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

session_start();

css();
destroy_session_and_data();
print_msg();

function destroy_session_and_data()
{
  $_SESSION = array();
  setcookie(session_name(), '', time() - 2592000, '/');
  session_destroy();
}

function print_msg()
{
  echo <<<_END
<div class='center'>
<h1>You've been logged out</h1>
<a href="login.php">Click here to login</a>
</div>
_END;
}

function css()
{
  echo <<<_END
<style>
.center {
    text-align: center;    
  }
</style>
_END;
}
