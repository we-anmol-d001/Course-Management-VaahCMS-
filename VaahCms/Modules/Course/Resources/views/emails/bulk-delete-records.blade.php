<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notification Email</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        table.main-table {
            background-color: #ffffff;
            width: 600px;
            margin: 40px auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
            border: none;
        }

        .header {
            background-color: #004085;
            color: #ffffff;
            padding: 20px 30px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        .content {
            padding: 30px;
            font-size: 15px;
            line-height: 1.6;
            color: #444;
        }

        .content p {
            margin-bottom: 15px;
        }

        .record-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            color: #333;
        }

        .record-table th,
        .record-table td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        .record-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .record-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
    </style>

</head>
<body>
    <table class="main-table" cellpadding="0" cellspacing="0">
        
        <tr>
            <td colspan="2" class="header">
                Database Update
            </td>
        </tr>

        
        <tr>
            <td colspan="2" class="content">
                <p>Hello {{ $super_admin }},</p>
                <p>
                    All <strong>{{ class_basename($record->first()) }}</strong> records listed below have been 
                    <strong>deleted</strong> from the system by 
                    <strong>{{ optional($record->first()->deletedByUser)->name ?? 'N/A' }}</strong> on 
                    <strong>{{ $record->first()->deleted_at }}</strong>.
                </p>


                <table class="record-table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                    @foreach($record as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
