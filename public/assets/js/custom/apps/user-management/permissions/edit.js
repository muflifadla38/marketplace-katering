"use strict";

function initFormValidationAndSubmit(permissionId) {
    {
        $("#permission_id").val(permissionId);

        // Get & Set permission data
        $.ajax({
            url: `permissions/${permissionId}`,
            type: "GET",
        }).then((response) => {
            $("[name='permission_name']").val(response.name);
        });
    }
}

// Class definition
var KTUsersUpdatePermission = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_update_permission");
    const form = element.querySelector("#kt_modal_update_permission_form");
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initUpdatePermission = () => {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(form, {
            fields: {
                permission_name: {
                    validators: {
                        notEmpty: {
                            message: "Permission name is required",
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
            '[data-kt-permissions-modal-action="close"]'
        );
        closeButton.addEventListener("click", (e) => {
            e.preventDefault();
            closeModal("#kt_modal_update_permission");

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
                    form.reset(); // Reset form
                    modal.hide(); // Hide modal
                }
            });
        });

        // Cancel button handler
        const cancelButton = element.querySelector(
            '[data-kt-permissions-modal-action="cancel"]'
        );
        cancelButton.addEventListener("click", (e) => {
            e.preventDefault();
            closeModal("#kt_modal_update_permission");

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
            '[data-kt-permissions-modal-action="submit"]'
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

                        const permissionId = $("#permission_id").val();
                        $.ajax({
                            url: `/permissions/${permissionId}`,
                            type: "POST",
                            cache: false,
                            data: {
                                name: form
                                    .querySelector('[name="permission_name"]')
                                    .value.toLowerCase(),
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

                                closeModal("#kt_modal_update_permission");

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
                                        datatable.draw();
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
                                                "Failed update permission!. " +
                                                error,
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

    return {
        // Public functions
        init: function () {
            initUpdatePermission();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdatePermission.init();

    $(document).on("click", ".edit-permission", function (d) {
        d.preventDefault();

        initFormValidationAndSubmit($(this).data("id"));
    });
});
