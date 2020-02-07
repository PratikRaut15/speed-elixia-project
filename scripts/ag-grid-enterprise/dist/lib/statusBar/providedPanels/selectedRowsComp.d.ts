// ag-grid-enterprise v19.0.0
import { IStatusPanelComp } from 'ag-grid-community';
import { NameValueComp } from "./nameValueComp";
export declare class SelectedRowsComp extends NameValueComp implements IStatusPanelComp {
    private eventService;
    private gridApi;
    constructor();
    protected postConstruct(): void;
    private isValidRowModel;
    private onRowSelectionChanged;
    init(): void;
}
