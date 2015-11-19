#!/bin/bash

echo "Reloading..."
cmd=$(pidof server_1000)
kill -USR1 $cmd
echo "Reloaded"

