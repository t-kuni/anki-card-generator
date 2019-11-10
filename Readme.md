# 本ツールについて


## 概要

英語のリーディング力向上のために作成したツールです。  
毎朝、[Anki](http://rs.luminousspice.com/how-to-anki/#i-1)に新しい問題を配信してくれます。
（※AnkiはスマホやWebで暗記カードを作成して学習できるアプリ＆Webサイト）  

![anki_card.png](https://raw.githubusercontent.com/t-kuni/github-issue-2-anki-card/master/docs/anki_card.png)
  
具体的には、Github Issue APIから英文を取得し、Google Translate APIに掛けて日本語化し  
表面が英語、裏面がその日本語訳となる暗記カードをAnkiに登録します。  
AnkiにはAPIが用意されていないためPuppeteer(ヘッドレスchrome)を使用してログイン＆カード追加を行っています。  

![screen_shot.png](https://raw.githubusercontent.com/t-kuni/github-issue-2-anki-card/master/docs/screen_shot.png)  
（実際にスマホ上のアプリ（Anki）で見るとこんな感じで見えます）

## アーキテクチャ

PHPのプログラムが動くコンテナ(docker)を作成し  
AWS Fargateのスケジューリング機能を使って毎朝実行しています。  

![architecture.png](https://raw.githubusercontent.com/t-kuni/github-issue-2-anki-card/master/docs/architecture.png)

## フォルダ構成

主要なフォルダ構成は以下の通りです。  
実験的にDDDな構成にしています。

```
(root)
├ app                # 主にソースコードが格納されているフォルダ
│ ├ src
│ │ ├ Application    # アプリケーション層
│ │ ├ Domain         #　ドメイン層
│ │ │ ├ Models
│ │ │ └ ObjectValues
│ │ ├ Infrastructure # インフラ層
│ │ └ App.php        # アプリケーションのメイン処理が記述されたスクリプト
│ ├ tests            # テストコードが格納されているフォルダ
│ ├ storage          # 保存データが格納されるフォルダ
│ ├ bootstrap.php    # DIコンテナの初期化などを行うスクリプト
│ ├ entrypoint.php   # 一番最初に実行されるスクリプト
│ ├ helper.php       # ヘルパ関数が定義されているスクリプト
│ └ .env             # 環境毎の設定ファイル
└ env                # コンテナの定義が格納されているフォルダ
　 ├ app              # 本番実行用のコンテナ定義
　 │ └ Dockerfile
　 └ app-debug        # デバッグ実行用のコンテナ定義	
　 　 └ Dockerfile
```

## 主な使用ライブラリ

* nesk/puphpeteer
    * ヘッドレスchromeをPHPから操作する 
* vlucas/phpdotenv
    * .envファイルを読み込む
* markrogoyski/simplelog-php
    * ファイルにログを書きだすシンプルなロガー
* rakibtg/sleekdb
    * シンプルなドキュメントDB
* nesbot/carbon
    * 日付操作
* opis/container
    * シンプルなDIコンテナ
* mockery/mockery
    * テスト用のモック作成ライブラリ

# Build

Build container.

```
docker-compose build app
```

Install php libraries.

```
docker-compose run --rm app composer install
```

Install node packages.  
Run follow command on host OS.

```
npm install
```

# Run

```
docker-compose run --rm app
```

# Boot shell

```
docker-compose run --rm app sh
```