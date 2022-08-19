<?php
/* 
Template Name: Process Coins Payment
*/
//token=57V28580BU1374356&tx=62L929462J419582H&st=Pending&amt=0.01&cc=USD&cm=&item_number=%23basic&sig=ENorEm5sjXUluucl2yNrR282R6oLWfNry2NGLP60WqoxPnK%2B%2FYkynFkWO9K0bql6l10QcDJz3hKAdibDCWRb1Dd02Bdbz%2BQ1y8ICOUVd7EzVMQ32B0026TzMj9EYv1JqLND4g8IRpxLgdpx9Q9kql4UaXC7y3z6iKLApKIqYRrw%3D
/*if(!isset($_GET['token']) || !isset($_GET['cm']) || !isset($_GET['tx']) || !isset($_GET['item_number'])){
	wp_redirect(site_url('purchase-coins/?invalid=1'));
	die;
}*/

$queryString = $_SERVER['QUERY_STRING'];
echo "<pre>";
print_r($queryString);
die;
