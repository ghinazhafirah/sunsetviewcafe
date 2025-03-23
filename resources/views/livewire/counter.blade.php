<div class="row d-flex align-items-center">
    {{-- catatan pelanggan --}}
    <div class="mb-3">
        <label for="exampleDataList" class="form-label fw-bold">Catatan:</label>
        <textarea class="form-control border-warning" wire:model.defer="note" id="exampleDataList" rows="2"
            placeholder="Opsional" style="width: 100%;"></textarea>
    </div>
    <div class="col-md-4 col-5 d-flex">
        <span>Rp {{ number_format($post->price, 0, ',', '.') }}</span>
    </div>
    <div class="col-auto d-flex align-items-center ms-auto">
        <button wire:click="decrement" class="btn btn-light border-0 px-2">-</button>
        <span class="mx-2 fw-bold">{{ $count }}</span>
        <button wire:click="increment" class="btn btn-light border-0 px-2">+</button>
    </div>
    <div class="col-auto px-2">
        {{-- <button wire:click="addToCart({{ $post->id }})" class="btn btn-outline-warning text-dark">Add</button> --}}
        <button wire:click.prevent="addToCart({{ $postId }})" class="btn btn-outline-warning text-dark">Add</button>
    </div>
</div>

<script>
    window.addEventListener('alert', event => {
        Swal.fire({
            title: "Sukses!",
            text: event.detail.message,
            icon: event.detail.type,
            width: "300px", // Ukuran lebih kecil
            showConfirmButton: false, // Hilangkan tombol OK
            timer: 1500 // Alert akan hilang dalam 1.5 detik
        });
    });
</script>
