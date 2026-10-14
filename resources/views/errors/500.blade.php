<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - サーバーエラー</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            text-align: center;
            background: #ffffff;
            padding: 48px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            width: 100%;
        }
        .code {
            font-size: 80px;
            font-weight: 800;
            color: #ef4444;
            line-height: 1;
            margin-bottom: 16px;
        }
        h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #0f172a;
        }
        p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .btn {
            display: inline-block;
            background: #475569;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #334155;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">500</div>
        <h1>サーバーエラーが発生しました</h1>
        <p>申し訳ありません。システム内部で予期しない問題が発生しました。しばらく時間を置いてから再度お試しください。</p>
        <a href="/" class="btn">トップページへ戻る</a>
    </div>
</body>
</html>
