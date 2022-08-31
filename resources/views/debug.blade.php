<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .Development {
            color: red;
        }

        .Production {
            color: blue;
        }

        .Not {
            color: black;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>Company Code</th>
            <th>Status</th>
        </tr>
        @foreach($list as $key => $value)
        <tr>
            <td>{{ $key }}</td>
            <td class="{{ $value }}">{{ $value }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>