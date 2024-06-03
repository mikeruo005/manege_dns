$(function() {
    var instances = {};
    var platforms = [
        {
            name: "yhz",
            domain: "ehzcs.com",
            title: "<p>源自凤凰，系出名门！</p>作为凤凰集团旗下历史最悠久的平台，一号站自2003年成立以来一直稳居业内彩票平台前三甲。</br>多方权威机构认证，大品牌信誉保障。</br>一号站将持续为广大玩家带来急速的充提体验，提供最安全、稳定的平台服务。"
        },
        {
            name: "2hz",
            domain: "2hzcs.com",
            title: "<p>同系名门，突破创新！</p>凤凰集团2018全新重磅力作！二号站完美传承一号站优良口碑</br>全网彩种玩法最全+凤凰集团大力扶持自主彩种</br>令人耳目一新的游戏体验等您来赢！"
        },
        {
            name: "bm",
            domain: ["bmcs518.com", "bmcs8.com", "bmcs008.com"],
            title: "<p>凤凰集团旗下权威认证娱乐平台！</p>自2014年上线以来，备受玩家认可的博猫游戏一直致力于构建合法、安全、专业的线上平台。</br>在提供彩票游戏、竞彩足球、电子娱乐场等多元化游戏服务的同时，满足您的多重需求！<br>选博猫，赢世界！"
        }
    ];
    function getPlatform(type, val) {
        if (type === "name") {
            return platforms.filter(function (platform) {
                return platform.name === val;
            })[0] || null;
        } else if (type === "domain") {
            return platforms.filter(function(platform) {
                var domain = platform.domain;
                if (typeof domain === "string") {
                    domain = [domain];
                }
                return domain.filter(function (_d) {
                    return val.indexOf(_d) >= 0;
                }).length === 1;
            })[0] || null;
        }
    }
    function switchPlatform(platform) {
        if ($(this).hasClass("active")) return;
        var targetDom = $("#" + platform.name);
        var titleDom = $("#title");
        titleDom.html(platform.title);

        //让内容框的第 _index 个显示出来，其他的被隐藏
        targetDom.show().siblings().hide();
        instances[platform.name].reset();
        //改变选中时候的选项框的样式，移除其他几个选项的样式
        $(this).addClass("active").siblings().removeClass("active");
    }
    $.ajax({
        url: 'domain_list.php',
        contentType: 'application/json',
        success: function (data) {
            var data = JSON.parse(data);
            Object.keys(data).forEach(function (id) {
                instances[id] = new accessSpeed(data[id], "#" + id);
            });

            $(".Retest").on("click", function () {
                Object.keys(instances).forEach(function (id) {
                    instances[id].reset();
                })
            });

            $(".tab-logo div").hover(function () {
                switchPlatform.call(this, getPlatform("name", $(this).attr("htmlFor")));
            });
            $(".tab-logo div").click(function () {
                switchPlatform.call(this, getPlatform("name", $(this).attr("htmlFor")));
            });

            var locationHost = location.host, matchDomain = null, logo = $(".tab-logo div"), index;
            if(locationHost.indexOf('12hzcs.com') === -1){
                matchDomain = getPlatform("domain", locationHost);
            }
            if (matchDomain !== null) {
                index = platforms.indexOf(matchDomain);
                logo = logo.eq(index);
                switchPlatform.call(logo, matchDomain);
                logo.insertBefore(logo.siblings().eq(0));

                $(".tab-logo div").css('display','none');
                logo.css('display','block');
            } else {
                logo = logo.eq(0);
                switchPlatform.call(logo, getPlatform("name", "yhz"));
            }
        },
        error: function (error) {
            throw error;
        }
    });
});