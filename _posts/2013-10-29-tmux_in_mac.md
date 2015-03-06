---
layout: post
title:  "Macにtmuxを導入する"
date:   2013-10-29
thumbnail: terminal.png
tags: [Mac, tmux]
---

職場の先輩にtmuxをおすすめされたので導入してみました！
これからはうっかりTerminalを閉じてしまって泣くことがなくなりそうです！

tmuxとは
引用 : [https://bytebucket.org/ns9tks/tmux-ja/wiki/tmux-ja.html](https://bytebucket.org/ns9tks/tmux-ja/wiki/tmux-ja.html)
> tmux は端末を多重化し、 1 つのスクリーンから複数の端末を作成、アクセス、制御することを可能にします。 tmux をスクリーンからデタッチしバックグランドで動作させておいて、 その後再度アタッチすることができます。
> tmux は起動されると 1 つのウィンドウを持つ新しい セッションを作成しスクリーンに表示します。 スクリーンの一番下にあるステータスラインはカレントセッションの情報を表示し、 対話型コマンドの入力に使用されます。
> セッションとは tmux に管理される疑似端末の集合の 1 つです。 各セッションはリンクする 1 つ以上のウィンドウを持ちます。 ウィンドウはスクリーン全体を占有し、 各々が独立した疑似端末である複数の矩形ペインへ分割することができます (疑似端末の技術的な詳細は pty(4) マニュアルページドキュメント)。 tmux のインスタンスはいくつでも同じセッションに接続することができ、 ウィンドウはいくつでも同じセッションに作成しておくことができます。 全セッションが kill されたときに tmux は終了します。

ようするに

* 1つの画面でたくさんの環境を開いておける
* Terminalを閉じてもまたtmuxを実行すれば閉じた時の状態で復元できる

ということです。
便利です。

では早速入れてみましょう。
おなじみのHomebrewでさくっとインストールします。

```
% brew install tmux
==> Installing dependencies for tmux: pkg-config, libevent
==> Installing tmux dependency: pkg-config
==> Downloading http://pkgconfig.freedesktop.org/releases/pkg-config-0.28.tar.gz
######################################################################## 100.0%
==> ./configure --prefix=/usr/local/Cellar/pkg-config/0.28 --disable-host-tool --with-internal-glib -
==> make
==> make check
==> make install
  /usr/local/Cellar/pkg-config/0.28: 10 files, 604K, built in 64 seconds
==> Installing tmux dependency: libevent
==> Downloading https://github.com/downloads/libevent/libevent/libevent-2.0.21-stable.tar.gz
######################################################################## 100.0%
==> ./configure --disable-debug-mode --prefix=/usr/local/Cellar/libevent/2.0.21
==> make
==> make install
  /usr/local/Cellar/libevent/2.0.21: 48 files, 1.8M, built in 30 seconds
==> Installing tmux
==> Downloading http://downloads.sourceforge.net/project/tmux/tmux/tmux-1.8/tmux-1.8.tar.gz
######################################################################## 100.0%
==> Patching
patching file osdep-darwin.c
patching file utf8.c
==> ./configure --prefix=/usr/local/Cellar/tmux/1.8 --sysconfdir=/usr/local/etc
==> make install
==> Caveats
Example configurations have been installed to:
  /usr/local/Cellar/tmux/1.8/share/tmux/examples

Bash completion has been installed to:
  /usr/local/etc/bash_completion.d
==> Summary
  /usr/local/Cellar/tmux/1.8: 14 files, 624K, built in 94 seconds
```

これでtmuxとTerminal上で入力すればtmuxが立ち上がるようになります。
tmux上での基本的な操作は下記を参照ください。
bind-keyはデフォルトではC-b(Ctrl + b)になっています。

* `bind-key d` : デタッチ(tmuxの終了)
* `bind-key c` : 新しいウィンドウを作る
* `bind-key n` : 次のウィンドウへ移動する
* `bind-key p` : 前のウィンドウへ移動する
* `bind-key 数字` : 指定したウィンドウへ移動する
* `bind-key w` : ウィンドウを一覧表示する
* `bind-key %` : 画面を縦に分割する
* `bind-key ”` : 画面を横に分割する
* `bind-key !` : 画面の分割を解除する
* `bind-key o` : 分割した画面間を移動する
* `bind-key ,` : windowに名前をつける
* `bind-key ?` : コマンド一覧を表示

よく使うtmuxコマンドは下記です。

* `% tmux ls` : セッション一覧を確認
* `% tmux a` : 最後に使用したセッションにアタッチ
* `% tmux a -t "セッション名"` : 指定したセッションにアタッチ
* `% tmux kill-session -t "セッション名"` : セッション終了

ちなみに私の.tmux.confはこのようになっています。
まだ導入したばかりであまりカスタマイズしていませんがご参考までに。

```
# Prefix変更 C-b -> C-t
set-option -g prefix C-t
bind-key C-t send-prefix
unbind-key C-b

# key bind (windowの移動)
# カーソルキーで移動
bind -n left previous-window
bind -n right next-window

# key bind (paneの移動)
# Shift + カーソルキーで移動
bind -n S-left select-pane -L
bind -n S-down select-pane -D
bind -n S-up select-pane -U
bind -n S-right select-pane -R

# 256色端末を使用する
set-option -g default-terminal "screen-256color"
# viのキーバインドを使用する
set-window-option -g mode-keys vi
```
