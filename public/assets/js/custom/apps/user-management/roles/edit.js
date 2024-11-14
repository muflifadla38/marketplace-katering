"use strict";

function initFormValidationAndSubmit(roleId) {
    $("#role_id").val(roleId);

    // Get & Set role data
    $.ajax({
        url: `roles/${roleId}`,
        type: "GET",
    }).then((response) => {
        // Insert data into input
        $("[name='role_name']").val(response.name);

        let permissionName;
        response.permissions.forEach((permission) => {
            permissionName = permission.name.replaceAll(" ", "_");
            $(`#${permissionName}`).prop("checked", true);
        });
    });
}

// Class definition
var KTUsersUpdatePermissions = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_update_role");
    const form = element.querySelector("#kt_modal_update_role_form");
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initUpdatePermissions = () => {
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
            closeModal("#kt_modal_update_role");

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
            closeModal("#kt_modal_update_role");

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

                        const checkboxes =
                            form.querySelectorAll('[type="checkbox"]');
                        let selectedPermission = [];

                        checkboxes.forEach((c) => {
                            if (c.checked) selectedPermission.push(c.value);
                        });

                        const roleId = $("#role_id").val();
                        $.ajax({
                            url: `/roles/${roleId}`,
                            type: "POST",
                            cache: false,
                            data: {
                                name: form.querySelector("[name='role_name']")
                                    .value.toLowerCase(),
                                permissions: selectedPermission,
                                _token: token,
                            },
                            headers: {
                                "X-HTTP-Method-Override": "PUT",
                            },
                        })
                            .done((response) => {
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                // Enable button
                                submitButton.disabled = false;

                                closeModal("#kt_modal_update_role");

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
                                    // re-draw datatable
                                    if (response.status == "success") {
                                        form.reset(); // Reset form
                                        location.reload();
                                    }
                                });
                            })
                            .fail((xhr, status, error) => {
                                // Remove loading indication
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                // Enable button
                                submitButton.disabled = false;

                                Swal.fire(
                                    Object.assign(
                                        {
                                            text:
                                                "Failed update role!. " + error,
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
            initUpdatePermissions();
            handleSelectAll();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdatePermissions.init();

    $(document).on("click", ".edit-role", function (d) {
        d.preventDefault();

        initFormValidationAndSubmit($(this).data("id"));
    });
});
