"use strict";

// Close modal handler
window.closeModal = function closeModal(target = "#kt_modal_add_user") {
    setTimeout(() => {
        $("button.swal2-confirm.btn.btn-primary").attr({
            "data-bs-dismiss": "modal",
            "data-bs-target": target,
        });
    }, 50);
};

// Class definition
var KTUsersAddUser = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_add_user");
    const form = element.querySelector("#kt_modal_add_user_form");
    // const modal = new bootstrap.Modal("#kt_modal_add_user");

    // Init add schedule modal
    var initAddUser = () => {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(form, {
            fields: {
                add_user_name: {
                    validators: {
                        notEmpty: {
                            message: "Full name is required",
                        },
                    },
                },
                add_user_email: {
                    validators: {
                        notEmpty: {
                            message: "Valid email address is required",
                        },
                        emailAddress: {
                            message: "Email is not a valid email address",
                        },
                    },
                },
                add_user_password: {
                    validators: {
                        notEmpty: {
                            message: "Password is required",
                        },
                        stringLength: {
                            min: 8,
                            message: "Password must be at least 8 characters",
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

        const removeImage = $('.remove-add-image');

        // Submit button handler
        const submitButton = element.querySelector(
            '[data-kt-users-modal-action="submit"]'
        );
        submitButton.addEventListener("click", (e) => {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    if (status == "Valid") {
                        // Show loading indication
                        submitButton.setAttribute("data-kt-indicator", "on");

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;

                        const addFormData = new FormData();
                        addFormData.append("image", $("#add_user_image")[0].files[0]);
                        addFormData.append("name", $("#add_user_name").val());
                        addFormData.append("email", $("#add_user_email").val());
                        addFormData.append("password", $("#add_user_password").val());
                        addFormData.append("role", $(".add_user_role:checked").val());

                        const errorSwal = {
                            buttonsStyling: false,
                            showConfirmButton: true,
                            confirmButtonText: "Close",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        };

                        $.ajax({
                            url: `/users`,
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: addFormData,
                            headers: {
                                'X-CSRF-TOKEN': token
                            },
                        })
                            .done((response) => {
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
                                    // re-draw datatable
                                    if (response.status == "success") {
                                        datatable.draw();
                                        removeImage.click();
                                        form.reset(); // Reset form
                                    }
                                });
                            })
                            .fail((xhr, status, error) => {
                                Swal.fire(
                                    Object.assign(
                                        {
                                            text:
                                                "Failed create user!. " +
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

        // Cancel button handler
        const cancelButton = element.querySelector(
            '[data-kt-users-modal-action="cancel"]'
        );
        cancelButton.addEventListener("click", (e) => {
            e.preventDefault();
            closeModal();

            Swal.fire({
                text: "Are you sure you would like to cancel ?",
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
                    removeImage.click();
                    form.reset(); // Reset form
                    // modal.hide();
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

        // Close button handler
        const closeButton = element.querySelector(
            '[data-kt-users-modal-action="close"]'
        );
        closeButton.addEventListener("click", (e) => {
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
                    removeImage.click();
                    form.reset(); // Reset form
                    // modal.hide();
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
    };

    return {
        // Public functions
        init: function () {
            initAddUser();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAddUser.init();
});
