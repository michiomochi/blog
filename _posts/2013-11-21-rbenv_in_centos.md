---
layout: post
title:  "CentOSにrbenvをglobal環境にインストールする"
date:   2013-11-21
thumbnail: terminal.png
tags: [Linux, Ruby]
---

## rbenvとは

* rubyのバージョン管理ツール
* rubyのバージョン切り替えがスムーズにできるようになる
* プロダクトごとにrubyのバージョンが指定できるようになる。 (ex, /var/www/html/1.9以下はruby1.9.3で動かして、/var/www/html/2.0以下はruby2.0.0で動かす等)

## 導入

以下の環境で導入していきます。
OS: CentOS6.4

rbenvをインストールする前に依存しているPackageをyumでインストールします。

```bash
% rpm -ivh http://ftp-srv2.kddilabs.jp/Linux/distributions/fedora/epel/6/x86_64/epel-release-6-8.noarch.rpm
% yum install --enablerepo=epel make gcc zlib-devel openssl-devel readline-devel ncurses-devel gdbm-devel db4-devel libffi-devel tk-devel libyaml-devel
```

globalにインストールするのでrootユーザーで作業します。
rbenvにinstallコマンドを付与してくれるruby-buildはrbenvのpluginとしてインストールします。

```bash
% su
% cd /usr/local
% git clone git://github.com/sstephenson/rbenv.git rbenv
% mkdir rbenv/shims rbenv/versions rbenv/plugins
% git clone git://github.com/sstephenson/ruby-build.git rbenv/plugins/ruby-build
% groupadd rbenv
% chgrp -R rbenv rbenv
% chmod -R g+rwxXs rbenv
```

/etc/profile.d/rbenv.sh

```bash
export RBENV_ROOT="/usr/local/rbenv"
export PATH="/usr/local/rbenv/bin:$PATH"
eval "$(rbenv init -)"
```

## rbenvを使用したrubyのインストール

```bash
% rbenv install --list
% rbenv install 2.0.0-p247
% rbenv versions
* system (set by /usr/local/rbenv/version)
  2.0.0-p247
% rbenv global 2.0.0-p247
  system
* 2.0.0-p247 (set by /usr/local/rbenv/version)
% rbenv rehash
% ruby -v
ruby 2.0.0p247 (2013-06-27 revision 41674) [x86_64-linux]
% which ruby
/usr/local/rbenv/shims/ruby
```

これで終わり。

## 最後に

注意点としてはrubyのバージョンを増やす時はrootユーザで作業すること。
通常ユーザーで`% sudo rbenv install 〜`を実行すると`/root/.rbenv`以下にインストールされてしまうようです。

参考:
[CentOSでsystem wideなrbenv+ruby-build環境を構築する](http://nomnel.net/blog/centos-system-wide-rbenv-and-ruby-build/)