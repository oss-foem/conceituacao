<?php

// UserController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        if(Auth::check()){
            return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso.');
        }else{
            return redirect()->route('login')->with('success', 'Usuário criado com sucesso.');
        }
        
    }

    public function edit(User $user)
    {
        $allProfiles = Profile::all();
        return view('users.edit', compact('user', 'allProfiles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        $user->profiles()->sync($request->input('profiles', []));
        
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }

    public function profiles(User $user)
    {
        $profiles = $user->profiles;
        return view('users.profiles', compact('user', 'profiles'));
    }

    public function attachProfile(Request $request, User $user)
    {
        $request->validate([
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $user->profiles()->attach($request->input('profile_id'));
        return redirect()->route('users.profiles', $user->id)->with('success', 'Perfil associado com sucesso.');
    }

    public function detachProfile(User $user, $profileId)
    {
        $user->profiles()->detach($profileId);
        return redirect()->route('users.profiles', $user->id)->with('success', 'Perfil desassociado com sucesso.');
    }
}