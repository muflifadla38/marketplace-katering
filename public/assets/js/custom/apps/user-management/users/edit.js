"use strict";

function closeModal(target = "#kt_modal_add_user", timeout = 50) {
    setTimeout(() => {
        $("button.swal2-confirm.btn.btn-primary").attr({
            "data-bs-dismiss": "modal",
            "data-bs-target": target,
        });
    }, timeout);
}

function initFormValidationAndSubmit(userId) {
    $("#user_id").val(userId);

    // Input element
    const imageWrapper = $(".image-input-wrapper").last()[0];
    const userName = $("#edit_user_name");
    const userEmail = $("#edit_user_email");
    const userRoles = $(".edit_user_role");

    // Get & Set user data
    $.ajax({
        url: `users/${userId}`,
        type: "GET",
    }).then((response) => {
        // Insert data into input
        userName.val(response.name);
        userEmail.val(response.email);
        imageWrapper.style.cssText = `background-image: url('storage/${response.image}')`;

        // Loop through the roles inputs
        for (const userRole of userRoles) {
            userRole.checked =
                userRole.value === response.roles[0].name ? true : false;
        }
    });
}

// Class definition
var KTUsersEditUser = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_edit_user");
    const form = element.querySelector("#kt_modal_edit_user_form");

    var initEditUser = () => {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(form, {
            fields: {
                edit_user_name: {
                    validators: {
                        notEmpty: {
                            message: "Full name is required",
                        },
                    },
                },
                edit_user_email: {
                    validators: {
                        notEmpty: {
                            message: "Valid email address is required",
                        },
                        emailAddress: {
                            message: "Email is not a valid email address",
                        },
                    },
                },
                edit_user_password: {
                    validators: {
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

        const removeImage = $(".remove-edit-image");

        // Submit button handler
        const submitButton = element.querySelector(
            '[data-kt-users-modal-action="submit"]'
        );
        submitButton.addEventListener("click", (e) => {
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

                        const editFormData = new FormData();
                        editFormData.append(
                            "image",
                            $("#edit_user_image")[0].files[0]
                        );
                        editFormData.append("name", $("#edit_user_name").val());
                        editFormData.append(
                            "email",
                            $("#edit_user_email").val()
                        );
                        editFormData.append(
                            "password",
                            $("#edit_user_password").val()
                        );
                        editFormData.append(
                            "role",
                            $(".edit_user_role:checked").val()
                        );

                        const userId = $("#user_id").val();
                        $.ajax({
                            url: `/users/${userId}`,
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: editFormData,
                            headers: {
                                "X-HTTP-Method-Override": "PUT",
                                "X-CSRF-TOKEN": token,
                            },
                        })
                            .done((response) => {
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                // Enable button
                                submitButton.disabled = false;

                                closeModal("#kt_modal_edit_user");
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
                                        datatable.ajax.reload();

                                        removeImage.click();
                                        form.reset(); // Reset form
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
                                                "Failed create user!. " + error,
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
            closeModal("#kt_modal_edit_user");

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

            closeModal("#kt_modal_edit_user");
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
            initEditUser();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersEditUser.init();

    $(document).on(
        "click",
        '[data-kt-users-table-filter="edit_row"]',
        function (d) {
            d.preventDefault();
            initFormValidationAndSubmit($(this).data("id"));
        }
    );
});
