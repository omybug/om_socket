#!/bin/sh
/usr/local/bin/inotifywait -mrq --fromfile './notify.list' --timefmt '%d/%m/%y/%H:%M' --format '%T%w%f' -e modify,delete,create |
while read file
do
    ./reload.sh
done

