@extends('layouts.app')
@section('content')


    <div class="w-full max-w-md mt-6 p-6 bg-white shadow-lg rounded-xl border border-gray-200">

        <h2 class="text-xl font-semibold mb-6 text-gray-800">Ajouter une question</h2>

        <form action="{{ route('surveys.store.question') }}" method="POST">
        @csrf

            <!-- Titre -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre de la question</label>
                <input type="text" name="title" id="title"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       required>
            </div>

            <!-- Type de question -->
            <div class="mb-4">
                <label for="question_type" class="block text-sm font-medium text-gray-700 mb-1">Type de question</label>
                <select name="question_type" id="question_type"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white "
                        required>
                    <option value="">-- Choisir --</option>
                    <option value="radio">Choix unique (radio)</option>
                    <option value="checkbox">Choix multiple (checkbox)</option>
                    <option value="text">Champ texte</option>
                    <option value="scale">Échelle 1 à 10</option>
                </select>
            </div>


            <div id="options_wrapper" >
                <label class="block text-sm font-medium text-gray-700 mb-1">Options</label>

                <div id="options_container" class="space-y-2"></div>

                <button type="button" id="add_option_btn"
                        class="mt-2 px-3 py-1.5 text-sm bg-gray-200 text-gray-800 rounded-lg">
                    Ajouter une option
                </button>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full mt-4 p-6 bg-blue-300 text-black py-2 rounded-lg transition">
                Enregistrer la question
            </button>

        </form>
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

                    // si aucune option encore, on en ajoute une automatiquement
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

@endsection
