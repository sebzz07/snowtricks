# Snowtricks - Project 6 

Codacy Badge :
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f839dfe379ab40518c419cc82bc433e2)](https://www.codacy.com/gh/sebzz07/snowtricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=sebzz07/snowtricks&amp;utm_campaign=Badge_Grade)

## Installation :

This project has been developed under php 8.1 and symfony 6.1.

### Start this project in localhost mode, run some command lines:  


1. clone the GitHub repo:

```git clone https://github.com/sebzz07/snowtricks.git```

2. install dependencies with composer:

```composer install```

3. install dependencies with npm (or yarn if you prefer) :

```npm install```

4. run :

```npm run build```

5. Create and fill out your own ```.env.*```


7. Create database and some fixtures via doctrine : 

```symfony console doctrine:database:create```

```symfony console make:migration```

```symfony console doctrine:migrations:migrate```

```symfony console doctrine:fixtures:load```

8. run local server : 

````symfony server:start -d````

7. run the asynchronous service to send mail :

```symfony console messenger:consume async -vvv```


*Now the project is normally deploy correctly* 


## Information to Test the project :

#### Two options : 

use the accounts created with the fixtures:

```admin//admin or user//user```

Or you can create your own account. 

You can check a version on my server (the subdomain is not HTTPS, sorry about that ) : 

[http://snowtricks.sebdru.fr/](http://snowtricks.sebdru.fr/)

Thank you

