<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $user->settings ?? null;

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'notification_blood_requests' => 'boolean',
            'notification_responses' => 'boolean',
            'privacy_show_profile' => 'boolean',
            'privacy_show_contact' => 'boolean',
        ]);

        $user->settings()->updateOrCreate([], $validated);

        return redirect()->route('settings')->with('success', 'Settings updated successfully.');
    }
}
