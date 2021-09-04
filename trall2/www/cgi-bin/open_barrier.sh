#!/bin/sh

echo 'HTTP/1.1 200 OK'
echo

#echo -e "\nbarrier opened for $(txtconfig /etc/streams.ini barrier push_time) seconds" >/dev/console
stmgrclient rise e_open_barrier
