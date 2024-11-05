document.addEventListener('DOMContentLoaded', function () {
    var table = $('#customersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/customers/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'phone' },
            { data: 'address' },
            { data: 'email' },
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

    // New Customer
    document.getElementById('newCustomerForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let newCustomerData = {
            name: document.getElementById('newCustomerName').value,
            phone: document.getElementById('newCustomerPhone').value,
            address: document.getElementById('newCustomerAddress').value,
            email: document.getElementById('newCustomerEmail').value
        };

        axios.post('/customers', newCustomerData)
            .then(response => {
                console.log(response);
                refreshTable();
                this.reset();
            })
            .catch(error => console.error(error));
    });

    // Edit Customer
    document.querySelector('#customersTable tbody').addEventListener('click', function (event) {
        if (event.target.closest('.editBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();

            document.getElementById('editName').value = data.name;
            document.getElementById('editPhone').value = data.phone;
            document.getElementById('editAddress').value = data.address;
            document.getElementById('editEmail').value = data.email;
            document.getElementById('saveEditCustomerBtn').dataset.id = data.id;
        }
    });

    document.getElementById('editCustomerForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let customerId = document.getElementById('saveEditCustomerBtn').dataset.id;
        let editCustomerData = {
            name: document.getElementById('editName').value,
            phone: document.getElementById('editPhone').value,
            address: document.getElementById('editAddress').value,
            email: document.getElementById('editEmail').value
        };

        axios.put(`/customers/${customerId}`, editCustomerData)
            .then(response => {
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });

    // Show cofirm delete modal
    document.getElementById('deleteCustomerButton').addEventListener('click', function () {
        let deleteModal = new bootstrap.Modal(document.getElementById('deleteCustomerModal'));
        deleteModal.show();
    });


    // Delete Customer
    document.getElementById('confirmDeleteCustomerBtn').addEventListener('click', function () {
        let customerId = document.getElementById('saveEditCustomerBtn').dataset.id;

        axios.delete(`/customers/${customerId}`)
            .then(response => {
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});
