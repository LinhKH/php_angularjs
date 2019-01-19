bookApp.config(function (paginationTemplateProvider, blockUIConfig) {
  paginationTemplateProvider.setPath(baseURL + 'themes/default/html/partials/dirPagination.tpl.html');
  blockUIConfig.autoBlock = false;
  blockUIConfig.templateUrl = baseURL + 'themes/default/html/partials/blockUI.html';
}).run(function (editableOptions) {
  editableOptions.theme = 'default'; // bootstrap3 theme. Can be also 'bs2', 'default'
});
