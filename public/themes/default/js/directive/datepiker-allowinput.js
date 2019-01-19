bookApp.directive('datepickerAllowInput', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            $(function () {
                var text = 'dpa' + Math.floor((Math.random() * 1000000000) + 1);
                $(element).attr('id', text);
                $('#' + text).datepicker({
                    dateFormat: 'yy/mm/dd',
                    showButtonPanel: true,
                    constrainInput: false,
                    beforeShow: function (input) {
                        // load button clear
                        setTimeout(function () {
                            $(input).datepicker('widget').find('.ui-datepicker-current').hide();
                            $(input).datepicker('widget').find('.ui-datepicker-close').hide();
                            var buttonPane = $(input).datepicker('widget').find('.ui-datepicker-buttonpane');
                            $('<button>', {
                                text: 'クリア',
                                click: function () {
                                    $.datepicker._clearDate(input);
                                    ngModelCtrl.$setViewValue(null);
                                }
                            }).appendTo(buttonPane).addClass('ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all');
                        }, 100);
                    },
                    onSelect: function (date, input) {
                        scope.$apply(function () {
                            ngModelCtrl.$setViewValue(date);
                        });
                        // reload button clear
                        if ($('.ui-datepicker-clear').length <= 0) {
                            setTimeout(function () {
                                $(input).datepicker('widget').find('.ui-datepicker-current').hide();
                                $(input).datepicker('widget').find('.ui-datepicker-close').hide();
                                var buttonPane = $(input).datepicker('widget').find('.ui-datepicker-buttonpane');
                                $('<button>', {
                                    text: 'クリア',
                                    click: function () {
                                        $.datepicker._clearDate(input);
                                        ngModelCtrl.$setViewValue(null);
                                    }
                                }).appendTo(buttonPane).addClass('ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all');
                            }, 100);
                        }
                    }
                });
            });
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/・/g,'/').replace(/。/g,'.').replace(/[^\d.\/\*\=\.\.\.]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});
