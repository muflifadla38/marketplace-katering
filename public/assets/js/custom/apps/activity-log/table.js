"use strict";

let dateRange = $("#date-range-filter"),
    startDateDefault = moment().subtract(6, "days"),
    endDateDefault = moment(),
    startDate,
    endDate;

const dateRangeInit = () => {
    dateRange.daterangepicker(
        {
            parentEl: "#date-range-menu",
            startDate: startDateDefault.format("DD MMM YYYY"),
            endDate: endDateDefault.format("DD MMM YYYY"),
            locale: {
                format: "DD MMM YYYY",
            },
            drops: "up",
            opens: "center",
        },
        (start, end) => {
            startDate = start.format("YYYY-MM-DD");
            endDate = end.format("YYYY-MM-DD");
        }
    );
};

function typeColor(type) {
    let color;

    switch (type) {
        case "login":
            color = "info";
            break;
        case "logout":
            color = "warning";
            break;
        case "add":
            color = "primary";
            break;
        case "edit":
            color = "success";
            break;
        default:
            color = "danger";
    }

    return color;
}

// Class definition
let KTDatatablesServerSide = (function () {
    // Shared variables
    let datatable,
        dateRangeForm = document.querySelector(
            '[data-kt-logs-date-range-filter="form"]'
        ),
        filterForm = document.querySelector(
            '[data-kt-logs-table-filter="form"]'
        );

    // Private functions
    let initDatatable = function () {
        datatable = $("#kt_table_logs").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[0, "desc"]],
            ajax: {
                url: $("#table-url").val(),
                data: function (param) {
                    param.startDate = startDate
                        ? `${startDate} 00:00:00`
                        : null;
                    param.endDate = endDate ? `${endDate} 23:59:59` : null;
                },
            },
            columns: [
                { data: "created_at" },
                { data: "user.name", name: "user.name", orderable: false },
                { data: "interface", orderable: false },
                { data: "subject" },
                { data: "type", orderable: false },
                { data: "table", orderable: false },
                { data: "action", orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row) {
                        return moment(data).format("D MMM YYYY H:m");
                    },
                },
                {
                    targets: 1,
                    className: "d-flex align-items-center",
                    render: function (data, type, row) {
                        return `
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 mb-1">${row.user.name}</span>
                            <span>${row.user.email}</span>
                        </div>`;
                    },
                },
                {
                    targets: 2,
                    className: "text-center",
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function (data, type, row) {
                        return `<span class="badge badge-light-${typeColor(
                            data
                        )} fs-7 fw-bold text-capitalize">${data}</span>`;
                    },
                },
                {
                    targets: 5,
                    className: "text-center",
                },
                {
                    targets: -1,
                    data: "action",
                    className: "text-center",
                    render: function (data, type, row) {
                        return `
                        <button class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1" data-bs-toggle="modal" data-kt-logs-table-filter="detail_row"
                        data-bs-target="#kt_modal_detail_log" data-id="${data}">
                            <i class="ki-duotone ki-eye fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                        `;
                    },
                },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on("draw", function () {
            KTMenu.createInstances();
        });
    };

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    let handleSearchDatatable = function () {
        const filterSearch = document.querySelector(
            '[data-kt-logs-table-filter="search"]'
        );
        filterSearch.addEventListener("keyup", function (e) {
            setTimeout(function () {
                datatable.search(e.target.value).draw();
            }, 500);
        });
    };

    // Filter Datatable
    let handleFilterDatatable = () => {
        const filterSubmit = filterForm.querySelector(
                '[data-kt-logs-table-filter="filter"]'
            ),
            filterLogType = filterForm.querySelector("#log-type-filter"),
            filterTable = filterForm.querySelector("#table-filter"),
            filterInterface = filterForm.querySelector("#interface-filter");

        // Filter datatable on submit
        filterSubmit.addEventListener("click", function () {
            datatable.column(2).search(filterInterface.value);
            datatable.column(4).search(filterLogType.value);

            let tableFilterValue = !filterTable.value
                ? filterTable.value
                : `^${filterTable.value}$`;
            datatable.column(5).search(tableFilterValue, true, false).draw();
        });
    };

    let handleDateRangeDatatable = () => {
        const dateRangeSubmit = dateRangeForm.querySelector(
            '[data-kt-logs-date-range-filter="filter"]'
        );

        dateRangeSubmit.addEventListener("click", function () {
            startDate ??= startDateDefault.format("YYYY-MM-DD");
            endDate ??= endDateDefault.format("YYYY-MM-DD");

            datatable.draw();
        });
    };

    // Reset Filter
    let handleResetForm = () => {
        // Select reset button
        const filterResetButton = document.querySelector(
                '[data-kt-logs-table-filter="reset"]'
            ),
            dateRangeResetButton = document.querySelector(
                '[data-kt-logs-date-range-filter="reset"]'
            );

        const filterSelect = filterForm.querySelectorAll("select");

        // Reset filter
        filterResetButton.addEventListener("click", function () {
            // Reset filter value
            filterSelect.forEach((e) => {
                $(e).val("").trigger("change");
            });

            dateRange
                .data("daterangepicker")
                .setStartDate(startDateDefault.format("DD MMM YYYY"));
            dateRange
                .data("daterangepicker")
                .setEndDate(endDateDefault.format("DD MMM YYYY"));

            datatable.column(2).search("");
            datatable.column(4).search("");
            datatable.column(5).search("").draw();
        });

        // Reset date range
        dateRangeResetButton.addEventListener("click", function () {
            startDate = startDateDefault.format("YYYY-MM-DD");
            endDate = endDateDefault.format("YYYY-MM-DD");

            dateRange
                .data("daterangepicker")
                .setStartDate(startDateDefault.format("DD MMM YYYY"));
            dateRange
                .data("daterangepicker")
                .setEndDate(endDateDefault.format("DD MMM YYYY"));

            datatable.draw();
        });
    };

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            handleFilterDatatable();
            handleDateRangeDatatable();
            handleResetForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    dateRangeInit();

    KTDatatablesServerSide.init();
});
