<div id="cart-icon-component-wrapper">
    <a href="{{ route('cart.show', [
        'table' => $tableNumber,
        'selectedCategory' => $selectedCategory ?? null,
        'search' => $search ?? null,
    ]) }}"
        class="btn btn-light position-relative py-2">
        <i class="fas fa-shopping-cart" style="font-size: 20px"></i>

        {{-- Display the badge only if itemCount is greater than 0 --}}
        @if ($itemCount > 0)
            <span id="cart-item-count-badge"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{-- Display the count, with a max of 99+ --}}
                {{ $itemCount > 99 ? '99+' : $itemCount }}
            </span>
        @endif
    </a>
</div>


{{-- Push the JavaScript logic to the scripts stack --}}
{{-- @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // DEBUG: Confirm that the main Livewire listener has been attached successfully.
            console.log('[Cart Badge] Livewire initialized. Attaching event listeners.');

            // --- Step 0: Find the SPECIFIC Livewire component instance ---
            // THE FIX: Target the component by a unique ID on its root element. This is the most reliable method.
            const cartBadgeElement = document.getElementById('cart-icon-component-wrapper');
            let livewireComponent = null;

            if (cartBadgeElement && cartBadgeElement.getAttribute('wire:id')) {
                livewireComponent = Livewire.find(cartBadgeElement.getAttribute('wire:id'));
                console.log('[Cart Badge] 0. Found specific Livewire component instance by ID:', livewireComponent);
            } else {
                console.error(
                    '[Cart Badge] 0a. FATAL: Could not find the cart badge component element (#cart-icon-component-wrapper) or its wire:id attribute. Ensure the ID is correct in your Blade file.'
                    );
            }


            // This function will be our single source of truth for updating the cart icon.
            function updateCartIconCount(source = 'unknown') {
                // Log the start of the function call and its trigger
                console.log(`%c[Cart Badge] updateCartIconCount triggered by: ${source}`,
                    'color: blue; font-weight: bold;');

                try {
                    // --- Step 1: Get Data ---
                    const tableNumber = @json($tableNumber);
                    console.log('[Cart Badge] 1. Table number from Blade:', tableNumber);

                    const cartData = sessionStorage.getItem('cart');
                    console.log('[Cart Badge] 2. Raw data from sessionStorage:', cartData);

                    if (!cartData) {
                        console.warn('[Cart Badge] 2a. No cart data found in sessionStorage. Count will be 0.');
                    }

                    // --- Step 2: Parse and Validate Data ---
                    let count = 0;
                    let cart = null;

                    if (cartData) {
                        try {
                            cart = JSON.parse(cartData);
                            console.log('[Cart Badge] 3. Parsed cart object:', cart);
                        } catch (e) {
                            console.error(
                                '[Cart Badge] FATAL: Could not parse cart data from sessionStorage. It might be malformed JSON.',
                                e);
                            return; // Stop execution if JSON is invalid
                        }

                        if (cart && cart[tableNumber]) {
                            console.log(`[Cart Badge] 4. Found cart entry for table "${tableNumber}":`, cart[
                                tableNumber]);

                            if (cart[tableNumber].items && typeof cart[tableNumber].items === 'object') {
                                console.log(`[Cart Badge] 5. Found "items" object:`, cart[tableNumber].items);

                                const keys = Object.keys(cart[tableNumber].items);
                                count = keys.length;
                                console.log(`[Cart Badge] 6. Counted ${count} keys (items) in the cart.`);
                            } else {
                                console.warn(
                                    '[Cart Badge] 5a. Cart entry for this table exists, but the "items" property is missing or not an object.'
                                    );
                            }
                        } else {
                            console.warn(
                                `[Cart Badge] 4a. No cart entry found for table "${tableNumber}" in the parsed data.`
                                );
                        }
                    }

                    // --- Step 4: Update Livewire Component ---
                    // Now this will target the correct component instance.
                    if (livewireComponent) {
                        console.log(
                            `[Cart Badge] 7. Livewire component instance found. Setting "itemCount" to ${count}.`
                            );
                        livewireComponent.set('itemCount', count);
                    } else {
                        console.error(
                            '[Cart Badge] 7a. FATAL: Livewire component instance is not available. Cannot update component state.'
                            );
                    }

                    // --- Step 5: Update DOM Directly (for instant visual feedback) ---
                    const badge = document.getElementById('cart-item-count-badge');
                    console.log('[Cart Badge] 8. Attempting to find DOM element with ID "cart-item-count-badge".');

                    if (badge) {
                        console.log('[Cart Badge] 8a. Badge element found.');
                        if (count > 0) {
                            badge.style.display = 'inline-block';
                            badge.textContent = count > 99 ? '99+' : count;
                            console.log(
                                `[Cart Badge] 8b. Badge updated to be visible with text: "${badge.textContent}".`
                                );
                        } else {
                            badge.style.display = 'none';
                            console.log('[Cart Badge] 8b. Badge hidden because count is 0.');
                        }
                    } else {
                        console.warn(
                            '[Cart Badge] 8c. Could not find badge element with ID "cart-item-count-badge". DOM manipulation skipped.'
                            );
                    }

                } catch (error) {
                    console.error('[Cart Badge] An unexpected error occurred in the updateCartIconCount function:',
                        error);
                }
                console.log('%c[Cart Badge] Update cycle finished.', 'color: blue;');
            }

            // --- Call the update function at the right times, passing the source for logging ---

            // 1. Call it once when Livewire is initialized.
            updateCartIconCount('livewire:init');

            // 2. Call it when the user navigates back to the page (e.g., using the browser's back button).
            window.addEventListener('pageshow', () => {
                console.log('[Cart Badge] "pageshow" event detected.');
                updateCartIconCount('pageshow');
            });

            // 3. Listen for a custom event that other parts of your app can trigger.
            window.addEventListener('sessionCartUpdated', () => {
                console.log('[Cart Badge] "sessionCartUpdated" event detected.');
                updateCartIconCount('sessionCartUpdated');
            });
        });
    </script>
@endpush --}}
