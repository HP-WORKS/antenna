# アダルト動画アンテナサイト
アクセストレードによくあるアンテナサイトのプログラムです。  
数年前に作った物ですが、旬が過ぎたと思うので公開しました。  
一部、簡単にインストール出来る様に改変しています。

## アンテナサイトって何ですか？  
インストール方法など技術的な話以外は下記のページで行なってますのでご覧下さい。  
[【ソース公開】アダルト動画アンテナサイトをリリースしました | アダルトサイト制作会社](https://hp-work.net/knowledge/adult-antenna)

### 動作サンプル
https://antenna.hp-work.net/

## 環境
昔作ったプログラムなので Zend Framework 1 を使っています。  
公開ついでに ZF2 にレプレイスしようと思いましたが、面倒だったので断念しました。。

### 動作要件
* PHP5.6以上
* MySQL5以上
 * PDOですが、MySQLの内部関数を使ってます。
* Apache2.4
 * .htaccessの書式が2.4ですが、変えれば2.2でも動きます
 
## セットアップ手順
1. サーバーのドキュメントルートにレポジトリ内のファイルをアップロード
2. データベースをダンプから作成
 * restrict/application/configs/dump.sql
3. Cronの設定
 * restrict/cron.php を任意に動かして下さい。サンプルサイトでは5分おき
4. 設定ファイルを弄って、初期設定
 * restrict/application/configs/application.ini
 
 ```ini
resources.db.params.host = ;接続先ホスト
resources.db.params.username = ;ユーザー名
resources.db.params.password = ;接続パスワード
resources.db.params.dbname = ;データベース名

admin.username = ;管理画面ユーザー名
admin.password = ;管理画面パスワード
 ```
 

  
  
  
  
