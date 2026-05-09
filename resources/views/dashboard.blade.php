<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4">

        {{-- Welcome --}}
        <h1 class="text-2xl font-bold text-white mb-6">
            Welcome, {{ auth()->user()->name }}
        </h1>

        {{-- Search & Filter Section --}}
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-8">
            
            {{-- قسم السيرش --}}
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <div class="relative w-full sm:w-80">
                    <input type="text" id="search" value="{{ request('search') }}"
                        placeholder="Search jobs, companies..."
                        class="bg-white/5 border-0 text-white rounded-lg px-4 py-2 w-full placeholder-white/30 focus:ring-1 focus:ring-purple-500" />
                    
                    {{-- زر مسح السيرش --}}
                    <button id="clear-search" 
                        class="{{ !request('search') ? 'hidden' : '' }} absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white transition">
                        ✕
                    </button>
                </div>
            </div>

            {{-- قسم الفلاتر --}}
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex gap-2 flex-wrap" id="filter-options">
                    <a href="#" data-type=""
                        class="filter-link px-4 py-2 rounded-lg border text-sm transition {{ !request('type') ? 'bg-white text-black' : 'border-white/20 text-white/70 hover:bg-white/10' }}">
                        All
                    </a>

                    @foreach(['Full-time', 'Part-time', 'Remote', 'Hybrid'] as $type)
                        <a href="#" data-type="{{ $type }}"
                            class="filter-link px-4 py-2 rounded-lg border text-sm transition {{ request('type') == $type ? 'bg-white text-black' : 'border-white/20 text-white/70 hover:bg-white/10' }}">
                            {{ $type }}
                        </a>
                    @endforeach
                </div>

                {{-- زر مسح الفلتر --}}
                <button id="clear-type" 
                    class="{{ !request('type') ? 'hidden' : '' }} text-sm text-red-400 hover:text-red-300 font-medium px-2 py-1 transition">
                    Reset Filter
                </button>
            </div>
        </div>

        {{-- Jobs List Container --}}
        <div id="jobs-wrapper">
            <div class="divide-y divide-white/10" id="jobs-list">
                @forelse ($jobs as $job)
                    <div class="py-5 flex items-start justify-between group">
                        <div>
                            {{-- التعديل هنا: ربط الوظيفة بصفحة التفاصيل --}}
                            <a href="{{ route('jobs.show', $job->id) }}" class="text-blue-400 font-semibold hover:underline text-lg">
                                {{ $job->title }}
                            </a>
                            
                            <p class="text-white/50 text-sm mt-1">
                                {{ $job->company?->name }} • {{ $job->location }}
                            </p>
                            <p class="text-white/30 text-xs mt-2 italic">
                                Salary: ${{ number_format($job->salary) }}
                            </p>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                            {{ $job->type }}
                        </span>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <p class="text-white/20 text-xl italic">No vacancies found matching your criteria.</p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                <div class="flex justify-between items-center mt-10 pb-10">
                    <div>
                        @if ($jobs->onFirstPage())
                            <span class="px-5 py-2 bg-white/5 text-white/10 rounded-lg cursor-not-allowed text-sm border border-white/5">Previous</span>
                        @else
                            <a href="{{ $jobs->previousPageUrl() }}" class="px-5 py-2 bg-white/5 border border-white/20 text-white/70 rounded-lg hover:bg-white/10 text-sm transition">Previous</a>
                        @endif
                    </div>
                     
                    <div>
                        @if ($jobs->hasMorePages())
                            <a href="{{ $jobs->nextPageUrl() }}" class="px-5 py-2 bg-white/5 border border-white/20 text-white/70 rounded-lg hover:bg-white/10 text-sm transition">Next</a>
                        @else
                            <span class="px-5 py-2 bg-white/5 text-white/10 rounded-lg cursor-not-allowed text-sm border border-white/5">Next</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script المطور للـ AJAX --}}
    <script>
        const searchInput = document.getElementById('search');
        const clearSearchBtn = document.getElementById('clear-search');
        const clearTypeBtn = document.getElementById('clear-type');
        const filterLinks = document.querySelectorAll('.filter-link');
        
        let timeout = null;
        let currentType = new URLSearchParams(window.location.search).get('type') || '';

        function updateResults(searchVal, typeVal) {
            // إظهار/إخفاء أزرار المسح
            searchVal ? clearSearchBtn.classList.remove('hidden') : clearSearchBtn.classList.add('hidden');
            typeVal ? clearTypeBtn.classList.remove('hidden') : clearTypeBtn.classList.add('hidden');

            const url = new URL(window.location.origin + window.location.pathname);
            if (searchVal) url.searchParams.set('search', searchVal);
            if (typeVal) url.searchParams.set('type', typeVal);
            
            // تحديث ستايل الفلاتر
            filterLinks.forEach(link => {
                if (link.getAttribute('data-type') === typeVal) {
                    link.className = 'filter-link px-4 py-2 rounded-lg border text-sm bg-white text-black transition';
                } else {
                    link.className = 'filter-link px-4 py-2 rounded-lg border text-sm border-white/20 text-white/70 hover:bg-white/10 transition';
                }
            });

            // جلب البيانات بـ AJAX
            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                document.getElementById('jobs-list').innerHTML = doc.getElementById('jobs-list').innerHTML;
            });

            window.history.replaceState(null, '', url.toString());
        }

        // أحداث البحث
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                updateResults(this.value, currentType);
            }, 300);
        });

        clearSearchBtn.addEventListener('click', () => {
            searchInput.value = '';
            updateResults('', currentType);
        });

        // أحداث الفلاتر
        filterLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                currentType = this.getAttribute('data-type');
                updateResults(searchInput.value, currentType);
            });
        });

        clearTypeBtn.addEventListener('click', () => {
            currentType = '';
            updateResults(searchInput.value, '');
        });
    </script>
</x-app-layout>