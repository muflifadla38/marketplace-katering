"use strict";

// Class definition
var KTSignupGeneral = (function () {
    // Elements
    var form;
    var submitButton;
    var validator;
    var passwordMeter;

    // Handle form
    var handleForm = function (e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(form, {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: "Name is required",
                        },
                    },
                },
                email: {
                    validators: {
                        regexp: {
                            regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                            message: "The value is not a valid email address",
                        },
                        notEmpty: {
                            message: "Email address is required",
                        },
                    },
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: "The password is required",
                        },
                        stringLength: {
                            min: 8,
                            message: "Password must be at least 8 characters",
                        },
                    },
                },
                password_confirmation: {
                    validators: {
                        notEmpty: {
                            message: "The password confirmation is required",
                        },
                        identical: {
                            compare: function () {
                                return form.querySelector('[name="password"]')
                                    .value;
                            },
                            message:
                                "The password and its confirm are not the same",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger({
                    event: {
                        password: false,
                    },
                }),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "", // comment to enable invalid state icons
                    eleValidClass: "", // comment to enable valid state icons
                }),
            },
        });

        // Handle form submit
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            validator.revalidateField("password");

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
                        url: `/register`,
                        type: "POST",
                        cache: false,
                        data: {
                            name: $('[name="name"]').val(),
                            email: $('[name="email"]').val(),
                            password: $('[name="password"]').val(),
                            password_confirmation: $(
                                '[name="password_confirmation"]'
                            ).val(),
                            _token: $('[name="csrf-token"]').attr("content"),
                        },
                    })
                        .done((response) => {
                            // Remove loading indication
                            submitButton.removeAttribute("data-kt-indicator");

                            // Enable button
                            submitButton.disabled = false;

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
                                form.reset(); // reset form
                                passwordMeter.reset(); // reset password meter

                                var redirectUrl = form.getAttribute(
                                    "data-kt-redirect-url"
                                );
                                if (
                                    response.status == "success" &&
                                    redirectUrl
                                ) {
                                    location.href = redirectUrl;
                                }
                            });
                        })
                        .fail((xhr, status, error) => {
                            Swal.fire(
                                Object.assign(
                                    {
                                        text: "Gagal register akun!. " + error,
                                        icon: "error",
                                    },
                                    errorSwal
                                )
                            );

                            // Remove loading indication
                            submitButton.removeAttribute("data-kt-indicator");

                            // Enable button
                            submitButton.disabled = false;
                        });
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
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
        });

        // Handle password input
        form.querySelector('input[name="password"]').addEventListener(
            "input",
            function () {
                if (this.value.length > 0) {
                    validator.updateFieldStatus("password", "NotValidated");
                }
            }
        );
    };

    // Password input validation
    var validatePassword = function () {
        return passwordMeter.getScore() > 30;
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            // Elements
            form = document.querySelector("#kt_sign_up_form");
            submitButton = document.querySelector("#kt_sign_up_submit");
            passwordMeter = KTPasswordMeter.getInstance(
                form.querySelector('[data-kt-password-meter="true"]')
            );

            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSignupGeneral.init();
});
