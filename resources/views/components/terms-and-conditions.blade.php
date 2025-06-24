<div class="flex-1 flex flex-col p-5 font-poppins">
    <h2 class="text-white text-3xl font-normal mb-10">SYARAT DAN KETENTUAN</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach ($terms as $term)
            <x-terms-card :title="$term['title']" :description="$term['description']" />
        @endforeach
    </div>
</div>
