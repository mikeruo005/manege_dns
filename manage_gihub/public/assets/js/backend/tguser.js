define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'tguser/index' + location.search,
                    add_url: 'tguser/add',
                    edit_url: 'tguser/edit',
                    del_url: 'tguser/del',
                    multi_url: 'tguser/multi',
                    import_url: 'tguser/import',
                    department:'tguser/department',
                    table: 'tguser',
                }
            });

            var department_data = $.getJSON('tguser/department');
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
                        {field: 'tg_nick', title: __('Tg_nick'), operate: 'LIKE'},
                        {field: 'tg_account', title: __('Tg_account'), operate: 'LIKE',visible:false,operate:false},
                        {field: 'tg_group_account', title: __('Tg_group_account'),visible:false,operate:false},
                        {field: 'tg_group_name', title: __('Tg_group_name')},
                        {field: 'department_id', title: __('Department_id'),searchList:department_data,formatter: function(value, row, index){
                            return department_data.responseJSON[value];
                            } },
                        {field: 'invite_tg_account', title: __('Invite_tg_account'), operate: 'LIKE',visible:false,operate:false},
                        {field: 'invite_tg_nick', title: __('Invite_tg_nick'), operate: 'LIKE'},
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
