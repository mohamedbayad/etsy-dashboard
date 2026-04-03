<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-background/50 border border-border/80 rounded-lg font-bold text-[10px] tracking-widest text-muted-foreground uppercase shadow-sm hover:bg-accent hover:text-foreground focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 focus:ring-offset-background disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>
