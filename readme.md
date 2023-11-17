## docker環境参考サイト 
[ハマりを回避してDockerでLaravel6系環境構築](https://qiita.com/2san/items/6e8af71f3186ad300538)

# 下記コマンドをたたく
```
$ docker compose build
$ docker compose up -d
$ docker compose exec backend bash
root@123456789123:/var/www/html# php -v

PHP 7.3.32 (cli) (built: Oct 28 2021 17:01:17) ( NTS )
// 省略

root@123456789123:/var/www/html# composer -V

Composer version 2.0.14 2021-05-21 17:03:37

root@123456789123:/var/www/html# composer create-project --prefer-dist "laravel/laravel=6.*" .
root@123456789123:/var/www/html# chown www-data storage/ -R

php artisan migrate
php artisan db:seed

```

## キャッシュ削除
php artisan config:cache