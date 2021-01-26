<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function editTimeZone()
    {
        return view('pwl.users.edit-timezone');
    }

    public function updateTimeZone(Request $request)
    {
        $data = $request->validate([
            'timezone' => 'timezone'
        ]);

        auth()->user()->update($data);

        return back()->withSuccess('Time Zone updated successfully.');
    }
}
