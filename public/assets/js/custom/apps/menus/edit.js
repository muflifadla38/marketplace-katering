"use strict";

let KTUpdateMenu = (function () {
    let menuId, oldName, currentName;
    const element = document.getElementById("kt_modal_edit_menu");
    const form = element.querySelector("#kt_modal_edit_menu_form");

    let initUpdateMenu = () => {
        let validator = FormValidation.formValidation(form, {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: "Name is required",
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
            closeModal("#kt_modal_edit_menu");

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

        $(document).on("click", ".edit-menu", function (e) {
            e.preventDefault();

            menuId = $(this).data("id");

            $.ajax({
                url: `/menus/${menuId}`,
                type: "GET",
            }).then((response) => {
                oldName = response.name;

                form.querySelector("[name='name']").value = oldName;
                form.querySelector("[name='price']").value = response.price;
                form.querySelector("[name='description']").value =
                    response.description;

                form.querySelector("[name='category']").value =
                    response.category;
                form.querySelector("[name='category']").dispatchEvent(
                    new Event("change")
                );

                if (response.image) {
                    const imageWrapper = $(".image-input-wrapper").last()[0];
                    imageWrapper.style.cssText = `background-image: url('storage/menus/${response.image}')`;
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

                        currentName = form.querySelector('[name="name"]').value;

                        const data = {
                            price: form.querySelector('[name="price"]').value,
                            _token: token,
                        };

                        if (currentName !== oldName) {
                            data.name =
                                form.querySelector('[name="name"]').value;
                        }

                        const errorSwal = {
                            buttonsStyling: false,
                            showConfirmButton: true,
                            confirmButtonText: "Close",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        };

                        const formData = new FormData();
                        formData.append("image", $("#edit-image")[0].files[0]);
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
                            url: `/menus/${menuId}`,
                            type: "POST",
                            cache: false,
                            data: formData,
                            contentType: false,
                            processData: false,
                            headers: {
                                "X-HTTP-Method-Override": "PUT",
                                "X-CSRF-TOKEN": token,
                            },
                        })
                            .done((response) => {
                                submitButton.disabled = false;
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                closeModal("#kt_modal_edit_menu");

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
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                submitButton.disabled = false;

                                Swal.fire(
                                    Object.assign(
                                        {
                                            text:
                                                "Failed update data!. " + error,
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
            initUpdateMenu();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTUpdateMenu.init();
});
