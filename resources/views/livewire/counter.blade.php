<div class="row d-flex justify-content-end align-items-center">
    <div class="col-auto p-0 d-flex align-items-center">
        <button wire:click="decrement" class="btn btn-light border-0 px-2">-</button>
        <span class="mx-2 fw-bold">{{ $count }}</span>
        {{-- <input type="text" wire:model={{ $count }} class="form-control text-center mx-1" style="width: 50px;" readonly> --}}
        <button wire:click="increment" class="btn btn-light border-0 px-2">+</button>
    </div>
    <div class="col-auto px-2">
        <button wire:click="addToCart" class="btn btn-outline-warning ms-2 text-dark">Add</button>
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
