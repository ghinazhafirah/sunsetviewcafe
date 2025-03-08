<div class="row d-flex align-items-center">
    <div class="col-auto p-0 d-flex align-items-center">
        <button wire:click="decrement" class="btn btn-light border-0 px-2">-</button>
        <input type="text" wire:model="count" class="form-control text-center mx-1" style="width: 50px;" readonly>
        <button wire:click="increaseCount" class="btn btn-light border-0 px-2">+</button>
    </div>
</div>
