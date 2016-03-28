Changelogs:
Fixed login.php captcha
Added banned functions
Login authorization is reset every login checks
Added Blowfish encryption for strengthening password
Use PEPPER for logged in check verification
Remove rand tokens from check verification
Fixed brute force prevention lock-down
Show how long you been locked out
Can define timer to logout in config.php

Version 1.1
Added csrf protection
Name and username only allowed alphanumeric characters
Remove um-login and um-register from admin folder


Fixed bugs:
Validate username and name to allow only alphanumeric characters
Change profile settings username input to lowercase characters

A admin panel that has:
Prepared statement MYSQLI
Bruteforce prevention
Captcha (From Securimage)
Binary-Admin V1.1
Can add/edit/delete users from the admin panel
Reset password if forget password via email that has reset link

Installation:
create database and upload db.sql to your mysql manager(PhpMyadmin)
Update your config file
default username: admin
default password: admin