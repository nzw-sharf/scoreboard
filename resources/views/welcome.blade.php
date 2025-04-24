@extends('layouts.main')
@section('content')
 @foreach ($teams as $team)
                <div class="relative p-3 md:p-6 mb-2 md:mb-6 flex justify-start shadow-xl text-white bg-black custom-border hover:border-transparent transition-all duration-500"
                    x-data="{ showPopup: false, selectedGame: null, showParticipants: false, participants: [] }">
                    <div class="my-auto">
                        @if ($team->logo)
                            <img src="{{ asset($team->logo) }}"
                                class="w-24 h-24 md:w-32 md:h-32  object-contain rounded-full border-1 custom-border p-3">
                        @else
                        <img src="/favicon.png"
                        class="w-24 h-24 md:w-32 md:h-32 object-fit-contain rounded-full border-1 custom-border p-4">
                        @endif
                    </div>
                    <div class=" pl-8 my-auto">
                        <div class="text-start">
                            <h3 class="text-lg md:text-2xl font-semibold">{{ $team->name }}</h3>
                            <p class="text-lg">Total Point: <span
                                    class="font-bold text-yellow-400 text-3xl">{{ $team->total_score }}</span></p>
                        </div>
                        <button @click="showPopup = true" class=" flex custom-border text-white text-sm py-2 px-3 mt-2 md:mt-4">
                            Point Breakdown
                        </button>
                    </div>

                    <!-- Popup Modal -->
                    <div x-show="showPopup"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                        <div class="bg-black text-white p-6 rounded-sm max-w-7xl shadow-lg relative custom-border w-full">
                            <!-- Close Button (top left corner) -->
                            <button @click="showPopup = false"
                                class="absolute -top-2 -right-2 text-black text-xl font-bold px-2 py-1 border-1 border-white rounded-full bg-white hover:text-black transition-all">
                                &times;
                            </button>
                            <div class="text-center">
                                <h3 class="text-2xl  font-semibold">
                                    {{ $team->name }}
                                </h3>
                                <p class="text-lg font-semibold">Total Score - <span class="text-yellow-400 text-lg">{{ $team->total_score }}</span></p>
                            </div>
                            
                            <table class="w-auto text-center justify-center border border-gray-300 my-3 mx-auto">
                                <thead>
                                    <tr class="bg-gray-900">
                                        @foreach ($team->category_scores as $category => $score)
                                            <th class="p-2 border">{{ $category }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach ($team->category_scores as $score)
                                            <td class="p-2 border font-bold text-blue-500">{{ $score }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                            
                            <h4 class="text-lg font-bold mb-4">Point Breakdown</h4>
                            <div class="overflow-x-auto">
                                <table id="scoreTable" class="w-full table-auto border-collapse border border-gray-300 scoreTable">
                                <thead>
                                    <tr class="bg-gray-900">
                                        <th class="border border-gray-300 px-4 py-2">Game</th>
                                        <th class="border border-gray-300 px-4 py-2">Category</th>
                                        <th class="border border-gray-300 px-4 py-2">Position</th>
                                        <th class="border border-gray-300 px-4 py-2">Point</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($team->score_breakdown as $game => $data)
                                        <tr class="text-center">
                                            <td class="border border-gray-300 px-4 py-2">{{ $game }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $data['category'] }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $data['position'] }}</td>
                                            <td class="border border-gray-300 px-4 py-2 font-bold text-yellow-600">
                                                {{ $data['score'] }}</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        
                        </div>
                    </div>

                </div>
            @endforeach
@endsection
