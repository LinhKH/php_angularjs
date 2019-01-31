AgencyManagementApp.directive('datetimepicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            $(function () {
                var text = 'dpa' + Math.floor((Math.random() * 1000000000) + 1);
                $(element).attr('id', text);
                $(element).attr('readonly', true);
                $("#" + text).datetimepicker({
                    showButtonPanel: true,
                    beforeShow: function (input) {
                        // load button clear
                        setTimeout(function () {
                            var buttonPane = $(input).datepicker('widget').find('.ui-datepicker-buttonpane');
                            $('<button>', {
                                text: 'クリア',
                                click: function () {
                                    $.datepicker._clearDate(input);
                                    ngModelCtrl.$setViewValue(null);
                                }
                            }).appendTo(buttonPane).addClass('ui-datetimepicker-clear ui-state-default ui-priority-primary ui-corner-all');
                        }, 1);
                    },
                    onSelect: function (date, input) {
                        scope.$apply(function () {
                            ngModelCtrl.$setViewValue(date);
                        });
                        var countBtnClear = $(input).datepicker('widget').find('.ui-datepicker-buttonpane > .ui-datetimepicker-clear').length;

                        // reload button clear
                        setTimeout(function () {
                            $(input).datepicker('widget').find('.ui-datepicker-buttonpane > .ui-datetimepicker-clear').remove();
                            var buttonPane = $(input).datepicker('widget').find('.ui-datepicker-buttonpane');
                            $('<button>', {
                                text: 'クリア',
                                click: function () {
                                    if (typeof input.regional === 'undefined') {
                                        $.datepicker._clearDate(input);
                                        $(input).val("");
                                        ngModelCtrl.$setViewValue(null);
                                    } else if (typeof input.regional !== 'undefined') {
                                        input.hour = '00';
                                        input.minute = '00';
                                        $.datepicker._clearDate(input.inst.input);
                                        $(input.inst.input).val('');
                                        ngModelCtrl.$setViewValue(null);
                                    }
                                }
                            }).appendTo(buttonPane).addClass('ui-datetimepicker-clear ui-state-default ui-priority-primary ui-corner-all');
                        }, 1);
                    }
                });
            });
        }
    };
});
