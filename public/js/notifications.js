// document.addEventListener('DOMContentLoaded', function () {
//     document.querySelectorAll('tr[data-bs-toggle="modal"]').forEach(function (row) {
//         row.addEventListener('click', function () {
//             const id = this.getAttribute('data-id');
//             document.getElementById('deleteNotificationForm').action = `/notifications/${id}`;
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', function () {
    var table = new DataTable('#notificationsTable', {
        ajax: {
            url: '/notifications/fetch',
            dataSrc: 'notifications'
        },
        columns: [
            { data: 'id' },
            { data: 'message' },
            {
                data: 'created_at',
                render: function (data) {
                    return new Date(data).toLocaleString();
                }
            },
            {
                data: null,
                defaultContent: '<button class="btn btn-warning deleteBtn" data-bs-toggle="modal" data-bs-target="#deleteNotificationModal"><i class="bi bi-x-circle"></i></button>'
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

    document.querySelector('#notificationsTable tbody').addEventListener('click', function (event) {
        if (event.target.closest('.deleteBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();

            document.getElementById('deleteNotificationForm').action = `/notifications/${data.id}`;
        }
    });
});
