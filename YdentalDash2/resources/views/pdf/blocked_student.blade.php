<!DOCTYPE html>
<html lang="ar">
<head>

    <meta charset="UTF-8">
    <title>الطلاب المحجوبين</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
       /* Add your styles here */
       body {
           direction: rtl;
           text-align: right;
           font-family: DejaVu Sans, sans-serif;
           background-color: #f8f9fa;
       }
       .container {
           margin-top: 50px;
           display: flex;
           justify-content: center; /* Center horizontally */
       }
       h1 {
           color: #343a40;
           margin-bottom: 30px;
           text-align: center;
       }

       table {
           direction: rtl;
           background-color: #ffffff;
           border-radius: 5px;
           box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
           width: 100%; /* Make the table responsive */
           max-width: 800px; /* Set a max width for the table */
       }
       th {
           background-color: #c2c2c2;
           color: white;
       }
       th, td {
           text-align: center;
       }
   </style>
</head>
<body>
    <h1>تقرير الطلاب المحجوبين </h1>
    <table>
        <thead>
            <tr>
                <th>رقم الطالب</th>
                <th>اسم الطالب</th>
                <th>البريد الالكتروني</th>
                <th>حالة الطالب</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name}}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->isBlocked }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
