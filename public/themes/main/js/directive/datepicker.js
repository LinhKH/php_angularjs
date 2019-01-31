AgencyManagementApp.directive('datepicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            $(function () {
                $(element).datepicker({
                    format: "yyyy/mm/dd",
                    todayHighlight: true,
                    language: 'ja',
                    autoclose: true,
                    todayBtn: "linked",
                    clearBtn: true
                });
            });
            element.bind('input', function (e) {
                var obj = e.target;
                var value = obj.value;
                var transformedInput = converToUnicode(value);
                transformedInput = transformedInput ? transformedInput.replace(/・/g,'/').replace(/[^\d.\/]/g, '') : null;
                ngModelCtrl.$setViewValue(transformedInput);
                ngModelCtrl.$render();
            });

            scope.$watch(attrs.ngModel, function (val) {
                var defaulVal = val;
                $(element).datepicker('update', defaulVal);
            });
        }
    };
});

// Phong added 7th Oct 2016
AgencyManagementApp.directive('monthpicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        replace: true,
        link: function (scope, element, attrs, ngModelCtrl) {
            $(function () {
                var text = 'dpa' + Math.floor((Math.random() * 1000000000) + 1);
                $(element).attr('id', text);
                $('#' + text).datetimepicker({
                    format: 'YYYY/MM',
                    viewMode: 'months',
                    locale: 'ja'
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

