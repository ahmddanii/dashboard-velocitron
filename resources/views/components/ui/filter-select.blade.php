@props([
    'name',
    'options' => [],
    'selected' => null
])

<div class="relative group">
    <select
        name="{{ $name }}"
        onchange="this.form.submit()"
        class="appearance-none pl-4 pr-10 py-2.5 rounded-xl border border-outline-variant bg-surface text-sm font-bold text-on-surface hover:border-primary focus:ring-2 focus:ring-primary focus:outline-none transition-all cursor-pointer shadow-sm">
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant group-hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-base">expand_more</span>
    </div>
</div>