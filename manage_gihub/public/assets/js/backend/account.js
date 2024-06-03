define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'account/index' + location.search,
                    add_url: 'account/add',
                    edit_url: 'account/edit',
                    del_url: 'account/del',
                    multi_url: 'account/multi',
                    import_url: 'account/import',
                    table: 'account',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        // {field: 'id', title: __('Id')},
                        {field: 'cloud_type', title: __('Cloud_type'), searchList: {"huawei":__('Huawei'),"aliyun":__('Aliyun')}, formatter: Table.api.formatter.normal},
                        // {field: 'key', title: __('Key'), operate: 'LIKE'},
                        // {field: 'secrect', title: __('Secrect'), operate: 'LIKE'},
                        {field: 'user', title: __('User'), operate: 'LIKE'},
                        {field: 'iam_name', title: __('Iam_name'), operate: 'LIKE'},
                        // {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
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
