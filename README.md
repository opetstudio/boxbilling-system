### Landingpage & Boxbilling System

### install
```bash
    git clone https://github.com/opetstudio/boxbilling-system.git
    cd boxbilling-system
    rm boxbilling/src/bb-config.php
    rm landingpage/composer.lock
    make all
```
### Access

    - landingpage: http://localhost:8081
    - boxbilling: http://localhost:8004/install/index.php
        - Database hostname: mysql
        - Database name: boxbilling
        - Database user: root
        - Database password: password123

### remove database
```bash
    make exec-db
    mysql -uroot -p
    password123
    drop database boxbilling
    exit
    exit
```