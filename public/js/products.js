document.addEventListener('DOMContentLoaded', function () {
    var table = new DataTable('#productsTable', {
        ajax: {
            url: '/products/fetch',
            dataSrc: 'products'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'price' },
            { data: 'quantity' },
            { data: 'description' },
            {
                data: 'created_at',
                render: function (data) {
                    return new Date(data).toLocaleString();
                }
            },
            {
                data: null,
                defaultContent: '<button class="btn btn-warning editBtn" data-bs-toggle="modal" data-bs-target="#editProductModal"><i class="bi bi-pencil-fill"></i></button>'
            }
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

    document.querySelector('#productsTable tbody').addEventListener('click', function (event) {
        if (event.target.closest('.editBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();

            document.getElementById('editProductForm').action = `/products/${data.id}`;
            document.getElementById('editName').value = data.name;
            document.getElementById('editPrice').value = data.price;
            document.getElementById('editQuantity').value = data.quantity;
            document.getElementById('editDescription').value = data.description;

            document.getElementById('deleteProductForm').action = `/products/${data.id}`;
        }
    });
});
