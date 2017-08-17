# Hammock-Database

<p align="center">
  <img src="https://raw.githubusercontent.com/Terag/Hammock-Database/master/img/HammockHelicopter.PNG" alt="Hammock Logo" height="150">
</p>

## Synopsis

Is it too difficult for you too manage all your projects of maintenance around aircraft ? You have too much information on paper and are afraid to lose it ?

You could like to centralise your information, and simplify you process with a simple tool.

So .. this tool could be helpfull for you and your team !

## Motivation

This tool had been developed in Hammoch Helicopter Sdn. Bhd. in Malaysia to provide then a simple tool to get their work faster and easier.

This software is developed to simply centralize and improve you work time with 4 important linked parts.

### PROJECT

Gives you access to all the steps of a maintenance process, from the scope of work to the PIF. Here you can manage your work flow and follow the status of your project.

### STOCK

Access to all the stock of your company to permit you to find the part faster for all your projects.

### MANAGEMENT

A simple tool to record all the work hours on your project.

### DOCUMENTATION

As an intelligent system, it will retains the previous jobs and keeps these basic information to provide you help in your futur projects.

## Installation

The installation is very simple. It works on a basic Apache server with PHP 5.6 and a mySQL 5.7 database.

### Configurate database

You just need to execute in your database the scripts contain in files :
  * sql.sql
  * query_insert.sql
  * query_update.sql
  * query_delete.sql
  * function.sql
  
You can fin these files in <b>SQL_MANAGEMENT</b> folder.

### Configurate you Server

#### 1

Copy past all the project files into the root directory of your server.
The system uses $_SERVER['DOCUMENT_ROOT'] for the includes

#### 2

Connect you database in /SQL_MANAGEMENT/connection.php file.

~~~ connection.php
  $db_host_name  = <database_host>;
  $db_database   = <database_name>;
  $db_user_name  = <database_user>;
  $db_password   = <database_password>;
~~~

#### 3

edit htaccess. You should check every htaccess of the project to check if the contained information are true or false.
Some problems could come from these files.

## Contributors

### Code
  * Terag
  
### Design
   * Terag

## License

Copyright (c) 2017, Hammock Database - git@rouquette.me

 * Hammock Database is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
 * Hammock Database is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
