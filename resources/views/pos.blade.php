<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop POS @isset($selectedBranchCode)
            - {{ $selectedBranchCode }}
        @endisset
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="selected-branch-id" content="{{ $selectedBranchId ?? '' }}">
    <meta name="selected-branch-code" content="{{ $selectedBranchCode ?? '' }}">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- ToastJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="p-8 font-poppins">
    <div class="max-w-6xl mx-auto p-8 rounded-lg shadow-md">
        <div class="flex items-start mb-6">
            <div class="w-full border-b border-hitam">
                <div class="flex justify-between">
                    <h1 class="text-3xl font-bold mb-5 font-poppins">Pilih Layanan</h1>

                    <button data-modal-target="popup-modal" data-modal-toggle="popup-modal"
                        class="bg-tombol hover:bg-tombol/80 text-hitam cursor-pointer font-bold py-2 px-4 rounded"
                        type="button">
                        logout
                    </button>

                    <div id="popup-modal" tabindex="-1"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <div class="relative bg-tombol rounded-lg shadow-sm">
                                <button type="button"
                                    class="absolute top-3 end-2.5 text-hitam bg-transparent cursor-pointer text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                    data-modal-hide="popup-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <div class="p-4 md:p-5 text-center">
                                    <svg class="mx-auto mb-4 text-red-500 w-12 h-12" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-hitam">
                                        apakah anda yakin ingin logout?
                                    </h3>
                                    <div class="flex justify-center">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-400 text-white cursor-pointer font-bold py-2.5 px-5 ms-3 rounded">
                                                Logout
                                            </button>
                                        </form>
                                        <button data-modal-hide="popup-modal" type="button"
                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none cursor-pointer bg-hitam rounded hover:bg-hitam/80">
                                            No, cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-1/2 space-x-4 mb-4">
                    <div class="w-full">
                        <button id="service-tab"
                            class="px-6 py-2 w-full cursor-pointer font-semibold text-white bg-tombol">SERVICE</button>
                    </div>
                    <div class="w-full">
                        <button id="product-tab"
                            class="px-6 py-2 w-full cursor-pointer font-semibold text-gray-700">PRODUCT</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8">
            <!-- Left Column: Service/Product List -->
            <div>
                <div id="service-content" class="space-y-2">
                    <p>Loading services...</p>
                </div>
                <div id="product-content" class="space-y-2 hidden">
                    <p>Loading products...</p>
                </div>
            </div>

            <!-- Right Column: Cart Summary -->
            <div>
                <div class="border-b pb-2 mb-4">
                    <h3 class="font-semibold text-gray-700">PRODUCT</h3>
                </div>
                <div id="cart-summary-items" class="space-y-2 mb-4">
                    <!-- Cart items will be rendered here -->
                    <p class="text-gray-500">Keranjang kosong.</p>
                </div>

                <div class="flex justify-between items-center border-t pt-4 mt-4">
                    <span class="text-xl font-bold">Total</span>
                    <span id="cart-total-summary" class="text-xl font-bold">Rp 0</span>
                </div>

                <div class="mt-6">
                    <button id="qris-pay-button"
                        class="w-full bg-hitam cursor-pointer text-white py-3 px-4 rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-opacity-50">
                        BAYAR DENGAN QRIS
                    </button>
                    <button id="cash-pay-button"
                        class="w-full bg-tombol cursor-pointer text-hitam py-3 px-4 rounded-md hover:bg-tombol/80 mt-2">
                        BAYAR TUNAI
                    </button>
                </div>

                <div id="cash-payment-section" class="mt-6 p-4 border border-gray-300 rounded-md hidden">
                    <h4 class="font-semibold mb-3">Pembayaran Tunai</h4>
                    <div class="mb-3">
                        <label for="amount-paid" class="block text-sm font-medium text-gray-700">Jumlah Dibayar
                            (Rp)</label>
                        <input type="number" id="amount-paid"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm p-2"
                            placeholder="0">
                    </div>
                    <div class="mb-3">
                        <label for="change-amount" class="block text-sm font-medium text-gray-700">Kembalian
                            (Rp)</label>
                        <input type="text" id="change-amount"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 sm:text-sm p-2"
                            readonly>
                    </div>
                    <button id="process-cash-payment"
                        class="w-full bg-hitam cursor-pointer text-white py-3 px-4 rounded-md hover:bg-hitam/80 mt-2">
                        PROSES PEMBAYARAN TUNAI
                    </button>
                </div>

                <div id="payment-result"
                    class="mt-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md hidden">
                    <p id="result-message" class="font-semibold"></p>
                    <p id="result-invoice"></p>
                    <p id="result-total"></p>
                    <p id="result-status"></p>
                    <div id="qr-code-display" class="mt-4 text-center">
                        <img id="qr-image" src="" alt="QRIS Code"
                            class="mx-auto w-48 h-48 border border-gray-300 rounded-md">
                        <p class="text-sm text-gray-600 mt-2">Scan QRIS ini untuk pembayaran.</p>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Status pembayaran akan diperbarui secara otomatis.</p>
                    <a href="/" class="block mt-4 text-center text-blue-600 hover:underline">Buat Transaksi
                        Baru</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembayaran Tunai -->
    <div id="cash-confirm-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-hitam rounded-lg shadow-sm">
                <button type="button"
                    class="absolute top-3 end-2.5 text-white bg-transparent cursor-pointer text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="cash-confirm-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-yellow-500 w-12 h-12" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-2 text-lg font-semibold text-white">Konfirmasi Pembayaran Tunai</h3>
                    <p id="cash-confirm-text" class="mb-5 text-base text-white"></p>
                    <div class="flex justify-center">
                        <button id="confirm-cash-pay-btn" type="button"
                            class="bg-green-500 hover:bg-green-400 text-white cursor-pointer font-bold py-2.5 px-5 ms-3 rounded">
                            Ya, proses
                        </button>
                        <button data-modal-hide="cash-confirm-modal" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none cursor-pointer bg-tombol rounded hover:bg-tombol/80">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <!-- ToastJS JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Toast notification utility functions
        function showToast(message, type = 'info') {
            const colors = {
                success: 'linear-gradient(to right, #e9c664, #f4d03f)',
                error: 'linear-gradient(to right, #dc2626, #ef4444)',
                warning: 'linear-gradient(to right, #f59e0b, #fbbf24)',
                info: 'linear-gradient(to right, #191919, #374151)'
            };

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: colors[type] || colors.info,
                stopOnFocus: true,
                close: true,
                style: {
                    fontFamily: 'Poppins, sans-serif',
                    fontSize: '14px',
                    fontWeight: '500',
                    color: type === 'success' ? '#191919' : '#ffffff',
                    borderRadius: '8px',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.15)'
                }
            }).showToast();
        }

        // Security utility function untuk sanitasi HTML
        function sanitizeHTML(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Security utility function untuk validasi input
        function validateInput(input, maxLength = 255) {
            if (typeof input !== 'string') return '';
            return input.trim().substring(0, maxLength);
        }

        let services = [];
        let products = [];
        let cart = {}; // { itemId: { quantity: X, type: 'service'|'product', name: '...', price: ... } }
        let paymentPollingInterval;
        let currentInvoiceNumber = '';

        const selectedBranchId = document.querySelector('meta[name="selected-branch-id"]').getAttribute('content');
        const selectedBranchCode = document.querySelector('meta[name="selected-branch-code"]').getAttribute('content');


        document.addEventListener('DOMContentLoaded', async () => {
            await fetchData();
            updateCartDisplay();
        });

        async function fetchData() {
            try {
                const serviceResponse = await fetch('/api/services');
                if (!serviceResponse.ok) {
                    throw new Error(`HTTP error! status: ${serviceResponse.status} from /api/services`);
                }
                const serviceData = await serviceResponse.json();
                services = serviceData;

                const productResponse = await fetch('/api/products'); // Assuming a /api/products endpoint
                if (!productResponse.ok) {
                    throw new Error(`HTTP error! status: ${productResponse.status} from /api/products`);
                }
                const productData = await productResponse.json();
                products = productData;

                renderItems(services, 'service-content', 'service');
                attachItemListeners('service-content');

                renderItems(products, 'product-content', 'product');
                attachItemListeners('product-content');

                // Set initial tab state
                document.getElementById('service-tab').click();

                // Tab functionality
                document.getElementById('service-tab').addEventListener('click', () => {
                    currentTab = 'service';
                    document.getElementById('service-content').classList.remove('hidden');
                    document.getElementById('product-content').classList.add('hidden');
                    document.getElementById('service-tab').classList.add('bg-tombol', 'text-white');
                    document.getElementById('service-tab').classList.remove('bg-gray-200', 'text-gray-700');
                    document.getElementById('product-tab').classList.remove('bg-tombol', 'text-white');
                    document.getElementById('product-tab').classList.add('bg-gray-200', 'text-gray-700');
                    updateItemStates();
                });

                document.getElementById('product-tab').addEventListener('click', () => {
                    currentTab = 'product';
                    document.getElementById('product-content').classList.remove('hidden');
                    document.getElementById('service-content').classList.add('hidden');
                    document.getElementById('product-tab').classList.add('bg-tombol', 'text-white');
                    document.getElementById('product-tab').classList.remove('bg-gray-200', 'text-gray-700');
                    document.getElementById('service-tab').classList.remove('bg-tombol', 'text-white');
                    document.getElementById('service-tab').classList.add('bg-gray-200', 'text-gray-700');
                    updateItemStates();
                });

            } catch (error) {
                console.error('Error fetching data:', error);
                // Menggunakan textContent untuk menghindari XSS
                const serviceContent = document.getElementById('service-content');
                const productContent = document.getElementById('product-content');

                serviceContent.textContent = '';
                const serviceError = document.createElement('p');
                serviceError.className = 'text-red-500';
                serviceError.textContent = 'Gagal memuat data. Silakan coba lagi nanti.';
                serviceContent.appendChild(serviceError);

                productContent.textContent = '';
                const productError = document.createElement('p');
                productError.className = 'text-red-500';
                productError.textContent = 'Gagal memuat data. Silakan coba lagi nanti.';
                productContent.appendChild(productError);

                showToast('Gagal memuat data layanan dan produk', 'error');
            }
        }

        let currentTab = 'service'; // 'service' or 'product'

        function renderItems(items, containerId, type) {
            const container = document.getElementById(containerId);
            container.textContent = ''; // Menggunakan textContent untuk keamanan

            if (items.length === 0) {
                const noItemsMsg = document.createElement('p');
                noItemsMsg.className = 'text-gray-500';
                noItemsMsg.textContent = `Belum ada ${type} yang ditambahkan. Silakan tambahkan melalui panel admin.`;
                container.appendChild(noItemsMsg);
                return;
            }

            items.forEach(item => {
                const itemCard = document.createElement('div');
                itemCard.className =
                    'service-item bg-white p-4 border border-gray-300 rounded-md flex justify-between items-center cursor-pointer';
                itemCard.dataset.id = item.id;
                itemCard.dataset.type = type; // 'service' or 'product'

                // Menggunakan createElement dan textContent untuk menghindari XSS
                const itemName = document.createElement('div');
                const itemTitle = document.createElement('h3');
                itemTitle.className = 'text-lg font-medium';
                itemTitle.textContent = validateInput(item.name);
                itemName.appendChild(itemTitle);

                const itemPrice = document.createElement('div');
                itemPrice.className = 'font-semibold';
                itemPrice.textContent = `Rp ${parseFloat(item.price).toLocaleString('id-ID')}`;

                itemCard.appendChild(itemName);
                itemCard.appendChild(itemPrice);
                container.appendChild(itemCard);
            });

            attachItemListeners();
            updateItemStates();
        }

        function attachItemListeners() {
            document.querySelectorAll('.service-item').forEach(item => {
                item.removeEventListener('click', handleItemClick);
                item.addEventListener('click', handleItemClick);
            });
        }

        function handleItemClick(e) {
            const id = e.currentTarget.dataset.id;
            const type = e.currentTarget.dataset.type;
            const uniqueId = `${type}-${id}`;
            const item = (type === 'service' ? services : products).find(s => s.id == id);

            if (cart[uniqueId]) {
                // If already in cart, do nothing on click, quantity is controlled by +/- buttons
            } else {
                cart[uniqueId] = {
                    quantity: 1,
                    type: type,
                    name: item.name,
                    price: item.price,
                    originalId: id
                }; // Store type, name, price, and originalId

                showToast(`${validateInput(item.name)} ditambahkan ke keranjang`, 'success');
            }
            updateCartDisplay();
            updateItemStates();
        }

        function updateCartDisplay() {
            const cartSummaryItemsDiv = document.getElementById('cart-summary-items');
            const cartTotalSummarySpan = document.getElementById('cart-total-summary');
            let total = 0;
            cartSummaryItemsDiv.textContent = ''; // Menggunakan textContent untuk keamanan

            if (Object.keys(cart).length === 0) {
                const emptyMsg = document.createElement('p');
                emptyMsg.className = 'text-gray-500';
                emptyMsg.textContent = 'Keranjang kosong.';
                cartSummaryItemsDiv.appendChild(emptyMsg);
                cartTotalSummarySpan.textContent = 'Rp 0';
                updateItemStates();
                return;
            }

            for (const uniqueId in cart) {
                const itemInCart = cart[uniqueId];
                const quantity = itemInCart.quantity;
                const itemTotal = itemInCart.price * quantity;
                total += itemTotal;

                const cartItemDiv = document.createElement('div');
                cartItemDiv.className = 'flex justify-between items-center';

                // Menggunakan createElement dan textContent untuk menghindari XSS
                const itemName = document.createElement('span');
                itemName.className = 'text-gray-700';
                itemName.textContent = validateInput(itemInCart.name);

                const itemControls = document.createElement('div');
                itemControls.className = 'flex items-center space-x-2';

                const itemPrice = document.createElement('span');
                itemPrice.className = 'font-semibold';
                itemPrice.textContent = `Rp ${parseFloat(itemTotal).toLocaleString('id-ID')}`;

                const decreaseBtn = document.createElement('button');
                decreaseBtn.className = 'quantity-control-btn decrease-quantity cursor-pointer';
                decreaseBtn.dataset.id = uniqueId;
                decreaseBtn.textContent = '-';

                const quantitySpan = document.createElement('span');
                quantitySpan.className = 'quantity-display';
                quantitySpan.textContent = quantity;

                const increaseBtn = document.createElement('button');
                increaseBtn.className = 'quantity-control-btn increase-quantity cursor-pointer';
                increaseBtn.dataset.id = uniqueId;
                increaseBtn.textContent = '+';

                itemControls.appendChild(itemPrice);
                itemControls.appendChild(decreaseBtn);
                itemControls.appendChild(quantitySpan);
                itemControls.appendChild(increaseBtn);

                cartItemDiv.appendChild(itemName);
                cartItemDiv.appendChild(itemControls);
                cartSummaryItemsDiv.appendChild(cartItemDiv);
            }
            cartTotalSummarySpan.textContent = `Rp ${parseFloat(total).toLocaleString('id-ID')}`;

            document.querySelectorAll('.increase-quantity').forEach(button => {
                button.addEventListener('click', (e) => {
                    const uniqueId = e.target.dataset.id;
                    cart[uniqueId].quantity++;
                    updateCartDisplay();
                    updateItemStates();
                    showToast(`Jumlah ${validateInput(cart[uniqueId].name)} ditambah`, 'info');
                });
            });

            document.querySelectorAll('.decrease-quantity').forEach(button => {
                button.addEventListener('click', (e) => {
                    const uniqueId = e.target.dataset.id;
                    if (cart[uniqueId].quantity > 1) {
                        cart[uniqueId].quantity--;
                        showToast(`Jumlah ${validateInput(cart[uniqueId].name)} dikurangi`, 'info');
                    } else {
                        const itemName = validateInput(cart[uniqueId].name);
                        delete cart[uniqueId];
                        showToast(`${itemName} dihapus dari keranjang`, 'warning');
                    }
                    updateCartDisplay();
                    updateItemStates();
                });
            });
        }

        function updateItemStates() {
            document.querySelectorAll('.service-item').forEach(item => {
                const itemId = item.dataset.id;
                const itemType = item.dataset.type;
                const uniqueId = `${itemType}-${itemId}`;

                if (cart[uniqueId] && cart[uniqueId].type === itemType) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });
        }


        async function checkPaymentStatus() {
            if (!currentInvoiceNumber) return;

            try {
                const response = await fetch(`/api/transaction-status/${currentInvoiceNumber}`);
                const data = await response.json();

                if (data.status === 'success') {
                    document.getElementById('result-status').textContent = `Status: ${data.payment_status}`;
                    const paymentResultDiv = document.getElementById('payment-result');
                    paymentResultDiv.classList.remove('bg-yellow-100', 'border-yellow-400', 'text-yellow-700',
                        'bg-red-100', 'border-red-400', 'text-red-700');
                    paymentResultDiv.classList.add('bg-green-100', 'border-green-400', 'text-green-700');
                    document.getElementById('qr-code-display').style.display = 'none';
                    clearInterval(paymentPollingInterval);
                    showToast('Pembayaran Berhasil!', 'success');
                } else if (data.status === 'expire' || data.status === 'failed' || data.status === 'cancelled') {
                    document.getElementById('result-status').textContent = `Status: ${data.payment_status}`;
                    const paymentResultDiv = document.getElementById('payment-result');
                    paymentResultDiv.classList.remove('bg-yellow-100', 'border-yellow-400', 'text-yellow-700',
                        'bg-green-100', 'border-green-400', 'text-green-700');
                    paymentResultDiv.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
                    document.getElementById('qr-code-display').style.display = 'none';
                    clearInterval(paymentPollingInterval);
                    showToast('Pembayaran Gagal atau Kadaluarsa!', 'error');
                } else {
                    document.getElementById('result-status').textContent = `Status: ${data.payment_status}`;
                }
            } catch (error) {
                console.error('Error polling payment status:', error);
            }
        }

        document.getElementById('qris-pay-button').addEventListener('click', async () => {
            // Existing QRIS payment logic
        });

        document.getElementById('cash-pay-button').addEventListener('click', () => {
            const cashPaymentSection = document.getElementById('cash-payment-section');
            const qrisPayButton = document.getElementById('qris-pay-button');
            // const payButton = document.getElementById('pay-button'); // This is the old pay-button, now qris-pay-button

            if (cashPaymentSection.classList.contains('hidden')) {
                cashPaymentSection.classList.remove('hidden');
                qrisPayButton.classList.add('hidden');
                // payButton.classList.add('hidden'); // Hide the QRIS button when cash is selected
            } else {
                cashPaymentSection.classList.add('hidden');
                qrisPayButton.classList.remove('hidden');
                // payButton.classList.remove('hidden'); // Show the QRIS button when cash is deselected
            }
        });

        document.getElementById('amount-paid').addEventListener('input', () => {
            const amountPaidInput = document.getElementById('amount-paid');
            const changeAmountInput = document.getElementById('change-amount');
            const totalAmount = parseFloat(document.getElementById('cart-total-summary').textContent.replace('Rp ',
                '').replace(/\./g, '').replace(/,/g, '.'));
            const amountPaid = parseFloat(amountPaidInput.value);

            if (!isNaN(amountPaid) && amountPaid >= totalAmount) {
                const change = amountPaid - totalAmount;
                changeAmountInput.value = `Rp ${change.toLocaleString('id-ID')}`;
            } else if (!isNaN(amountPaid) && amountPaid < totalAmount) {
                changeAmountInput.value = `Kurang Rp ${(totalAmount - amountPaid).toLocaleString('id-ID')}`;
            } else {
                changeAmountInput.value = '';
            }
        });

        document.getElementById('process-cash-payment').addEventListener('click', async (e) => {
            e.preventDefault();
            const amountPaidInput = document.getElementById('amount-paid');
            const totalAmount = parseFloat(document.getElementById('cart-total-summary').textContent.replace(
                'Rp ', '').replace(/\./g, '').replace(/,/g, '.'));
            const amountPaid = parseFloat(amountPaidInput.value);

            if (Object.keys(cart).length === 0) {
                showToast('Keranjang belanja kosong. Silakan tambahkan layanan terlebih dahulu.', 'warning');
                return;
            }

            if (isNaN(amountPaid) || amountPaid < totalAmount) {
                showToast('Jumlah pembayaran tunai tidak mencukupi atau tidak valid.', 'error');
                return;
            }

            // Tampilkan modal konfirmasi
            const modal = document.getElementById('cash-confirm-modal');
            const modalText = document.getElementById('cash-confirm-text');
            modalText.textContent =
                `Jumlah dibayar: Rp ${amountPaid.toLocaleString('id-ID')}\nTotal belanja: Rp ${totalAmount.toLocaleString('id-ID')}\n\nApakah Anda yakin ingin memproses pembayaran tunai?`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Simpan data sementara untuk proses
            window._cashPayTemp = {
                amountPaid,
                totalAmount
            };
        });

        // Proses jika user klik "Ya, proses" di modal
        document.getElementById('confirm-cash-pay-btn').addEventListener('click', async () => {
            const amountPaid = window._cashPayTemp.amountPaid;
            const totalAmount = window._cashPayTemp.totalAmount;
            const amountPaidInput = document.getElementById('amount-paid');

            const itemsToSend = [];
            for (const itemId in cart) {
                const itemInCart = cart[itemId];
                itemsToSend.push({
                    id: itemInCart.originalId,
                    quantity: itemInCart.quantity,
                    type: itemInCart.type
                });
            }

            if (!selectedBranchId || selectedBranchId === 'null' || selectedBranchCode === 'UNASSIGNED') {
                showToast('Tidak dapat membuat transaksi: Akun Anda tidak terhubung ke cabang yang valid.',
                    'error');
                return;
            }

            document.getElementById('process-cash-payment').disabled = true;
            document.getElementById('process-cash-payment').textContent = 'Memproses...';

            try {
                const response = await fetch('/api/create-cash-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        items: itemsToSend,
                        branch_id: selectedBranchId,
                        amount_paid: amountPaid,
                        total_amount: totalAmount
                    })
                });

                const result = await response.json();
                const paymentResultDiv = document.getElementById('payment-result');

                if (response.ok) {
                    document.getElementById('result-message').textContent = result.message;
                    document.getElementById('result-invoice').textContent =
                        `Nomor Invoice: ${result.invoice_number}`;
                    document.getElementById('result-total').textContent =
                        `Jumlah Total: Rp ${parseFloat(result.total_amount).toLocaleString('id-ID')}`;
                    document.getElementById('result-status').textContent =
                        `Status: ${result.transaction_status}`;
                    document.getElementById('qr-code-display').style.display = 'none'; // No QR for cash
                    paymentResultDiv.classList.remove('hidden', 'bg-yellow-100', 'border-yellow-400',
                        'text-yellow-700');
                    paymentResultDiv.classList.add('bg-green-100', 'border-green-400', 'text-green-700');
                    cart = {};
                    updateCartDisplay();
                    document.getElementById('cash-payment-section').classList.add('hidden');
                    document.getElementById('qris-pay-button').classList.remove('hidden');
                    amountPaidInput.value = '';
                    document.getElementById('change-amount').value = '';

                    showToast('Transaksi tunai berhasil diproses!', 'success');

                } else {
                    document.getElementById('result-message').textContent =
                        `Gagal membuat transaksi tunai: ${result.message || 'Terjadi kesalahan'}`;
                    document.getElementById('qr-code-display').style.display = 'none';
                    paymentResultDiv.classList.remove('hidden', 'bg-green-100', 'border-green-400',
                        'text-green-700');
                    paymentResultDiv.classList.add('bg-red-100', 'border-red-400', 'text-red-700');

                    showToast(`Gagal membuat transaksi tunai: ${result.message || 'Terjadi kesalahan'}`,
                        'error');
                }
                paymentResultDiv.classList.remove('hidden');

            } catch (error) {
                console.error('Error:', error);
                const paymentResultDiv = document.getElementById('payment-result');
                document.getElementById('result-message').textContent =
                    `Terjadi kesalahan jaringan: ${error.message}`;
                document.getElementById('qr-code-display').style.display = 'none';
                paymentResultDiv.classList.remove('hidden', 'bg-green-100', 'border-green-400',
                    'text-green-700');
                paymentResultDiv.classList.add('bg-red-100', 'border-red-400', 'text-red-700');

                showToast(`Terjadi kesalahan jaringan: ${error.message}`, 'error');
            } finally {
                document.getElementById('process-cash-payment').disabled = false;
                document.getElementById('process-cash-payment').textContent = 'PROSES PEMBAYARAN TUNAI';
                // Tutup modal
                document.getElementById('cash-confirm-modal').classList.add('hidden');
                document.getElementById('cash-confirm-modal').classList.remove('flex');
            }
        });

        // Tutup modal jika klik tombol batal atau close
        document.querySelectorAll('[data-modal-hide="cash-confirm-modal"]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('cash-confirm-modal').classList.add('hidden');
                document.getElementById('cash-confirm-modal').classList.remove('flex');
                showToast('Pembayaran tunai dibatalkan.', 'info');
            });
        });

        // Original QRIS payment button listener (renamed from pay-button to qris-pay-button)
        document.getElementById('qris-pay-button').addEventListener('click', async () => {
            const itemsToSend = [];

            for (const itemId in cart) {
                const itemInCart = cart[itemId];
                itemsToSend.push({
                    id: itemInCart.originalId,
                    quantity: itemInCart.quantity,
                    type: itemInCart.type
                });
            }

            if (itemsToSend.length === 0) {
                showToast('Keranjang belanja kosong. Silakan tambahkan layanan terlebih dahulu.', 'warning');
                return;
            }

            // --- PERBAIKAN DI SINI: Validasi client-side untuk branch_id dan kirim ke API ---
            // selectedBranchId bisa berupa string kosong jika 'null' dari Blade.
            // selectedBranchCode bisa 'UNASSIGNED'.
            if (!selectedBranchId || selectedBranchId === 'null' || selectedBranchCode === 'UNASSIGNED') {
                showToast('Tidak dapat membuat transaksi: Akun Anda tidak terhubung ke cabang yang valid.',
                    'error');
                document.getElementById('qris-pay-button').disabled = false; // Aktifkan kembali tombol
                document.getElementById('qris-pay-button').textContent = 'LANJUTKAN';
                return; // Hentikan eksekusi jika validasi gagal
            }


            // --- AKHIR PERBAIKAN ---


            if (paymentPollingInterval) {
                clearInterval(paymentPollingInterval);
            }

            document.getElementById('qris-pay-button').disabled = true;
            document.getElementById('qris-pay-button').textContent = 'Memproses...';

            try {
                const response = await fetch('/api/create-qris-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        items: itemsToSend,
                        branch_id: selectedBranchId
                    })
                });

                const result = await response.json();
                const paymentResultDiv = document.getElementById('payment-result');

                if (response.ok) {
                    currentInvoiceNumber = result.invoice_number;
                    document.getElementById('result-message').textContent = result.message;
                    document.getElementById('result-invoice').textContent =
                        `Nomor Invoice: ${result.invoice_number}`;
                    document.getElementById('result-total').textContent =
                        `Jumlah Total: Rp ${parseFloat(result.total_amount).toLocaleString('id-ID')}`;
                    document.getElementById('result-status').textContent =
                        `Status: ${result.transaction_status}`;
                    document.getElementById('qr-image').src = result.qr_code_url;
                    document.getElementById('qr-code-display').style.display = 'block';
                    paymentResultDiv.classList.remove('hidden', 'bg-yellow-100', 'border-yellow-400',
                        'text-yellow-700');
                    paymentResultDiv.classList.add('bg-green-100', 'border-green-400', 'text-green-700');
                    cart = {};
                    updateCartDisplay();

                    paymentPollingInterval = setInterval(checkPaymentStatus, 5000);

                    showToast('Transaksi QRIS berhasil dibuat! Silakan scan QR code untuk pembayaran.',
                        'success');

                } else {
                    document.getElementById('result-message').textContent =
                        `Gagal membuat transaksi: ${result.message || 'Terjadi kesalahan'}`;
                    document.getElementById('qr-code-display').style.display = 'none';
                    paymentResultDiv.classList.remove('hidden', 'bg-green-100', 'border-green-400',
                        'text-green-700');
                    paymentResultDiv.classList.add('bg-red-100', 'border-red-400', 'text-red-700');

                    showToast(`Gagal membuat transaksi: ${result.message || 'Terjadi kesalahan'}`, 'error');
                }
                paymentResultDiv.classList.remove('hidden');

            } catch (error) {
                console.error('Error:', error);
                const paymentResultDiv = document.getElementById('payment-result');
                document.getElementById('result-message').textContent =
                    `Terjadi kesalahan jaringan: ${error.message}`;
                document.getElementById('qr-code-display').style.display = 'none';
                paymentResultDiv.classList.remove('hidden', 'bg-green-100', 'border-green-400',
                    'text-green-700');
                paymentResultDiv.classList.add('bg-red-100', 'border-red-400', 'text-red-700');

                showToast(`Terjadi kesalahan jaringan: ${error.message}`, 'error');
            } finally {
                document.getElementById('qris-pay-button').disabled = false;
                document.getElementById('qris-pay-button').textContent = 'LANJUTKAN';
            }
        });
    </script>
    <script>
        // Cegah klik kanan
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Cegah shortcut inspect element & view source
        document.addEventListener('keydown', function(e) {
            // F12
            if (e.keyCode === 123) {
                e.preventDefault();
            }
            // Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S
            if ((e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) ||
                (e.ctrlKey && (e.keyCode === 85 || e.keyCode === 83))) {
                e.preventDefault();
            }
        });

        // Cegah drag & select
        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
        });
        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
        });
    </script>
</body>

</html>
