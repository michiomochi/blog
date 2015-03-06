---
layout: post
title:  "CentOSにfluentdを導入"
date:   2013-10-15
thumbnail: fluentd-logo.png
tags: [fluentd, Linux]
---

環境: CentOS6.4

Documentを参照しながら進めていきます
[fluentd Document](http://fluentd.org)

fluentdはgemを使ってインストールも出来るようですが今回はCentOSということもあり、RPMパッケージを使ってインストールしてみたいと思います。
[Installing Fluentd Using rpm Package](http://docs.fluentd.org/articles/install-by-rpm)

# Step0: Before Installation(まずやっておくこと)

Documentに従い、Before Installing fluentdを行う。

## Set Up NTP(ntpサーバーをセットアップ)
参考サイト: http://centossrv.com/ntp.shtml

```
$ yum install -y ntp
```

NTP設定

/etc/ntp.conf

```
#restrict 192.168.1.0 mask 255.255.255.0 nomodify notrap
↓
restrict 192.168.1.0 mask 255.255.255.0 nomodify notrap

server 0.centos.pool.ntp.org
server 1.centos.pool.ntp.org
server 2.centos.pool.ntp.org
↓
# server 0.centos.pool.ntp.org
# server 1.centos.pool.ntp.org
# server 2.centos.pool.ntp.org

server ntp.nict.jp # 日本標準時を提供しているNTPサーバー
server ntp.jst.mfeed.ad.jp # 上記サーバーと直接同期しているNTPサーバー
```

サーバーのタイムゾーンを日本に設定

```
$ cp -p /usr/share/zoneinfo/Japan /etc/localtime
```

NTPサーバー起動時に大幅に時刻がずれているとNTPサーバーが起動できないので、いったん、手動で時刻を合わせる

```
$ ntpdate ntp.nict.jp
```

NTPサーバー起動

```
$ /etc/rc.d/init.d/ntpd start
$ chkconfig ntpd on
```

下記のようにサーバー名の左に*がついていれば同期がとれている証拠

```
     remote           refid      st t when poll reach   delay   offset  jitter
==============================================================================
*ntp-a3.nict.go. .NICT.           1 u   53   64  177   14.559  -221.38 134.998
+ntp2.jst.mfeed. 133.243.236.19   2 u   18   64  377   14.413  -240.87 154.688
```

## Increase Max # of File Descriptors(File descriptorsの最大値をあげる)
File Descriptors:
http://e-words.jp/w/E38395E382A1E382A4E383ABE38387E382A3E382B9E382AFE383AAE38397E382BF.html

現在のFile Descriptorsの最大値を確認

```
$ ulimit -n
1024
```

1024では宜しくないということなのでDocumentに従い上限をあげる

/etc/security/limits.conf

```
# 下記を追加
root soft nofile 65536
root hard nofile 65536
* soft nofile 65536
* hard nofile 65536
```

そしてサーバーを再起動

上限が上がったか確認

```
$ ulimit -n
65536
```

## Optimize Network Kernel Parameters(Network Kernelパラメータの最適化)
sysctl.confファイルをいじることでLinuxはカーネルのチューニングができるようです
http://sourceforge.jp/magazine/08/09/12/0134255

Documentに従いパラメータを追加します

/etc/sysctl.conf

```
net.ipv4.tcp_tw_recycle = 1
net.ipv4.tcp_tw_reuse = 1
net.ipv4.ip_local_port_range = 10240    65535
```

設定を反映

```
$ sysctl -w
```

# Step1: Install from rpm Repository(RPMレポジトリからインストール)
install-redhat.shなるものを使うと自動でtd-agentをインストールしてくれるらしいのでやってみる

```
$ curl -L http://toolbelt.treasure-data.com/sh/install-redhat.sh | sh
```

td.repoとtd-agentパッケージがあるか確認

```
$ ls /etc/yum.repos.d/
td.repo
$ rpm -qa | grep td-agent
td-agent-1.1.17-0.x86_64
```

ある

# Step2: Launch Daemon(デーモンを開始する)
Step1で/etc/init.d/td-agentスクリプトが作成されているのでそれを使う

```
$ sudo /etc/init.d/td-agent start
Starting td-agent: [  OK  ]
```

start, stop, restart, statusコマンドが使えるそうな

ちなみにconfigファイルは下記ディレクトリ
/etc/td-agent/td-agent.conf

# Step3: Post Sample Logs via HTTP(HTTP経由でサンプルログを出す)
デフォルトでログファイルは/var/log/td-agent/td-agent.logにはき出されるようになっています。

Documentに従ってcurlでPOSTリクエストを投げます

```
$ curl -X POST -d 'json={"json":"message"}' http://localhost:8888/debug.test
```

ログが出てるか確認

```
$ tail -f /var/log/td-agent/td-agent.log
2013-10-14 17:56:49 +0000 debug.test: {"json":"message"}
```

出た

デフォルトの設定はポート8888に向けてJsonかMsgpackのパラメータを含ませたHTTPリクエストを投げるとログに記載されるようです
これでfluentdが使えるようになったのであとはconfigファイルをカスタマイズするだけです。

Next Stepsという形で各言語などのログを抽出する方法もDocumentに載っているので確認しましょう。