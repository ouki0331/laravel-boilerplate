<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'PDF Document' }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; padding: 20px; }
        h1 { color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f1f5f9; }
    </style>
</head>
<body>
    <h1>{{ $title ?? '帳票サンプル' }}</h1>
    <p>発行日: {{ $date ?? now()->toDateString() }}</p>
    <p>お客様名: {{ $customer ?? 'サンプル株式会社' }} 様</p>

    <table>
        <thead>
            <tr>
                <th>項目</th>
                <th>数量</th>
                <th>単価</th>
                <th>合計</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items ?? [] as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['qty'] }}</td>
                <td>¥{{ number_format($item['price']) }}</td>
                <td>¥{{ number_format($item['qty'] * $item['price']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
