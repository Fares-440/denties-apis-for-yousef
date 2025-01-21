{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->

        <style>

body {
            direction: rtl;
            text-align: right;
            font-family: DejaVu Sans, sans-serif;
            background-color: #f8f9fa;
        }
            .table th, .table td {
                direction: rtl;

    vertical-align: middle;
}

        </style>
    </head>
    <body>
        <div class="container mt-5">
            <h2 class="text-center mb-4">تفاصيل الحجز </h2>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تقرير الحجز</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>اسم الطبيب</th>
                            <td>{{ $appointment->student->name }}</td>
                        </tr>
                        <tr>
                            <th>اسم المريض</th>
                            <td>{{ $appointment->patient->name }}</td>
                        </tr>
                        <tr>
                            <th>الجنس</th>
                            <td>{{ $appointment->thecase->gender == 'M' ? 'ذكر' : 'أنثى' }}</td>
                        </tr>
                        <tr>
                            <th>التكلفة</th>
                            <td>{{ $appointment->thecase->cost }} سنة</td>
                        </tr>
                        <tr>
                            <th>الإجراء</th>
                            <td>{{ $appointment->thecase->procedure }}</td>
                        </tr>
                        <tr>
                            <th>وصف الحالة </th>
                            <td>{{ $appointment->thecase->description }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الحجز</th>
                            <td>{{ $appointment->thecase->schedules->available_date }}</td>
                        </tr>
                        <tr>
                            <th>وقت الحجز</th>
                            <td>{{ $appointment->thecase->schedules->available_time }}</td>
                        </tr>
                        <tr>
                            <th>حالة الحجز</th>
                            <td>
                                @if($appointment->status == 'مؤكد')
                                    <span class="badge bg-success">مؤكد</span>
                                @elseif($appointment->status == 'بانتظار التأكيد')
                                    <span class="badge bg-warning">قيد الانتظار</span>
                                    @elseif($appointment->status == 'مكتمل')
                                    <span class="badge bg-info"> مكتمل</span>
                                @else
                                    <span class="badge bg-danger">ملغي</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <a  class="btn btn-primary">عودة إلى الصفحة الرئيسية</a>
                </div>
            </div>
        </div>
    </body>
</html> --}}

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الحجز  </title>
    <style>

body {
    font-family: DejaVu Sans, sans-serif;
    background-color: #f4f4f9;
    color: #333;
}

.container {
    max-width: 900px;

    margin: 40px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

h1, h2 {
    text-align: center;
    color: #333;
}

hr {
    margin-top: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
}

/* معلومات الطبيب */
.doctor-info {
    text-align: center;
    padding-bottom: 20px;
}

.doctor-info h1 {
    font-size: 2rem;
    color: #616161;
}

/* جدول المعلومات */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table th, table td {
    padding: 12px;
    text-align: right;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #f7f7f7;
    font-weight: bold;
}

table td {
    background-color: #fafafa;
}

/* معلومات المريض */
.patient-info {
    margin-bottom: 30px;
}

.patient-info h2 {
    color: #616161;
}

/* معلومات الحجز */
.booking-info {
    margin-bottom: 30px;
}

.booking-info h2 {
    color: #616161;
}






    </style>
</head>
<body>
    <div class="container">
        <!-- اسم الطبيب -->
        <div class="doctor-info">
            <h1>د. {{ $appointment->student->name }}</h1>
            <hr>
        </div>

        <!-- معلومات المريض -->
        <div class="patient-info">
            <h2>معلومات المريض</h2>
            <table>
                <tr>
                    <th>اسم المريض</th>
                    <td>{{ $appointment->patient->name }}</td>
                </tr>
                <tr>
                    <th>الجنس</th>
                    <td>{{ $appointment->thecase->gender }}</td>
                </tr>
                <tr>
                    <th>التكلفة</th>
                    <td>{{ $appointment->thecase->cost }} ريال</td>
                </tr>
            </table>
        </div>

        <!-- معلومات الحجز -->
        <div class="booking-info">
            <h2>تفاصيل الحجز</h2>
            <table>
                <tr>
                    <th>الإجراء</th>
                    <td>{{ $appointment->thecase->procedure }}</td>
                </tr>
                <tr>
                    <th>ملاحظة حول الحجز</th>
                    <td>{{ $appointment->thecase->description }}</td>
                </tr>
                <tr>
                    <th>تاريخ الحجز</th>
                    <td>{{ $appointment->thecase->schedules->available_date }}</td>
                </tr>
                <tr>
                    <th>وقت الحجز</th>
                    <td>{{ $appointment->thecase->schedules->available_time }}</td>
                </tr>
                <tr>
                    <th>حالة الحجز</th>
                    <td>
                     {{ $appointment->status }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- زر العودة -->

    </div>


</body>
</html>



