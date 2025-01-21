<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Student;

use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class ReportController extends Controller
{
    public function downloadReport($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Generate PDF
        $pdf = FacadePdf::loadView('pdf.appointment_details', ['appointment' => $appointment]);
        return $pdf->download('appointment_details_' . $appointment->id . '.pdf');

        // $appointments = Appointment::where('status', 'مكتمل')->get();

        // $pdf = FacadePdf::loadView('pdf.completed_appointments', compact('appointments'));
        // return $pdf->download('completed_appointments.pdf');
    }

    public static function downloadPdf(Request $request)
    {
        $appointments = Appointment::query();
        // Fetch canceled appointments
        if ($request->has('status')) {
            $appointments->where('status', $request->get('status'));
        }

        $appointments = $appointments->get();

        // Generate PDF
        $pdf = FacadePdf::loadView('pdf.completed_appointments', ['appointments' => $appointments]);
        return $pdf->stream('appointments_report.pdf');
    }

    public function generateBlockedUsersPDF()
    {
        // Fetch blocked users
        $students = Student::where('isBlocked', 'محظور')->get();

        $pdf = FacadePdf::loadView('pdf.blocked_student', compact('students'));
        return $pdf->download('blocked_students.pdf');
    }
}
