@props([
    'name',
    'options' => [],
    'selected' => null
])

<div class="relative group">
    <select
        name="{{ $name }}"
        onchange="this.form.submit()"
        class="pl-4 pr-10 py-2.5 rounded-xl border border-outline-variant bg-surface text-sm font-bold text-on-surface hover:border-primary focus:ring-2 focus:ring-primary focus:outline-none transition-all cursor-pointer shadow-sm">
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>