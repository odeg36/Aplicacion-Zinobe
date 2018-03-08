var app = {
    eliminarDisableDFormSubmit: function () {
        $("form").submit(function (elemento) {
            var form = elemento.target;
            var inputs = $(form).find("[disabled]");

            $(inputs).removeAttr("disabled");
        });
    },
};

$(document).ready(function () {
    app.eliminarDisableDFormSubmit();
});