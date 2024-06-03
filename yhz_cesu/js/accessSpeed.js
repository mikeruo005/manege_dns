function accessSpeed(domains, dom) {
    this.$domNode = $(dom);
    this.domains = domains;

    this.reset();
    return this;
}

accessSpeed.prototype.reset = function() {
    var array = [];
    var _this = this;
    if (this.domains && this.domains.length > 0) {
        this.domains = this.domains.slice(0, 4);
        this.domains.forEach(function (item) {
            var p1 = _this.createImg(item[0]).done(function (time) {
                return {time: time, domain: item[0]};
            });
            array.push(p1);
        });
        $.when.apply(null, array).done(function () {
            _this.createEycmainList(Array.prototype.slice.call(arguments));
        });
    }
};

accessSpeed.prototype.createImg = function (url) {
    var dfd = $.Deferred();
    var img = document.createElement('img');
    url = url.replace("http://", "https://");
    var prefix = url.indexOf("http") === -1 ? "https://" : "";
    img.src = prefix + url + '/favicon.ico';
    img.style.display = 'none';
    var oldTime = new Date().getTime();
    var loadTime = 0;
    img.onload = function () {
        loadTime = new Date().getTime() - oldTime;
        dfd.resolve({time: loadTime / 1000, domain: url});
    };
    img.onerror = function () {
        loadTime = 0;
        dfd.resolve({time: loadTime, domain: url});
    };
    document.body.appendChild(img);
    return dfd;
};

accessSpeed.prototype.createEycmainList = function (array) {
    var data = array.map(function (item, index) {
        return {
            time: item.time,
            domain: item.domain,
            id: index + 1
        }
    });
    var _html = '';
    data = data.sort(function (a, b) {
        return a.time - b.time
    });
    data.forEach(function (item) {
        var statusClass,bgColoer, domain;
        domain = item.domain;
        if (item.time === 0) {
            return;
        } else if (item.time < 1.5) {
            statusClass = "fast";
            bgColoer='Rectangle2Copycoloer'
        } else if (item.time < 2.5) {
            statusClass = "normal";
            bgColoer='Rectangle2Copycoloer2'
        } else {
            statusClass = "slow";
            bgColoer='Rectangle2Copycoloer3'
        }
        domain = domain.indexOf("http") >= 0 ? domain : "http://" + domain;
        domain = domain.indexOf("https") >= 0 ? domain : domain.replace("http", "https");
        _html += (
            '<div class="line_list" data-id=' + item.id + '>'
            + '<p class="linename">线路' + item.id + '</p>'
            + '<div class="coloerRound ' + statusClass + '">'
            + '</div>'
            + '<div class="Rectangle2Copy ' + bgColoer + '">'
            + '<div class="progressing ' + statusClass + '" data-time=' + item.time + '></div>'
            + '</div>'
            + '<a class="login" target="_blank" href="' + domain + '">' + (statusClass === "fast" ? "" : "") + '</a>'
            + '</div>'
        );
    });
    this.$domNode.html(_html);
    this.$domNode.find(">div").each(function (index, item) {
        var $current = $(item).find('.progressing');
        var time = Number($current.attr('data-time'));
        var width = $current.parent().width() - 8;
        var math = time * 100 + (time * 100);
        math = math > 800 ? 800 : math;
        $current.animate({
            width: width * ((1000 - math) / 1000) + 'px'
        }, 1200)
    });
};
