<!DOCTYPE html>
<html lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>الحجوزات </title>
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
    <h1>جميع الحجوزات</h1>
     <div class="container">
        <h4>تقرير حول الحجوزات  </h4>

        @if($appointments->isEmpty())
            <div class="alert alert-warning" role="alert">
لم يتم العثور على حجوزات
            </div>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>رقم الحجز</th>
                        <th>اسم الطالب</th>
                        <th>اسم المريض</th>
                        <th>تاريخ الحجز</th>
                        <th>حالة الحجز</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->student->name}}</td>
                            <td>{{ $appointment->patient->name }}</td>
                            <td>{{ $appointment->thecase->schedules->available_date }}</td>
                            <td>{{$appointment->status}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>


</body>
</html>
