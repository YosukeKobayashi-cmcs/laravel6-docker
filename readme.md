## はじめに
このレポジトリはdockerでlaravelとmysqlの環境を用意します。
それをもとに検証するレポジトリです。

## docker&laravel環境参考サイト 
[ハマりを回避してDockerでLaravel6系環境構築](https://qiita.com/2san/items/6e8af71f3186ad300538)
[モダンフロントとLaravelでCRUD処理をやってみる](https://qiita.com/2san/items/57d6a2cbe053dd314223)

## 準備
docker desktopをインストールしておく
https://www.docker.com/products/docker-desktop/

## このプロジェクトのルートフォルダに行き下記コマンドをたたく(初回のみ)
```
## コンテナを立ち上げる
docker compose up

## laravelのサーバーに入る
docker compose exec backend bash

## dbの初期化
php artisan migrate
php artisan db:seed
```

## 二回目以降
```
## コンテナを立ち上げる
docker compose up
```

## ホーム
http://127.0.0.1/public

## 対象URL
### SFTPでファイルを書き出しサンプル
http://127.0.0.1/public/outputCsvBySFtp

### SFTPでファイルをリネームサンプル
http://127.0.0.1/public/renameSFtpFile

### mysqlのデーターをjsonで返すサンプル（参照：chromeでjsonの確認方法）
http://127.0.0.1/public/fetchTodos

### コードカバレッジ結果の生成と表示
http://127.0.0.1/public/codeCoverage/report

### コードカバレッジデータの削除
http://127.0.0.1/public/codeCoverage/delete

## chromeでjsonの確認方法
文字化けするので下記をインストール

https://chromewebstore.google.com/detail/json-viewer/gbmdgpbipfallnflgajpaliibnhdgobh?hl=ja&pli=1

## キャッシュ削除
php artisan config:cache

## 開発メモ
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
