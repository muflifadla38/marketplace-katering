const closeModal = (target) => {
    setTimeout(() => {
        $("button.swal2-confirm.btn.btn-primary").attr({
            "data-bs-dismiss": "modal",
            "data-bs-target": target,
        });
    }, 50);
};
