"use strict";

function roleColor(role) {
    let color;
    switch (role) {
        case "administrator":
            color = "danger";
            break;
        case "supervisor":
            color = "success";
            break;
        case "manager":
            color = "primary";
            break;
        default:
            color = "secondary";
            break;
    }

    return color;
}

// Class definition
var KTUsersPermissionsList = (function () {
    // Shared variables
    var datatable;
    var table;

    // Init add schedule modal
    var initPermissionsList = () => {
        // Set date data order
        const tableRows = table.querySelectorAll("tbody tr");

        tableRows.forEach((row) => {
            const dateRow = row.querySelectorAll("td");
            const realDate = moment(
                dateRow[2].innerHTML,
                "DD MMM YYYY, LT"
            ).format(); // select date from 2nd column in table
            dateRow[2].setAttribute("data-order", realDate);
        });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[0, "desc"]],
            ajax: {
                url: $("#table-url").val(),
            },
            info: false,
            columns: [
                { data: "name" },
                {
                    data: "roles.name",
                    name: "roles.name",
                },
                { data: "created_at" },
                { data: "action", orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: true,
                    className: "text-capitalize",
                    render: function (data, type, row) {
                        return data.replaceAll('-', ' ');
                    }
                },
                {
                    targets: 1,
                    orderable: true,
                    render: function (data, type, row) {
                        let roleElement = "";
                        data.map((role) => {
                            roleElement += `<span class="badge badge-light-${roleColor(
                                role
                            )} fs-7 fw-bold text-capitalize m-1">${role}</span>`;
                        });
                        return roleElement;
                    },
                },
                {
                    targets: 2,
                    orderable: true,
                    render: function (data, type, row) {
                        return moment(data).format("D MMMM YYYY");
                    },
                },
                {
                    orderable: false,
                    targets: 3,
                    className: "text-end",
                    render: function (data, type, row) {
                        return `
                        <button class="edit-permission btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#kt_modal_update_permission" data-id="${data}">
                            <i class="ki-duotone ki-pencil fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                        <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-kt-permissions-table-filter="delete_row" data-id="${data}">
                            <i class="ki-duotone ki-trash fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </button>`;
                    },
                }, // Disable ordering on column 3 (actions)
            ],
        });

        // Global/ accessible datatable
        window.datatable = datatable;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on("draw", function () {
            handleDeleteRows();
            KTMenu.createInstances();
        });
    };

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector(
            '[data-kt-permissions-table-filter="search"]'
        );
        filterSearch.addEventListener("keyup", function (e) {
            datatable.search(e.target.value).draw();
        });
    };

    // Delete user
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll(
            '[data-kt-permissions-table-filter="delete_row"]'
        );

        deleteButtons.forEach((d) => {
            // Delete button on click
            d.addEventListener("click", function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest("tr");

                // Get permission name
                const permissionName =
                parent.querySelectorAll("td")[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                const permissionId = $(this).data("id");
                Swal.fire({
                    text:
                        "Are you sure you want to delete " +
                        permissionName +
                        "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then(function (result) {
                    if (result.value) {
                        Swal.fire({
                            text: "Deleting " + permissionName,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            buttonsStyling: false,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();

                                const errorSwal = {
                                    showConfirmButton: true,
                                    timer: undefined,
                                    timerProgressBar: false,
                                    confirmButtonText: "Close",
                                    customClass: {
                                        confirmButton:
                                            "btn fw-bold btn-primary",
                                    },
                                };

                                //fetch to delete data
                                $.ajax({
                                    url: `/permissions/${permissionId}`,
                                    type: "DELETE",
                                    cache: false,
                                    data: {
                                        _token: token,
                                    },
                                })
                                    .done((response) => {
                                        Swal.fire(
                                            Object.assign(
                                                {
                                                    text: response.message,
                                                    icon: response.status,
                                                    buttonsStyling: false,
                                                    showConfirmButton: false,
                                                    timer: 1000,
                                                    timerProgressBar: true,
                                                },
                                                response.status == "error"
                                                    ? errorSwal
                                                    : {}
                                            )
                                        ).then(() => {
                                            // delete row data from server and re-draw datatable
                                            datatable.draw();
                                        });
                                    })
                                    .fail((jqXHR, textStatus, error) => {
                                        Swal.fire(
                                            Object.assign(
                                                {
                                                    text:
                                                        "Failed delete " +
                                                        permissionName +
                                                        "!. " +
                                                        jqXHR.responseJSON
                                                            .message,
                                                    icon: "error",
                                                },
                                                errorSwal
                                            )
                                        );
                                    });
                            },
                        });
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: permissionName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    }
                });
            });
        });
    };

    return {
        // Public functions
        init: function () {
            table = document.querySelector("#kt_permissions_table");

            if (!table) {
                return;
            }

            initPermissionsList();
            handleSearchDatatable();
            handleDeleteRows();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersPermissionsList.init();
});
