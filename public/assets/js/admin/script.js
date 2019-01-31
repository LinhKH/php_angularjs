/***** GENERAL *****/
var GENERAL = {
    toggleSidebar: function () {
        $('#nav-icon3').click(function () {
            $(this).toggleClass('open');
        });
    },
    toggleButton: function () {
        $(".sidebar-toggle-button").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    },
    select2Custom: function () {
        $(".select-2-custom").select2({
            placeholder: " ",
            allowClear: true,
            theme: "bootstrap"
        });
    },
    checkIsCollapsed: function () {
        $(".panel-custom").click(function () {
            var flag = $(this).closest(".panel").find(".panel-body").is(":visible");
            if (flag === true) {
                $(this).find("i").removeClass("fa-caret-up");
                $(this).find("i").addClass("fa-caret-down");
            } else {
                $(this).find("i").removeClass("fa-caret-down");
                $(this).find("i").addClass("fa-caret-up");
            }
        });
    },
    inputDateTime: function () {
        $(".input-date").datepicker({
            format: "yyyy/mm/dd",
            todayHighlight: true,
            language: 'ja',
            autoclose: true,
            todayBtn: "linked"
        });
    },
    inputDateTime2: function () {
        $(".input-date-monthyear").datepicker({
            format: "yyyy/mm",
            language: 'ja',
            startView: 1,
            minViewMode: 1,
            autoclose: true,
            todayBtn: "linked"
        });
    },
    inputTime: function () {
        $(".input-time").clockpicker({
            autoclose: true
        });
    },
    inputTypeFileBootstrap: function () {
        $(document).on('change', ':file', function () {
            var input = $(this);
            var numFiles = input.get(0).files ? input.get(0).files.length : 1;
            var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        $(':file').on('fileselect', function (event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text');
            var log = numFiles > 1 ? numFiles + ' files selected' : label;
            if (input.length) {
                input.val(log);
            }
        });
    }
};

/***** GENERAL *****/

$(document).ready(function () {
    //    alert();
    GENERAL.toggleSidebar();
    GENERAL.toggleButton();
    GENERAL.checkIsCollapsed();
    GENERAL.select2Custom();
    GENERAL.inputDateTime();
    GENERAL.inputDateTime2();
    GENERAL.inputTime();
    GENERAL.inputTypeFileBootstrap();
    return false;
});