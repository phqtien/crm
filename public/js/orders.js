document.addEventListener('DOMContentLoaded', function () {
    var table = new DataTable('#ordersTable', {
        ajax: {
            url: '/orders/fetch',
            dataSrc: 'orders',
        },
        columns: [
            { data: 'id' },
            { data: 'customer_name' },
            { data: 'user_name' },
            { data: 'total_amount' },
            { data: 'status' },
            {
                data: 'created_at',
                render: function (data) {
                    return new Date(data).toLocaleString();
                }
            },
            {
                data: null,
                defaultContent: '<button class="btn btn-warning editBtn" data-bs-toggle="modal" data-bs-target="#orderModal"><i class="bi bi-pencil-fill"></i></button>'
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

    document.querySelector('#ordersTable tbody').addEventListener('click', function (event) {
        if (event.target.closest('.editBtn')) {
            var row = table.row(event.target.closest('tr'));
            var rowData = row.data();

            axios.get(`/orders/detail?id=${rowData.id}`)
                .then(response => {
                    const data = response.data;

                    document.getElementById('orderId').textContent = data.order.id;
                    document.getElementById('customerName').textContent = data.customer.name;
                    document.getElementById('customerPhone').textContent = data.customer.phone;
                    document.getElementById('customerAddress').textContent = data.customer.address;
                    document.getElementById('customerEmail').textContent = data.customer.email;
                    document.getElementById('totalAmount').textContent = data.order.total_amount;
                    document.getElementById('status').textContent = data.order.status;
                    document.getElementById('createdAt').textContent = new Date(data.order.created_at).toLocaleString();
                    const orderItemsTableBody = document.getElementById('orderItems');
                    orderItemsTableBody.innerHTML = '';

                    data.order_items.forEach(item => {
                        const row = document.createElement('tr');
                        const totalPrice = (item.unit_price * item.quantity).toFixed(2);
                        row.innerHTML = `
                                <td>${item.product_name}</td>
                                <td>${item.quantity}</td>
                                <td>${item.unit_price}</td>
                                <td>${totalPrice}</td>
                            `;
                        orderItemsTableBody.appendChild(row);
                    });


                    document.getElementById('deleteOrderForm').action = `/orders/${data.order.id}`;
                    document.getElementById('generatePdfLink').href = `/orders/pdf?id=${data.order.id}`;
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    });
});
