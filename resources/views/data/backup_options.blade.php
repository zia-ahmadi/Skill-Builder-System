<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Backup data
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/70 shadow-sm sm:rounded-lg border border-slate-700/60">
                <div class="p-6 text-gray-200 space-y-4">
                    <h3 class="text-lg font-semibold">Last backup</h3>
                    @if($lastBackup)
                        <div class="space-y-1 text-sm text-gray-300">
                            <div><span class="font-medium">Time:</span> {{ $lastBackup['time'] ?? '' }}</div>
                            <div><span class="font-medium">Folder:</span> {{ $lastBackup['path'] ?? '' }}</div>
                            <div><span class="font-medium">Skills:</span> {{ $lastBackup['skills'] ?? 0 }}</div>
                            <div><span class="font-medium">Topics:</span> {{ $lastBackup['topics'] ?? 0 }}</div>
                            <div><span class="font-medium">Sessions:</span> {{ $lastBackup['sessions'] ?? 0 }}</div>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No backup has been recorded yet.</p>
                    @endif
                </div>
            </div>

            <div class="bg-slate-900/70 shadow-sm sm:rounded-lg border border-slate-700/60">
                <div class="p-6 text-gray-200 space-y-6">
                    <h3 class="text-lg font-semibold">Create a backup</h3>
                    <form method="POST" action="{{ route('data.export.run') }}" class="space-y-5">
                        @csrf
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input id="mode_default" type="radio" name="mode" value="default" class="h-4 w-4 text-indigo-500 border-slate-600 bg-slate-800" checked>
                                <label for="mode_default" class="ml-2 text-sm text-gray-200">
                                    Use default folder: <span class="font-mono">{{ $defaultDir }}</span>
                                </label>
                            </div>
                            <div class="flex items-start">
                                <input id="mode_custom" type="radio" name="mode" value="custom" class="mt-1 h-4 w-4 text-indigo-500 border-slate-600 bg-slate-800">
                                <div class="ml-2 flex-1">
                                    <label for="mode_custom" class="text-sm text-gray-200">Choose a different folder</label>
                                    <input type="text" name="custom_path" class="mt-2 block w-full rounded-md border-slate-700 bg-slate-800 text-sm text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. D:\MyBackups">
                                    <p class="mt-1 text-xs text-gray-400">The path is used on this server. Make sure the folder is accessible.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-primary-button>
                                Run backup now
                            </x-primary-button>
                            @if (session('status'))
                                <p class="text-sm text-emerald-400">{{ session('status') }}</p>
                            @endif
                            @if (session('error'))
                                <p class="text-sm text-rose-400">{{ session('error') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

