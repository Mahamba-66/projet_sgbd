@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                {{ isset($candidate) ? 'Modifier le Candidat' : 'Ajouter un Candidat' }}
            </h1>
            <a href="{{ route('admin.candidates.index') }}" class="text-gray-600 hover:text-gray-900">
                Retour à la liste
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($candidate) ? route('admin.candidates.update', $candidate) : route('admin.candidates.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @if(isset($candidate))
                @method('PUT')
            @endif

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nom du Candidat
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="name"
                       type="text"
                       name="name"
                       value="{{ old('name', $candidate->name ?? '') }}"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="party_name">
                    Nom du Parti
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="party_name"
                       type="text"
                       name="party_name"
                       value="{{ old('party_name', $candidate->party_name ?? '') }}"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="bio">
                    Biographie
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                          id="bio"
                          name="bio"
                          rows="4">{{ old('bio', $candidate->bio ?? '') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="photo">
                    Photo
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="photo"
                       type="file"
                       name="photo"
                       accept="image/*">
                @if(isset($candidate) && $candidate->photo)
                    <div class="mt-2">
                        <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->name }}" class="h-20 w-20 object-cover rounded">
                    </div>
                @endif
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="program">
                    Programme Electoral (PDF)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="program"
                       type="file"
                       name="program"
                       accept=".pdf">
                @if(isset($candidate) && $candidate->program)
                    <div class="mt-2">
                        <a href="{{ Storage::url($candidate->program) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            Voir le programme actuel
                        </a>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                    {{ isset($candidate) ? 'Mettre à jour' : 'Créer le candidat' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
