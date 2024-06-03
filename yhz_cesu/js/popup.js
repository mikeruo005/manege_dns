

function popup() {
    this.$domNode = document.createElement("div");
    this.$domNode.className = "Mask";

    $(this.$domNode).on("click", ".Close2", this.hide.bind(this));
    $(this.$domNode).on("click", ".Close", this.hide.bind(this));
    $(this.$domNode).on("click", ".Login_immediately", this.hide.bind(this));
    document.body.appendChild(this.$domNode);
};

popup.prototype.show = function (state, message, jumpto) {
    this.$domNode.innerHTML = this.popupHTML(state, message, jumpto);

    this.$domNode.className = "Mask fadein";
};

popup.prototype.popupHTML = function(state, message, jumpto) {
    var _html = this.popupTpl;
    if (state === true) {
        _html = _html.replace("${MESSAGE_TITLE}", "<div class=\"Eject_right\"></div><p class=\"Eject_success\">验证成功</p>");
        _html = _html.replace("${MESSAGE_CONTENT}", "<p class=\"Eject_success_describe\">" + message + "</p>");
        _html = _html.replace("${BUTTONS}", "<a type=\"submit\" class=\"Login_immediately\" href=\"" + jumpto + "\">立即登录 </a><button type=\"submit\" class=\"Close\" >关闭 </button>");
    } else {
        _html = _html.replace("${MESSAGE_TITLE}", "<div class=\"Eject_right2\"></div><p class=\"Eject_error\">验证失败</p>");
        _html = _html.replace("${MESSAGE_CONTENT}", "<p class=\"Eject_error_describe\">" + message + "</p>");
        _html = _html.replace("${BUTTONS}", "<button type=\"submit\" class=\"Close2\" >关闭 </button>");
    }

    return _html;
};

popup.prototype.hide = function () {
    this.$domNode.className = "Mask";
};

popup.prototype.popupTpl = (
    '<div class="Eject">' +
        '<div class="Eject_font">' +
            '${MESSAGE_TITLE}' +
        '</div>' +
        '${MESSAGE_CONTENT}' +
        '<div class="Eject_button">' +
            '${BUTTONS}' +
        '</div>' +
    '</div>'
);

