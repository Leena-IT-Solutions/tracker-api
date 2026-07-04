<nav class="flex items-center gap-3">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="px-4 py-2 text-sm font-bold rounded-xl bg-violet-600 hover:bg-violet-500 text-white transition-all duration-200 shadow-lg shadow-violet-600/15"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="px-4 py-2 text-sm font-bold rounded-xl text-slate-300 hover:text-white hover:bg-slate-900/60 transition-all duration-200"
        >
            Log in
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="px-4 py-2 text-sm font-bold rounded-xl bg-violet-600 hover:bg-violet-500 text-white transition-all duration-200 shadow-lg shadow-violet-600/15"
            >
                Register
            </a>
        @endif
    @endauth
</nav>
