define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'record/huawei/index' + location.search,
                    add_url: 'record/huawei/add' + location.search,
                    edit_url: 'record/huawei/edit',
                    del_url: 'record/huawei/del',
                    multi_url: 'record/huawei/multi',
                    import_url: 'record/huawei/import',
                    table: 'record_huawei',
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
                        // {field: 'zone_id', title: __('Zone_id'), operate: 'LIKE'},
                        {field: 'domain', title: __('Domain'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), searchList: {"A":__('A'),"NS":__('Ns'),"MX":__('Mx'),"TXT":__('Txt'),"CNAME":__('Cname'),"SRV":__('Srv'),"AAAA":__('Aaaa')}, formatter: Table.api.formatter.normal},
                        {field: 'rr', title: __('Rr'), operate: 'LIKE'},
                        {field: 'value', title: __('Value'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'ttl', title: __('ttl')},
                        {field: 'status', title: __('Status'), searchList: {"ACTIVE":__('ACTIVE'),"ERROR":__('ERROR'),"DISABLE":__('DISABLE'),"FREEZE":__('FREEZE'),"PENDING_CREATE":__('PENDING_CREATE'),"PENDING_UPDATE":__('PENDING_UPDATE'),"PENDING_DELETE":__('PENDING_DELETE')}, formatter: Table.api.formatter.normal},
                        {field: 'descript', title: __('Descript'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
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
