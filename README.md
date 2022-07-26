# RouterosAPI Development
# Laravel 8 Framework

### Sample of connecting to routeros / mikrotik  
<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/make-routeros-connection.png"/>  

<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/webfig.png"/>


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



## Playlist :  
https://www.youtube.com/watch?v=a6UjEQxTW-w&list=PLPSEuAupgnCjBss5K50iH95K8Kz75VDb-  


## Samples preview  
#### Set Interface  
- Error 
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-interface/error.png"/>  

- success  
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-interface/success.png"/>  

<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-interface/webfig.png"/>  


#### Added new address  
- Error Interface  
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-address/error-interface.png"/>
- Error Have Address  
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-address/error-interface-have-address.png"/>

- success  
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-address/success1.png"/>  

	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-address/success1.png"/>

<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-address/webfig.png"/>  


#### Added ip routes  
- Error  
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-route/error.png"/>

- Success  
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-route/success.png"/>  

<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-route/webfig.png"/>  


#### Added dns servers    
<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-dns-servers/success.png"/>

<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-dns-servers/webfig.png"/>  

#### Added ip firewall nat  
- Error  
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-firewall-nat/error.png"/>

- Success  
	<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-firewall-nat/success.png"/>  
<img src="https://raw.githubusercontent.com/codesyariah122/new-laravel-routeros-api/main/documentation-preview/add-ip-firewall-nat/webfig.png"/>  

### Reboot & Shutdown  
- Reboot  
	<img src="https://github.com/codesyariah122/new-laravel-routeros-api/blob/main/documentation-preview/reboot.gif?raw=true"/>  

- Shutdown  
	<img src="https://github.com/codesyariah122/new-laravel-routeros-api/blob/main/documentation-preview/shutdown.gif?raw=true"/>
