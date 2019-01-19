bookApp.directive('timepicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        replace: true,
        link: function (scope, element, attrs, ngModelCtrl) {
            $(function () {
                var text = 'dpa' + Math.floor((Math.random() * 1000000000) + 1);
                $(element).attr('id', text);
                $('#' + text).datetimepicker({
                    format: 'HH:mm',
                    useCurrent: false,
                    useStrict: true,
                    allowInputToggle: false,
                    keepInvalid: true,
                    keyBinds: {
                        left: {},
                        right: {}
                    },
                    tooltips: {
                        today: "Go to today",
                        clear: "Clear selection",
                        close: "Close the picker",
                        selectMonth: "Select Month",
                        prevMonth: "Previous Month",
                        nextMonth: "Next Month",
                        selectYear: "Select Year",
                        prevYear: "Previous Year",
                        nextYear: "Next Year",
                        selectDecade: "Select Decade",
                        prevDecade: "Previous Decade",
                        nextDecade: "Next Decade",
                        prevCentury: "Previous Century",
                        nextCentury: "Next Century",
                        pickHour: "",
                        incrementHour: "",
                        decrementHour: "",
                        pickMinute: "",
                        incrementMinute: "",
                        decrementMinute: "",
                        pickSecond: "",
                        incrementSecond: "",
                        decrementSecond: "",
                        togglePeriod: "",
                        selectTime: ""},
                    icons: {
                        up: 'glyphicon glyphicon-chevron-up',
                        down: 'glyphicon glyphicon-chevron-down'
                    }
                }).on('dp.change', function (ev) {
                    if (ev.currentTarget.value) {
                        scope.$apply(function () {
                            ngModelCtrl.$setViewValue(ev.currentTarget.value);
                        });
                    }
                });
            });
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/。/g,'.').replace(/[^\d\*\=\.\.\.\:]/g, ''): null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });
        }
    };
});

bookApp.directive('timePicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        replace: true,
        link: function (scope, element, attrs, ngModelCtrl) {
            ngModelCtrl.$formatters.push(function (inputValue) {
                if (typeof inputValue === 'undefined') {
                    $(element).data('DateTimePicker').clear();// xoa trang thai cua datepicker
                    // $(element).data('DateTimePicker').date(null); // set lai ngay mac dinh
                    ngModelCtrl.$setViewValue('');
                    ngModelCtrl.$render();
                    return '';
                }
            });

            $(function () {
                var text = 'dpa' + Math.floor((Math.random() * 1000000000) + 1);
                $(element).attr('id', text);
                $('#' + text).datetimepicker({
                    format: 'HH:mm',
                    useCurrent: false,//not use current date
                    // showClear: true,
                    // useCurrent:'day',//reset time 00:00
                    // startDate: new Date(),
                    tooltips: {
                        today: "Go to today",
                        clear: "Clear selection",
                        close: "Close the picker",
                        selectMonth: "Select Month",
                        prevMonth: "Previous Month",
                        nextMonth: "Next Month",
                        selectYear: "Select Year",
                        prevYear: "Previous Year",
                        nextYear: "Next Year",
                        selectDecade: "Select Decade",
                        prevDecade: "Previous Decade",
                        nextDecade: "Next Decade",
                        prevCentury: "Previous Century",
                        nextCentury: "Next Century",
                        pickHour: "",
                        incrementHour: "",
                        decrementHour: "",
                        pickMinute: "",
                        incrementMinute: "",
                        decrementMinute: "",
                        pickSecond: "",
                        incrementSecond: "",
                        decrementSecond: "",
                        togglePeriod: "",
                        selectTime: ""},
                    icons: {
                        up: 'glyphicon glyphicon-chevron-up',
                        down: 'glyphicon glyphicon-chevron-down'
                    }
                }).on('dp.change', function (ev) {
                    if (ev.currentTarget.value) {
                        scope.$apply(function () {
                            ngModelCtrl.$setViewValue(ev.currentTarget.value);
                        });
                    }
                });
            });
        }
    };
});
