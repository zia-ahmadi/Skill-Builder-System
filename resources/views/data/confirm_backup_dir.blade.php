<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-100 leading-tight">Confirm Backup Folder</h2>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl p-6 shadow-2xl backdrop-blur-sm">
                <p class="text-slate-300 mb-4">The folder for saving CSV backups was not found.</p>
                <p class="text-white font-semibold mb-6">Folder: {{ $dir }}</p>
                <form method="POST" action="{{ route('data.export.create_dir') }}" class="flex items-center gap-3">
                    @csrf
                    <button class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-lg text-white text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">Create Folder</button>
                    <a href="{{ url()->previous() }}" class="px-6 py-2.5 bg-slate-800/60 hover:bg-slate-700/60 border border-slate-700/50 rounded-lg text-sm text-white font-semibold transition-all duration-300 hover:scale-105">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
