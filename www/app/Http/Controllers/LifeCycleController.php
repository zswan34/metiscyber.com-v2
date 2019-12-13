<?php

namespace App\Http\Controllers;

use App\LifeCycle;
use Illuminate\Http\Request;

class LifeCycleController extends Controller
{
    public function getLifeCycleApi ()
    {
        $lifeCycles = LifeCycle::all();
        $reactSelectFormatted = [];

        if (request('format') === 'react-select') {
            foreach ($lifeCycles as $lifeCycle) {
                array_push($reactSelectFormatted, [
                    'label' => $lifeCycle->name,
                    'value' => $lifeCycle->value
                ]);
            }
            return $reactSelectFormatted;
        }

        return $lifeCycles;
    }
}
