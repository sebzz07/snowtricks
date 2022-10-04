Codacy Badge :
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f839dfe379ab40518c419cc82bc433e2)](https://www.codacy.com/gh/sebzz07/snowtricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=sebzz07/snowtricks&amp;utm_campaign=Badge_Grade)


To start the website in localhost mode : 

1. clone the github repo;

2. run :
```composer install```
or
```composer install --no-dev --optimize-autoloader```

3. run :
   ```npm install```

4. run the asynchronous service to send mail :

```symfony console messenger:consume async -vvv```