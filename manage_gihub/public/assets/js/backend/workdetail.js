define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'workdetail/index' + location.search,
                    add_url: 'workdetail/add',
                    edit_url: 'workdetail/edit',
                    del_url: 'workdetail/del',
                    multi_url: 'workdetail/multi',
                    import_url: 'workdetail/import',
                    table: 'workdetail',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),visible:false,operate:false},
                        {field: 'tg_account', title: __('Tg_account'),visible:false,operate:false},
                        {field: 'tg_nick', title: __('Tg_nick'), operate: 'LIKE'},
                        {field: 'start_work_time', title: __('Start_work_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'off_work_time', title: __('Off_work_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'ask_reset_hours', title: __('Ask_reset_hours')},
                        {field: 'leave_table_mins', title: __('Leave_table_mins')},
                        {field: 'later_work_mins', title: __('Later_work_mins')},
                        {field: 'early_work_mins', title: __('Early_work_mins')},
                        {field: 'add_work_mins', title: __('Add_work_mins')},
                        {field: 'leave_times', title: __('Leave_times')},
                        {field: 'dates', title: __('Dates'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'work_status', title: __('Work_status'), searchList: {"0":__('Work_status 0'),"1":__('Work_status 1'),"2":__('Work_status 2'),"3":__('Work_status 3'),"4":__('Work_status 4'),"5":__('Work_status 5'),"6":__('Work_status 6')}, formatter: Table.api.formatter.status},
                        {field: 'set_start_time', title: __('Set_start_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false,operate:false},
                        {field: 'set_off_time', title: __('Set_off_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false,operate:false},
                        {field: 'ask_start_time', title: __('Ask_start_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false,visible:false,operate:false},
                        {field: 'ask_end_time', title: __('Ask_end_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false,visible:false,operate:false},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status,visible:false,operate:false},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false,visible:false,operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false,visible:false,operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
