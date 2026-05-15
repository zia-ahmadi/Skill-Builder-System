<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-100 leading-tight">Edit Skill</h2>
                <p class="text-sm text-gray-400 mt-1">Update your skill information</p>
            </div>
            <a href="{{ route('skills.show', $skill) }}" class="px-5 py-2.5 text-sm text-gray-300 hover:text-white border border-slate-700/60 bg-slate-800/40 hover:bg-slate-700/60 rounded-lg font-semibold transition-all duration-300 hover:scale-105 backdrop-blur-sm">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl p-8 shadow-2xl backdrop-blur-sm">
                <form method="POST" action="{{ route('skills.update', $skill) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Name</label>
                        <input name="name" value="{{ old('name', $skill->name) }}" required class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Enter skill name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Goal Hours</label>
                            <input type="number" step="0.1" min="0" name="goal_hours" value="{{ old('goal_hours', $skill->goal_hours) }}" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="0.0">
                            <x-input-error :messages="$errors->get('goal_hours')" class="mt-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Deadline (optional)</label>
                            <input type="date" name="deadline" value="{{ old('deadline', $skill->deadline?->toDateString()) }}" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none" placeholder="Describe this skill...">{{ old('description', $skill->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Banner Image (optional)</label>
                        <input type="file" name="banner" accept="image/*" class="w-full text-sm text-gray-300 bg-slate-800/60 border border-slate-700/50 rounded-lg px-4 py-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 transition-all">
                        @if($skill->banner_url)
                            <div class="mt-4 overflow-hidden rounded-xl border-2 border-slate-700/50">
                                <img src="{{ $skill->banner_url }}" alt="Current banner" class="w-full h-40 object-cover">
                            </div>
                        @endif
                        <p class="text-xs text-slate-400 mt-2 font-medium">Upload JPG/PNG/WebP up to 2MB to replace current banner.</p>
                        <x-input-error :messages="$errors->get('banner')" class="mt-2" />
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-700/50">
                        <a href="{{ route('skills.show', $skill) }}" class="px-6 py-3 text-sm text-gray-300 hover:text-white border border-slate-700/60 bg-slate-800/40 hover:bg-slate-700/60 rounded-lg font-semibold transition-all duration-300 hover:scale-105 backdrop-blur-sm">Cancel</a>
                        <button class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-lg text-white text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

