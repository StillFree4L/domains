#!/bin/sh

echo 'HTTP/1.1 200 OK'
echo

stmgrclient check s_inv_push_bar
#stmgrclient check s_push_barrier