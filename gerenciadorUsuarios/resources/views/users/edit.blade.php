<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-300 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-semibold text-center text-white mb-6">Editar Usuário</h1>
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- nome --> 
            <div class="mb-4">
                <label for="name" class="block text-gray-300 mb-2">Nome:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <!-- email --> 
            <div class="mb-4">
                <label for="email" class="block text-gray-300 mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('name', $user->email) }}" required
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <!-- senha --> 
            <div class="mb-4">
                <label for="password" class="block text-gray-300 mb-2">Password:</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <!-- perfis --> 
            @can('is-administrator')
            <div class="mb-4">
                <label for="profiles" class="block text-gray-300 mb-2">Perfis:</label>
                <select name="profiles[]" id="profiles" multiple
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                    @foreach($allProfiles as $profile)
                        <option value="{{ $profile->id }}" {{ in_array($profile->id, $user->profiles->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $profile->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endcan
            <!-- botão de salvar -->
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-lg font-medium">Salvar Alterações</button>
        </form>
        <div class="mt-4">
            <a href="javascript:history.back()" class="mt-4 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                ← Voltar
            </a>
        </div>
    </div>
</body>

</html>