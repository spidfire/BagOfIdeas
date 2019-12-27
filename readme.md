# BagOfIdeas


This tool is written to run a West marches style D&D campaign but it can be used for everything.

It's totally opensource so do with it what you want. 

Do you got usefull additions of improvements open a ticket or a pull request.

# Installation (Most linux based systems)

- Clone this repo in a Apache + PHP(>= 7.2) + Mysql (or Mariadb) environment
    - `git clone git@????`
- Copy `config.php.example` -> `config.php`
- Create a SQL DB and fill in the username and password in `config.php`
- Make the storage directory writable `chmod 777 -R storage`
- Install composer `php composer.phar install`
- Create a DB `php bin/Propel.php diff`
- Create a DB `php bin/Propel.php up`

Now it should run