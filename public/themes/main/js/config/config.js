AgencyManagementApp.config(function (paginationTemplateProvider, blockUIConfig) {
    paginationTemplateProvider.setPath(baseURL + 'themes/main/html/partials/dirPagination.tpl.html');
    blockUIConfig.autoBlock = false;
    blockUIConfig.templateUrl = baseURL + 'themes/main/html/partials/blockUI.html';
}).run(function (editableOptions) {
    editableOptions.theme = 'default'; // bootstrap3 theme. Can be also 'bs2', 'default'
});
