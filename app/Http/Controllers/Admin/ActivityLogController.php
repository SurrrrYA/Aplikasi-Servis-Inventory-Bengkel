<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // =====================================================
    // DAFTAR ACTIVITY LOG
    // =====================================================

    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // SEARCH
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%");
            });
        }

        // FILTER USER
        if ($request->filled('user_id')) {

            $query->where(
                'user_id',
                $request->user_id
            );
        }

        // FILTER ACTION
        if ($request->filled('action')) {

            $query->where(
                'action',
                $request->action
            );
        }

        // FILTER MODULE
        if ($request->filled('module')) {

            $query->where(
                'module',
                $request->module
            );
        }

        // DATA LOG
        $logs = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // USER
        $users = User::orderBy('name')->get();

        // ACTION
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // MODULE
        $modules = ActivityLog::select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return view(
            'admin.activity-logs.index',
            compact(
                'logs',
                'users',
                'actions',
                'modules'
            )
        );
    }


    // =====================================================
    // DETAIL ACTIVITY LOG
    // =====================================================

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');

        return view(
            'admin.activity-logs.show',
            compact('activityLog')
        );
    }
}