@extends('dashboard.layouts.main') {{-- mengambil menggunakan layout main.blade --}}

@section('container')
    <!-- apapun yang ada didalam section akan menggantikan yield -->

    <!-- Tabel Pendataan Menu Start -->
    <div class="bg-light rounded mt-4 p-3">

        {{-- Notifikasi --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="mb-3">DATA MENU</h3>
        </div>

        <div class="row g-2 align-items-center mb-3">
            <!-- Tombol New Menu -->
            <div class="col-12 col-md-auto">
                <a href="/dashboard/posts/create" class="btn btn-warning w-100"><i class="bi bi-plus-square"></i> New
                    Menu</a>
            </div>

            <!-- Form Filter -->
            <div class="col-12 col-md">
                <form action="{{ route('posts.index') }}" method="GET" class="d-flex flex-column flex-md-row gap-2">
                    <select name="filter" class="form-select">
                        <option value="">Semua Menu</option>
                        <option value="fav_available" {{ request('filter') == 'fav_available' ? 'selected' : '' }}>Available
                            & Hanya Favorit</option>
                        <option value="fav_not_available" {{ request('filter') == 'fav_not_available' ? 'selected' : '' }}>
                            Not Available & Hanya Favorit</option>
                        <option value="not_fav_available" {{ request('filter') == 'not_fav_available' ? 'selected' : '' }}>
                            Available & Biasa</option>
                        <option value="not_fav_not_available"
                            {{ request('filter') == 'not_fav_not_available' ? 'selected' : '' }}>Not Available & Biasa
                        </option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark text-center">
                        <th scope="col">No.</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Status</th>
                        <th scope="col">Rekomendasi</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $post->title }}</td>
                            <td>Rp {{ number_format($post->price, 0, ',', '.') }}</td>
                            <td>{{ $post->category->name }}</td>
                            <td>
                                @if ($post->status == 'available')
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Not Available</span>
                                @endif
                            </td>
                            <td>
                                @if ($post->favorite)
                                    <span class="badge bg-success">Favorit</span>
                                @else
                                    <span class="badge bg-secondary">Biasa</span>
                                @endif
                            </td>
                            <td>
                                <a href="/dashboard/posts/{{ $post->slug }}" class="btn badge bg-info"><i
                                        class="bi bi-eye"></i></a>
                                <a href="/dashboard/posts/{{ $post->slug }}/edit" class="btn badge bg-warning"><i
                                        class="bi bi-pencil-square"></i></a>
                                <form action="/dashboard/posts/{{ $post->slug }}" method="post" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button class="btn badge bg-danger"
                                        onclick="return confirm('Apakah tetap menghapus Menu?')"><i
                                            class="bi bi-x-square"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Pagination -->
    <div class="pagination mt-3 d-flex justify-content-center">
        {{ $posts->links('vendor.pagination.index') }}
    </div>
    <!-- Tabel Pendataan Menu End -->
@endsection
