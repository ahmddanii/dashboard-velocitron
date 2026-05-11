@props([
    'name',
    'options' => [],
    'selected' => null
])

<select
    name="{{ $name }}"
    onchange="this.form.submit()"

    class="px-4 py-2 rounded-xl
    border border-slate-200
    bg-white text-sm font-medium
    focus:ring-2 focus:ring-blue-500
    focus:outline-none">

    @foreach($options as $value => $label)

        <option
            value="{{ $value }}"
            {{ $selected == $value ? 'selected' : '' }}>

            {{ $label }}

        </option>

    @endforeach

</select>