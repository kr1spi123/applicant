<?php

namespace App\Jobs;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationCreated;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $application;

    /**
     * Create a new job instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Generate QR Code
        // URL to verify the application (e.g., admin page or public verification page)
        $verificationUrl = route('applications.verify', ['id' => $this->application->id]);
        $qrCodeSvg = QrCode::format('svg')->size(200)->generate($verificationUrl);

        // 2. Generate PDF
        $pdf = Pdf::loadView('pdf.application', [
            'application' => $this->application,
            'qrCodeSvg' => $qrCodeSvg
        ]);

        // 3. Save PDF
        $fileName = "applications/application_{$this->application->id}.pdf";
        Storage::put("public/{$fileName}", $pdf->output());

        // 4. Update Application
        $this->application->qr_code_path = $fileName;
        $this->application->save();

        // 5. Send Email (Killer Feature #3)
        Mail::to($this->application->email)->send(new ApplicationCreated($this->application, $fileName));
    }
}
