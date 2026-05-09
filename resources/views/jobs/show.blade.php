<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">
        
        {{-- زر الرجوع --}}
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-8 transition group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Back to Dashboard</span>
        </a>

        {{-- رسالة النجاح بعد التقديم --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-xl flex items-center shadow-lg shadow-blue-500/5">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        {{-- الحاوية الرئيسية --}}
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            
            {{-- Header الوظيفة --}}
            <div class="p-8 border-b border-white/10 bg-white/[0.02]">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-4xl font-bold text-white tracking-tight">
                            {{ $job->title }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-4 text-white/50 text-lg">
                            <span class="flex items-center">
                                <span class="text-blue-500 mr-2">🏢</span> {{ $job->company?->name }}
                            </span>
                            <span class="flex items-center">
                                <span class="text-blue-500 mr-2">📍</span> {{ $job->location }}
                            </span>
                            <span class="flex items-center">
                                <span class="text-blue-500 mr-2">📅</span> {{ $job->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    {{-- Badge النوع --}}
                    <span class="px-5 py-2 rounded-full bg-blue-500/10 text-blue-400 text-sm font-bold border border-blue-500/20 shadow-sm">
                        {{ $job->type }}
                    </span>
                </div>
            </div>

            {{-- المحتوى الرئيسي --}}
            <div class="p-8 space-y-10">
                
                {{-- الوصف الوظيفي --}}
                <div>
                    <h2 class="text-white font-bold text-xl mb-6 flex items-center">
                        <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-3 shadow-[0_0_15px_rgba(37,99,235,0.4)]"></span>
                        Job Description
                    </h2>
                    <div class="text-white/70 leading-relaxed text-lg pl-4 border-l border-white/5">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>

                {{-- كروت التفاصيل السريعة --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                    <div class="bg-white/[0.03] border border-white/5 p-6 rounded-2xl hover:bg-white/[0.05] transition group">
                        <p class="text-white/40 text-sm font-medium uppercase tracking-wider mb-2 group-hover:text-blue-400/50 transition">Estimated Salary</p>
                        <p class="text-blue-400 font-bold text-2xl group-hover:scale-105 transition-transform origin-left">
                            ${{ number_format($job->salary) }} <span class="text-sm text-white/20 font-normal italic">/ year</span>
                        </p>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 p-6 rounded-2xl hover:bg-white/[0.05] transition group">
                        <p class="text-white/40 text-sm font-medium uppercase tracking-wider mb-2 group-hover:text-blue-400/50 transition">Employment Status</p>
                        <p class="text-white font-bold text-2xl">{{ $job->type }}</p>
                    </div>
                </div>

                {{-- زرار التقديم (Apply Now) --}}
                <div class="pt-8">
                    <a href="{{ route('jobs.apply.form', $job->id) }}" 
                       class="block w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-5 rounded-xl shadow-lg shadow-blue-600/20 transition-all transform active:scale-[0.98] text-center text-xl uppercase tracking-widest">
                        Apply Now
                    </a>
                    <p class="text-center text-white/20 text-xs mt-4 uppercase tracking-[0.2em]">
                        Clicking apply will open the resume submission page
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>