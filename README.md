# pfh_import_mysql

PHP script for import **Personal Finances Home 3.8** CSV file to MySQL database. Keep in mind that script does not track transaction uniqueness, so if you upload same CSV twice or more times it will create duplicate rows. But this does not apply to other tables.

###Export from Personal Finances Home
* Go to *Tools*->*Export*
* Select ";" as separator
* Check all fields on *Fields* tab

###DB schema
![screenshot](https://github.com/Bushikot/pfh_import_mysql/blob/master/db/schema.png)
