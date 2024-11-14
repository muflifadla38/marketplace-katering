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
var KTDatatablesServerSide = (function () {
    const token = $("meta[name='csrf-token']").attr("content");
    window.token = token;

    // Shared variables
    var table;
    var datatable;
    var filterRole;

    // Private functions
    var initDatatable = function () {
        datatable = $("#kt_table_users").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[4, "desc"]],
            // stateSave: false,
            select: {
                style: "multi",
                selector: 'td:first-child input[type="checkbox"]',
                className: "row-selected",
            },
            ajax: {
                url: $("#table-url").val(),
            },
            columns: [
                { data: "id" },
                { data: "name" },
                { data: "email" },
                { data: "roles.name", name: "roles.name", orderable: false },
                { data: "created_at" },
                { data: "action", orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data) {
                        return `
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="${data}" />
                            </div>`;
                    },
                },
                {
                    targets: 1,
                    className: "d-flex align-items-center",
                    render: function (data, type, row) {
                        const profile = row.image
                            ? `<div class="symbol symbol-50px symbol-circle">
                                    <div class="image symbol-label" style="background-image:url('storage/${row.image}')"></div>
                                </div>`
                            : `
                                <div class="symbol-label">
                                    <div class="symbol-label fs-2 fw-semibold bg-primary text-inverse-primary">${data.charAt(
                                        0
                                    )}</div>
                                </div>`;

                        return `
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                            ${profile}
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 mb-1">${data}</span>
                        </div>`;
                    },
                },
                {
                    targets: 3,
                    render: function (data, type, row) {
                        return `<span class="badge badge-light-${roleColor(
                            data
                        )} fs-7 fw-bold text-capitalize">${data}</span>`;
                    },
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                        return moment(data).format("D MMMM YYYY");
                    },
                },
                {
                    targets: -1,
                    data: "action",
                    orderable: false,
                    className: "text-end",
                    render: function (data, type, row) {
                        return `
                        <button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" data-bs-toggle="modal" data-kt-users-table-filter="edit_row"
                        data-bs-target="#kt_modal_edit_user" data-id="${data}">
                            <i class="ki-duotone ki-pencil fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                        <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                            data-kt-users-table-filter="delete_row" data-id="${data}">
                            <i class="ki-duotone ki-trash fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </button>
                        `;
                    },
                },
            ],
        });

        table = datatable.$;

        // Global/ accessible datatable
        window.datatable = datatable;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on("draw", function () {
            initToggleToolbar();
            toggleToolbars();
            handleDeleteRows();
            KTMenu.createInstances();
        });
    };

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector(
            '[data-kt-user-table-filter="search"]'
        );
        filterSearch.addEventListener("keyup", function (e) {
            setTimeout(function () {
                datatable.search(e.target.value).draw();
            }, 500);
        });
    };

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        filterRole = document.querySelector(
            '[data-kt-user-table-filter="form"]'
        );

        const filterButton = filterRole.querySelector(
                '[data-kt-user-table-filter="filter"]'
            ),
            filterSelect = filterRole.querySelectorAll("select");

        // Filter datatable on submit
        filterButton.addEventListener("click", function () {
            // Get filter values
            let roleValue = "";

            // Get role value
            filterSelect.forEach((e, n) => {
                e.value &&
                    "" !== e.value &&
                    (0 !== n && (roleValue += " "), (roleValue += e.value));
            });

            // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search(roleValue).draw();
        });
    };

    // Delete users
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll(
            '[data-kt-users-table-filter="delete_row"]'
        );

        deleteButtons.forEach((d) => {
            // Delete button on click
            d.addEventListener("click", function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest("tr");

                const userId = $(this).data("id");
                const userName = parent
                    .querySelectorAll("td")[1]
                    .innerText.trim()
                    .replace(/[a-zA-Z]+\n/, "");

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + userName + "?",
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
                            text: "Deleting " + userName,
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
                                    url: `/users/${userId}`,
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
                                                        userName +
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
                            text: userName + " was not deleted.",
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

    // Reset Filter
    var handleResetForm = () => {
        // Select reset button
        const resetButton = document.querySelector(
            '[data-kt-user-table-filter="reset"]'
        );

        const filterSelect = document
            .querySelector('[data-kt-user-table-filter="form"]')
            .querySelectorAll("select");

        // Reset datatable
        resetButton.addEventListener("click", function () {
            // Reset role type
            filterSelect.forEach((e) => {
                $(e).val("").trigger("change");
            });

            // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search("").draw();
        });
    };

    // Init toggle toolbar
    var initToggleToolbar = function () {
        // Toggle selected action toolbar
        // Select all checkboxes
        const container = document.querySelector("#kt_table_users");
        const checkboxes = container.querySelectorAll('[type="checkbox"]');

        // Select elements
        const deleteSelected = document.querySelector(
            '[data-kt-user-table-select="delete_selected"]'
        );

        // Toggle delete selected toolbar
        let selectedUserId = [];
        let allUserId = [];
        const rowCount = datatable.rows().count();
        checkboxes.forEach((c, key) => {
            // get all users id
            if (key != 0) allUserId.push(c.value);

            // Checkbox on click event
            c.addEventListener("click", function () {
                if (c.value == "all") {
                    c.checked
                        ? (selectedUserId = allUserId)
                        : (selectedUserId = []);
                } else {
                    if (c.checked) {
                        selectedUserId.push(c.value);
                    } else {
                        var indexToRemove = selectedUserId.indexOf(c.value);
                        if (indexToRemove != -1) {
                            selectedUserId.splice(indexToRemove, 1);
                        }
                    }

                    if (checkboxes.length != rowCount && checkboxes[0].checked)
                        checkboxes[0].checked = false;
                }

                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        // Deleted selected rows
        deleteSelected.addEventListener("click", function () {
            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            Swal.fire({
                text: "Are you sure you want to delete selected users?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
            }).then(function (result) {
                if (result.value) {
                    // Simulate delete request -- for demo purpose only
                    const errorSwal = {
                        showConfirmButton: true,
                        timer: undefined,
                        timerProgressBar: false,
                        confirmButtonText: "Close",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    };

                    //fetch to delete data
                    $.ajax({
                        url: `/users/selected`,
                        type: "DELETE",
                        cache: false,
                        data: {
                            users: selectedUserId,
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
                                    response.status == "error" ? errorSwal : {}
                                )
                            ).then(() => {
                                checkboxes[0].checked = false;

                                // delete row data from server and re-draw datatable
                                datatable.draw();
                            });
                        })
                        .fail((jqXHR, textStatus, error) => {
                            Swal.fire(
                                Object.assign(
                                    {
                                        text:
                                            "Failed delete selected users!. " +
                                            jqXHR.responseJSON.message,
                                        icon: "error",
                                    },
                                    errorSwal
                                )
                            );
                        });
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Selected users was not deleted.",
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
    };

    // Toggle toolbars
    var toggleToolbars = function () {
        // Define variables
        const container = document.querySelector("#kt_table_users");
        const toolbarBase = document.querySelector(
            '[data-kt-user-table-toolbar="base"]'
        );
        const toolbarSelected = document.querySelector(
            '[data-kt-user-table-toolbar="selected"]'
        );
        const selectedCount = document.querySelector(
            '[data-kt-user-table-select="selected_count"]'
        );

        // Select refreshed checkbox DOM elements
        const allCheckboxes = container.querySelectorAll(
            'tbody [type="checkbox"]'
        );

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach((c) => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
        if (checkedState) {
            selectedCount.innerHTML = count;
            toolbarBase.classList.add("d-none");
            toolbarSelected.classList.remove("d-none");
        } else {
            toolbarBase.classList.remove("d-none");
            toolbarSelected.classList.add("d-none");
        }
    };

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            initToggleToolbar();
            handleFilterDatatable();
            handleDeleteRows();
            handleResetForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});
