<?php

namespace App\Http\Controllers;

use App\LeadStatus;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{
    public function getLeadStatusApi ()
    {
        $leadStatuses = LeadStatus::all();
        $reactSelectFormatted = [];

        if (request('format') === 'react-select') {
            foreach ($leadStatuses as $leadStatus) {
                array_push($reactSelectFormatted, [
                    'label' => $leadStatus->name,
                    'value' => $leadStatus->value
                ]);
            }
            return $reactSelectFormatted;
        }

        return $leadStatuses;
    }
}
