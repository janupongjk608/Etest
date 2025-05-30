<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * แสดงรายการโปรไฟล์ทั้งหมด
     */
    public function index()
    {
        $profiles = Profile::orderBy('birth_date')->get();

        return response()->json($profiles, 200);
    }

    /**
     * แสดงโปรไฟล์ตาม ID
     */
    public function show($id)
    {
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json(null, 404);
        }

        return response()->json($profile, 200);
    }

    /**
     * บันทึกข้อมูลโปรไฟล์ใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|in:นาย,นาง,นางสาว',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'path_profile' => 'nullable|image|max:2048',
        ]);

        $profile = new Profile();
        $profile->title = $request->input('title');
        $profile->name = $request->input('name');
        $profile->last_name = $request->input('last_name');
        $profile->birth_date = $request->input('birth_date');

        if ($request->hasFile('path_profile')) {
            $path = $request->file('path_profile')->store('profiles', 'public');
            $profile->path_profile = $path;
        }

        $profile->save();

        return response()->json($profile, 201);
    }

    /**
     * อัปเดตข้อมูลโปรไฟล์
     */
    public function update(Request $request, $id)
    {
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json(null, 404);
        }

        $request->validate([
            'title' => 'required|string|in:นาย,นาง,นางสาว',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'path_profile' => 'nullable|image|max:2048',
        ]);

        $profile->title = $request->input('title');
        $profile->name = $request->input('name');
        $profile->last_name = $request->input('last_name');
        $profile->birth_date = $request->input('birth_date');

        if ($request->hasFile('path_profile')) {
            if ($profile->path_profile && Storage::disk('public')->exists($profile->path_profile)) {
                Storage::disk('public')->delete($profile->path_profile);
            }

            $path = $request->file('path_profile')->store('profiles', 'public');
            $profile->path_profile = $path;
        }

        $profile->save();

        return response()->json($profile, 200);
    }

    /**
     * ลบโปรไฟล์
     */
    public function destroy($id)
    {
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json(null, 404);
        }

        if ($profile->path_profile && Storage::disk('public')->exists($profile->path_profile)) {
            Storage::disk('public')->delete($profile->path_profile);
        }

        $profile->delete();

        return response()->json(null, 200);
    }

    
 public function ageReport()
{
    $profiles = Profile::all();

    $ageGroups = [
        '0-9' => 0,
        '10-19' => 0,
        '20-29' => 0,
        '30-39' => 0,
        '40-49' => 0,
        '50-59' => 0,
        '60-69' => 0,
        '70-79' => 0,
        '80-89' => 0,
        '90-99' => 0,
    ];

    foreach ($profiles as $profile) {
        $age = \Carbon\Carbon::parse($profile->birth_date)->age;

        if ($age <= 9) {
            $ageGroups['0-9']++;
        } elseif ($age <= 19) {
            $ageGroups['10-19']++;
        } elseif ($age <= 29) {
            $ageGroups['20-29']++;
        } elseif ($age <= 39) {
            $ageGroups['30-39']++;
        } elseif ($age <= 49) {
            $ageGroups['40-49']++;
        } elseif ($age <= 59) {
            $ageGroups['50-59']++;
        } elseif ($age <= 69) {
            $ageGroups['60-69']++;
        } elseif ($age <= 79) {
            $ageGroups['70-79']++;
        } elseif ($age <= 89) {
            $ageGroups['80-89']++;
        } elseif ($age <= 99) {
            $ageGroups['90-99']++;
        } elseif ($age <= 0) {
    }

    return response()->json($ageGroups);
}

}
}
