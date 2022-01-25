<?php 
    $query = "CREATE TABLE IF NOT EXISTS `test`(
        `id` int(10) NOT NULL auto_increment, 
        `a` varchar(255) NOT NULL, 
        `b` varchar(255) NOT NULL, 
        `c` varchar(255) NOT NULL,
        PRIMARY KEY(`id`)
    );"
?>