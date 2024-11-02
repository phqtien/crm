function searchCustomer() {
    const phone = document.getElementById('phone').value;
    clearMessages();
    hideProductTable();

    axios.get(`/orders/newOrder/search_customer_by_phone?phone=${phone}`)
        .then(response => {
            displayCustomerInfo(response.data.customer);
            showProductTable();
        })
        .catch(handleError);
}

function debounce(func, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}

const debounceFetchProductDetails = debounce(fetchProductDetails, 500);

function fetchProductDetails(input) {
    const productName = input.value;
    const row = input.closest('tr');

    axios.get(`/orders/newOrder/search_product_by_name?name=${productName}`)
        .then(response => {
            const product = response.data.product;
            const priceCell = row.querySelector('.product-price');
            const quantityInput = row.querySelector('input[type="number"]');

            if (product) {
                priceCell.innerText = product.price;
                quantityInput.setAttribute('max', product.quantity);
            } else {
                priceCell.innerText = '';
                quantityInput.removeAttribute('max');
            }
            calculateTotal(input);
        })
        .catch(() => {
            row.querySelector('.product-price').innerText = '';
            input.removeAttribute('max');
            calculateTotal(input);
        });
}

function calculateTotal(input) {
    const row = input.closest('tr');
    const price = parseFloat(row.querySelector('.product-price').innerText) || 0;
    let number = parseInt(input.value) || 0;

    if (input.id === "number") {
        const minQuantity = 0;
        const maxQuantity = input.getAttribute('max') !== null ? parseInt(input.getAttribute('max')) : Infinity;

        if (number > maxQuantity) {
            number = maxQuantity;
            input.value = maxQuantity;
            alert(`Quantity of the product is ${maxQuantity}`);
        } else if (number < minQuantity) {
            number = minQuantity;
            input.value = minQuantity;
        }
    }

    const total = price * number;
    row.querySelector('.product-total').innerText = total;
    updateOrderTotal();
}

function addProductRow() {
    const productBody = document.getElementById('product-body');
    const rowCount = productBody.getElementsByTagName('tr').length;
    const newRow = `
            <tr>
                <td class="align-content-center">${rowCount + 1}</td>
                <td>
                    <input type="text" class="form-control" placeholder="Enter product name" onkeyup="fetchProductDetails(this)">
                </td>
                <td class="align-content-center product-price"></td>
                <td>
                    <input type="number" id="number" class="w-50 form-control" min="1" oninput="calculateTotal(this)">
                </td>
                <td class="align-content-center product-total"></td>
            </tr>
        `;
    productBody.insertAdjacentHTML('beforeend', newRow);
}

function updateOrderTotal() {
    const rows = document.querySelectorAll('#product-body tr');
    let grandTotal = Array.from(rows).reduce((total, row) => {
        return total + (parseFloat(row.querySelector('.product-total').innerText) || 0);
    }, 0);

    document.getElementById('order-total').innerText = grandTotal.toFixed(2);
}

function clearMessages() {
    document.getElementById('error-message').classList.add('d-none');
    document.getElementById('customer-info').innerHTML = '';
}

function hideProductTable() {
    document.getElementById('product-table').classList.add('d-none');
}

function showProductTable() {
    document.getElementById('product-table').classList.remove('d-none');
}

function displayCustomerInfo(customer) {
    document.getElementById('customer-info').innerHTML = `
            <h3>Customer Info</h3>
            <p><strong>Name:</strong> ${customer.name}</p>
            <p><strong>Phone:</strong> ${customer.phone}</p>
            <p><strong>Address:</strong> ${customer.address}</p>
            <p><strong>Email:</strong> ${customer.email}</p>
        `;
}

function handleError(error) {
    const errorMessage = error.response && error.response.status === 404 ?
        error.response.data.error :
        'Error, Please try again!!!.';
    document.getElementById('error-message').innerText = errorMessage;
    document.getElementById('error-message').classList.remove('d-none');
}

function createOrder() {
    const products = [];
    const rows = document.querySelectorAll('#product-body tr');

    rows.forEach(row => {
        const productName = row.querySelector('input[type="text"]').value;
        const productPrice = parseFloat(row.querySelector('.product-price').innerText) || 0;
        const productQuantity = parseInt(row.querySelector('input[type="number"]').value) || 0;
        const productTotal = parseFloat(row.querySelector('.product-total').innerText) || 0;

        if (productName && productQuantity > 0) {
            products.push({
                name: productName,
                price: productPrice,
                quantity: productQuantity,
                total: productTotal
            });
        }
    });

    const totalAmount = parseFloat(document.getElementById('order-total').innerText);

    axios.post('/orders/newOrder', {
        customer_phone: document.getElementById('phone').value,
        total_amount: totalAmount,
        products: products
    })
        .then(response => {
            alert('Order created successfully!');
            window.location.href = '/orders';

        })
        .catch(error => {
            handleError(error);
        });
}