-- Project Name : noname
-- Date/Time    : 2015/01/13 18:41:23
-- Author       : fumiaki
-- RDBMS Type   : MySQL
-- Application  : A5:SQL Mk-2

-- 管理者
drop table admin cascade;

create table admin (
  id INT UNSIGNED AUTO_INCREMENT not null comment '管理者ID'
  , username VARCHAR(50) not null comment 'アカウント'
  , password CHAR(255) not null comment 'パスワード'
  , group INT default 1 not null comment 'グループ'
  , email VARCHAR(255) not null comment 'メールアドレス'
  , last_login VARCHAR(25) not null comment '最終ログイン'
  , login_hash VARCHAR(255) not null comment 'ログインハッシュ'
  , profile_fields TEXT not null comment 'プロファイルフィールド'
  , created_at INT not null comment '作成日時	 UNIXタイムスタンプ'
  , updated_at INT not null comment '更新日時	 UNIXタイムスタンプ'
  , constraint admin_PKC primary key (id)
) comment '管理者' ENGINE=InnoDB CHARACTER SET utf8;
