<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-black min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if($applications->isEmpty())
                <div class="text-center p-8 bg-[#111827] rounded-xl border border-gray-800">
                    <p class="text-gray-400 text-lg">You haven't applied to any jobs yet.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($applications as $app)
                        {{-- Job Card --}}
                        <div class="bg-[#111827] border border-gray-800 rounded-xl p-6 shadow-2xl">
                            
                            {{-- Card Header: Title & Badge --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ $app->jobVacancie->title ?? 'N/A' }}</h3>
                                    <p class="text-gray-400 text-sm mt-1">{{ $app->jobVacancie->company->name ?? 'N/A' }}</p>
                                    <p class="text-gray-500 text-xs mt-1 italic">{{ $app->jobVacancie->location ?? 'Dubai, UAE' }}</p>
                                    <p class="text-gray-500 text-xs mt-1">{{ $app->created_at->format('d M Y') }}</p>
                                </div>
                                <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-md shadow-lg">
                                    Full-Time
                                </span>
                            </div>

                            

                            {{-- Badges: Status & Score --}}
                            <div class="flex items-center gap-2 mb-6">
                                <div class="bg-[#FBBF24] px-4 py-1.5 rounded-md flex items-center shadow-md">
                                    <span class="text-black text-sm font-bold uppercase tracking-tight">Status: {{ $app->status }}</span>
                                </div>
                                <div class="bg-[#4F46E5] px-4 py-1.5 rounded-md flex items-center shadow-md">
                                    <span class="text-white text-sm font-bold uppercase tracking-tight">Score: {{ $app->{'ai generated score'} ?? '0' }}</span>
                                </div>
                            </div>

                            {{-- AI Feedback Section --}}
                            <div class="border-t border-gray-800/50 pt-4">
                                <h4 class="text-white font-bold text-sm mb-2">AI Feedback:</h4>
                                <p class="text-gray-400 text-sm leading-relaxed text-justify">
                                    {{ $app->{'ai generated feedback'} ?? 'Analysis in progress...' }}
                                </p>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>