"use strict";

let KTAddVillage = (function () {
    const element = document.getElementById("kt_modal_add_village"),
        form = element.querySelector("#kt_modal_add_village_form"),
        token = $("meta[name='csrf-token']").attr("content");

    let initAddVillage = () => {
        window.token = token;

        let validator = FormValidation.formValidation(form, {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: "Nama is required",
                        },
                    },
                },
                subdistrict_id: {
                    validators: {
                        notEmpty: {
                            message: "Kecamatan is required",
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

        const closeButton = element.querySelector(
            '[data-kt-villages-modal-action="close"]'
        );
        closeButton.addEventListener("click", (e) => {
            e.preventDefault();
            closeModal("#kt_modal_add_village");

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
                }
            });
        });

        const submitButton = element.querySelector(
            '[data-kt-villages-modal-action="submit"]'
        );
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    if (status == "Valid") {
                        submitButton.setAttribute("data-kt-indicator", "on");
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
                            url: submitButton.dataset.url,
                            type: "POST",
                            cache: false,
                            data: {
                                name: form.querySelector('[name="name"]').value,
                                subdistrict_id: form.querySelector(
                                    '[name="subdistrict_id"]'
                                ).value,
                                _token: token,
                            },
                        })
                            .done((response) => {
                                closeModal("#kt_modal_add_village");

                                submitButton.disabled = false;
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                if (response.status == "success") closeModal("#kt_modal_add_village");

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
                                        form.reset();
                                        datatable.draw();
                                    }
                                });
                            })
                            .fail((xhr, status, error) => {
                                Swal.fire(
                                    Object.assign(
                                        {
                                            text:
                                                "Failed create data!. " + error,
                                            icon: "error",
                                        },
                                        errorSwal
                                    )
                                );
                            });
                    } else {
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
        init: function () {
            initAddVillage();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTAddVillage.init();
});
