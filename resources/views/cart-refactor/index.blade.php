@extends('layouts.main')

@section('container')
    <div class="container my-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Keranjang Anda (Meja <span id="table-number-display"></span>)</h4>
                        <a href="{{ route('menu', ['table' => $tableNumber]) }}" class="btn btn-sm btn-light">
                            <i class="fa fa-plus me-1"></i> Tambah Menu
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="cart-items-container">
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Total:</h5>
                            <h5 id="total-price-display" class="fw-bold mb-0">Rp 0</h5>
                        </div>

                        <div>
                            <div class="mb-2">
                                <label for="customer-name" class="form-label">Nama Pelanggan</label>
                                <input type="text" id="customer-name" class="form-control"
                                    placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="mb-2">
                                <label for="customer-email" class="form-label">Email Pelanggan</label>
                                <input type="email" id="customer-email" class="form-control"
                                    placeholder="Masukkan email Anda" required>
                            </div>
                            <div class="mb-2">
                                <label for="customer-phone" class="form-label">Telepon Pelanggan</label>
                                <input type="tel" id="customer-phone" class="form-control"
                                    placeholder="Masukkan nomor telepon Anda" required>
                            </div>
                        </div>

                        <div class="d-flex flex-row gap-2 mb-2">
                            <button type="submit" id="cash-checkout-button" class="btn btn-primary w-100">
                                <i class="fa fa-money-bill me-1"></i>Pembayaran Tunai
                            </button>
                            <button type="submit" id="digital-checkout-button" class="btn btn-success w-100">
                                <i class="fa fa-wallet me-1"></i>Pembayaran Digital
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript"
        src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableNumber = @json($tableNumber);
            const cartItemsContainer = document.getElementById('cart-items-container');
            const totalPriceDisplay = document.getElementById('total-price-display');
            const tableNumberDisplay = document.getElementById('table-number-display');
            const cashCheckoutButton = document.getElementById('cash-checkout-button');
            const digitalCheckoutButton = document.getElementById('digital-checkout-button');

            function renderCart() {
                tableNumberDisplay.textContent = tableNumber;
                const cart = JSON.parse(sessionStorage.getItem('cart')) || {};
                const tableCart = cart[tableNumber] || {
                    items: {}
                };
                const items = tableCart.items;
                cartItemsContainer.innerHTML = '';

                const hasItems = Object.keys(items).length > 0;
                cashCheckoutButton.disabled = !hasItems;
                digitalCheckoutButton.disabled = !hasItems;

                if (!hasItems) {
                    cartItemsContainer.innerHTML =
                        '<p class="text-center text-muted">Keranjang Anda masih kosong.</p>';
                    totalPriceDisplay.textContent = formatRupiah(0);
                    return;
                }

                let totalPrice = 0;
                for (const itemId in items) {
                    const item = items[itemId];
                    totalPrice += item.price * item.quantity;
                    const itemElement = document.createElement('div');
                    itemElement.className =
                        'd-flex justify-content-between align-items-center border p-3 rounded-3 mb-2';
                    itemElement.innerHTML = `
                        <div class="me-3" style="flex-grow: 1;">
                            <h6 class="mb-0">${item.title}</h6>
                            <small class="text-muted">${formatRupiah(item.price)}</small>
                            ${item.note ? `<p class="text-muted p-0 m-0">${item.note}</p>` : `<p class="text-muted p-0 m-0"></p>`}
                        </div>
                        <div class="d-flex align-items-center flex-shrink-0">
                            <button class="btn btn-sm btn-outline-secondary" data-id="${itemId}" data-action="decrease">-</button>
                            <span class="mx-3 fw-bold">${item.quantity}</span>
                            <button class="btn btn-sm btn-outline-secondary" data-id="${itemId}" data-action="increase">+</button>
                            <button class="btn btn-sm btn-outline-danger ms-3" data-id="${itemId}" data-action="delete"><i class="fa fa-trash"></i></button>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemElement);
                }
                totalPriceDisplay.textContent = formatRupiah(totalPrice);
            }

            function modifyCart(itemId, action) {
                const cart = JSON.parse(sessionStorage.getItem('cart')) || {};
                if (!cart[tableNumber] || !cart[tableNumber].items[itemId]) return;
                const item = cart[tableNumber].items[itemId];
                switch (action) {
                    case 'increase':
                        item.quantity++;
                        break;
                    case 'decrease':
                        if (--item.quantity <= 0) delete cart[tableNumber].items[itemId];
                        break;
                    case 'delete':
                        delete cart[tableNumber].items[itemId];
                        break;
                }
                if (Object.keys(cart[tableNumber].items).length === 0) delete cart[tableNumber];
                sessionStorage.setItem('cart', JSON.stringify(cart));
                window.dispatchEvent(new Event('sessionCartUpdated'));
                renderCart();
            }

            function processPayment(paymentType, buttonElement) {
                buttonElement.disabled = true;
                buttonElement.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

                const originalButtonText = paymentType === 'digital' ?
                    '<i class="fa fa-wallet me-1"></i>Pembayaran Digital' :
                    '<i class="fa fa-money-bill me-1"></i>Pembayaran Tunai';

                const cart = JSON.parse(sessionStorage.getItem('cart')) || {};
                const tableCart = cart[tableNumber] || {
                    items: {}
                };
                if (Object.keys(tableCart.items).length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Keranjang Kosong',
                        text: 'Keranjang Anda kosong!',
                    });
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalButtonText;
                    return;
                }

                const customerName = document.getElementById('customer-name').value.trim();
                const customerEmail = document.getElementById('customer-email').value.trim();
                const customerPhone = document.getElementById('customer-phone').value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (customerName.length < 3) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Input Tidak Valid',
                        text: 'Nama pelanggan wajib diisi dan minimal 3 karakter.',
                    });
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalButtonText;
                    return;
                }

                if (!customerEmail || !emailRegex.test(customerEmail)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Input Tidak Valid',
                        text: 'Email pelanggan wajib diisi dengan format yang valid.',
                    });
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalButtonText;
                    return;
                }

                if (!customerPhone) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Input Tidak Valid',
                        text: 'Nomor telepon pelanggan wajib diisi.',
                    });
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalButtonText;
                    return;
                }

                const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenElement) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Konfigurasi',
                        text: 'Terjadi kesalahan konfigurasi. Silakan hubungi dukungan.',
                    });
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalButtonText;
                    return;
                }

                fetch('{{ route('api.payment.process') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfTokenElement.getAttribute('content')
                        },
                        body: JSON.stringify({
                            table: tableNumber,
                            payment_type: paymentType,
                            customer_name: customerName,
                            customer_email: customerEmail,
                            customer_phone: customerPhone,
                            items: tableCart.items
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(errorData.message ||
                                    `Server error: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Payment Response:', data);

                        if (data.error || data.status === 'error') throw new Error(data.message ||
                            'Gagal membuat token pembayaran.');

                        if (paymentType === 'cash') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pesanan Dibuat',
                                text: 'Pesanan tunai berhasil dibuat! Silakan bayar di kasir.',
                            }).then(() => {
                                window.dispatchEvent(new Event('sessionCartUpdated'));
                                renderCart();
                                buttonElement.disabled = false;
                                buttonElement.innerHTML = originalButtonText;

                                // * Navigasi ke halaman sukses checkout
                                window.location.href =
                                    '{{ route('checkout.success', ['order_id' => 'ORDER_ID_PLACEHOLDER']) }}'
                                    .replace('ORDER_ID_PLACEHOLDER', data.data.order_id);

                            });
                            return;
                        }

                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil',
                                    text: 'Terima kasih atas pembayaran Anda.',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => {
                                    delete cart[tableNumber];
                                    sessionStorage.setItem('cart', JSON.stringify(cart));
                                    window.dispatchEvent(new Event('sessionCartUpdated'));
                                    renderCart();
                                    document.getElementById('customer-name').value = '';
                                    document.getElementById('customer-email').value = '';
                                    document.getElementById('customer-phone').value = '';

                                    if (result.order_id) {
                                        window.location.href =
                                            '{{ route('checkout.success', ['order_id' => 'ORDER_ID_PLACEHOLDER']) }}'
                                            .replace('ORDER_ID_PLACEHOLDER', result
                                                .order_id);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Terjadi Kesalahan',
                                            text: 'order_id tidak tersedia setelah pembayaran berhasil.'
                                        });
                                    }
                                });
                            },
                            onPending: function(result) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Menunggu Pembayaran',
                                    text: 'Pembayaran Anda sedang menunggu penyelesaian.',
                                });
                                buttonElement.disabled = false;
                                buttonElement.innerHTML = originalButtonText;
                            },
                            onError: function(result) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran Gagal',
                                    text: 'Pembayaran gagal. Silakan coba lagi.',
                                });
                                buttonElement.disabled = false;
                                buttonElement.innerHTML = originalButtonText;
                            },
                            onClose: function() {
                                buttonElement.disabled = false;
                                buttonElement.innerHTML = originalButtonText;
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: error.message,
                        });
                        buttonElement.disabled = false;
                        buttonElement.innerHTML = originalButtonText;
                    });
            }

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }

            cartItemsContainer.addEventListener('click', function(e) {
                const button = e.target.closest('button');
                if (button?.dataset.action) modifyCart(button.dataset.id, button.dataset.action);
            });

            digitalCheckoutButton.addEventListener('click', function() {
                processPayment('digital', this);
            });

            cashCheckoutButton.addEventListener('click', function() {
                processPayment('cash', this);
            });

            renderCart();
        });
    </script>
@endpush
