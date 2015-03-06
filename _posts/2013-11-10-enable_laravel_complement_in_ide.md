---
layout: post
title:  "LaravelでIDE補完を有効にする"
date:   2013-11-10
thumbnail: laravel-logo.png
tags: [Laravel, PHP, PhpStorm]
---

補完が無いIDEなんてIDEの意味ないじゃない。
ということで今流行りのLaravelでIDE補完機能を有効にする設定を行います。
当方PhpStormを使用していますがEclipse等でも同じやり方で補完がきくようになるはずです。

ではやっていきます。
Laravel IDE Helper Generatorなる便利なものがあるのでこちらを使用します。
[Laravel IDE Helper Generator](https://github.com/Barryvdh/laravel-ide-helper)

最近はComposerを使用するのがナウいようなのでcomposerを使用してLaravel IDE Helperを導入します。
LaravelをComposerで導入した人はすでにComposerが入っていると思いますが、まだ入っていないよーという人はComposerの公式ページ通りやってくださいな。curlで取ってきて実行するだけです。
[Composer](http://getcomposer.org/doc/00-intro.md)

ではではcomposer.jsonに下記を記載しましょう。
composer.jsonはすでにLaravelプロジェクトディレクトリにあるはずなのでそちらに追記する形にしましょう。

LaravelでIDE補完を有効にする

```
{
	"require": {
		// ↓下記を追加↓
		"barryvdh/laravel-ide-helper": "1.*"
	},
}
```

追記が完了したらcomposer updateを実行します。

```
% composer update
```

Composerをグローバルに置いていない方は↓のような実行方法になるかもしれません。適宜実行してください。

```
% php composer.phar update
```

composer updateが完了したらvendorディレクトリ以下にbarryvdh/laravel-ide-helperというディレクトリが作成されていればここまで問題なしです。

次にlaravelの設定ファイルapp/config/app.phpのservice providerの箇所に追記をします。

app/config/app.php

```
'providers' => array(
	// ↓ 下記を追記 ↓
	'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider',
),
```

service providerに追記箇所を追記したらLarabelのコマンドラインツールのartisanを使用してIDE補完に使うファイルを生成します。

```
% php artisan ide-helper:generate
```

_ide_helper.phpというファイルが生成されていれば完了です。
これで補完がきくようになります。

チームで開発などをしている場合はcomposer.jsonファイルに下記のように記載をしてcommitしてあげると他の人がLaravel IDE Helperをいれる場合にcomposer updateをするだけで入るので便利です。

composer.json

```
{
	"scripts":{
    		"post-update-cmd":[
			// ↓ 下記を追記 ↓
        			"php artisan ide-helper:generate",
        			"php artisan optimize",
    		]
	},
}
```

これでじゃかじゃか補完を使用して開発しましょう。
