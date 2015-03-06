---
layout: post
title:  "AngularJSを使用してajax通信を行おう"
date:   2013-12-09
thumbnail: angularjs-logo.png
tags: [JavaScript, AngularJS]
---

最近話題になっているAngularJS。
乗り遅れないようにちょっと触ってみたので備忘録として残します。

AngularJSの全体像は他の記事で触れるとして、今回はAngularJSでajax通信を行う手順を紹介します。

AngularJSでajax通信を行う方法は2パターンあります。

* $httpサービスを使用する
* $resourceサービスを使用する

です。

各サービスのDocumentは下記を参照してください。
$http
[公式(英語)](http://angularjs-jp.github.io/docs/api/ng.$http)
[js STUDIO(日本語翻訳版)](http://js.studio-kingdom.com/angularjs/ng_service/$http)

$resource
[公式(英語)](http://angularjs-jp.github.io/docs/api/ngResource.$resource)
[js STUDIO(日本語翻訳版)](http://js.studio-kingdom.com/angularjs/ngresource_service/$resource)

今回は$resourceサービスを使う方法でajax通信を行い、該当URLのfacebookいいね数を取得してみたいと思います。
本ブログでもこのロジックを使用し、記事のいいね数を取得しています。
![取得したいいね数表示箇所](/img/sns_count.png)

まずは完成コード。

index.html

```html
<!DOCTYPE html>
<html ng-app="ajax-app">
<head>
    <meta charset="utf-8">
    <script src="/js/angular.min.js"></script>
    <script src="/js/angular-resource.min.js"></script>
    <script src="/js/ajax.js"></script>
</head>
<body>
<div class="sns" ng-controller="snsCtrl">
    <ul>
        <li ng-init="getFacebookCount('http://google.com')">
            <span>{ { fbCount.count } }</span>
        </li>
    </ul>
</div>
</body>
</html>
```

js/ajax.js

```javascript
angular.module('ajax-app', ['ngResource');
var snsCtrl = function ($scope, $resource) {
    $scope.getFacebookCount = function($url) {
        var FacebookCount = $resource('/api/facebook.php?url=:url');
        FacebookCount.get({ url: $url }, function(data) {
            $scope.fbCount = {
                url: data.url,
                count: data.count
            };
        });
    };
};
```

api/facebook_count_api.php

```php
<?php

header('Content-Type: application/json; charset=utf-8');

$url = $_GET['url'];
$api = 'http://api.facebook.com/restserver.php?method=links.getStats&urls=';
$requestUrl = $api . urlencode($url);

$response = file_get_contents($requestUrl);
$response = simplexml_load_string($response);
$facebookCount = array();
$facebookCount['url'] = $url;
$facebookCount['count'] = (Int)$response->link_stat->like_count;
echo json_encode($facebookCount);
```

では順番に見ていきましょう。

$resourceを使用するにはngResourceモジュールをインポートする必要があるのでまずはインポートを行います。

```javascript
angular.module('ajax-app', ['ngResource');
```

その次にコントローラーを作成します。
コントローラー名はsnsCtrlとします。

```javascript
var snsCtrl = function ($scope, $resource) {
};
```

AngularJSでの通常のコントローラだと引数に$scopeのみしか指定しませんが、$resourceサービスを使用する時は上記のように$resourceも指定します。
次にajaxを行うgetFacebookCountメソッドをコントローラー内に作成します。
引数にurlを渡せるようにしましょう。

```javascript
var snsCtrl = function ($scope, $resource) {
    var getFacebookCount = function($url) {
    };
};
```

作成したメソッドがhtmlファイルが表示されたら自動的に呼ばれるようng-initディレクティブをhtmlファイル上で設定します。
ng-initディレクティブは指定された要素が表示される前にタスクを実行するという機能を持っています。
ネイティブjsのwindow.onloadだと思っていただければわかりやすいかもしれません。

```html
<li ng-init="getFacebookCount('http://google.com')">
</li>
```

メソッドを作成したら$resourceを使用したajaxロジックを作成します。

```javascript
var snsCtrl = function ($scope, $resource) {
    var getFacebookCount = function($url) {
        var FacebookCount = $resource('/api/facebook.php?url=:url');
        FacebookCount.get({ url: $url }, function(data) {
        });
    };
};
```

$resourceはresourceオブジェクトを返します。
resourceオブジェクトはデフォルトで下記のメソッドを保持しています。

```
{ 'get':    {method:'GET'},
  'save':   {method:'POST'},
  'query':  {method:'GET', isArray:true},
  'remove': {method:'DELETE'},
  'delete': {method:'DELETE'} };
```

$resourceの引数に指定したurlへ上記のメソッドを使用しアクセスするイメージです。
本コードではurlは別途PHPで作成したapiを指定しています。
メソッド実行時にurlのバインドが使用できますので動的な実行もできるようになっています。
メソッドが実行され、返却されたデータはfunction(data)のdataの中にcallbackされてきます。
callbackされたデータを使用して、AngularJSお得意のhtmlファイルへのバインド表示を行います。

```
var FacebookCount = $resource('/api/facebook.php?url=:url');
FacebookCount.get({ url: $url }, function(data) {
    $scope.fbCount = {
        url: data.url,
        count: data.count
    };
});
```

```
 <span>{ { fbCount.count } }</span>
```

これでajax通信を行った結果がhtmlファイルに反映されるようになりました。
AngularJSはまだまだ奥が深そうなのでもう少し触ってみたくなりました。
いろいろなことができるのでドキュメントを眺めているだけでも楽しいですよね。