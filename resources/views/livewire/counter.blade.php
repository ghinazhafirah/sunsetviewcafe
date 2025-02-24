<div class="d-flex border rounded align-items-center justify-content-center px-2" style="width: 120px;">
    <button wire:click="decrement" class="btn btn-light border-0 px-2">-</button>
    <span class="mx-2 fw-bold">{{ str_pad($count, 2, '0', STR_PAD_LEFT) }}</span>
    <button wire:click="increment" class="btn btn-light border-0 px-2">+</button>
</div>
