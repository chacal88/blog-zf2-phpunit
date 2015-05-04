Blog Project
=======================

Introduction
------------


Features added:

- Core module with 
    - TableGateway implementation
    - PHPUnit tests
    - Entity class
    - Service class
    - AdapterServiceFactory using module-based db configuration

- Skel module

Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository
and use composer to install dependencies:

    php composer.phar install


Virtual Host
------------

	<VirtualHost *:80>
    	ServerName blogproject.dev
	    DocumentRoot /vagrant/blogproject/public
    	SetEnv APPLICATION_ENV "development"
	    SetEnv PROJECT_ROOT "/vagrant/blogproject" 
   		<Directory /vagrant/blogproject/public>
        	DirectoryIndex index.php
        	AllowOverride All
        	Order allow,deny
        	Allow from all
    	</Directory>
	</VirtualHost>

# blog-zf2-phpunit
