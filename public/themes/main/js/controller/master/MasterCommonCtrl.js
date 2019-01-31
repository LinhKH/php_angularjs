AgencyManagementApp.controller('MasterCommonCtrl', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile',
    function ($scope, $rootScope, commonService, $timeout, $compile) {
        $scope.urlList = 'listMasterCommon';
        $scope.urlUpsert = 'upsertMasterCommon';
        $scope.urlDelete = 'deleteMasterCommon';
        $scope.urlDetail = 'detailMasterCommon';
        $scope.title = '汎用マスタ';

        /** use hold data for input like: select,radio check, checkbox */
        $scope.arrBoolean = [ 
            {'val':'無','key':'0'},
            {'val':'有','key':'1'} 
        ];
        
        /** List form fields */
        $scope.arrFields = [
            {   
                'key':'mst_id','label':'マスタID','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'mst_nm','label':'マスタ名','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'elem_change_ok_flg','label':'要素変更可否フラグ','type': 'select','data_source':{'data':'arrBoolean','disp_val':'val','code_val':'key'},'attr':['multi']
            },
            {
                'key':'code_len','label':'コード桁数','type': 'text','data_source':{},'attr':['numbers-only']
            },
            {
                'key':'code_value','label':'コード値','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'disp_value','label':'表示値','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'sort_value','label':'ソート順','type': 'text','data_source':{},'attr':['numbers-only']
            },
        ];

        /** Config table seach result */
        $scope.arrTableData = [
            {
                'label':'No','content':'{{($index+1) + (currentPage-1)*pageSize}}', 'class':'w-10p'
            },
            {
                'label':'マスタID','content':'{{item.mst_id}}'
            },
            {
                'label':'マスタ名','content':'{{item.mst_nm}}'
            },
            {
                'label':'要素変更可否フラグ','content':'{{arrBoolean[item.elem_change_ok_flg].val}}'
            },
            {
                'label':'コード桁数','content':'{{item.code_len}}'
            },
            {
                'label':'コード値','content':'{{item.code_value}}'
            },
            {
                'label':'表示値','content':'{{item.disp_value}}'
            },

            {
                'label':'ソート順','content':'{{item.sort_value}}'
            },
            {
                'label':'編集',
                'content': '<button ng-click="edit(item.id)" class="btn btn-primary" type="button">編集</button>'
                                    +'<button ng-click="confirmDelete(item.id)" class="btn btn-danger" type="button">削除</button>',
                'class': 'text-center'
            }
        ];
        
        $scope.createTableHeader = function () {
            var header = '';
            var count = 1;
            angular.forEach($scope.arrTableData, function (v, i) {
                if (typeof v.label == 'undefined') {
                    v.label = '';
                }
                if(count==1) {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey" style="width:3% !important;">' + v.label + '</th>';
                } else {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey">' + v.label + '</th>';
                }
                count++;
            });
            $scope.tableHeader = '<tr>' + header + '</tr>';
        };
        
        $scope.createTableContent = function () {
            var content = '';
            var count = 1;
            angular.forEach($scope.arrTableData, function (v, i) {
                if (typeof v.content == 'undefined') {
                    v.content = '';
                }
                if(count==1) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" style="width:3% !important;">' + v.content + '</td>';
                } else if(count==9) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" align="center">' + v.content + '</td>';
                } else {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1">' + v.content + '</td>';
                }
                count++;

            });
            $scope.tableContent = '<tr dir-paginate="item in list|itemsPerPage:pageSize" current-page="currentPage" total-items="totalItems" class="view">' + content + '</tr>';
        };

        $scope.$on('$viewContentLoaded', function () {
            
        });


    }
]);
