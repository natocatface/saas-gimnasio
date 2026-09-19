<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberMeasurement;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function store(Request $request, Member $member)
    {
        $data = $request->validate([
            'measured_on' => 'required|date',
            'weight'      => 'nullable|numeric|min:0',
            'height'      => 'nullable|numeric|min:0',
            'body_fat'    => 'nullable|numeric|min:0|max:100',
            'chest'       => 'nullable|numeric|min:0',
            'waist'       => 'nullable|numeric|min:0',
            'hips'        => 'nullable|numeric|min:0',
            'arm'         => 'nullable|numeric|min:0',
            'thigh'       => 'nullable|numeric|min:0',
            'notes'       => 'nullable|string',
        ]);

        $data['gymnasium_id'] = $member->gymnasium_id;
        $data['member_id']    = $member->id;

        MemberMeasurement::create($data);

        return back()->with('success', 'Medición registrada.');
    }

    public function destroy(MemberMeasurement $measurement)
    {
        $measurement->delete();
        return back()->with('success', 'Medición eliminada.');
    }
}
