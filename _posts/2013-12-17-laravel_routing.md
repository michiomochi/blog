---
layout: post
title:  "Laravelのルーティング書き方まとめ"
date:   2013-12-17
thumbnail: laravel-logo.png
tags: [PHP, Laravel]
---
これは[Laravel Advent Calender](http://qiita.com/advent-calendar/2013/laravel) 17日目の記事です。
昨日は[HiroKwsさんの最強のデバッグツールlaravel-debugger](http://kore1server.com/229)についてでした。

今回はLaravelのルーティングの書き方をまとめてみようと思います。

まず基本ですがLaravelではルーティングの設定はapp/routes.phpにまとめて記載するようになっていますので今回ご紹介するルーティングのコードは全てapp/routes.phpに記載するものと思ってください。

Laravelのルーティングは大きく分けて3つの書き方がありますので順番に見ていきましょう。

## 1, ルーティングと同時に表示内容もクロージャで記載してしまう方法

これは一番シンプルなルーティングの書き方かもしれません。
コードと一緒に見ていきます。

```php
Route::get('hello/', function()
{
    return 'Hello World';
});
```

↑ http://hoge.com/helloにアクセスされたら「Hello World」と表示させるというルーティングです。

```
Route::get('hello/{message}', function($message)
{
    return 'Hello World' . $message;
});
```

↑ helloの次に指定されたパスをパラメータとして受け取り、受け取ったパラメータをクロージャに引数として渡し表示内容を変化させるといったルーティングです。

```
Route::get('hello/{message}', function($message)
{
    return 'Hello World' . $message;
})
->where('message', '[A-Za-z]+');
```

↑ 受け取るパラメータの値をwhereメソッドで指定し、指定した値のものであった場合に適用するといったルーティングです。
whereでの指定の仕方は1番目の引数にパラメータ名を指定し、2番目にパラメータ内容を指定します。
パラメータ内容指定には正規表現が使用可能です。

getの部分はpostやput等のhttpメソッドに対応していますので適宜応用が可能です。

## 2, コントローラーとメソッドを指定しルーティングさせる方法

フレームワークでおなじみのルーティングです。
なんの変哲もないと思います。

```php
Route::get('hello/', 'App\Controllers\helloController@goodmorning');
```

↑ hello/にアクセスされたらhelloControllerコントローラーのgoodmorningメソッドを呼び出せというルーティングです。

```php
Route::get('hello/{message}', 'App\Controllers\helloController@goodmorning');
```

↑ helloの次に指定されたパスをパラメータとして受け取り、受け取ったパラメータをメソッドの引数として渡すというルーティングです。
コントローラーは下記のように書けばいいと思います。

```php
namespace App\Controllers;
class helloContorller extends BaseController
{
    public function goodmorning($message)
    {
        〜
    }
}
```

おなじみですね。

## 3, RESTfulにルーティングさせる方法

このルーティングはすてきです。
僕が最初にLaravelでおおっ！と思ったところはこのルーティングでした。
まずはルーティングのコード。

```
Route::controller('hello/', 'App\Controllers\helloController');
```

↑ このワンライナーのコードを書くだけで下記のような多数のルーティングに対応することができます。

```php
namespace App\Controllers;
class helloController extends BaseController
{
    // getでhello/にアクセスされた場合
    public function getIndex()
    {
        〜
    }

    // getでhello/goodmorningにアクセスされた場合
    public function getGoodmorning()
    {
        〜
    }

    // postでhello/goodmorningにアクセスされた場合
    public function postGoodmorning()
    {
        〜
    }

    // getでhello/goodmorning/messageでアクセスされた場合
    public function getGoodmorning($message)
    {
        〜
    }
}
```

説明不要だと思いますがアクセスされたパスと同じ名前を持つアクションメソッドが実行されています。
各アクションメソッド名の前についているgetやpostはアクセス時のhttpメソッドです。
とても分かりやすくてすてきですね。

## 4, Resourcefulにルーティングさせる方法

これもすてきです。
でも今回調べるまで知りませんでした。orz
てかResourcefulとか言われてもピンとこないと思うのでまずはルーティングコードから見ていきましょう。

```
Route::resource('hello', 'App\Controllers\helloController');
```

これも3のRestfulルーティングと同様ワンライナーで多数のルーティングに対応させることができます。

```php
namespace App\Controllers;
class helloController extends BaseController
{
    // getでhello/にアクセスされた場合
    public function index()
    {
        〜
    }

    // getでhello/createにアクセスされた場合
    public function create()
    {
        〜
    }

    // postでhello/にアクセスされた場合
    public function store()
    {
        〜
    }

    // getでhello/messageにアクセスされた場合
    public function show($message)
    {
        〜
    }

    // getでhello/message/editにアクセスされた場合
    public function edit($message)
    {
        〜
    }

    // putまたはpatchでhello/messageにアクセスされた場合
    public function update($message)
    {
        〜
    }

    // deleteでhello/messageにアクセスされた場合
    public function destroy($message)
    {
        〜
    }
}
```

ちょっとわかりにくいかと思いますので、Laravelの公式ドキュメントに図が掲載されていたのでこちらとあわせてご覧ください。

![Laravel Resourceful routing map](/img/laravel-resourceful-routing-map.png)

図をみるとわかりますが指定したresource(今回の例ではhello)に対してhttpメソッドやパスで直感的にアクセスできるようルーティングしてくれています。

## 最後に
LaravelのルーティングはRestfulルーティングやResourcefulルーティングのおかげで非常にわかりやすくそして綺麗に書けます。
ルーティングの書きやすさは間違いなくLaravelの目玉の一つだと言えると思います。

今回は基本的なルーティングについて説明させて頂きましたがLaravelは他にもルーティングをする前に認証をかけられたり、ルーティングに名前をつけられたりとまだまだ機能は豊富です。
Laravelが気になった方はそちらも確認してみてください。

さて明日の[Laravel Advent Calender](http://qiita.com/advent-calendar/2013/laravel) はkam01さんで「laravelでmodule」についてです。
おたのしみに！