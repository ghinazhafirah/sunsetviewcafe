@extends('layouts.main')

@section('container')
    {{-- container setinggi layar penuh --}}
    <div class="container d-flex min-vh-100">
        {{-- konten utama diatas, tombol dibawah --}}
        <div class="row justify-content-center flex-grow-1">
            {{-- kolom untuk card --}}
            <div class="col-md-6 p-2 d-flex flex-column">
                {{-- 
                    FIX 1: Removed redundant nested <div class="card">.
                    The outer div is now the only card container.
                --}}
                <div class="card d-flex flex-column flex-grow-1">

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
                            {{-- 
                                FIX 2: Combined the two `class` attributes into one.
                                Added w-100 for better responsiveness within the card.
                            --}}
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

                        {{-- Pushes the counter to the bottom --}}
                        <div class="mt-auto">
                            <div class="row d-flex align-items-center">
                                {{-- <div class="col-12"> --}}
                                {{-- @livewire('counter', ['postId' => $post->id, 'tableNumber' => $tableNumber]) --}}

                                {{-- ? Note Input --}}
                                <div class="mb-2">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea id="note" class="form-control" rows="3" placeholder="Add a note (optional)"></textarea>
                                </div>

                                {{-- ? Custom Counter --}}
                                <div class="col-md-4 col-5 d-flex">
                                    <span id="price">Rp. </span>
                                </div>
                                <div class="col-md-4 col-5 d-flex align-items-center ms-auto">
                                    <button id="decrement-button" class="btn btn-light border-0 px-2">-</button>
                                    {{-- <input type="number" id="quantity" value="1" min="1"
                                        class="form-control mx-2" style="width: 80px;"> --}}
                                    <input id="quantity" value="1" min="1"
                                        class="form-control mx-2 fw-bold " style="width: 45px;">
                                    <button id="increment-button" class="btn btn-light border-0 px-2">+</button>
                                </div>
                                <div class="col-md-4 px-2">

                                    {{-- ? Add Button --}}
                                    <button id="add-button" class="btn btn-outline-warning text-dark" style="justify-content-end">Add</button>
                                </div>
                                {{-- </div> --}}
                            </div>
                        </div>
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
            priceElement.textContent = 'Rp. ' + initialPrice.toLocaleString('id-ID');

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
                priceElement.textContent = 'Rp. ' + totalPrice.toLocaleString('id-ID');
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
                    confirmButtonText: 'OK'
                });
            });
        });

        /* document.addEventListener('livewire:init', () => {
                Livewire.on('updateCart', (event) => {
                    let rawPhpEvent = event;
                    let jsonString = JSON.stringify(rawPhpEvent, null, 2);
                    let jsonEvent = JSON.parse(jsonString);

                    console.log('Received event:', jsonEvent);

                    if (jsonEvent.length > 0) {
                        const tableNumber = jsonEvent[0].tableNumber;
                        const {
                            id,
                            quantity,
                            note
                        } = jsonEvent[0].product;

                        console.log('Table Number:', tableNumber);
                        console.log('Product ID:', id);
                        console.log('Quantity:', quantity);
                        console.log('Note:', note);

                        // * 1. Check cart in sessionStorage
                        let cart = JSON.parse(sessionStorage.getItem('cart')) || {};
                        console.log('Current Cart:', cart);

                        // * 2. If there's no cart for this table, create one
                        if (!cart[tableNumber]) {
                            cart[tableNumber] = {
                                items: {}
                            };
                        }
                        console.log('Cart for Table Number:', cart[tableNumber]);

                        // * 3. If the item already exists, update it; otherwise, add it
                        if (cart[tableNumber].items[id]) {
                            // Update existing item
                            cart[tableNumber].items[id].quantity += quantity;
                            cart[tableNumber].items[id].note = note; // Update note
                        } else {
                            // Add new item
                            cart[tableNumber].items[id] = {
                                quantity: quantity,
                                note: note
                            };
                        }
                        console.log('Updated Cart for Table Number:', cart[tableNumber]);

                        // * 4. Save the updated cart back to sessionStorage
                        sessionStorage.setItem('cart', JSON.stringify(cart));
                        console.log('Cart updated in sessionStorage:', JSON.parse(sessionStorage.getItem(
                            'cart')));
                    } else {
                        console.warn('Received an empty event:', jsonEvent);
                    }

                    // FIX 4: Implemented the logic to update sessionStorage.
                    // Assuming the event sends an array `[item, table_number, orderId]`
                    // const [item, tableNumber, orderId] = event;

                    // // Retrieve the existing cart from sessionStorage or initialize a new one
                    // let cart = JSON.parse(sessionStorage.getItem('cart')) || {};

                    // // Ensure the structure for the current order exists
                    // if (!cart[orderId]) {
                    //     cart[orderId] = {
                    //         table_number: tableNumber,
                    //         items: {}
                    //     };
                    // }

                    // // Add or update the item in the cart
                    // // If quantity is 0, you might want to remove the item
                    // if (item.quantity > 0) {
                    //     cart[orderId].items[item.id] = {
                    //         title: item.title,
                    //         price: item.price,
                    //         quantity: item.quantity,
                    //         total_menu: item.total_menu,
                    //         note: item.note
                    //     };
                    // } else {
                    //     // If quantity is zero, remove the item from the cart
                    //     delete cart[orderId].items[item.id];
                    // }

                    // // Save the updated cart back to sessionStorage
                    // sessionStorage.setItem('cart', JSON.stringify(cart));
                    // console.log('Cart updated in sessionStorage:', JSON.parse(sessionStorage.getItem('cart')));

                    // // Optionally, dispatch a custom JS event if other non-Livewire parts of your app need to react
                    // window.dispatchEvent(new CustomEvent('sessionCartUpdated', {
                    //     detail: {
                    //         cart: cart
                    //     }
                    // }));
                });
            }); */
    </script>
@endpush
