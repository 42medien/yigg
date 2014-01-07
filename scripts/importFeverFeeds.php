<?php
mysql_connect("volcano.yigg.de", "root", "c253eh08");
mysql_select_db("yigg_blog");

$result = mysql_query("SHOW SLAVE STATUS;");

while ($row = mysql_fetch_array($result, MYSQL_NUM))
{
   var_dump($row);
}