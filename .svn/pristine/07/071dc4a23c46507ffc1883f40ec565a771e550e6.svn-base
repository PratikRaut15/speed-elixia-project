<?php
// connect and login to FTP server
$ftp_server = "203.112.150.29";
$ftp_conn = ftp_ssl_connect($ftp_server, 22, 30) or die("Could not connect to $ftp_server");
$login = ftp_login($ftp_conn, 'vendorportal', 'Dalmia123!@');

$local_file = "/var/html/www/erp/public/downloads/customer/538/pilot.csv";
$server_file = "/ftp/vendorportal/pilot/pilot.csv";

// download server file
if (ftp_get($ftp_conn, $local_file, $server_file, FTP_ASCII))
  {
  echo "Successfully written to $local_file.";
  }
else
  {
  echo "Error downloading $server_file.";
  }
// close connection
ftp_close($ftp_conn);
?> 