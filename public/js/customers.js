document.addEventListener('DOMContentLoaded', function () {
    var table = new DataTable('#customersTable', {
        ajax: {
            url: '/customers/fetch',
            dataSrc: 'customers'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'phone' },
            { data: 'address' },
            { data: 'email' },
            {
                data: 'created_at',
                render: function (data) {
                    return new Date(data).toLocaleString();
                }
            },
            {
                data: null,
                defaultContent: '<button class="btn btn-warning editBtn" data-bs-toggle="modal" data-bs-target="#editCustomerModal"><i class="bi bi-pencil-fill"></i></button>'
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

    document.querySelector('#customersTable tbody').addEventListener('click', function (event) {
        if (event.target.closest('.editBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();

            document.getElementById('editCustomerForm').action = `/customers/${data.id}`;
            document.getElementById('editName').value = data.name;
            document.getElementById('editPhone').value = data.phone;
            document.getElementById('editAddress').value = data.address;
            document.getElementById('editEmail').value = data.email;

            document.getElementById('deleteCustomerForm').action = `/customers/${data.id}`;
        }
    });
});
