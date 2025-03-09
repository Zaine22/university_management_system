<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Achievement;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CertificateTemplate;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\AchievementApiResource;

class AchievementController extends Controller
{
    public function index()
    {
        return AchievementApiResource::collection(Achievement::latest()->get());
    }

    public function show($studentID)
    {
        $student = Student::where('register_no', $studentID)->firstOrFail();
        $achievement = Achievement::where('student_id', $student)->firstOrFail();

        if ($achievement) {
            $certificates = json_decode($achievement->certificates, true);
            $certificateImages = array_column($certificates, 'certificate');

            return response()->json([
                'certificate_images' => $certificateImages,
            ]);
        } else {
            return response()->json(['error' => 'Achievement not found'], 404);
        }
    }

    public function testPrintCertificate()
    {
        $template = CertificateTemplate::first();
        $studentName = 'TESTING STUDENT';

        return view('certificate.web-certificate', compact('studentName', 'template'));
    }

    public static function printCertificate(Achievement $achievement)
    {
        $studentName = $achievement->student->student_name;
        $batch = $achievement->batch->batch_name;
        $template = $achievement->certificateTemplate;

        $imagePath = public_path('images/certificate.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,'.$imageData;

        $name = 'certificate '.$studentName.' '.$batch.'.pdf';

        $t = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('certificate.web-certificate', compact('studentName', 'template', 'batch', 'imageSrc'))->setPaper('A4', 'portrait');

        return response()->streamDownload(fn () => print ($t->output()), $name);
    }
}