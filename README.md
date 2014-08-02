Zend Test
=======================

This application uses

    Packages:

        "zendframework/zendframework": "2.3.*",
        "hybridauth/hybridauth" : "2.2.*",
        "zf-commons/zfc-base" : "0.1.*",
        "zf-commons/zfc-user" :"1.*",
        "socalnick/scn-social-auth": "1.*",
        "doctrine/doctrine-orm-module": "0.*",
        "zf-commons/zfc-user-doctrine-orm": "1.*",
        "socalnick/scn-social-auth-doctrine-orm": "1.*",
        "bjyoungblood/bjy-authorize": "1.4.*",
        "zendframework/zend-developer-tools": "dev-master",
        "guzzlehttp/guzzle": "~4.1.4",
        "guzzlehttp/oauth-subscriber": "0.1.*"

Installation
------------

    git clone git://github.com/tineo/zendtest.git --recursive
    cd zendtest/
    php composer.phar update

    #Deploy database with Doctrine Mapping
    php vendor/bin/doctrine-module orm:schema-tool:update --force

    Edit config/autoload/database.global.php for your own database configuration.



Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/zendtest/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/zendtest/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
