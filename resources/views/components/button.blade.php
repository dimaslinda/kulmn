<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full text-center items-center px-4 py-2 bg-tombol cursor-pointer border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
