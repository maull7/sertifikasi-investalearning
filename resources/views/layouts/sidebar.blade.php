<aside id="sidebar"
    class="fixed top-0 left-0 z-[9999] h-screen bg-white/90 backdrop-blur-xl dark:bg-gray-950/90 text-gray-900 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)] border-r border-gray-200/50 dark:border-gray-800/50 flex flex-col"
    x-data="{
        openSubmenus: {},
        tooltipText: '',
        tooltipVisible: false,
        tooltipTop: 0,
        unverifiedCount: {{ $unverifiedCount ?? 0 }},
        pendingPackageCount: {{ $pendingPackageCount ?? 0 }},
        get expanded() {
            if (window.innerWidth < 1280) return true;
            return $store.sidebar.isExpanded;
        },
        toggleSubmenu(key) { 
            if(!this.expanded) {
                $store.sidebar.toggleExpanded();
                setTimeout(() => { this.openSubmenus[key] = true; }, 100);
            } else {
                this.openSubmenus[key] = !this.openSubmenus[key]; 
            }
        },
        showTooltip(e, text) {
            if(!this.expanded) {
                this.tooltipText = text;
                this.tooltipVisible = true;
                this.tooltipTop = e.currentTarget.getBoundingClientRect().top + (e.currentTarget.offsetHeight / 2);
            }
        },
        fetchUnverified() {
            fetch('{{ route('admin.unverified-count') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(d => { this.unverifiedCount = d.count || 0 })
                .catch(() => {});
        },
        fetchPendingPackages() {
            fetch('{{ route('admin.pending-packages-count') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(d => { this.pendingPackageCount = d.count || 0 })
                .catch(() => {});
        }
    }"
    x-init="$nextTick(() => { if ({{ $isAdmin ? 'true' : 'false' }} && typeof fetch !== 'undefined') { $data.fetchUnverified(); $data.fetchPendingPackages(); setInterval(() => { $data.fetchUnverified(); $data.fetchPendingPackages(); }, 30000); } })"
    :class="{
        'w-72': expanded,
        'w-20': !expanded,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen,
        'translate-x-0': $store.sidebar.isMobileOpen,
    }"
    @mouseleave="tooltipVisible = false">

   <div x-show="tooltipVisible && !expanded" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="fixed left-[90px] z-[10000] px-4 py-2.5 bg-gray-900 dark:bg-indigo-600 text-white text-[13px] font-bold rounded-xl pointer-events-none shadow-2xl min-w-[120px] whitespace-nowrap border border-white/10"
         :style="`top: ${tooltipTop}px; transform: translateY(-50%);`"
         x-cloak>
        
        <div class="flex items-center gap-2">
            <span x-text="tooltipText"></span>
        </div>

        <div class="absolute left-0 top-1/2 -translate-x-full -translate-y-1/2 w-0 h-0 border-y-[7px] border-y-transparent border-r-[7px] border-r-gray-900 dark:border-r-indigo-600"></div>
    </div>

    {{-- Logo Area --}}
    <div class="h-18 flex items-center p-4 overflow-hidden shrink-0">
        <a href="/" class="flex items-center gap-4 group shrink-0">
            <div class="relative w-12 h-12 bg-gradient-to-tr from-slate-50 to-zinc-100 dark:from-slate-200 dark:to-slate-400 rounded-2xl flex items-center justify-center transition-all duration-500 group-hover:rotate-[10deg] group-hover:scale-110">
                <img src="{{asset('img/favicon.png')}}" alt="Favicon">
            </div>
            <div x-show="expanded" x-transition class="flex flex-col">
                <span class="font-bold text-lg dark:text-white leading-none">
                    In<span class="text-indigo-600 dark:text-white">ves</span>ta
                </span>
                <span class="text-sm font-semibold text-gray-400">Sertifikasi Pelatihan</span>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <div class="flex-1 overflow-y-auto no-scrollbar py-4 px-4">
        <nav class="space-y-6">
            @foreach ($menuGroups as $gIdx => $group)
                <div class="space-y-2">
                    <h2 class="px-4 text-[11px] font-semibold text-gray-400 dark:text-gray-500 tracking-wider"
                        x-show="expanded" x-cloak>
                        {{ $group['title'] }}
                    </h2>
                    <ul class="space-y-1">
                        @foreach ($group['items'] as $iIdx => $item)
                            @php 
                                $key = "sub-$gIdx-$iIdx"; 
                                $isGroupActive = isset($item['activePattern']) && request()->routeIs($item['activePattern']);
                                $isSingleActive = isset($item['route']) && request()->routeIs($item['route']);
                            @endphp
                            <li class="relative">
                                @if (isset($item['subItems']))
                                    {{-- Dropdown Menu Toggler --}}
                                    <button @click="toggleSubmenu('{{ $key }}')"
                                        @mouseenter="showTooltip($event, '{{ $item['name'] }}')"
                                        class="w-full flex items-center py-2.5 rounded-xl transition-all duration-300 group relative"
                                        :class="[
                                            openSubmenus['{{ $key }}'] || {{ $isGroupActive ? 'true' : 'false' }} ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900/50',
                                            expanded ? 'px-4' : 'justify-center'
                                        ]">
                                        
                                        <span class="relative shrink-0">
                                            <i class="ti ti-{{ $item['icon'] }} text-xl transition-transform duration-300 group-hover:scale-110"></i>
                                            @if(in_array('user.not.active', array_column($item['subItems'] ?? [], 'route')))
                                                <span x-show="unverifiedCount > 0" x-transition
                                                    class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-rose-500 ring-2 ring-white dark:ring-gray-950 animate-pulse"></span>
                                            @endif
                                            @if(in_array('approve-packages.index', array_column($item['subItems'] ?? [], 'route')))
                                                <span x-show="pendingPackageCount > 0" x-transition
                                                    class="absolute -top-1 -right-3.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-2 ring-white dark:ring-gray-950 animate-pulse"></span>
                                            @endif
                                        </span>
                                        
                                        <span x-show="expanded" x-cloak class="ml-3 font-medium text-sm grow text-left flex items-center gap-2">
                                            {{ $item['name'] }}
                                            @if(in_array('user.not.active', array_column($item['subItems'] ?? [], 'route')))
                                                <span x-show="unverifiedCount > 0" x-transition
                                                    class="min-w-[20px] h-5 px-1.5 flex items-center justify-center rounded-full bg-rose-500 text-white text-[10px] font-bold animate-pulse"
                                                    x-text="unverifiedCount"></span>
                                            @endif
                                            @if(in_array('approve-packages.index', array_column($item['subItems'] ?? [], 'route')))
                                                <span x-show="pendingPackageCount > 0" x-transition
                                                    class="min-w-[20px] h-5 px-1.5 flex items-center justify-center rounded-full bg-amber-500 text-white text-[10px] font-bold animate-pulse"
                                                    x-text="pendingPackageCount"></span>
                                            @endif
                                        </span>

                                        <i x-show="expanded" x-cloak
                                            class="ti ti-chevron-right text-xs transition-transform duration-300"
                                            :class="openSubmenus['{{ $key }}'] ? 'rotate-90' : ''"></i>
                                    </button>

                                    {{-- Submenu Content --}}
                                    <div x-show="openSubmenus['{{ $key }}'] && expanded" 
                                         x-init="openSubmenus['{{ $key }}'] = {{ $isGroupActive ? 'true' : 'false' }}"
                                         x-cloak x-collapse>
                                        <ul class="mt-1 ml-9 space-y-1 relative before:absolute before:left-[-12px] before:top-0 before:w-px before:h-full before:bg-gray-100 dark:before:bg-gray-800">
                                            @foreach ($item['subItems'] as $sub)
                                                <li>
                                                    <a href="{{ route($sub['route']) }}" 
                                                       class="relative flex items-center justify-between gap-2 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs($sub['route']) ? 'text-indigo-600' : 'text-gray-400 hover:text-indigo-500' }}">
                                                        <span class="flex items-center gap-2 min-w-0">
                                                            @if(request()->routeIs($sub['route']))
                                                                <div class="absolute left-[-14px] w-1 h-1 bg-indigo-600 rounded-full"></div>
                                                            @endif
                                                            {{ $sub['name'] }}
                                                        </span>
                                                        @if(($sub['route'] ?? '') === 'user.not.active')
                                                            <span x-show="unverifiedCount > 0" x-transition
                                                                class="shrink-0 min-w-[20px] h-5 px-1.5 flex items-center justify-center rounded-full bg-rose-500 text-white text-[10px] font-bold animate-pulse"
                                                                x-text="unverifiedCount"></span>
                                                        @endif
                                                        @if(($sub['route'] ?? '') === 'approve-packages.index')
                                                            <span x-show="pendingPackageCount > 0" x-transition
                                                                class="shrink-0 min-w-[20px] h-5 px-1.5 flex items-center justify-center rounded-full bg-amber-500 text-white text-[10px] font-bold animate-pulse"
                                                                x-text="pendingPackageCount"></span>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    {{-- Single Link --}}
                                    <a href="{{ route($item['route']) }}" 
                                       @mouseenter="showTooltip($event, '{{ $item['name'] }}')"
                                       class="flex items-center py-2.5 rounded-xl group relative overflow-hidden transition-all duration-300"
                                       :class="[
                                           {{ $isSingleActive ? 'true' : 'false' }} ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900/50',
                                           expanded ? 'px-4' : 'justify-center'
                                       ]">
                                        
                                        <i class="ti ti-{{ $item['icon'] }} text-xl shrink-0 z-10 transition-transform duration-300 group-hover:scale-110"></i>
                                        
                                        <span x-show="expanded" x-cloak class="ml-3 font-semibold text-sm grow z-10 flex items-center">
                                            {{ $item['name'] }}
                                            @if(!empty($item['new']))
                                                <span class="ml-auto bg-rose-500 text-[9px] text-white px-1.5 py-0.5 rounded-md font-bold animate-pulse">New</span>
                                            @endif
                                        </span>
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </div>

    {{-- Bottom Section --}}
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 space-y-3 shrink-0">
        <div class="bg-gray-100/50 dark:bg-gray-900/50 rounded-xl p-1 flex items-center gap-1" x-show="expanded" x-cloak>
            <button @click="$store.theme.toggle()" class="flex-1 py-2 rounded-lg transition-all flex justify-center items-center gap-2 text-xs font-semibold"
                    :class="$store.theme.theme === 'light' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-400'">
                <i class="ti ti-sun"></i> Light
            </button>
            <button @click="$store.theme.toggle()" class="flex-1 py-2 rounded-lg transition-all flex justify-center items-center gap-2 text-xs font-semibold"
                    :class="$store.theme.theme === 'dark' ? 'bg-gray-800 text-indigo-400 shadow-sm' : 'text-gray-400'">
                <i class="ti ti-moon"></i> Dark
            </button>
        </div>

        <div class="relative group cursor-pointer bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-3 border border-transparent transition-all hover:border-indigo-500/30"
             @click="!expanded ? $store.sidebar.toggleExpanded() : null">
            <div class="flex items-center gap-3" :class="expanded ? '' : 'justify-center'">
                <div class="relative shrink-0">
                    <img src="https://ui-avatars.com/api/?name=Rafael&background=6366f1&color=fff" class="w-9 h-9 rounded-xl" alt="User">
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-950 rounded-full"></div>
                </div>
                <div x-show="expanded" x-cloak class="overflow-hidden">
                    <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>