let services = [];
let products = [];
let cart = {}; // { itemId: { quantity: X, type: 'service'|'product', name: '...', price: ... } }
let paymentPollingInterval;
let currentInvoiceNumber = "";

const selectedBranchId = document
    .querySelector('meta[name="selected-branch-id"]')
    .getAttribute("content");
const selectedBranchCode = document
    .querySelector('meta[name="selected-branch-code"]')
    .getAttribute("content");

document.addEventListener("DOMContentLoaded", async () => {
    await fetchData();
    updateCartDisplay();
});

async function fetchData() {
    try {
        const serviceResponse = await fetch("/api/services");
        if (!serviceResponse.ok) {
            throw new Error(
                `HTTP error! status: ${serviceResponse.status} from /api/services`
            );
        }
        const serviceData = await serviceResponse.json();
        services = serviceData;

        const productResponse = await fetch("/api/products"); // Assuming a /api/products endpoint
        if (!productResponse.ok) {
            throw new Error(
                `HTTP error! status: ${productResponse.status} from /api/products`
            );
        }
        const productData = await productResponse.json();
        products = productData;

        renderItems(services, "service-content", "service");
        attachItemListeners("service-content");

        renderItems(products, "product-content", "product");
        attachItemListeners("product-content");

        // Set initial tab state
        document.getElementById("service-tab").click();

        // Tab functionality
        document.getElementById("service-tab").addEventListener("click", () => {
            currentTab = "service";
            document
                .getElementById("service-content")
                .classList.remove("hidden");
            document.getElementById("product-content").classList.add("hidden");
            document
                .getElementById("service-tab")
                .classList.add("bg-tombol", "text-white");
            document
                .getElementById("service-tab")
                .classList.remove("bg-gray-200", "text-gray-700");
            document
                .getElementById("product-tab")
                .classList.remove("bg-tombol", "text-white");
            document
                .getElementById("product-tab")
                .classList.add("bg-gray-200", "text-gray-700");
            updateItemStates();
        });

        document.getElementById("product-tab").addEventListener("click", () => {
            currentTab = "product";
            document
                .getElementById("product-content")
                .classList.remove("hidden");
            document.getElementById("service-content").classList.add("hidden");
            document
                .getElementById("product-tab")
                .classList.add("bg-tombol", "text-white");
            document
                .getElementById("product-tab")
                .classList.remove("bg-gray-200", "text-gray-700");
            document
                .getElementById("service-tab")
                .classList.remove("bg-tombol", "text-white");
            document
                .getElementById("service-tab")
                .classList.add("bg-gray-200", "text-gray-700");
            updateItemStates();
        });
    } catch (error) {
        console.error("Error fetching data:", error);
        document.getElementById("service-content").innerHTML =
            '<p class="text-red-500">Gagal memuat data. Silakan coba lagi nanti.</p>';
        document.getElementById("product-content").innerHTML =
            '<p class="text-red-500">Gagal memuat data. Silakan coba lagi nanti.</p>';
    }
}

let currentTab = "service"; // 'service' or 'product'

function renderItems(items, containerId, type) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";
    if (items.length === 0) {
        container.innerHTML = `<p class="text-gray-500">Belum ada ${type} yang ditambahkan. Silakan tambahkan melalui panel admin.</p>`;
        return;
    }

    items.forEach((item) => {
        const itemCard = document.createElement("div");
        itemCard.className =
            "service-item bg-white p-4 border border-gray-300 rounded-md flex justify-between items-center cursor-pointer";
        itemCard.dataset.id = item.id;
        itemCard.dataset.type = type; // 'service' or 'product'
        itemCard.innerHTML = `
            <div>
                <h3 class="text-lg font-medium">${item.name}</h3>
            </div>
            <div class="font-semibold">Rp ${parseFloat(
                item.price
            ).toLocaleString("id-ID")}</div>
        `;
        container.appendChild(itemCard);
    });

    attachItemListeners();
    updateItemStates();
}

function attachItemListeners() {
    document.querySelectorAll(".service-item").forEach((item) => {
        item.removeEventListener("click", handleItemClick);
        item.addEventListener("click", handleItemClick);
    });
}

function handleItemClick(e) {
    const id = e.currentTarget.dataset.id;
    const type = e.currentTarget.dataset.type;
    const uniqueId = `${type}-${id}`;
    const item = (type === "service" ? services : products).find(
        (s) => s.id == id
    );

    if (cart[uniqueId]) {
        // If already in cart, do nothing on click, quantity is controlled by +/- buttons
    } else {
        cart[uniqueId] = {
            quantity: 1,
            type: type,
            name: item.name,
            price: item.price,
            originalId: id,
        }; // Store type, name, price, and originalId
    }
    updateCartDisplay();
    updateItemStates();
}

function updateCartDisplay() {
    const cartSummaryItemsDiv = document.getElementById("cart-summary-items");
    const cartTotalSummarySpan = document.getElementById("cart-total-summary");
    let total = 0;
    cartSummaryItemsDiv.innerHTML = "";

    if (Object.keys(cart).length === 0) {
        cartSummaryItemsDiv.innerHTML =
            '<p class="text-gray-500">Keranjang kosong.</p>';
        cartTotalSummarySpan.textContent = "Rp 0";
        updateItemStates();
        return;
    }

    for (const uniqueId in cart) {
        const itemInCart = cart[uniqueId];
        const quantity = itemInCart.quantity;
        const itemTotal = itemInCart.price * quantity;
        total += itemTotal;

        const cartItemDiv = document.createElement("div");
        cartItemDiv.className = "flex justify-between items-center";
        cartItemDiv.innerHTML = `
            <span class="text-gray-700">${itemInCart.name}</span>
            <div class="flex items-center space-x-2">
                <span class="font-semibold">Rp ${parseFloat(
                    itemTotal
                ).toLocaleString("id-ID")}</span>
                <button class="quantity-control-btn decrease-quantity" data-id="${uniqueId}">-</button>
                <span class="quantity-display">${quantity}</span>
                <button class="quantity-control-btn increase-quantity" data-id="${uniqueId}">+</button>
            </div>
        `;
        cartSummaryItemsDiv.appendChild(cartItemDiv);
    }
    cartTotalSummarySpan.textContent = `Rp ${parseFloat(total).toLocaleString(
        "id-ID"
    )}`;

    document.querySelectorAll(".increase-quantity").forEach((button) => {
        button.addEventListener("click", (e) => {
            const uniqueId = e.target.dataset.id;
            cart[uniqueId].quantity++;
            updateCartDisplay();
            updateItemStates();
        });
    });

    document.querySelectorAll(".decrease-quantity").forEach((button) => {
        button.addEventListener("click", (e) => {
            const uniqueId = e.target.dataset.id;
            if (cart[uniqueId].quantity > 1) {
                cart[uniqueId].quantity--;
            } else {
                delete cart[uniqueId];
            }
            updateCartDisplay();
            updateItemStates();
        });
    });
}

function updateItemStates() {
    document.querySelectorAll(".service-item").forEach((item) => {
        const itemId = item.dataset.id;
        const itemType = item.dataset.type;
        const uniqueId = `${itemType}-${itemId}`;

        if (cart[uniqueId] && cart[uniqueId].type === itemType) {
            item.classList.add("selected");
        } else {
            item.classList.remove("selected");
        }
    });
}

async function checkPaymentStatus() {
    if (!currentInvoiceNumber) return;

    try {
        const response = await fetch(
            `/api/transaction-status/${currentInvoiceNumber}`
        );
        const data = await response.json();

        if (data.status === "success") {
            document.getElementById(
                "result-status"
            ).textContent = `Status: ${data.payment_status}`;
            const paymentResultDiv = document.getElementById("payment-result");
            paymentResultDiv.classList.remove(
                "bg-yellow-100",
                "border-yellow-400",
                "text-yellow-700",
                "bg-red-100",
                "border-red-400",
                "text-red-700"
            );
            paymentResultDiv.classList.add(
                "bg-green-100",
                "border-green-400",
                "text-green-700"
            );
            document.getElementById("qr-code-display").style.display = "none";
            clearInterval(paymentPollingInterval);
            alert("Pembayaran Berhasil!");
        } else if (
            data.status === "expire" ||
            data.status === "failed" ||
            data.status === "cancelled"
        ) {
            document.getElementById(
                "result-status"
            ).textContent = `Status: ${data.payment_status}`;
            const paymentResultDiv = document.getElementById("payment-result");
            paymentResultDiv.classList.remove(
                "bg-yellow-100",
                "border-yellow-400",
                "text-yellow-700",
                "bg-green-100",
                "border-green-400",
                "text-green-700"
            );
            paymentResultDiv.classList.add(
                "bg-red-100",
                "border-red-400",
                "text-red-700"
            );
            document.getElementById("qr-code-display").style.display = "none";
            clearInterval(paymentPollingInterval);
            alert("Pembayaran Gagal atau Kadaluarsa!");
        } else {
            document.getElementById(
                "result-status"
            ).textContent = `Status: ${data.payment_status}`;
        }
    } catch (error) {
        console.error("Error polling payment status:", error);
    }
}

// ... (lanjutan kode event listener dan fungsi pembayaran cash/QRIS, dipindahkan persis dari pos.blade.php) ...
