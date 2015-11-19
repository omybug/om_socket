#!/bin/sh

for i in {2..300}; do
    sleep 1;$(nohup php StressTest.php $i)&
done
