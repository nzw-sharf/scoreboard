<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Game') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ gameType: '' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-white text-lg font-bold mb-4">Create New Game</h3>

                <form action="{{ route('games.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Game Name:</label>
                        <input type="text" name="name" required class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Game Type:</label>
                        <select name="type" required x-model="gameType" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600">
                            <option value="">Select Game Type</option>
                            <option value="individual">Individual</option>
                            <option value="group">Group</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Game Category:</label>
                        <select name="category" required class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600">
                            <option value="">Select Game Category</option>
                            <option value="Men's">Men's</option>
                            <option value="Women's">Women's</option>
                            <option value="Kids">Kids</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-white text-lg mb-2">Game Date:</label>
                        <input type="date" name="game_date" required class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600">
                    </div>

                    <!-- Show only for individual -->
                    <div class="mb-4" x-show="gameType === 'individual'" x-cloak>
                        <label class="block text-white text-lg mb-2">Max Participants:</label>
                        <input type="number" name="max_participants_per_team" min="1" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600">
                    </div>

                    <!-- Show only for group -->
                    <div x-show="gameType === 'group'" x-cloak>
                        <div class="mb-4">
                            <label class="block text-white text-lg mb-2">Number of Teams Allowed:</label>
                            <input type="number" name="number_of_teams" min="1" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600">
                        </div>

                        <div class="mb-4">
                            <label class="block text-white text-lg mb-2">Members Per Team:</label>
                            <input type="number" name="members_per_team" min="1" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600">
                        </div>
                    </div>

                    <button type="submit" class="w-full p-3 bg-blue-600 text-white font-semibold rounded-lg mt-4 hover:bg-blue-500">
                        Save Game
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
