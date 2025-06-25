{{-- navbar --}}
<nav id="navbar-bg" class="top-0 fixed w-full z-20 backdrop-blur-md bg-transparent">
    <div
        class="flex flex-wrap justify-between items-center p-4 mx-auto max-w-screen-xl bg-transparent md:px-8 transition-colors duration-300">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('img/general/logo.webp') }}" class="h-8 xl:h-10" alt="logo kulmn" />
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">

            <a href="https://wa.me/62895411871843" target="_blank"
                class="text-white font-inter cursor-pointer uppercase bg-transparent border-2 border-tombol hover:text-hitam hover:bg-tombol focus:ring-4 focus:outline-none focus:ring-navbar font-medium text-sm px-6 md:px-9 py-2 text-center">
                konsultasi
            </a>

            <button data-collapse-toggle="navbar-sticky" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-tombol rounded-lg md:hidden hover:bg-tombol focus:outline-none focus:ring-2 focus:ring-tombol"
                aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul
                class="flex flex-col font-inter text-base xl:text-xl capitalize p-4 md:p-0 mt-4 font-medium md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent">
                <li>
                    <a href="/"
                        class="block py-2 px-3 hover:bg-tombol hover:text-tombol md:hover:bg-transparent md:border-0 md:hover:text-tombol rounded-sm md:bg-transparent md:text-white capitalize"
                        aria-current="page">
                        Home
                    </a>
                </li>
                <li>
                    <a href="/mitra"
                        class="block py-2 px-3 hover:bg-tombol hover:text-tombol md:hover:bg-transparent md:border-0 md:hover:text-tombol rounded-sm md:bg-transparent md:text-white capitalize"
                        aria-current="page">
                        mitra
                    </a>
                </li>
                <li>
                    <a href="/academy"
                        class="block py-2 px-3 hover:bg-tombol hover:text-tombol md:hover:bg-transparent md:border-0 md:hover:text-tombol rounded-sm md:bg-transparent md:text-white capitalize">
                        academy
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="block py-2 px-3 text-white md:text-cardhitam capitalize rounded-sm hover:bg-tombol hover:text-tombol md:hover:bg-transparent md:hover:text-tombol">
                        artikel
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
{{-- end navbar --}}
