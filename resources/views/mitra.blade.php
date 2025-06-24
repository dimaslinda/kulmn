@extends('layouts.main')
@section('banner')
    <section class="relative min-h-[400px] md:min-h-[600px] flex items-center bg-black overflow-hidden">
        <img src="{{ asset('img/general/bg-mitra.webp') }}" alt="KULMN BARBER & SHOP"
            class="absolute inset-0 w-full h-full object-cover object-center opacity-60 z-0">
        <div
            class="container mx-auto relative z-10 flex flex-col md:flex-row items-center md:items-start px-6 py-20 md:py-24 xl:py-32">
            <div class="w-full md:w-1/2 text-white">
                <div class="font-poppins italic font-normal text-3xl md:text-5xl xl:text-7xl leading-tight mb-6">
                    BERGABUNG <br>
                    DENGAN <br>
                    MITRA KAMI
                </div>
                <div class="font-poppins text-sm md:text-base font-light mb-8 max-w-lg">
                    Bergabung sebagai mitra KULMN barbershop membuka peluang besar untuk berkembang bersama. Dapatkan
                    dukungan penuh, akses ke jaringan luas, dan sistem kolaborasi yang saling menguntungkan. Tumbuh dan maju
                    bersama KULMN.
                </div>
                <a href="https://wa.me/62895411871843" target="_blank"
                    class="inline-block border border-[#E9C664] text-[#E9C664] font-poppins text-xs md:text-sm px-8 py-3 rounded transition hover:bg-[#E9C664] hover:text-black font-semibold tracking-widest">
                    KONSULTASI SEKARANG
                </a>
            </div>
        </div>
    </section>
@endsection
@section('content')
@endsection
