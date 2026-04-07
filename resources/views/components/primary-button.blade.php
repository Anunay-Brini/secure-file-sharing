<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:from-indigo-700 hover:to-blue-700 hover:shadow-lg hover:shadow-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all ease-in-out duration-300 transform hover:-translate-y-0.5 active:translate-y-0']) }}>
    {{ $slot }}
</button>
