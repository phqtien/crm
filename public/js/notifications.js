document.addEventListener('DOMContentLoaded', function () {
    var table = new DataTable('#notificationsTable', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '/notifications/fetch',
            type: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'message' },
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

    document.querySelector('#notificationsTable tbody').addEventListener('click', function (event) {
        if (event.target.closest('.deleteBtn')) {
            var row = table.row(event.target.closest('tr'));
            var data = row.data();

            document.getElementById('confirmDeleteNotificationBtn').dataset.id = data.id;
        }
    });

    function refreshTable() {
        table.draw();
    }

    // Delete Notification
    document.getElementById('confirmDeleteNotificationBtn').addEventListener('click', function () {
        let notificationId = document.getElementById('confirmDeleteNotificationBtn').dataset.id;

        axios.delete(`/notifications/${notificationId}`)
            .then(response => {
                console.log(response);
                refreshTable();
            })
            .catch(error => console.error(error));
    });
});
