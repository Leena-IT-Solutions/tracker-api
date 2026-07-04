<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white rounded-2xl font-semibold text-sm tracking-wide shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/35 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-violet-500/50 active:scale-[0.98] transition-all duration-200']) }}>
    {{ $slot }}
</button>
