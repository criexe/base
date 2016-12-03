<?php


$sock = socket_create(AF_INET, SOCK_STREAM, 0);

socket_connect($sock, '127.0.0.1', 3001) or die("Error");

socket_send($sock, "whoami", strlen("whoami"), 0);

socket_recv($sock, $buf, 2045, MSG_WAITALL);

echo $buf;

socket_close($sock);