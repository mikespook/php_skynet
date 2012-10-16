#!/bin/sh
nohup doozerd -timeout 5 -l ':10000' -w ':8000' -c 'dzns' >/tmp/dzns.log 2>&1 &
nohup doozerd -timeout 5 -l ':8046' -w ':8001' -c 'skynet' -b 'doozer:?ca=:10000' >/tmp/doozer.log 2>&1 &
nohup skydaemon --version=1 >/tmp/skynet_daemon.log 2>&1 &
