<?php

$link = mysql_connect(HOST, MYSQL_USER, MYSQL_PASSWORD) or
   die("Could not connect: " . mysql_error());
mysql_select_db(MYDB);

?>