--
-- 事前準備クエリ
--

-- 管理者登録
-- admin / admin
INSERT INTO admin (id, username, password, `group`, email, last_login, created_at, updated_at) VALUES(1, 'admin', 'K0VTGAnXgKh1OJcRXlmDcf64j5wriR1MuZdREx1f1lA=', 100, 'admin@hoge.com', UNIX_TIMESTAMP(NOW()), UNIX_TIMESTAMP(NOW()) , UNIX_TIMESTAMP(NOW()));
