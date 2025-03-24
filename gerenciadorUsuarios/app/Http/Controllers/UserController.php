<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Exibe a lista de todos os usuários.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Exibe o formulário para criar um novo usuário.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Armazena um novo usuário no banco de dados.
     */
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

    /**
     * Exibe o formulário para editar um usuário.
     */
    public function edit(User $user)
    {
        $allProfiles = Profile::all();
        return view('users.edit', compact('user', 'allProfiles'));
    }

    /**
     * Atualiza um usuário no banco de dados.
     */
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
        
        if(Gate::allows('no-profile', $user)){
            return redirect()->route('dashboard')->with('success', 'Usuário atualizado com sucesso.');
        }else{
            return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
        }
        
    }

    /**
     * Remove um usuário do banco de dados.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }

    /**
    * Exibe os perfis associados a um usuário.
    */
    public function profiles(User $user)
    {
        $profiles = $user->profiles;
        return view('users.profiles', compact('user', 'profiles'));
    }

    /**
     * Associa um perfil a um usuário.
     */
    public function attachProfile(Request $request, User $user)
    {
        $request->validate([
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $user->profiles()->attach($request->input('profile_id'));
        return redirect()->route('users.profiles', $user->id)->with('success', 'Perfil associado com sucesso.');
    }

    /**
     * Desassocia um perfil de um usuário.
     */
    public function detachProfile(User $user, $profileId)
    {
        $user->profiles()->detach($profileId);
        return redirect()->route('users.profiles', $user->id)->with('success', 'Perfil desassociado com sucesso.');
    }
}