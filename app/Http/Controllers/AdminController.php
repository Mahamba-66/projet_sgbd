<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ElectoralFile;
use App\Models\ElectoralPeriod;
use App\Services\ElectoralFileValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_voters' => User::where('user_type', 'voter')->count(),
            'total_candidates' => User::where('user_type', 'candidate')->count(),
            'total_sponsorships' => 0, // À implémenter
            'current_period' => ElectoralPeriod::orderBy('created_at', 'desc')->first()
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function electoralFile()
    {
        $lastUpload = ElectoralFile::latest()->first();
        return view('admin.electoral-file', compact('lastUpload'));
    }

    public function uploadElectoralFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'checksum' => 'required|string|size:64'
        ]);

        $file = $request->file('file');
        $checksum = strtoupper(hash_file('sha256', $file->path()));

        if ($checksum !== $request->checksum) {
            return back()->withErrors(['checksum' => 'L\'empreinte SHA256 ne correspond pas au fichier.']);
        }

        // Créer un enregistrement pour le fichier
        $electoralFile = ElectoralFile::create([
            'filename' => $file->getClientOriginalName(),
            'original_filename' => $file->getClientOriginalName(),
            'checksum' => $checksum,
            'status' => 'processing',
            'total_records' => 0,
            'processed_records' => 0,
            'error_log' => json_encode(['errors' => [], 'duplicates' => []])
        ]);

        // Valider le fichier
        $validationService = new ElectoralFileValidationService();
        $isValid = $validationService->validate($electoralFile, $file->path());

        return view('admin.electoral-file-validation', [
            'electoralFile' => $electoralFile,
            'isValid' => $isValid,
            'errors' => $validationService->getErrors(),
            'duplicates' => $validationService->getDuplicates()
        ]);
    }

    public function showFileValidationResults($id)
    {
        $electoralFile = ElectoralFile::findOrFail($id);
        $errorLog = json_decode($electoralFile->error_log, true);

        return view('admin.electoral-file-validation', [
            'electoralFile' => $electoralFile,
            'errors' => $errorLog['errors'] ?? [],
            'duplicates' => $errorLog['duplicates'] ?? []
        ]);
    }

    public function sponsorshipPeriod()
    {
        $currentPeriod = ElectoralPeriod::latest()->first();
        return view('admin.sponsorship-period', compact('currentPeriod'));
    }

    public function saveSponsorshipPeriod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'min_sponsorships' => 'required|integer|min:1',
            'max_sponsorships' => 'required|integer|gt:min_sponsorships',
            'description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Désactiver toutes les périodes actives
            ElectoralPeriod::where('status', 'active')
                ->update(['status' => 'completed']);

            // Créer la nouvelle période
            $period = ElectoralPeriod::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'min_sponsorships' => $request->min_sponsorships,
                'max_sponsorships' => $request->max_sponsorships,
                'status' => 'active',
                'description' => $request->description ?? 'Période de parrainage ' . now()->format('Y')
            ]);

            DB::commit();

            return redirect()->route('admin.sponsorship-period')
                ->with('success', 'La période de parrainage a été enregistrée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function showCandidatesList()
    {
        $candidates = User::where('user_type', 'candidate')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        return view('admin.candidates-list', compact('candidates'));
    }

    public function showStatistics()
    {
        $stats = [
            'candidates_count' => User::where('user_type', 'candidate')->count(),
            'voters_count' => User::where('user_type', 'voter')->count(),
            'sponsorships_by_candidate' => DB::table('sponsorships')
                ->select('candidate_id', DB::raw('count(*) as total'))
                ->groupBy('candidate_id')
                ->get()
        ];

        return view('admin.statistics', compact('stats'));
    }
}
