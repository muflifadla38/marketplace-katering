"use strict";

// Class definition
var KTAccountSettingsProfileDetails = (function () {
    // Private variables
    var form = document.getElementById("kt_account_profile_details_form");
    var submitButton = form.querySelector("#kt_account_profile_details_submit");
    const token = $("meta[name='csrf-token']").attr("content");
    var validation;

    // Private functions
    var initValidation = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(form, {
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
                        notEmpty: {
                            message: "E-mail is required",
                        },
                    },
                },
                role: {
                    validators: {
                        notEmpty: {
                            message: "Role is required",
                        },
                    },
                },
                newpassword: {
                    validators: {
                        stringLength: {
                            min: 8,
                            message:
                                "New password must be at least 8 characters",
                        },
                    },
                },
                newpassword_confirmation: {
                    validators: {
                        identical: {
                            compare: function () {
                                return form.querySelector(
                                    '[name="newpassword"]'
                                ).value;
                            },
                            message:
                                "The password and its confirm are not the same",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "",
                    eleValidClass: "",
                }),
            },
        });
    };

    var handleForm = function () {
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            validation.validate().then(function (status) {
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

                    const formData = new FormData();
                    formData.append("image", $("#image")[0].files[0]);
                    formData.append("name", $("#name").val());
                    formData.append("email", $("#email").val());
                    formData.append(
                        "currentpassword",
                        $("#currentpassword").val()
                    );
                    formData.append("newpassword", $("#newpassword").val());
                    formData.append(
                        "newpassword_confirmation",
                        $("#newpassword_confirmation").val()
                    );

                    $.ajax({
                        url: `/profile`,
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            "X-HTTP-Method-Override": "PUT",
                            "X-CSRF-TOKEN": token,
                        },
                    })
                        .done((response) => {
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
                                if (response.status == "success") {
                                    window.location.reload();
                                }
                            });
                        })
                        .fail((xhr, status, error) => {
                            // Remove loading indication
                            submitButton.removeAttribute("data-kt-indicator");

                            // Enable button
                            submitButton.disabled = false;

                            Swal.fire(
                                Object.assign(
                                    {
                                        text:
                                            "Failed update profile!. " + error,
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
        });
    };

    // Public methods
    return {
        init: function () {
            initValidation();
            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAccountSettingsProfileDetails.init();
});
