# pfh_export_mysql

PHP script for import **Personal Finances Home 3.8** CSV file to MySQL database. Keep in mind that script does not track transaction uniqueness, so if you upload same CSV twice or more times it will create duplicate rows.

###DB schema
![screenshot](https://github.com/bushikot/pfh_export_mysql/raw/master/db/schema.png)
