<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Résultats : ') }} {{ $survey->title }}
            </h2>
            <a href="{{ route('survey.index', $survey->organization_id) }}" class="text-gray-600 hover:text-gray-900 text-sm">
                &larr; Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white p-6 shadow-md rounded-xl border-t-4 border-indigo-500">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Participation par jour</h3>
                    <div class="relative h-64">
                        <canvas id="participationChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 shadow-md rounded-xl border-t-4 border-teal-500">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Répartition : {{ $chartQuestion ? Str::limit($chartQuestion->title, 30) : 'Aucune question à choix' }}
                    </h3>
                    <div class="relative h-64">
                        @if($chartQuestion)
                            <canvas id="distributionChart"></canvas>
                        @else
                            <p class="text-gray-500 text-center mt-10">Ajoutez une question "Radio" ou "Checkbox" pour voir ce graphique.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Détail par question</h3>
                </div>
                
                <div class="p-6 space-y-8">
                    @foreach($survey->questions as $question)
                        <div class="border-b border-gray-100 pb-6 last:border-0">
                            <h4 class="font-semibold text-lg text-indigo-700 mb-3">{{ $loop->iteration }}. {{ $question->title }}</h4>
                            
                            @if($question->answers->isEmpty())
                                <p class="text-sm text-gray-400 italic">Aucune réponse.</p>
                            @else
                                <div class="bg-gray-50 rounded-lg p-4 max-h-48 overflow-y-auto">
                                    <ul class="space-y-2">
                                        @foreach($question->answers as $answer)
                                            <li class="text-sm text-gray-700 border-l-2 border-indigo-200 pl-3">
                                                {{-- Affichage propre selon le type --}}
                                                @if($question->question_type === 'checkbox')
                                                    {{ implode(', ', json_decode($answer->answer, true) ?? []) }}
                                                @else
                                                    {{ $answer->answer }}
                                                @endif
                                                <span class="text-xs text-gray-400 block mt-0.5">
                                                    {{ $answer->created_at->format('d/m/Y H:i') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // --- Graphique 1 : Participation ---
        const ctx1 = document.getElementById('participationChart').getContext('2d');
        const dates = @json($datesData->pluck('date'));
        const counts = @json($datesData->pluck('count'));

        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Nombre de réponses',
                    data: counts,
                    borderColor: '#6366f1', // Indigo-500
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // --- Graphique 2 : Distribution ---
        @if($chartQuestion && !empty($distributionData))
            const ctx2 = document.getElementById('distributionChart').getContext('2d');
            const labels2 = @json(array_keys($distributionData));
            const data2 = @json(array_values($distributionData));

            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: labels2,
                    datasets: [{
                        label: 'Réponses',
                        data: data2,
                        backgroundColor: [
                            '#14b8a6', '#f59e0b', '#8b5cf6', '#ef4444', '#3b82f6', '#10b981'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });
        @endif
    </script>
</x-app-layout>