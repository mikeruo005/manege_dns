define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'domains/index' + location.search,
                    add_url: 'domains/add',
                    //edit_url: 'domains/edit',
                    del_url: 'domains/del',
                    multi_url: 'domains/multi',
                    import_url: 'domains/import',
                    table: 'domains',
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
                        // {field: 'id', title: __('Id')},
                        {field: 'cloud_type', title: __('cloud_type'), searchList: {"huawei":__('华为云'),"aliyun":__('阿里云')}, formatter: Table.api.formatter.normal},
                        {field: 'account_id', title: __('Account_id'), operate: 'LIKE'},
                        {field: 'DomainName', title: __('Domainname'), operate: 'LIKE'},
                        // {field: 'DomainId', title: __('Domainid')},
                        // {field: 'Punycode', title: __('Punycode'), operate: 'LIKE'},
                        {field: 'DnsServers', title: __('Dnsservers')},
                        // {field: 'DomainLoggingSwitchStatus', title: __('Domainloggingswitchstatus'), searchList: {"OPEN":__('Open'),"CLOSE":__('Close')}, formatter: Table.api.formatter.status},
                        // {field: 'VersionCode', title: __('Versioncode'), operate: 'LIKE'},
                        // {field: 'VersionName', title: __('Versionname'), operate: 'LIKE'},
                        {field: 'RecordCount', title: __('Recordcount')},
                        {field: 'CreateTime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate},
                        // {field: 'Tags', title: __('Tags'), formatter: Table.api.formatter.flag},
                        // {field: 'CreateTimestamp', title: __('Createtimestamp')},
                        {field: 'operate', title: __('解析'), table: table, events: Table.api.events.operate, formatter: function(value, row, index) {
                                if(row['cloud_type'] == 'huawei'){
                                    var turl = 'record/huawei?domain='+row.DomainName;
                                }else{
                                    var turl = 'record/aliyun?DomainName='+row.DomainName;
                                }
                                var operateButtons = [
                                    '<a class="btn btn-xs btn-success jump-btn" href="'+turl+'"><i class="fa fa-check"></i> ' + __('查看') + '</a>',
                                ];

                                return operateButtons.join('');
                            }},

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
