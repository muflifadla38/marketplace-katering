"use strict";

let KTUpdateVillage = (function () {
    let villageId, oldName, currentName;
    const element = document.getElementById("kt_modal_edit_village");
    const form = element.querySelector("#kt_modal_edit_village_form");

    let initUpdateVillage = () => {
        let validator = FormValidation.formValidation(form, {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: "Name is required",
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
            closeModal("#kt_modal_edit_village");

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

        $(document).on("click", ".edit-village", function (e) {
            e.preventDefault();

            villageId = $(this).data("id");

            $.ajax({
                url: `/villages/${villageId}`,
                type: "GET",
            }).then((response) => {
                oldName = response.name;

                form.querySelector("[name='name']").value = oldName;
                form.querySelector("[name='subdistrict_id']").value =
                    response.subdistrict_id;

                form.querySelector("[name='subdistrict_id']").dispatchEvent(
                    new Event("change")
                );
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

                        currentName = form.querySelector('[name="name"]').value;

                        const data = {
                            subdistrict_id: form.querySelector(
                                '[name="subdistrict_id"]'
                            ).value,
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

                        $.ajax({
                            url: `/villages/${villageId}`,
                            type: "POST",
                            cache: false,
                            data: data,
                            headers: {
                                "X-HTTP-Method-Override": "PUT",
                            },
                        })
                            .done((response) => {
                                submitButton.disabled = false;
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                closeModal("#kt_modal_edit_village");

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
            initUpdateVillage();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTUpdateVillage.init();
});
