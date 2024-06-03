define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'record/aliyun/index' + location.search,
                    add_url: 'record/aliyun/add' + location.search,
                    edit_url: 'record/aliyun/edit',
                    del_url: 'record/aliyun/del',
                    multi_url: 'record/aliyun/multi',
                    import_url: 'record/aliyun/import',
                    table: 'record_aliyun',
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
                        {field: 'DomainName', title: __('Domainname'), operate: 'LIKE'},
                        {field: 'RR', title: __('Rr'), operate: 'LIKE'},
                        {field: 'Type', title: __('Type'), searchList: {"A":__('A'),"NS":__('Ns'),"MX":__('Mx'),"TXT":__('Txt'),"CNAME":__('Cname'),"SRV":__('Srv'),"AAAA":__('Aaaa')}, formatter: Table.api.formatter.normal},
                        {field: 'Value', title: __('Value'), operate: 'LIKE'},
                        {field: 'ttl', title: __('ttl')},
                        //{field: 'Status', title: __('Status'), searchList: {"ACTIVE":__('ACTIVE'),"ERROR":__('ERROR'),"DISABLE":__('DISABLE'),"FREEZE":__('FREEZE'),"PENDING_CREATE":__('PENDING_CREATE'),"PENDING_UPDATE":__('PENDING_UPDATE'),"PENDING_DELETE":__('PENDING_DELETE')}, formatter: Table.api.formatter.normal},
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
