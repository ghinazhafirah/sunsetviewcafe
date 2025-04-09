@extends('dashboard.layouts.main')


@section('container')
    <div class="container my-5">
        {{-- <div class="bg-light rounded p-4 w-100 my-5"> --}}
        <h2 class="mb-3 mt-2">NEW MENU</h2>

        <div class="col-lg-8">
            <form method="post" action="/dashboard/posts" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Nama Menu</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" required value="{{ old('title') }}">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    {{-- ini untuk slugnya bisa di edit/read only --}}
                    {{-- <input type="text" class="form-control" id="slug" name="slug" disabled readonly> --}}
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                        name="slug" required value="{{ old('slug') }}">
                    @error('slug')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga Menu</label>
                    <input type="number" class="form-control" name="price" id="price"
                        value="{{ old('price', $post->price ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Kategori Menu</label>
                    <select class="form-select" name="category_id">
                        @foreach ($categories as $category)
                            @if (old('category_id') == $category->id)
                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                            @else
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Ketersediaan Menu?</label>
                    <div class="d-flex gap-3 align-items-center">
                        <!-- Available -->
                        <div class="d-flex align-items-center gap-2">
                            <input type="radio" class="btn-check" name="status" id="available" value="available"
                                {{ old('status', $post->status ?? '') == 'available' ? 'checked' : '' }}>
                            <label
                                class="btn btn-outline-success rounded-circle d-flex align-items-center justify-content-center"
                                for="available" style="width: 30px; height: 30px;">
                                <i class="bi bi-check-lg text-success"></i>
                            </label>
                            <span class="text-success">Available</span>
                        </div>

                        <!-- Not Available -->
                        <div class="d-flex align-items-center gap-2">
                            <input type="radio" class="btn-check" name="status" id="not_available" value="not_available"
                                {{ old('status', $post->status ?? '') == 'not_available' ? 'checked' : '' }}>
                            <label
                                class="btn btn-outline-danger rounded-circle d-flex align-items-center justify-content-center"
                                for="not_available" style="width: 30px; height: 30px;">
                                <i class="bi bi-x-lg text-danger"></i>
                            </label>
                            <span class="text-danger">Not Available</span>
                        </div>
                    </div>
                </div>


                <div class="mb-3">
                    <label class="form-label">Tandai sebagai Menu Favorit?</label>
                    <div class="d-flex gap-3 align-items-center">
                        <!-- Tidak Favorit -->
                        <div class="d-flex align-items-center gap-2">
                            <input type="radio" class="btn-check" name="favorite" id="favorite_no" value="0"
                                {{ old('favorite', $post->favorite ?? 0) == 0 ? 'checked' : '' }}>
                            <label
                                class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
                                for="favorite_no" style="width: 30px; height: 30px;">
                                <i class="bi bi-heart text-secondary"></i>
                            </label>
                            <span class="text-secondary">Tidak</span>
                        </div>

                        <!-- Favorit -->
                        <div class="d-flex align-items-center gap-2">
                            <input type="radio" class="btn-check" name="favorite" id="favorite_yes" value="1"
                                {{ old('favorite', $post->favorite ?? 0) == 1 ? 'checked' : '' }}>
                            <label
                                class="btn btn-outline-danger rounded-circle d-flex align-items-center justify-content-center"
                                for="favorite_yes" style="width: 30px; height: 30px;">
                                <i class="bi bi-heart-fill text-danger"></i>
                            </label>
                            <span class="text-danger">Ya</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Foto Menu</label>
                    <img class="img-preview img-fluid mb-3 col-sm-5">
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image"
                        name="image" onchange="previewImage()">
                    @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="excerpt" class="form-label">Deskripsi Menu</label>
                    <input type="body" class="form-control @error('body') is-invalid @enderror" type="hidden"
                        name="body" required value="{{ old('body') }}">
                    @error('body')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-warning">Create Menu</button>
            </form>
        </div>
    </div>
    
    <script>
        //JS Lanjutan Fetch API 
        const title = document.querySelector('#title');
        const slug = document.querySelector('#slug');

        //event handler yg menangani ketika yg dituliskan dalam title itu berubah, jadi butuh eventnya change
        title.addEventListener('change', function() {
            fetch('/dashboard/posts/checkSlug?title=' + title.value) ////part 18 (17.08)
                .then(response => response.json())
                .then(data => slug.value = data.slug)

        });


        // CATATAN FUNGSI PREVIEW GAMBAR// PART 22 6.05
        function previewImage() {
            // frame.src=URL.createObjectURL(event.target.file[0]);
            const image = document.querySelector('#image');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
            const blob = URL.createObjectURL(image.files[0]);
            imgPreview.src = blob;

        }
    </script>
@endsection
