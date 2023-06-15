<?php
echo "1. It's important to use prepared statements when executing 
queries for two reasons. The first reason is that it makes the query
you want to perform parameterized and reusable and the second reason is 
that it also is able to execute the SQL command safely and securely,
preventing SQL injection attacks.<br><br>";

echo "2. The meaning of having two rows in the lab6_credentials table is
that you have a row with a hashed password already defined for something
like a root user and then another hashed password that can be used for 
testing purposes. This allows an admin to have their own special password
to do things like moderate the site and make substantial changes while the 
other password can be used to view changes on the site and do testing for the
blog site.<br><br>";

echo "3. The two primary security concerns that I have when making a page like 
this are that if a user makes a blog post and inputs html or javascript, if you
don't have some sort of protection against this like with htmlspecialchars(), 
someone could potentially do an html or javascript injection attack and change 
the website to something undesirable. The other side of this for the backend would
be SQL injection attacks if you don't use things like prepared statements, which can
also really mess up the functionality of the page and completley destory the stability
of any tables you might have.<br><br>";

echo "4. It would be considered safe to have the database password in plaintext in the
db.php file because if the permissions of the file are set for you to be the only person
who can read the file, then no one else will be able to view the file unless they 
were able to log into your account directly. Even if a person got a hold of the password,
they wouldn't be able to do anything with it since they wouldn't be able to make a 
connection to the database on Odin unless they were logged into Odin directly.<br><br>";

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>CMPS 3680 Lab 6</title>
    </head>
    <body>
    </body>
</html>