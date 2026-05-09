<x-app-layout>
    <div class="max-w-2xl mx-auto py-12 px-4" x-data="{ fileName: '', fileError: false }">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Apply for {{ $job->title }}</h1>
            <p class="text-blue-400 mt-2">Submit your resume to {{ $job->company?->name }}</p>
        </div>

        <form action="{{ route('jobs.apply.submit', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- التنبيه في حالة وجود CV قديم --}}
            @if($application?->resume)
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-2xl p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <p class="text-yellow-400 font-semibold text-sm">Current CV</p>
                            <p class="text-white/70 text-xs font-mono truncate max-w-xs">
                                {{ $application->resume->file_name }}
                            </p>
                        </div>
                    </div>
                    <span class="text-yellow-500/60 text-xs font-mono">Will be replaced</span>
                </div>
            @endif

            {{-- منطقة رفع الملف --}}
            <div class="bg-white/5 border border-dashed rounded-2xl p-10 text-center transition-all group"
                 :class="fileName ? 'border-blue-500/50' : (fileError ? 'border-red-500/50' : '{{ $errors->has('resume_file') ? 'border-red-500/50' : 'border-white/10' }}')">

                {{-- الـ Input المخفي --}}
                <input type="file" name="resume_file" id="resume_file" accept=".pdf" class="hidden"
                       @change="
                            const file = $event.target.files[0];
                            if (file) {
                                if (file.type !== 'application/pdf') {
                                    fileName = '';
                                    fileError = true;
                                    $event.target.value = '';
                                } else {
                                    fileName = file.name;
                                    fileError = false;
                                }
                            }
                       ">

                {{-- Label منفصل عن الـ button ✅ --}}
                <label for="resume_file" class="cursor-pointer block">
                    <div class="text-blue-500 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>

                    <span class="text-white text-lg font-semibold" 
                          x-text="fileName ? 'New file selected' : '{{ $application?->resume ? 'Upload New CV' : 'Upload your CV' }}'">
                    </span>
                    
                    <div class="mt-4">
                        <template x-if="fileName">
                            <div>
                                <p x-text="fileName" class="text-blue-400 font-bold text-md"></p>
                                <p class="text-gray-400 text-sm mt-1 animate-pulse">Click to change file</p>
                            </div>
                        </template>
                        
                        <template x-if="!fileName && !fileError">
                            <p class="text-white/30 text-sm font-mono">PDF only (Max 5MB)</p>
                        </template>

                        <template x-if="fileError">
                            <div class="text-red-400 font-bold">
                                <span>⚠ PDF files only!</span>
                                <p class="text-xs font-normal mt-1 text-red-300/70">Please select a valid document</p>
                            </div>
                        </template>
                    </div>
                </label>
            </div>

            {{-- عرض الـ Validation Errors --}}
            @error('resume_file')
                <p class="text-red-400 text-sm">{{ $message }}</p>
            @enderror

             <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-5 rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] text-lg uppercase tracking-wider">
                {{ $application?->resume ? 'REPLACE CV' : 'SUBMIT APPLICATION' }}
            </button>
        </form>
    </div>
</x-app-layout>