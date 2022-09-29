# books-parser
# PHP Version: 8.1

sql dump is available at books-parser/books.sql

run server in books-parser/public with localhost -S :port

script is accessible using php bin/command.php

main idea on scheduling is that php bin/command.php will create a file then put the json encoded data inside the file and create a cron job that has the file name as argument

# envvariables.php
CONSTANTS are defined in this file such as:

DATA_THRESHOLD is present if this gets hit, it'll create multiple files that has the json encoded data and the command will create a job for each file.

DATA_STORAGE is currently set to storage folder, change this to the path of parent folder that will contain subfolders and xml files
