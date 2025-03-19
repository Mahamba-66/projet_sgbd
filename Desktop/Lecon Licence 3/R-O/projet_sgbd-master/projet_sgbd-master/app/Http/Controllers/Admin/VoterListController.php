<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VoterListController extends Controller
{
    public function index()
    {
        $voters = User::where('role', 'voter')
            ->with('region')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.voters.list', compact('voters'));
    }
}
