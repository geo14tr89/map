#Check whether your debugging client is listening on the indicated address. 
    - run docker-compose up
    - On Linux and OSX, you can use netstat -a -n | grep LISTEN to check. Windows ipconfig
![img.png](img.png)
    - Copy local.env.exmample to local.env and change client_host ip to founded in net stat

    - configure in phpStorm server - 
        name localhost
        host = your container ip/
        map file directory to your
    - run docker-compose up -d
![img_1.png](img_1.png)

    - configure Debug in settings
![img_3.png](img_3.png)
![img_4.png](img_4.png)

---
![img_5.png](img_5.png)
![img_2.png](img_2.png)
    
    - Setup xdebug in chrome browser
![img_6.png](img_6.png)
    
    - enable debug
![img_7.png](img_7.png)