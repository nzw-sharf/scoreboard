<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Score Summary
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-lg text-white font-bold mb-4">Team Score Summary</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-600 text-white">
                        <thead>
                            <tr class="bg-gray-700">
                                <th class="border border-gray-600 px-4 py-2">Team</th>
                                @foreach ($categories as $category)
                                    <th class="border border-gray-600 px-4 py-2">{{ $category }}</th>
                                @endforeach
                                <th class="border border-gray-600 px-4 py-2">Total Points</th>
                                <th class="border border-gray-600 px-4 py-2">Point Breakdown</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($summary as $index => $row)
                                <tr class="bg-gray-900">
                                    <td class="border border-gray-600 px-4 py-2">{{ $row['team'] }}</td>
                                    @foreach ($row['category_scores'] as $score)
                                        <td class="border border-gray-600 px-4 py-2 text-center">{{ $score }}</td>
                                    @endforeach
                                    <td class="border border-gray-600 px-4 py-2 text-center font-bold">
                                        {{ $row['total_score'] }}</td>
                                    <td class="border border-gray-600 px-4 py-2 text-center">
                                        <button onclick="openModal({{ $index }})"
                                            class="bg-blue-600 px-3 py-1 rounded text-white hover:bg-blue-500">
                                            View Breakdown
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Point Breakdown Modals -->
@foreach ($summary as $index => $row)
<div id="modal-{{ $index }}" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-4xl max-h-screen overflow-y-auto">
        <h3 class="text-lg text-white font-bold mb-4">{{ $row['team'] }} - Point Breakdown</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full table-auto border border-gray-600 text-white mb-4">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="border border-gray-600 px-4 py-2">Game Name</th>
                        <th class="border border-gray-600 px-4 py-2">Category</th>
                        <th class="border border-gray-600 px-4 py-2">Position</th>
                        <th class="border border-gray-600 px-4 py-2">Points</th>
                        <th class="border border-gray-600 px-4 py-2">Winner(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($row['breakdown'] as $i => $detail)
                        <!-- Main breakdown row -->
                        <tr class="bg-gray-900">
                            <td class="border border-gray-600 px-4 py-2">{{ $detail['game_name'] }}</td>
                            <td class="border border-gray-600 px-4 py-2">{{ $detail['category'] }}</td>
                            <td class="border border-gray-600 px-4 py-2 text-center">{{ $detail['position'] }}</td>
                            <td class="border border-gray-600 px-4 py-2 text-center">{{ $detail['points'] }}</td>
                          
                            <td class="border border-gray-600 px-4 py-2 text-center">
                                <button onclick="toggleWinnerDetails({{ $index }}, {{ $i }})"
                                        class="bg-green-600 px-2 text-sm py-1 rounded text-white hover:bg-green-500">
                                    View Winners
                                </button>
                            </td>
                        </tr>
                
                        <!-- Hidden Winner Row -->
                        <tr id="winner-details-{{ $index }}-{{ $i }}" class="hidden bg-gray-800 winnerDetails">
                            <td colspan="6" class="px-4 py-3 text-white">
                                <div class="space-y-1">
                                    @if($detail['winning_team'])
                                    <div><strong>Winning Team:</strong> {{ $detail['winning_team'] ?? 'N/A' }}</div>
                                    @endif
                                    <div><strong>Players:</strong> {{ $detail['players'] ?? 'N/A' }}</div>
                                    <div>
                                        
                                        @if (!empty($detail['is_tie_or_not']) && $detail['is_tie_or_not'])
                                        <strong>Tie Status:</strong> ✅ Tie
                                        @else
                                            
                                        @endif
                                    </div>
                                </div>
                                <button onclick="toggleWinnerDetails({{ $index }}, {{ $i }})"
                                        class="mt-2 text-sm bg-yellow-600 px-3 py-1 rounded text-white hover:bg-yellow-500">
                                    Close
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>

        <div class="flex flex-col sm:flex-row justify-between gap-2">
          
            <button onclick="closeModal({{ $index }})"
                class="bg-red-600 px-4 py-2 rounded text-white hover:bg-red-500 w-full sm:w-auto">
                Close Breakdown
            </button>
        </div>

       
    </div>
</div>
@endforeach


<script>
    function openModal(index) {
        document.querySelectorAll("[id^='modal-']").forEach(modal => {
            modal.classList.add('hidden');
            modal.style.display = "none";
        });

        let modal = document.getElementById('modal-' + index);
        modal.classList.remove('hidden');
        modal.style.display = "flex";
    }

    function closeModal(index) {
        let modal = document.getElementById('modal-' + index);
        modal.classList.add('hidden');
        modal.style.display = "none";
        $('.winnerDetails').addClass('hidden');
    }

   function toggleWinnerDetails(modalIndex, rowIndex) {
        const id = `winner-details-${modalIndex}-${rowIndex}`;
        const row = document.getElementById(id);
        row.classList.toggle('hidden');
    }

</script>

</x-app-layout>
