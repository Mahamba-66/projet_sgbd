<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['causer', 'subject'])->latest()->paginate(20);
        return view('admin.audit-logs.index', compact('activities'));
    }

    public function show($id)
    {
        $activity = Activity::with(['causer', 'subject'])->findOrFail($id);
        return view('admin.audit-logs.show', compact('activity'));
    }
}
