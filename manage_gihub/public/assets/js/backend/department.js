define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'department/index' + location.search,
                    add_url: 'department/add',
                    edit_url: 'department/edit',
                    del_url: 'department/del',
                    multi_url: 'department/multi',
                    import_url: 'department/import',
                    table: 'department',
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
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'start_work_time', title: __('Start_work_time')},
                        {field: 'off_work_time', title: __('Off_work_time')},
                        {field: 'add_minutes', title: __('Add_minutes')},
                        {field: 'reset_day', title: __('Reset_day'), searchList: {"0":__('Reset_day 0'),"1":__('Reset_day 1'),"2":__('Reset_day 2'),"3":__('Reset_day 3'),"4":__('Reset_day 4'),"5":__('Reset_day 5'),"6":__('Reset_day 6')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status,visible:false,operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false,visible:false,operate:false},
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
