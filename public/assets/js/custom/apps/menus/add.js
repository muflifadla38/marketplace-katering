"use strict";

let KTAddMenu = (function () {
    const element = document.getElementById("kt_modal_add_menu"),
        form = element.querySelector("#kt_modal_add_menu_form"),
        token = $("meta[name='csrf-token']").attr("content");

    let initAddMenu = () => {
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
                price: {
                    validators: {
                        notEmpty: {
                            message: "Price is required",
                        },
                    },
                },
                description: {
                    validators: {
                        notEmpty: {
                            message: "Description is required",
                        },
                    },
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: "Category is required",
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
            '[data-kt-menus-modal-action="close"]'
        );
        closeButton.addEventListener("click", (e) => {
            e.preventDefault();
            closeModal("#kt_modal_add_menu");

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
            '[data-kt-menus-modal-action="submit"]'
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

                        const formData = new FormData();
                        formData.append("image", $("#add-image")[0].files[0]);
                        formData.append(
                            "name",
                            form.querySelector('[name="name"]').value
                        );
                        formData.append(
                            "price",
                            form.querySelector('[name="price"]').value
                        );
                        formData.append(
                            "description",
                            form.querySelector('[name="description"]').value
                        );
                        formData.append(
                            "category",
                            form.querySelector('[name="category"]').value
                        );

                        $.ajax({
                            url: submitButton.dataset.url,
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            headers: {
                                "X-CSRF-TOKEN": token,
                            },
                        })
                            .done((response) => {
                                closeModal("#kt_modal_add_menu");

                                submitButton.disabled = false;
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                if (response.status == "success")
                                    closeModal("#kt_modal_add_menu");

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
            initAddMenu();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTAddMenu.init();
});
