@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 dark:border-slate-800/80 bg-white/40 dark:bg-slate-950/40 text-slate-800 dark:text-slate-100 placeholder-slate-400/70 focus:border-violet-500 focus:ring-violet-500/20 rounded-2xl shadow-sm px-4 py-3 transition-all duration-200']) }}>
