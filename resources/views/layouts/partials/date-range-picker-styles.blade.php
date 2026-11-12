<style>
    .daterangepicker {
        z-index: 3002 !important;
        max-width: calc(100vw - 16px) !important;
        color: #1d3557;
        font-family: inherit;
        line-height: 1.4;
        border: 1px solid #dfeeff;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(29, 53, 87, 0.16);
    }

    .daterangepicker,
    .daterangepicker * {
        box-sizing: border-box;
    }

    #agenda-filter-menu,
    #supervisi-filter-menu {
        overflow: visible;
    }

    .daterangepicker .drp-calendar {
        box-sizing: border-box;
        max-width: 278px;
    }

    .daterangepicker .calendar-table {
        overflow: visible;
        border: 0;
        background-color: #fff;
    }

    .daterangepicker .calendar-table table {
        box-sizing: border-box;
        width: 100%;
        min-width: 0;
        max-width: 100%;
        margin: 0;
        border: 0;
        border-spacing: 0;
        border-collapse: separate;
        table-layout: fixed;
        background-color: #fff;
    }

    #kt_content .daterangepicker .calendar-table table {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        margin: 0 !important;
        font-family: inherit !important;
        background-color: #fff !important;
    }

    #kt_content .daterangepicker .calendar-table table thead,
    #kt_content .daterangepicker .calendar-table table thead tr,
    #kt_content .daterangepicker .calendar-table table thead th,
    #kt_content .daterangepicker .calendar-table table tbody,
    #kt_content .daterangepicker .calendar-table table tbody tr {
        background-color: #fff !important;
        color: #1d3557 !important;
        border-color: transparent !important;
    }

    #kt_content .daterangepicker .calendar-table table tbody td {
        border-color: transparent !important;
    }

    .daterangepicker .calendar-table th,
    .daterangepicker .calendar-table td {
        box-sizing: border-box;
        width: 32px;
        min-width: 32px;
        height: 34px;
        padding: 4px 2px;
        font-family: inherit;
        font-size: 0.8125rem;
        line-height: 1.25;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    .daterangepicker .calendar-table th.month {
        width: auto;
        min-width: 0;
        font-size: 0.875rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .daterangepicker .calendar-table th.prev,
    .daterangepicker .calendar-table th.next {
        min-width: 32px;
    }

    .daterangepicker .calendar-table th.prev span,
    .daterangepicker .calendar-table th.next span {
        border-color: #59749a;
        border-width: 0 2px 2px 0;
        display: inline-block;
    }

    .daterangepicker .calendar-table th.prev:hover span,
    .daterangepicker .calendar-table th.next:hover span {
        border-color: #009ef7;
    }

    .daterangepicker .calendar-table th.week,
    .daterangepicker .calendar-table td.week {
        width: 28px;
        min-width: 28px;
        color: #7e8299;
    }

    .daterangepicker td.off,
    .daterangepicker td.off.in-range,
    .daterangepicker td.off.start-date,
    .daterangepicker td.off.end-date {
        color: #b5b5c3;
        background-color: transparent;
    }

    #kt_content .daterangepicker .calendar-table table tbody td.off,
    #kt_content .daterangepicker .calendar-table table tbody td.off.in-range,
    #kt_content .daterangepicker .calendar-table table tbody td.off.start-date,
    #kt_content .daterangepicker .calendar-table table tbody td.off.end-date {
        color: #b5b5c3 !important;
    }

    #kt_content .daterangepicker .calendar-table table tbody tr:hover td {
        background-color: transparent !important;
    }

    #kt_content .daterangepicker .calendar-table table tbody td.available:hover:not(.active):not(.in-range):not(.off) {
        background-color: #f1faff !important;
    }

    .daterangepicker td.in-range:not(.active) {
        color: #1d3557;
        background-color: #eaf3ff;
    }

    #kt_content .daterangepicker .calendar-table table tbody td.in-range:not(.active) {
        background-color: #eaf3ff !important;
    }

    .daterangepicker td.active,
    .daterangepicker td.active:hover,
    .daterangepicker .ranges li.active {
        color: #fff;
        background-color: #009ef7;
    }

    #kt_content .daterangepicker .calendar-table table tbody td.active,
    #kt_content .daterangepicker .calendar-table table tbody td.active:hover {
        color: #fff !important;
        background-color: #009ef7 !important;
    }

    .daterangepicker select.monthselect,
    .daterangepicker select.yearselect {
        box-sizing: border-box;
        height: 28px;
        min-height: 0;
        margin: 0;
        padding: 3px 5px;
        color: #1d3557;
        font-family: inherit;
        font-size: 0.8125rem;
        line-height: 1.25;
        border: 1px solid #dfeeff;
        border-radius: 6px;
        background-color: #fff;
    }

    .daterangepicker .drp-buttons {
        box-sizing: border-box;
        padding: 8px 12px;
        line-height: 1.4;
        border-top: 1px solid #dfeeff;
    }

    .daterangepicker.show-calendar:not(.auto-apply) .drp-buttons {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .daterangepicker.auto-apply .drp-buttons {
        display: none !important;
    }

    .daterangepicker .drp-selected {
        flex: 1 1 100%;
        padding: 0;
        color: #7e8299;
        font-size: 0.8125rem;
        text-align: left;
    }

    .daterangepicker .drp-buttons .btn {
        flex: 0 0 auto;
        min-width: 76px !important;
        min-height: 32px !important;
        margin: 0 !important;
        padding: 6px 12px !important;
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        line-height: 1.25 !important;
        white-space: nowrap;
        border-radius: 6px !important;
    }

    @media (max-width: 767.98px) {
        .daterangepicker {
            width: calc(100vw - 16px) !important;
            max-width: 360px !important;
            max-height: calc(100vh - 16px);
            overflow-y: auto;
        }

        .daterangepicker.show-calendar .ranges {
            float: none !important;
            width: auto !important;
            height: auto !important;
            max-height: 120px;
            margin: 0 0 6px;
            overflow-y: auto;
        }

        .daterangepicker.show-calendar .drp-calendar {
            float: none !important;
            display: block;
            width: 100% !important;
            max-width: 100% !important;
            padding: 8px !important;
        }

        .daterangepicker.show-calendar .drp-calendar.right {
            margin-left: 0 !important;
        }
    }
</style>
