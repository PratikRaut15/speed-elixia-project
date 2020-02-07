function createAgGrid(gridId, gridColumns, gridData, gridCustomOptions){
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var varRowSelection =  'multiple';
    var varRowGroupPanelShow = 'always';
    var varPivotPanelShow = 'always';
    var varFloatingFilter = true;
    var varEnableFilter = true;
    var varEnableSorting = true;
    var varUnSortIcon = true;
    var varSuppressRowClickSelection =  true;
    var varGroupSelectsChildren = true;
    var varEnableRangeSelection = true;
    var varPagination = true;
    var varPaginationPageSize = 10;
    var varRowGroup = true;
    var varFilter = 'agTextColumnFilter';
    var varComponant = '';
    var varOnGridReady = '';
    if (gridCustomOptions !== "") {
        /*
        param : rowSelection
        default : multiple
        functionality : set to either 'single' or 'multiple' to enable selection.
        Single sets to single row selection, such that when you select a row,
        the previously selected row gets unselected. Multiple allows multiple row selection
        */
        if (typeof gridCustomOptions['rowSelection'] !== "undefined") {
            varRowSelection = gridCustomOptions['rowSelection'];
        }
        /*
        param : rowGroupPanelShow
        default : always
        functionality : When to show the 'row group panel' (where you drag rows to group) at the top.
        Default is never. Set to either 'always' or 'onlyWhenGrouping'
        */
        if (typeof gridCustomOptions['rowGroupPanelShow'] !== "undefined") {
            varRowGroupPanelShow = gridCustomOptions['rowGroupPanelShow'];
        }
        /*
        param : pivotPanelShow
        default : always
        functionality : When to show the 'pivot panel' (where you drag rows to pivot) at the top.
        Default is never. Set to either 'always' or 'onlyWhenPivoting'.
        Note that the pivot panel will never show if pivotMode is off.
        */
        if (typeof gridCustomOptions['pivotPanelShow'] !== "undefined") {
            varPivotPanelShow = gridCustomOptions['pivotPanelShow'];
        }
        /*
        param : floatingFilter
        default : true
        Floating Filter components allow you to add your own floating filter types to ag-Grid. Use this:
        When the provided floating filter for a provided filter does not meet your requirements and you want to replace with one of your own.
        When you have a custom filter and want to provide a floating filter for your custom filter.
        */
        if (typeof gridCustomOptions['floatingFilter'] !== "undefined") {
            varFloatingFilter = gridCustomOptions['floatingFilter'];
        }
        /*
        param : enableFilter
        default : true
        Set to true when using Client-side Row Model to enable Row Sorting. Clicking a column header will cause the grid to sort the data.
        */
        if (typeof gridCustomOptions['enableFilter'] !== "undefined") {
            varEnableFilter = gridCustomOptions['enableFilter'];
        }
        /*
        param : enableSorting
        default : true
         Turn sorting on for the grid by enabling sorting in the grid options.
         Sort a column by clicking on the column header.
        */
        if (typeof gridCustomOptions['enableSorting'] !== "undefined") {
            varEnableSorting = gridCustomOptions['enableSorting'];
        }
        /*
        param : unSortIcon
        default : true
        Set to true to show the 'no sort' icon
        */
        if (typeof gridCustomOptions['unSortIcon'] !== "undefined") {
            varUnSortIcon = gridCustomOptions['unSortIcon'];
        }
        /*
        param : suppressRowClickSelection
        default : true
        If true, row selection won't happen when rows are clicked. Use when you want checkbox selection exclusively.
        */
        if (typeof gridCustomOptions['suppressRowClickSelection'] !== "undefined") {
            varSuppressRowClickSelection = gridCustomOptions['suppressRowClickSelection'];
        }
        /*
        param : groupSelectsChildren
        default : true
        If true, row selection won't happen when rows are clicked. Use when you want checkbox selection exclusively.
        */
        if (typeof gridCustomOptions['groupSelectsChildren'] !== "undefined") {
            varGroupSelectsChildren = gridCustomOptions['groupSelectsChildren'];
        }
        /*
        param : enableRangeSelection
        default : true
        Set to true to enable
        */
        if (typeof gridCustomOptions['enableRangeSelection'] !== "undefined") {
            varEnableRangeSelection = gridCustomOptions['enableRangeSelection'];
        }
        /*
        param : pagination
        default : true
        True - Pagination is enabled.
        False (Default) - Pagination is disabled.
        */
        if (typeof gridCustomOptions['pagination'] !== "undefined") {
            varPagination = gridCustomOptions['pagination'];
        }
        /*
        param : paginationPageSize
        default : 20
        Number. How many rows to load per page. Default value = 100.
        If paginationAutoPageSize is specified, this property is ignored. See example Customising Pagination.
        */
        if (typeof gridCustomOptions['paginationPageSize'] !== "undefined") {
            varPaginationPageSize = gridCustomOptions['paginationPageSize'];
        }
        /*
        param : filter
        default : 20
        */
        if (typeof gridCustomOptions['filter'] !== "undefined") {
            varFilter = gridCustomOptions['filter'];
        }
        if (typeof gridCustomOptions['components'] !== "undefined") {
            varComponant = gridCustomOptions['components'];
        }
        if (typeof gridCustomOptions['onGridReady'] !== "undefined") {
            varOnGridReady = gridCustomOptions['onGridReady'];
        }
        console.log(varOnGridReady);
    }

    gridOptions = {
        rowSelection: varRowSelection,
        rowGroupPanelShow: varRowGroupPanelShow,
        pivotPanelShow: varPivotPanelShow,
        floatingFilter: varFloatingFilter,
        enableFilter: varEnableFilter,
        enableSorting: varEnableSorting,
        unSortIcon: varUnSortIcon,
        suppressRowClickSelection: varSuppressRowClickSelection,
        groupSelectsChildren: varGroupSelectsChildren,
        enableRangeSelection: varEnableRangeSelection,
        pagination: varPagination,
        paginationPageSize: varPaginationPageSize,
        columnDefs: gridColumns,
        rowData: gridData,
        defaultColDef: {
            enableRowGroup: varRowGroup,
            enablePivot: false,
            enableValue: true,
            filter: varFilter
        },
        components: varComponant,
        onGridReady: varOnGridReady,
        animateRows: true,
        debug: true,
        suppressSizeToFit : true,
    };

    new agGrid.Grid(gridId,gridOptions);
    return gridOptions;
}
