"use strict";

// Class definition
var KTSettingFeatures = (function () {
    // Shared variables
    const featureElement = $('input[type="checkbox"]');
    const token = $("meta[name='csrf-token']").attr("content");

    // Init activate feature
    var initActivateFeature = () => {
        window.token = token;

        // Submit button handler
        featureElement.on("click", function (e) {
            let toastStatus, toastIcon, toastTitle;
            const element = e.currentTarget;

            element.disabled = true;

            const feature = $(this).val();
            const featureStatus = $(this).prop("checked");

            $.ajax({
                url: `/features/${feature}`,
                type: "POST",
                cache: false,
                data: {
                    active: featureStatus,
                    _token: token,
                },
                headers: {
                    "X-HTTP-Method-Override": "PUT",
                },
            })
                .done((response) => {
                    element.disabled = false;

                    toastStatus = response.status == 'success' ? 'success' : 'danger';
                    toastIcon = response.status == 'success' ? 'check' : 'cross';
                    toastTitle = response.status == 'success' ? 'Success' : 'Error';

                    $("#toast-title").text(toastTitle);
                    $("#toast-message").text(response.message);

                    openToast(toastIcon, toastStatus);

                    if (toastStatus == "danger") {
                        element.prop("checked", !featureStatus);
                    }
                })
                .fail((xhr, status, error) => {
                    element.disabled = false;

                    const textStatus = featureStatus
                        ? "activate"
                        : "deactivate";

                    $("#toast-title").text("Error");
                    $("#toast-message").text(
                        `Failed to ${textStatus} feature. ${error}`
                    );

                    $(this).prop("checked", !featureStatus);
                    openToast("cross", "danger");
                });
        });
    };

    return {
        // Public functions
        init: function () {
            initActivateFeature();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSettingFeatures.init();
});
