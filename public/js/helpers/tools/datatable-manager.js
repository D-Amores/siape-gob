class DataTableManager {
    constructor(selector, data, columns, options = {}) {
        this.selector = selector;
        this.data = data;
        this.columns = columns;
        this.options = options;
        this.table = null;
        this.init();
    }

    init() {
        if (this.table) {
            this.updateData(this.data);
            return;
        }

        const defaultOptions = {
            data: this.data,
            columns: this.columns,
            destroy: true,
            responsive: true,
            pageLength: 10,
            language: { url: window.languageDataTable },
            drawCallback: () => this.activateTooltips(),
            ...this.options, // permite sobrescribir config
        };

        this.table = new DataTable(this.selector, defaultOptions);
        this.activateTooltips();
    }

    updateData(newData) {
        this.table.clear().rows.add(newData).draw();
    }

    activateTooltips() {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
            new bootstrap.Tooltip(el);
        });
    }

    getTable() {
        return this.table;
    }
}

export default DataTableManager;