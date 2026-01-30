@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => '
            w-full
            px-3 py-2
            border border-gray-300
            bg-white
            text-gray-900
            placeholder-gray-400
            rounded-md
            shadow-sm
            focus:outline-none
            focus:ring-2
            focus:ring-sky-500
            focus:border-sky-500
        '
    ]) }}
>
