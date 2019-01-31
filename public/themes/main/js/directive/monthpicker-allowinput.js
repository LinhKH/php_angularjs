AgencyManagementApp.directive('monthpickerAllowInput', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            $(function () {
                var text = 'dpa' + Math.floor((Math.random() * 1000000000) + 1);
                $(element).attr('id', text);
                $("#" + text).MonthPicker({
                	ShowIcon: false,
			        MonthFormat: "yy/mm",
			        i18n: {
			            year: '年',
			            jumpYears: "年選択",
			            backTo: "年に戻る",
			            months: ["01月","02月","03月","04月","05月","06月","07月","08月","09月","10月","11月","12月"]
			        },
			        Position: {
			            my: "left bottom",
			            at: "left top",
			            collision: "none"
			        }
                });

                $("#" + text).keydown(function (e) {
			        if(e.keyCode === 13) {			        	
			            $("#" + text).MonthPicker('Close');			      
			            ngModelCtrl.$setViewValue($("#" + text).val());
			        }
			    });
			    /*
			     * Format Input Method, only number and special character.
			     */
			    $("#" + text).bind('keypress', function (event) {
			    	var regex = new RegExp("^[a-zA-Z]+$");
			        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
			        if (regex.test(key)) {
			           event.preventDefault();
			           return false;
			        }
			    });
			    
			    /*
			     * Mask Input Date
			     */
			    $("#" + text).bind('keyup','keydown', function(event){
			        datestr = $(this).val();
			        if (event.which !== 8) {
			            var num = datestr.length;
			            if (num == 4){
			                var newdatestr = datestr;
			                newdatestr += '/';
			                $(this).val(newdatestr);
			            }
			        }
			    });
			    
			    /*
			     * Select when focus.
			     */
			    
			    $("#" + text).focus(function(){
			        $(this).select();
			    });

			    /*
			     * Validation date value. Return now.
			     */
			    
			    $("#" + text).on("blur", function(event){
			        ngModelCtrl.$setViewValue($("#" + text).val());
			    });
			    
			    $("#" + text).on("keyup", function(event){
			        ngModelCtrl.$setViewValue($("#" + text).val());
			    });
			    
            });
        }
    };
});
