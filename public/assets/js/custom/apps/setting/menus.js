"use strict";

// const closeModal = (target = "#kt_modal_add_menu") => {
//     setTimeout(() => {
//         $("button.swal2-confirm.btn.btn-primary").attr({
//             "data-bs-dismiss": "modal",
//             "data-bs-target": target,
//         });
//     }, 50);
// };

let KTNestedMenus = (function () {
    let menuList = $("#kt_menu_list"),
        editButton = $(".dd-list .dd-item button"),
        target = document.querySelector("#kt_menu_list"),
        blockUI = new KTBlockUI(target),
        token = $("meta[name='csrf-token']").attr("content");

    const errorSwal = {
        showConfirmButton: true,
        timer: undefined,
        timerProgressBar: false,
        confirmButtonText: "Close",
        customClass: {
            confirmButton: "btn fw-bold btn-primary",
        },
    };

    let initNestedMenu = () => {
        menuList.nestable();
        // $(".dd").nestable();
    };

    let handleAddMenu = () => {
        const form = document.querySelector("#kt_modal_add_menu_form");
        const addButton = document.querySelector("#button_add_menu");
        const submitButton = document.querySelector("#submit_add_menu");

        addButton.addEventListener("click", () => {
            console.log("add button clicked");
        });

        submitButton.addEventListener("click", (event) => {
            event.preventDefault();

            submitButton.setAttribute("data-kt-indicator", "on");
            submitButton.disabled = true;

            const icon = $('[name="add-icon"]').val();
            const path = $('[name="add-path"]').val();

            const addFormData = new FormData();
            addFormData.append("name", $('[name="add-name"]').val());
            addFormData.append("feature", $('[name="add-feature"]').val());
            icon ? addFormData.append("icon", icon) : null;
            path ? addFormData.append("path", path) : null;
            addFormData.append("route", $('[name="add-route"]').val());
            addFormData.append(
                "heading",
                $('[name="add-heading"]').is(":checked")
            );

            $.ajax({
                url: submitButton.dataset.url,
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: addFormData,
                headers: {
                    "X-CSRF-TOKEN": token,
                },
            })
                .done((response) => {
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;

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
                            form.reset(); // Reset form
                            location.reload();
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
                                text: "Failed add menu!. " + error,
                                icon: "error",
                            },
                            errorSwal
                        )
                    );
                });
        });
    };

    let handleEditMenu = () => {
        editButton.on("mousedown", function (event) {
            event.preventDefault();
            return false;
        });

        editButton.on("click", function (event) {
            event.preventDefault();

            // const item = $(this).closest(".dd-item");
            const updateButton = document.querySelector("#button_update_menu");

            updateButton.addEventListener("click", function () {
                updateButton.setAttribute("data-kt-indicator", "on");
                updateButton.disabled = true;

                const editFormData = new FormData();
                editFormData.append("name", $('[name="add-name"]').val());
                editFormData.append("feature", $('[name="add-feature"]').val());
                editFormData.append("icon", $('[name="add-icon"]').val());
                editFormData.append("path", $('[name="add-path"]').val());
                editFormData.append("route", $('[name="add-route"]').val());
                editFormData.append(
                    "heading",
                    $('[name="add-heading"]').is(":checked")
                );

                $.ajax({
                    url: $(this).data("url"),
                    type: "POST",
                    cache: false,
                    data: {
                        data: data,
                        _token: token,
                    },
                    headers: {
                        "X-HTTP-Method-Override": "PUT",
                    },
                })
                    .done((response) => {
                        updateButton.removeAttribute("data-kt-indicator");
                        updateButton.disabled = false;

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
                                location.reload();
                            }
                        });
                    })
                    .fail((xhr, status, error) => {
                        updateButton.removeAttribute("data-kt-indicator");
                        updateButton.disabled = false;

                        Swal.fire(
                            Object.assign(
                                {
                                    text: "Failed update menu!. " + error,
                                    icon: "error",
                                },
                                errorSwal
                            )
                        );
                    });
            });
        });
    };

    let handleUpdateMenu = () => {
        const updateButton = document.querySelector("#button_update_menu");

        updateButton.addEventListener("click", function () {
            updateButton.setAttribute("data-kt-indicator", "on");
            updateButton.disabled = true;
            blockUI.block();

            let data = menuList.nestable("serialize");

            $.ajax({
                url: $(this).data("url"),
                type: "POST",
                cache: false,
                data: {
                    data: data,
                    _token: token,
                },
                headers: {
                    "X-HTTP-Method-Override": "PUT",
                },
            })
                .done((response) => {
                    updateButton.removeAttribute("data-kt-indicator");
                    updateButton.disabled = false;
                    blockUI.release();

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
                            location.reload();
                        }
                    });
                })
                .fail((xhr, status, error) => {
                    updateButton.removeAttribute("data-kt-indicator");
                    updateButton.disabled = false;
                    blockUI.release();

                    Swal.fire(
                        Object.assign(
                            {
                                text: "Failed update menu!. " + error,
                                icon: "error",
                            },
                            errorSwal
                        )
                    );
                });
        });
    };

    let handleRefreshMenu = () => {};

    return {
        init: function () {
            initNestedMenu();
            handleAddMenu();
            handleEditMenu();
            handleUpdateMenu();
            handleRefreshMenu();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTNestedMenus.init();
});
