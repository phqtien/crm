document.addEventListener('DOMContentLoaded', function () {
    var table = new DataTable('#productsTable', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '/products/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'price' },
            { data: 'quantity' },
            { data: 'description' },
            { data: 'created_at' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>"
    });

    function refreshTable() {
        table.draw();
    }

    // New Product
    document.getElementById('newProductForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let newProductData = {
            name: document.getElementById('name').value,
            price: document.getElementById('price').value,
            quantity: document.getElementById('quantity').value,
            description: document.getElementById('description').value
        };

        axios.post('/products', newProductData)
            .then(response => {
                console.log(response);
                refreshTable();
                this.reset();
            })
            .catch(error => console.error(error));
    });

    // Edit Product
    document.querySelector('#productsTable tbody').addEventListener('click', function (event) {
        if (event.target.closest('.editBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();

            document.getElementById('editName').value = data.name;
            document.getElementById('editPrice').value = data.price;
            document.getElementById('editQuantity').value = data.quantity;
            document.getElementById('editDescription').value = data.description;
            document.getElementById('saveEditProductBtn').dataset.id = data.id;
        }
    });

    document.getElementById('editProductForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let productId = document.getElementById('saveEditProductBtn').dataset.id;
        let editProductData = {
            name: document.getElementById('editName').value,
            price: document.getElementById('editPrice').value,
            quantity: document.getElementById('editQuantity').value,
            description: document.getElementById('editDescription').value
        };

        axios.put(`/products/${productId}`, editProductData)
            .then(response => {
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Show cofirm delete modal
    document.getElementById('deleteProductButton').addEventListener('click', function () {
        let deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
        deleteModal.show();
    });


    // Delete Product
    document.getElementById('confirmDeleteProductBtn').addEventListener('click', function () {
        let productId = document.getElementById('saveEditProductBtn').dataset.id;

        axios.delete(`/products/${productId}`)
            .then(response => {
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});