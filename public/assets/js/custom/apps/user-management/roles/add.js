"use strict";

// Close modal handler
window.closeModal = function closeModal(
    target = "#kt_modal_add_role",
    timeout = 50
) {
    setTimeout(() => {
        $("button.swal2-confirm.btn.btn-primary").attr({
            "data-bs-dismiss": "modal",
            "data-bs-target": target,
        });
    }, timeout);
};

// Class definition
var KTUsersAddRole = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_add_role");
    const form = element.querySelector("#kt_modal_add_role_form");
    const token = $("meta[name='csrf-token']").attr("content");
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initAddRole = () => {
        window.token = token;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(form, {
            fields: {
                role_name: {
                    validators: {
                        notEmpty: {
                            message: "Role name is required",
                        },
                    },
                },
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "",
                    eleValidClass: "",
                }),
            },
        });

        // Close button handler
        const closeButton = element.querySelector(
            '[data-kt-roles-modal-action="close"]'
        );
        closeButton.addEventListener("click", (e) => {
            e.preventDefault();
            closeModal();

            Swal.fire({
                text: "Are you sure you would like to close?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, close it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light",
                },
            }).then(function (result) {
                if (result.value) {
                    form.reset();
                    modal.hide(); // Hide modal
                }
            });
        });

        // Cancel button handler
        const cancelButton = element.querySelector(
            '[data-kt-roles-modal-action="cancel"]'
        );
        cancelButton.addEventListener("click", (e) => {
            e.preventDefault();
            closeModal();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light",
                },
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form
                    modal.hide(); // Hide modal
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                }
            });
        });

        const checkboxes = form.querySelectorAll('[type="checkbox"]');
        let selectedPermission = [];
        let allPermission = [];

        let checkedCount = 0;
        checkboxes.forEach((c, key) => {
            if (key != 0) allPermission.push(c.value); // get all permissions

            // Checkbox on click event
            c.addEventListener("click", function () {
                if (c.value == "all") {
                    if (c.checked) {
                        checkedCount = checkboxes.length;
                        selectedPermission = allPermission;
                    } else {
                        checkedCount = 0;
                        selectedPermission = [];
                    }
                } else {
                    if (c.checked) {
                        checkedCount++;
                        selectedPermission.push(c.value);
                    } else {
                        checkedCount--;
                        var indexToRemove = selectedPermission.indexOf(c.value);
                        if (indexToRemove != -1) {
                            selectedPermission.splice(indexToRemove, 1);
                        }
                    }

                    if (
                        checkboxes.length != checkedCount &&
                        checkboxes[0].checked
                    )
                        checkboxes[0].checked = false;

                    if (
                        checkboxes.length == checkedCount &&
                        !checkboxes[0].checked
                    )
                        checkboxes[0].checked = true;
                }
            });
        });

        // Submit button handler
        const submitButton = element.querySelector(
            '[data-kt-roles-modal-action="submit"]'
        );
        submitButton.addEventListener("click", function (e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    if (status == "Valid") {
                        // Show loading indication
                        submitButton.setAttribute("data-kt-indicator", "on");

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;

                        const errorSwal = {
                            buttonsStyling: false,
                            showConfirmButton: true,
                            confirmButtonText: "Close",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        };

                        $.ajax({
                            url: `/roles`,
                            type: "POST",
                            cache: false,
                            data: {
                                name: $('[name="name"]').val().toLowerCase(),
                                permissions: selectedPermission,
                                _token: token,
                            },
                        })
                            .done((response) => {
                                closeModal();

                                // Remove loading indication
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                // Enable button
                                submitButton.disabled = false;

                                if (response.status == "success") closeModal();

                                // Show popup confirmation
                                Swal.fire(
                                    Object.assign(
                                        {
                                            text: response.message,
                                            icon: response.status,
                                        },
                                        errorSwal
                                    )
                                ).then(() => {
                                    if (response.status == "success") {
                                        form.reset(); // Reset form
                                        location.reload();
                                    }
                                });
                            })
                            .fail((xhr, status, error) => {
                                Swal.fire(
                                    Object.assign(
                                        {
                                            text:
                                                "Failed create role!. " + error,
                                            icon: "error",
                                        },
                                        errorSwal
                                    )
                                );
                            });
                    } else {
                        // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    }
                });
            }
        });
    };

    // Select all handler
    const handleSelectAll = () => {
        // Define variables
        const selectAll = form.querySelector("#kt_roles_select_all");
        const allCheckboxes = form.querySelectorAll('[type="checkbox"]');

        // Handle check state
        selectAll.addEventListener("change", (e) => {
            // Apply check state to all checkboxes
            allCheckboxes.forEach((c) => {
                c.checked = e.target.checked;
            });
        });
    };

    return {
        // Public functions
        init: function () {
            initAddRole();
            handleSelectAll();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAddRole.init();
});
