@extends('layouts.main')

@section('container')
    {{-- container setinggi layar penuh --}}
    <div class="container d-flex min-vh-100">
        {{-- konten utama diatas, tombol dibawah --}}
        <div class="row justify-content-center flex-grow-1">
            {{-- kolom untuk card --}}
            <div class="col-md-6 p-2 d-flex flex-column">
                <div class="card d-flex flex-column flex-grow-1">
                    {{-- <div class="card d-flex flex-column flex-grow-1"> --}}

                    {{-- Close button is placed early for better positioning context --}}
                    <a href="{{ route('menu', [
                        'table' => $tableNumber,
                        'selectedCategory' => $selectedCategory,
                        'search' => $search,
                    ]) }}"
                        class="btn btn-warning position-absolute end-0 top-0 m-2" style="z-index: 10;">
                        <i class="fa fa-close"></i>
                    </a>

                    {{-- Image section --}}
                    <div>
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->category->name }}"
                                class="card-img-top w-100 menu-image">
                        @else
                            <img src="{{ asset('img/notavailable.png') }}" alt="Image Not Available"
                                class="card-img-top w-100 menu-image">
                        @endif
                    </div>

                    {{-- Card body for text content and actions --}}
                    <div class="card-body d-flex flex-column flex-grow-1">
                        <h5 class="card-title">{{ $post['title'] }}</h5>
                        <p class="card-text border-bottom border-warning pb-2"> {!! $post->body !!}</p>
                        {{-- 
                        <div class="mt-auto"> --}}
                        <div class="row pb-2">
                            <div class="col-12">

                                {{-- Pushes the counter to the bottom --}}
                                {{-- <div class="mt-auto"> --}}
                                {{-- <div class="row d-flex align-items-center"> --}}
                                {{-- <div class="col-12"> --}}
                                {{-- @livewire('counter', ['postId' => $post->id, 'tableNumber' => $tableNumber]) --}}

                                {{-- ? Note Input --}}
                                <div class="mb-3">
                                    <label for="note" class="form-label">Catatan</label>
                                    <textarea id="note" class="form-control" rows="3" placeholder="(optional)"></textarea>
                                </div>
                                {{-- </div> --}}

                                <div
                                    style="display: grid; grid-template-columns: auto auto auto; gap: 0.5rem; align-items: center;">
                                    {{-- ? Custom Counter --}}
                                    <div style="white-space: nowrap;">
                                        <span id="price">Rp. </span>
                                    </div>
                                    <div class="d-flex align-items-center ms-auto">
                                        <button id="decrement-button" class="btn btn-light border-0 px-2">-</button>
                                        {{-- <input type="number" id="quantity" value="1" min="1"
                                        class="form-control mx-2" style="width: 80px;"> --}}
                                        <input id="quantity" value="1" min="1"
                                            class="form-control mx-1 fw-bold " style="width: 45px;">
                                        <button id="increment-button" class="btn btn-light border-0 px-2">+</button>
                                    </div>
                                    <div>
                                        <button id="add-button" class="btn btn-outline-warning text-dark ms-auto">Add</button>

                                        {{-- ? Add Button --}}
                                    </div>
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // * 1. Make sure the document is fully loaded before running the script
        document.addEventListener('DOMContentLoaded', function() {

            // * 2. Initialize the elements
            const noteInput = document.getElementById('note');
            const priceElement = document.getElementById('price');
            const incrementButton = document.getElementById('increment-button');
            const decrementButton = document.getElementById('decrement-button');
            const quantityInput = document.getElementById('quantity');
            const addButton = document.getElementById('add-button');

            // * 3. Set the initial price based on the post's price
            const initialPrice = {{ $post->price }};
            // priceElement.textContent = 'Rp. ' + initialPrice.toLocaleString('id-ID');
            priceElement.innerHTML = '<strong>Rp. ' + initialPrice.toLocaleString('id-ID') + '</strong>';


            // * 4. Sync the quantity and price with sessionStorage
            syncWithSessionStorage();

            function incrementQuantity() {
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
                updatePrice();
            }

            function decrementQuantity() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
                updatePrice();
            }

            function updatePrice() {
                const quantity = parseInt(quantityInput.value);
                const pricePerItem = {{ $post->price }};
                const totalPrice = pricePerItem * quantity;
                // priceElement.textContent = 'Rp. ' + totalPrice.toLocaleString('id-ID');
                priceElement.innerHTML = '<strong>Rp. ' + totalPrice.toLocaleString('id-ID') + '</strong>';

            }

            function saveToSessionStorage() {
                const quantity = parseInt(quantityInput.value);
                const note = noteInput.value.trim();
                const product = {
                    id: {{ $post->id }},
                    title: '{{ $post->title }}',
                    price: {{ $post->price }},
                    quantity: quantity,
                    note: note
                };

                // Retrieve the current cart from sessionStorage or initialize a new one
                let cart = JSON.parse(sessionStorage.getItem('cart')) || {};
                const tableNumber = '{{ $tableNumber }}';

                // Ensure the structure for the current table exists
                if (!cart[tableNumber]) {
                    cart[tableNumber] = {
                        items: {}
                    };
                }

                // Add or update the item in the cart
                if (quantity > 0) {
                    cart[tableNumber].items[product.id] = product;
                } else {
                    delete cart[tableNumber].items[product.id];
                }

                // Save the updated cart back to sessionStorage
                sessionStorage.setItem('cart', JSON.stringify(cart));
            }

            function syncWithSessionStorage() {
                // Retrieve the cart from sessionStorage
                let cart = JSON.parse(sessionStorage.getItem('cart')) || {};
                const tableNumber = '{{ $tableNumber }}';
                const currentProductId = {{ $post->id }};

                // Check if this specific product exists in the cart for this table
                if (cart[tableNumber] &&
                    cart[tableNumber].items &&
                    cart[tableNumber].items[currentProductId]) {
                    // Set quantity to this specific product's quantity
                    quantityInput.value = cart[tableNumber].items[currentProductId].quantity;
                    // Also set the note if available
                    if (cart[tableNumber].items[currentProductId].note) {
                        noteInput.value = cart[tableNumber].items[currentProductId].note;
                    }
                } else {
                    // Reset to default if this product is not in the cart
                    quantityInput.value = 1;
                }
                updatePrice();
            }

            incrementButton.addEventListener('click', incrementQuantity);
            decrementButton.addEventListener('click', decrementQuantity);
            addButton.addEventListener('click', function() {
                saveToSessionStorage();

                Swal.fire({
                    title: 'Item Added',
                    text: 'Your item has been added to the cart.',
                    icon: 'success',
                    width: "300px",
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        });
    </script>
@endpush
