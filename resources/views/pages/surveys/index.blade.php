<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(' Questions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Créer une nouvelle question</h3>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600">{{ $error }}</p>
                    @endforeach
                @endif

                <form action="{{ route('surveys.question') }}" method="POST">
                    @csrf
                    <input type="hidden" name="survey_id" value="{{ $survey->id }}">

                    <!-- Titre -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Saisissez votre question</label>
                        <input type="text" name="title" id="title"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                               required>
                    </div>

                    <!-- Type de question -->
                    <div class="mb-4">
                        <label for="question_type" class="block text-sm font-medium text-gray-700 mb-1">Type de question</label>
                        <select name="question_type" id="question_type"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white"
                                required>
                            <option value="">-- Choisir --</option>
                            <option value="radio">Choix unique (radio)</option>
                            <option value="checkbox">Choix multiple (checkbox)</option>
                            <option value="text">Champ texte</option>
                        </select>
                    </div>

                    <div id="options_wrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Options</label>

                        <div id="options_container" class="space-y-2"></div>

                        <button type="button" id="add_option_btn" class="text-sm font-medium">
                            Ajouter une option
                        </button>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="w-full mt-4 p-6 bg-[#1b1b18] text-white py-2 rounded-lg transition">
                        Enregistrer la question
                    </button>
                </form>
            </div> <!-- / .bg-white p-6 -->

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Liste de vos questions (Sondage: {{ $survey->title ?? 'N/A' }})</h3>

                    @if ($questions->isEmpty())
                        <p class="text-gray-500 italic p-4 border border-gray-200 rounded-lg">
                            <span class="font-medium">Information:</span> Aucune question ajoutée pour le moment.
                        </p>
                    @else
                        <div class="space-y-6">
                            @foreach ($questions as $index => $question)
                                <div class="border-l-4 border-blue-600 bg-blue-50 p-4 rounded-lg shadow-sm">
                                    <div class="flex sm:flex-row justify-between items-start sm:items-center">

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="bg-blue-600 text-white text-sm font-bold px-3 py-1 rounded-full flex-shrink-0">
                                                    Q{{ $index + 1 }}
                                                </span>
                                                <p class="font-bold text-gray-900 text-lg break-words">{{ $question->title }}</p>
                                            </div>

                                            <div class="flex items-center gap-2 mb-3 ml-10">
                                                <span class="text-sm text-gray-600">Type:</span>
                                                @if ($question->question_type === 'radio')
                                                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-medium">Choix unique</span>
                                                @elseif ($question->question_type === 'checkbox')
                                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-medium">Choix multiple</span>
                                                @else
                                                    <span class="bg-gray-200 text-gray-800 text-xs px-2 py-0.5 rounded-full font-medium">Texte libre</span>
                                                @endif
                                            </div>

                                            @if ($question->question_type !== 'text' && $question->options)
                                                <div class="mt-4 ml-10 space-y-2 border-t pt-3">
                                                    <p class="text-sm font-medium text-gray-700">Options disponibles:</p>
                                                    <ul class="list-none p-0 space-y-1">
                                                        @foreach ($question->options as $option)
                                                            <li class="flex items-center gap-3 text-sm text-gray-700">
                                                                @if ($question->question_type === 'radio')
                                                                    <span class="w-3 h-3 border border-gray-500 rounded-full flex-shrink-0"></span>
                                                                @else
                                                                    <span class="w-3 h-3 border border-gray-500 rounded-sm flex-shrink-0"></span>
                                                                @endif
                                                                {{ $option }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div> <!-- / .flex-1 -->

                                        <div class="mt-4 sm:mt-0 flex flex-shrink-0 gap-3">
                                            <form method="POST" action="{{ route('surveys.question.destroy', $question) }}" onsubmit="return confirm('Confirmer la suppression de cette question?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition duration-150">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('question_type');
            const optionsWrapper = document.getElementById('options_wrapper');
            const optionsContainer = document.getElementById('options_container');
            const addOptionBtn = document.getElementById('add_option_btn');

            // Afficher les options si radio ou checkbox
            typeSelect.addEventListener('change', function () {
                const type = this.value;

                if (type === 'radio' || type === 'checkbox') {
                    optionsWrapper.classList.remove('d-none');

                    // si aucune option en ajt une
                    if (optionsContainer.children.length === 0) {
                        addNewOption();
                    }

                } else {
                    optionsWrapper.classList.add('d-none');
                    optionsContainer.innerHTML = '';
                }
            });

            // Ajouter une nouvelle option
            addOptionBtn.addEventListener('click', function () {
                addNewOption();
            });

            function addNewOption() {
                const index = optionsContainer.children.length;

                const div = document.createElement('div');
                div.classList.add('input-group', 'mb-2');

                div.innerHTML = `
                    <input type="text" name="options[]" class="form-control" placeholder="Option ${index + 1}" required>
                    <button type="button" class="btn btn-danger remove_option_btn">&times;</button>
                `;

                // bouton supprimer
                div.querySelector('.remove_option_btn').addEventListener('click', function () {
                    div.remove();
                });

                optionsContainer.appendChild(div);
            }
        });
    </script>
</x-app-layout>
