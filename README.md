## Local Testing
1. run
```
$ php artisan serve
```
2. then
```
$ ngrok http --domain=strictly-skilled-seahorse.ngrok-free.app 8000
```
3. run schedule
```
$ php artisan schedule:run
```