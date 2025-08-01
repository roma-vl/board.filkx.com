<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Logs\ActivityLogsRequest;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(ActivityLogsRequest $request)
    {

        $validated = $request->validatedWithDefaults();

        $logs = Activity::query()
            ->with('causer', 'subject')
            ->orderBy($validated['sort_by'], $validated['sort_order'])
            ->paginate($validated['per_page'])
            ->through(function ($log) {
                return [
                    'id' => $log->id,
                    'description' => $log->description,
                    'subject_type' => class_basename($log->subject_type),
                    'subject_id' => $log->subject_id,
                    'causer' => $log->causer ? $log->causer->name ?? 'System' : 'System',
                    'created_at' => $log->created_at->diffForHumans(),
                    'changes' => $log->properties->toArray(), // зміни
                ];
            });

        return Inertia::render('Admin/Logs/Activity/Index', [
            'logs' => $logs,
        ]);
    }
}
