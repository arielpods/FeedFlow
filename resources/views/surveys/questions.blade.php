<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Questions du sondage : ') }} {{ $survey->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ajouter une question</h3>
                
                <form method="POST" action="{{ route('surveys.questions.store', $survey) }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Intitulé de la question</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" required placeholder="Ex: Quelle est votre couleur préférée ?">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Type de réponse</label>
                            <select name="question_type" id="question_type" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" onchange="toggleOptions()">
                                <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>Texte libre</option>
                                {{-- Échelle 1-10 supprimée comme demandé --}}
                                <option value="radio" {{ old('question_type') == 'radio' ? 'selected' : '' }}>Choix unique (Radio)</option>
                                <option value="checkbox" {{ old('question_type') == 'checkbox' ? 'selected' : '' }}>Choix multiple (Checkbox)</option>
                            </select>
                            @error('question_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div id="options_wrapper" class="hidden border-l-4 border-indigo-100 pl-4">
                            <label class="block font-medium text-sm text-gray-700 mb-2">Options de réponse</label>
                            
                            <div id="options_list" class="space-y-3">
                                {{-- Si on a des erreurs de validation (old input), on réaffiche les champs --}}
                                @if(old('options'))
                                    @foreach(old('options') as $option)
                                        <div class="flex gap-2">
                                            <input type="text" name="options[]" value="{{ $option }}" class="border-gray-300 rounded-md shadow-sm block w-full" placeholder="Option..." required>
                                            <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 px-2">X</button>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Par défaut, on affiche 2 champs vides --}}
                                    <div class="flex gap-2">
                                        <input type="text" name="options[]" class="border-gray-300 rounded-md shadow-sm block w-full" placeholder="Option 1" >
                                        <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 px-2">X</button>
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text" name="options[]" class="border-gray-300 rounded-md shadow-sm block w-full" placeholder="Option 2" >
                                        <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 px-2">X</button>
                                    </div>
                                @endif
                            </div>

                            <button type="button" onclick="addOption()" class="mt-3 text-sm text-indigo-600 hover:text-indigo-900 font-semibold flex items-center gap-1">
                                <span>+</span> Ajouter une option
                            </button>
                            
                            @error('options') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                            @error('options.*') <span class="text-red-500 text-sm block mt-1">Chaque option doit être remplie.</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="w-full mt-4 p-6 bg-[#1b1b18] text-white py-2 rounded-lg transition"> Enregistrer la question </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Questions existantes</h3>
                
                @if($questions->isEmpty())
                    <p class="text-gray-500 italic text-center py-4">Aucune question pour le moment.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($questions as $question)
                            <li class="border border-gray-200 rounded-lg p-4 bg-gray-50 flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-gray-800 text-lg mb-1">{{ $question->title }}</div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded text-xs uppercase font-bold tracking-wide">
                                            {{ $question->question_type === 'radio' ? 'Choix unique' : ($question->question_type === 'checkbox' ? 'Choix multiple' : 'Texte') }}
                                        </span>
                                    </div>
                                    
                                    @if($question->options)
                                        <div class="pl-4 border-l-2 border-gray-300">
                                            <ul class="list-disc list-inside text-sm text-gray-600">
                                                @foreach($question->options as $opt)
                                                    <li>{{ $opt }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <form action="{{ route('surveys.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Supprimer cette question ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Supprimer</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
                
                <div class="mt-6 border-t pt-4">
                     <a href="{{ route('survey.index', $survey->organization_id) }}" class="text-gray-600 hover:text-gray-900 underline text-sm">← Retour à la liste des sondages</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Script pour gérer l'affichage dynamique --}}
    <script>
        function toggleOptions() {
            const type = document.getElementById('question_type').value;
            const wrapper = document.getElementById('options_wrapper');
            const inputs = wrapper.querySelectorAll('input');

            if (type === 'radio' || type === 'checkbox') {
                wrapper.classList.remove('hidden');
                // Rend les champs requis si visibles
                inputs.forEach(input => input.required = true);
            } else {
                wrapper.classList.add('hidden');
                // Enlève le required pour ne pas bloquer le formulaire si caché
                inputs.forEach(input => input.required = false);
            }
        }

        function addOption() {
            const list = document.getElementById('options_list');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="options[]" class="border-gray-300 rounded-md shadow-sm block w-full" placeholder="Nouvelle option" required>
                <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 px-2">X</button>
            `;
            list.appendChild(div);
        }

        function removeOption(btn) {
            const list = document.getElementById('options_list');
            // On empêche de supprimer s'il ne reste qu'une option (optionnel)
            if (list.children.length > 1) {
                btn.parentElement.remove();
            } else {
                alert("Il faut au moins une option !");
            }
        }

        // Initialiser l'état au chargement de la page (utile si retour d'erreur)
        document.addEventListener('DOMContentLoaded', function() {
            toggleOptions();
        });
    </script>
</x-app-layout>