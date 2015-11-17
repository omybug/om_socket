#!/bin/sh

for i in $(seq 10); do
    sleep 1;$(nohup php StressTest.php $i)&
done

