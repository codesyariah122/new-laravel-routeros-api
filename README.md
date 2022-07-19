# RouterosAPI Development
# Laravel 8 Framework

### If you use laradock as software development
download/clone laradock : https://github.com/Laradock/laradock.git 


#### For laradock mysql bug  
***Added New Field in laradock .env config***  
```
DB_HOST=mysql
```  

***New field for laravel .env config for Database***  
```
DB_HOST=mysql
```

**Access mysql bash**  
```
# docker-compose exec mysql bash
bash-4.4# mysql -u root -proot
mysql> SET GLOBAL innodb_fast_shutdown = 1;
mysql> mysql_upgrade -u root -proot
mysql> exit
bash-4.4# ctrl+d
```  

**Cant access phpmyadmin**  
```
# docker-compose exec phpmyadmin bash
root@4e80fb18b0a8:/var/www/html# cp .config.sample.inc.php config.inc.php
root@4e80fb18b0a8:/var/www/html# nano config.inc.php
```  
***Change field***  
```
$cfg['Servers'][$i]['host'] = 'localhost';

to : 

$cfg['Servers'][$i]['host'] = 'mysql';
```  

**Access phpmyadmin on browser**  
http://localhost:8081