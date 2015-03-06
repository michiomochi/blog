---
layout: post
title:  "git使い方まとめ"
date: 2014-07-30
thumbnail: git_logo.png
categories: git
---

# submoduleの使い方

## リモートリポジトリをsubmoduleとして登録する
`% git submodule add "リモートリポジトリURL" "ディレクトリパス"`
このコマンドを実行すると指定したリモートリポジトリがsubmoduleとして登録されます。
submoduleの設定ファイルは.gitmodulesです。

## submoduleを含むソースをcloneする
submoduleを含むソースをclone等してきた場合、submoduleとして登録されているディレクトリは空のままcloneされてきます。
なので一度初期化をしてからupdateを行う必要があります。
`% git submodule`コマンドで初期化されていないsubmoduleは確認ができます。
`-0b18172dcd099078d286821236b226b3e9699217 .vim/bundle/neobundle.vim`
このように頭に「-」がついているものは初期化が済んでいないsubmoduleです。

```
% ls -al .vim/bundle/neobundle.vim/
  合計 8
  drwxrwxr-x 2 vagrant vagrant 4096 11月 18 16:31 2013 .
  drwxrwxr-x 3 vagrant vagrant 4096 11月 18 16:31 2013 ..
% git submodule
-0b18172dcd099078d286821236b226b3e9699217 .vim/bundle/neobundle.vim
% git submodule init
Submodule '.vim/bundle/neobundle.vim' (https://github.com/Shougo/neobundle.vim.git) registered for path '.vim/bundle/neobundle.vim'
% git submodule update
Initialized empty Git repository in /home/vagrant/dotfiles/.vim/bundle/neobundle.vim/.git/
remote: Counting objects: 5358, done.
remote: Compressing objects: 100% (2668/2668), done.
remote: Total 5358 (delta 2105), reused 5301 (delta 2050)
Receiving objects: 100% (5358/5358), 1.22 MiB | 411 KiB/s, done.
Resolving deltas: 100% (2105/2105), done.
Submodule path '.vim/bundle/neobundle.vim': checked out '0b18172dcd099078d286821236b226b3e9699217'
% ls -al bundle/neobundle.vim/
合計 48
drwxr-xr-x 10 vagrant vagrant 4096 11月 18 16:35 2013 .
drwxrwxr-x  3 vagrant vagrant 4096 11月 18 16:34 2013 ..
drwxrwxr-x  8 vagrant vagrant 4096 11月 18 16:35 2013 .git
-rw-rw-r--  1 vagrant vagrant    9 11月 18 16:35 2013 .gitignore
-rw-rw-r--  1 vagrant vagrant 2614 11月 18 16:35 2013 README.md
drwxrwxr-x  4 vagrant vagrant 4096 11月 18 16:35 2013 autoload
drwxrwxr-x  2 vagrant vagrant 4096 11月 18 16:35 2013 doc
drwxrwxr-x  2 vagrant vagrant 4096 11月 18 16:35 2013 ftdetect
drwxrwxr-x  2 vagrant vagrant 4096 11月 18 16:35 2013 plugin
drwxrwxr-x  2 vagrant vagrant 4096 11月 18 16:35 2013 syntax
drwxrwxr-x  2 vagrant vagrant 4096 11月 18 16:35 2013 test
drwxrwxr-x  2 vagrant vagrant 4096 11月 18 16:35 2013 vest
```

gitのcommitメッセージ入力時に使用するeditorの指定
`% git config --global core.editor "vim"`
