AgencyManagementApp.controller('MasterOamachineCtrl', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile',
    function ($scope, $rootScope, commonService, $timeout, $compile) {
        $scope.urlList = 'listMasterOamachine';
        $scope.urlUpsert = 'upsertMasterOamachine';
        $scope.urlDelete = 'deleteMasterOamachine';
        $scope.urlDetail = 'detailMasterOamachine';
        $scope.title = 'OA機器マスタ';

        /** use hold data for input like: select,radio check, checkbox */
        $scope.arrBoolean = [ 
            
        ];
        
        /** List form fields */
        $scope.arrFields = [
            {   
                'key':'maker_nm','label':'メーカー','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'type_cd','label':'種別','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'product_nm','label':'商品名','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'model_nm','label':'型式','type': 'text','data_source':{},'attr':[]
            }
        ];

        /** Config table seach result */
        $scope.arrTableData = [
            {
                'label':'No','content':'{{($index+1) + (currentPage-1)*pageSize}}','class':'w-10p'
            },
            {
                'label':'メーカー','content':'{{item.maker_nm}}'
            },
            {
                'label':'種別','content':'{{item.type_cd}}'
            },
            {
                'label':'商品名','content':'{{item.product_nm}}'
            },
            {
                'label':'型式','content':'{{item.model_nm}}'
            },
            {
                'label':'編集',
                'content': '<button ng-click="edit(item.id)" class="btn btn-primary" type="button">編集</button>'
                                    +'<button ng-click="confirmDelete(item.id)" class="btn btn-danger" type="button">削除</button>',
                'class': 'text-center'
                
            }
        ];
        
        $scope.$on('$viewContentLoaded', function () {
            
        });


    }
]);
