<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display a listing of profiles.
     */
    public function index(): View
    {
        $profiles = Profile::all();
        return view('profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new profile.
     */
    public function create(): View
    {
        return view('profiles.create');
    }

    /**
     * Store a newly created profile in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Profile::create($validatedData);

        return redirect()->route('profiles.index')->with('success', 'Perfil criado com sucesso!');
    }

    /**
     * Show the form for editing the specified profile.
     */
    public function edit(Profile $profile): View
    {
        return view('profiles.edit', compact('profile'));
    }

    /**
     * Update the specified profile in storage.
     */
    public function update(Request $request, Profile $profile): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $profile->update($validatedData);

        return redirect()->route('profiles.index')->with('success', 'Perfil editado com sucesso!');
    }

    /**
     * Remove the specified profile from storage.
     */
    public function destroy(Profile $profile): RedirectResponse
    {
        $profile->delete();

        return redirect()->route('profiles.index')->with('success', 'Perfil deletado com sucesso!');
    }
}