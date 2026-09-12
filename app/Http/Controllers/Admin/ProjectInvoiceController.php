<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectInvoiceController extends Controller
{
    public function download(Project $project)
    {
        $project->load([
            'items.product',
            'items.service',
            'costs',
        ]);

        $pdf = Pdf::loadView('admin.projects.invoice', compact('project'))
            ->setPaper('a4', 'portrait');

        return $pdf->download(
            'Invoice-' . $project->code . '.pdf'
        );
    }
}