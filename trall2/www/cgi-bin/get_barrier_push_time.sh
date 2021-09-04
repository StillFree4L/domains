#!/bin/sh

echo 'HTTP/1.1 200 OK'
echo

echo "$(txtconfig /etc/streams.ini barrier push_time)"