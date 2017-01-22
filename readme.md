
# real-time-pad

this is a really-real time collaborative word processor, you can visit the [home page](http://pad.laravel.band/pad) for more information.


## Installation

first, make sure you have installed mongodb and swoole extension on your php.

clone from github and install dependence

```
$ git clone https://github.com/zhaohehe/real-time-pad.git
$ composer install
```

then copy config file

```
$ cp config.example.php config.php
```
run your mongodb and redis, then start socket server

```
$ php socket.php
```



## License
The pad is licensed under the MIT license.

