<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Departments Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Header with logo + title -->
    <div class="header">       
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ public_path('newlogo.png') }}" alt="GGCC Logo" height="250">
            <h1>GGCC INVENTORY</h1>
             <h2>Departments Report</h2>
        </div>
    </div>
        

    <!-- Departments Table -->
    <table>
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Total Members</th>
                <th>Total Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
            <tr>
                <td>{{ $department->name }}</td>
                <td>{{ $department->members_count }}</td>
                <td>{{ $department->items_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
