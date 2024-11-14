"use strict";

function initFormValidationAndSubmit(logId) {
    // Input element
    const logName = $("#detail_log_name");
    const logEmail = $("#detail_log_email");
    const logSubject = $("#detail_log_subject");
    const logInterface = $("#detail_log_interface");
    const logTable = $("#detail_log_table");
    const logTableId = $("#detail_log_table_id");
    const logTypes = $(".detail_log_type");

    // Get & Set log data
    $.ajax({
        url: `activity-logs/${logId}`,
        type: "GET",
    }).then((response) => {
        // Insert data into input
        logName.val(response.user.name);
        logEmail.val(response.user.email);
        logSubject.val(response.subject);
        logInterface.val(response.interface);
        logTable.val(response.table);
        logTableId.val(response.table_id);

        // Loop through the roles inputs
        for (const logType of logTypes) {
            logType.checked = logType.value === response.type ? true : false;
        }
    });
}

// Class definition
var KTLogsDetailLog = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_detail_log");
    const form = document.getElementById("kt_modal_detail_log_form");

    var initDetailLog = () => {
        // Cancel button handler
        const cancelButton = element.querySelector(
            '[data-kt-logs-modal-action="cancel"]'
        );
        cancelButton.addEventListener("click", (e) => {
            e.preventDefault();
            form.reset();
        });

        // Close button handler
        const closeButton = element.querySelector(
            '[data-kt-logs-modal-action="close"]'
        );
        closeButton.addEventListener("click", (e) => {
            e.preventDefault();
            form.reset();
        });
    };

    return {
        // Public functions
        init: function () {
            initDetailLog();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTLogsDetailLog.init();

    $(document).on(
        "click",
        '[data-kt-logs-table-filter="detail_row"]',
        function (d) {
            d.preventDefault();
            initFormValidationAndSubmit($(this).data("id"));
        }
    );
});
