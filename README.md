Learnlog is a PHP-HTML/CSS-based web application that grants students, parents, and teachers greater visibility into a student's assignment progress. This project was co-developed by myself and a classmate, and development spanned 7 weeks. This web application allows functionality for three types of users: Parents, teachers, and students. To use functionality, all users must create an account, with the credentials being stored in a cloud-hosted database. All users have a dashboard where they will see details, determined by their account type. Parents are able to create an account for their child and view their child's profile, where they will see their child's assignments and the status of those assignments. Teachers are able to create classes and assignments for those classes. Students are able to join classes, see what classes they are currently enrolled in, view their assignments, and update their assignment status (Incomplete, In Progress, Completed). This project successfully implemented all functional requirements outlined at the beginning of the project. Despite this, in future versions, there would definitely be additions to complete functionality (Deleting account, leaving class, deleting assignment) and improve the service (notifications, more metrics, better layout). This project sought to produce a minimally-viable product, and was successful in doing so. Additionally, a main purpose of this project was to practice and understand how sprints work, what the development process is like, and how to thoroughly test software; this project was successful in ensuring this.

Below is the contents of the README file during development, useful for installing the language & unit testing capabilities.

-- Installing PHP and PHP Composer

1. install php 
2. verify version php -v 
3. install : php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
4. move to global: sudo mv composer.phar /usr/local/bin/composer

--- Running PHP Unit Tests

5. find project folder cd 
7. install phpunit : composer require --dev phpunit/phpunit
8. run test : vendor/bin/phpunit tests

-- Running a PHP Server
 
10. open integrated terminal on Public folder 
11. run php server php -S localhost:8000 

-- Database connection 

12. create a config.php file based on example.Config.php
