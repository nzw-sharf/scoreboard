<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('teams.index', ['teams' => Team::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:teams',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle file upload directly in the public directory
    $logoPath = null;
    if ($request->hasFile('logo')) {
        // Store the image directly in the public directory
        $logoPath = $request->file('logo')->move(public_path('storage/logos'), $request->file('logo')->getClientOriginalName());
    }

    Team::create([
        'name' => $request->name,
        'logo' => 'storage/logos/' . $request->file('logo')->getClientOriginalName(),
    ]);

    return redirect()->route('teams.index')->with('success', 'Team added.');
}


    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        // No specific implementation here
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Team $team)
{
    $request->validate([
        'name' => 'required|unique:teams,name,' . $team->id,
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle file upload only if a new logo is uploaded
    if ($request->hasFile('logo')) {
        // Delete old logo if it exists
        if ($team->logo && file_exists(public_path( $team->logo))) {
            unlink(public_path( $team->logo)); // Delete old logo file
        }

        // Store the new logo
        $logoName = time() . '_' . $request->file('logo')->getClientOriginalName();
        $request->file('logo')->move(public_path('storage/logos'), $logoName);

        // Update the logo path to be stored in the database
        $fullpath = 'storage/logos/' . $logoName;
    }else{
        $fullpath = $team->logo;
    }
    try{
        $team->name = $request->name;
        $team->logo = $fullpath;
        $team->save();
    }catch(Exception $e){
        echo $e;
    }
    

    return redirect()->route('teams.index')->with('success', 'Team updated successfully');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        // Delete the logo file if exists
        if ($team->logo && \Storage::exists('public/' . $team->logo)) {
            \Storage::delete('public/' . $team->logo);
        }

        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully');
    }
}
