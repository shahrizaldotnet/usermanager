# UserManager
A Simple User Management Script

### Installation:
1. Create database and upload db.sql to your mysql manager(PhpMyadmin)
2. Update your config file
  * Default Username: admin
  * Default Password: admin

### Requirements:
* PHP 5.3 or greater
* GD 2.0
* FreeType (Required, for TTF fonts)
* PDO (if using Sqlite, MySQL, or PostgreSQL)

### Add this lines below to lock any webpage you want:

    require_once('includes/config.php'); 
    //make sure user is logged in, function will redirect use if not logged in
    $user->login_required();
    $user->check_if_time_is_expired();

	
### Resources used:

PHP CAPTCHA Script [phpcaptcha](http://www.phpcaptcha.org) 
