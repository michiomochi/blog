---
layout: post
title:  "PHPのログをfluentdにて収集"
date:   2013-10-16
thumbnail: fluentd-logo.png
tags: [fluentd, Linux, PHP]
---

fluentd Document
[Data Import from PHP Applications](http://docs.fluentd.org/articles/php)

PHPからログをfluentdにインポートするには「fluent-logger-php」というライブラリを使用します。

fluent-logger-phpはPHP 5.3以上でないと使用することができないので注意です

なにはともあれ、fluentdがインストールされていないとどうにもならないので下記エントリを参考にサクッとfluentdをインストールして下さい
[CentOSにfluentdを導入](http://qiita.com/michiomochi@github/items/1a3cd07497550bc4d5c2)

fluentdのconfigファイルを編集し、ログの受取り口を設定します

/etc/td-agent/td-agent.conf

```
# fluent-logger-php test用に追記
<source>
  type unix
  path /var/run/td-agent/td-agent.sock
</source>
<match fluentd.test.**>
  type stdout
</match>
```

fluentdを再起動

```
$ /etc/init.d/td-agent restart
```

fluent-logger-phpをgit cloneしてきます

```
$ git clone https://github.com/fluent/fluent-logger-php.git
```

git cloneしてくるとfluent-logger-phpというディレクトリが生成されるのでそのディレクトリ内にfluent-logger-php-test.phpというファイルを作成し、下記を記載します

fluent-logger-php/fluent-logger-php-test.php

```
<?php
require_once './src/Fluent/Autoloader.php';
use Fluent\Logger\FluentLogger;
Fluent\Autoloader::register();
$logger = new FluentLogger("unix:///var/run/td-agent/td-agent.sock");
$logger->post("fluentd.test.follow", array("from"=>"userA", "to"=>"userB"));
```

で、fluent-logger-php-test.phpをcuiで実行します

```
$ php fluent-logger-php-test.php
```

fluentdのログを確認します

/var/log/td-agent/td-agent.log

```
2013-10-16 03:52:12 +0900 fluentd.test.follow: {"from":"userA","to":"userB"}
```

出た
