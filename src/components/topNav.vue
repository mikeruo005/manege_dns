<template>
    <div class="eo-top-nav f-cc" data-v-3cdcf58c>
        <div class="header-top" data-v-aeaa857a data-v-3cdcf58c>
            <img src="../assets/images/logo3.png" alt="" class="logo" data-v-aeaa857a>
            <div  class="header-top__nav" data-v-aeaa857a>
                <div @click="$router.push('/')"  :class="{ active: $route.path =='/' }" id="munu_active" data-v-aeaa857a  v-on:mouseenter="showSubMenu" v-on:mouseleave="hideSubMenu">
                    优币
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAKCAYAAAC0VX7mAAAAAXNSR0IArs4c6QAAAERlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAA6ABAAMAAAABAAEAAKACAAQAAAABAAAAFKADAAQAAAABAAAACgAAAABtDdkeAAAA1ElEQVQoFWPs6+vj/P3j2+X/DP+VGRkY88uraiYxkAA621rygHonAvXeZeXg0mUqKir6/p/pfzcjI+N/kARIAbHmwQ0D6QWaATKLGaR5z579Z11cHV8AbfEGGurp6uz8fs/efSfxGYxiGOP/zIqK2pkg9WADQQxSDMVlGIqBxBqKzzAMAwkZSsgwkH5GEIENdHQ0pzP+Z5z+//9/RlDsg9SAYxMUAUhhhq4Xp4EghciGgvjglIDHMJAaeKSAOOgAOaKAhjHgcxm6Xrx8kEtBGK8iqCQAUtSlFD8MgyUAAAAASUVORK5CYII="
                        class="eo-munu-arrow " data-v-aeaa857a>
                    <div class="hover" id="munu_hover" data-v-aeaa857a v-show="isSubMenuVisible">
                        <a  data-v-aeaa857a @click="$router.push('/')" href="#package">
                            <h3 class="eo-n-t-title" data-v-aeaa857a>多链钱包</h3>
                            <p class="eo-n-t-text" data-v-aeaa857a>轻松管理您的多链资产</p><img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAWCAYAAADafVyIAAAA4ElEQVRIS2NkIBF0trdkcXIzrc7Lq3pNjFZGYhTB1HR0NGcy/GOYxsjIcJWJhcOptLT0FSH9JFkwaVKb6Pevf/f//8+gDbKEk5vZkZBPSLIA5FpSLSHZAlItIcsCUiwh2wJiLaHIAgxLGBivcPIwOSFHPMUWgCzp7u4W+/fnxz5w6kKzhCoW4LOEsaOt+T+hzEKOPCMj43VObiZ72ltAjuuw6cEVD1SJA5pGMkrRQe1kSky5RHYQEWM4KK7IsoBYw8mygBTDSbaAVMNJtgBUH////38qsbUZyRaANJBa6QMAd0DRlQhmB64AAAAASUVORK5CYII="
                                class="nav-arrow" data-v-aeaa857a>
                            <div class="glod-line" data-v-aeaa857a></div>
                        </a>
                        <a @click="$router.push('/')" href="#platform"  data-v-aeaa857a>
                            <h3 class="eo-n-t-title" data-v-aeaa857a>场外交易平台</h3>
                            <p class="eo-n-t-text" data-v-aeaa857a>法币数币一键兑换</p><img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAWCAYAAADafVyIAAAA4ElEQVRIS2NkIBF0trdkcXIzrc7Lq3pNjFZGYhTB1HR0NGcy/GOYxsjIcJWJhcOptLT0FSH9JFkwaVKb6Pevf/f//8+gDbKEk5vZkZBPSLIA5FpSLSHZAlItIcsCUiwh2wJiLaHIAgxLGBivcPIwOSFHPMUWgCzp7u4W+/fnxz5w6kKzhCoW4LOEsaOt+T+hzEKOPCMj43VObiZ72ltAjuuw6cEVD1SJA5pGMkrRQe1kSky5RHYQEWM4KK7IsoBYw8mygBTDSbaAVMNJtgBUH////38qsbUZyRaANJBa6QMAd0DRlQhmB64AAAAASUVORK5CYII="
                                class="nav-arrow" data-v-aeaa857a>
                            <div class="glod-line" data-v-aeaa857a></div>
                        </a>
                        <a data-v-aeaa857a>
                            <h3 class="eo-n-t-title" data-v-aeaa857a>下载APP</h3>
                            <p class="eo-n-t-text" data-v-aeaa857a>区块链世界从优币开始</p><img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAWCAYAAADafVyIAAAA4ElEQVRIS2NkIBF0trdkcXIzrc7Lq3pNjFZGYhTB1HR0NGcy/GOYxsjIcJWJhcOptLT0FSH9JFkwaVKb6Pevf/f//8+gDbKEk5vZkZBPSLIA5FpSLSHZAlItIcsCUiwh2wJiLaHIAgxLGBivcPIwOSFHPMUWgCzp7u4W+/fnxz5w6kKzhCoW4LOEsaOt+T+hzEKOPCMj43VObiZ72ltAjuuw6cEVD1SJA5pGMkrRQe1kSky5RHYQEWM4KK7IsoBYw8mygBTDSbaAVMNJtgBUH////38qsbUZyRaANJBa6QMAd0DRlQhmB64AAAAASUVORK5CYII="
                                class="nav-arrow" data-v-aeaa857a>
                        </a>
                    </div>
                </div>
                <div :class="{ active: $route.path =='/solution' }" @click="$router.push('/solution')"  data-v-aeaa857a>
                    开放支付
                </div>
                <div :class="{ active: $route.path =='/download' }" @click="$router.push('/download')" data-v-aeaa857a>
                    下载APP
                </div>
                <!-- <div data-v-aeaa857a>
                      最新公告
                    </div> -->
                <div @click="$router.push('/help')" data-v-aeaa857a>
                    帮助中心
                </div>
            </div>
            <div class="lang" data-v-aeaa857a>
                <div data-v-aeaa857a>
                    <!-- <span class="active" data-v-aeaa857a>中文</span> -->
                    <!-- <em data-v-aeaa857a>|</em> -->
                </div>
                <div data-v-aeaa857a>
                    <!-- <span data-v-aeaa857a>繁体</span> -->
                    <!-- <em data-v-aeaa857a>|</em> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
    return {
        isSubMenuVisible: false
    };
  },
  mounted() {

  },
  methods:{
    showSubMenu() {
      this.isSubMenuVisible = true
    },
    hideSubMenu() {
      this.isSubMenuVisible = false
    },
  },
}
</script>
<style>
 .eo-top-nav {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1999;
      -webkit-transition: all .3s;
      transition: all .3s
    }
    [data-theme=bh] .eo-top-nav {
      background: rgba(19, 78, 238, .9)
    }

    [data-theme=ybf] .eo-top-nav {
      background: rgba(45, 44, 43, .5)
    }
    .eo-top-nav[data-v-3cdcf58c] {
      background: rgba(45, 44, 43, .5)
    }
    .eo-top-nav.top[data-v-3cdcf58c] {
      background: #2d2c2b
    }
    .eo-top-nav>div[data-v-3cdcf58c] {
      width: 100%;
      padding: 0 30px
    }
    .header-top[data-v-aeaa857a] {
      padding: 0 30px;
      height: 80px;
      -webkit-box-pack: justify;
      -ms-flex-pack: justify;
      justify-content: space-between;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      color: #888683
    }
    .header-top[data-v-aeaa857a],
    .header-top .lang[data-v-aeaa857a] {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex
    }

    .header-top .lang span[data-v-aeaa857a] {
      cursor: pointer
    }

    .header-top .lang em[data-v-aeaa857a] {
      font-style: normal;
      margin: 0 8px
    }

    .header-top .lang .active[data-v-aeaa857a] {
      color: #ddba82
    }

    .header-top .header-top__nav[data-v-aeaa857a] {
      height: 80px;
      line-height: 80px
    }

    .header-top .eo-munu-arrow[data-v-aeaa857a] {
      width: 11px;
      height: 6px;
      margin-bottom: 3px;
      margin-left: 6px;
      -webkit-transition: all .3s;
      transition: all .3s
    }

    .header-top .eo-munu-arrow.active[data-v-aeaa857a] {
      -webkit-transform: rotate(-180deg);
      transform: rotate(-180deg)
    }

    .header-top .eo-n-t-text[data-v-aeaa857a],
    .header-top .eo-n-t-title[data-v-aeaa857a] {
      text-align: left
    }

    .header-top__nav[data-v-aeaa857a] {
      -webkit-box-flex: 1;
      -ms-flex: 1;
      flex: 1;
      padding-left: 48px;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex
    }

    .header-top__nav>div[data-v-aeaa857a] {
      position: relative;
      margin-right: 80px;
      cursor: pointer
    }

    .header-top__nav>div[data-v-aeaa857a]:hover {
      color: #ddba82
    }

    .header-top__nav>div.active[data-v-aeaa857a] {
      position: relative;
      color: #ddba82
    }

    .header-top__nav>div.active[data-v-aeaa857a]:after {
      content: "";
      position: absolute;
      left: 50%;
      bottom: 20px;
      margin-left: -10px;
      width: 20px;
      height: 1px;
      background: #ddba82;
      border-radius: 20px
    }

    .header-top .logo[data-v-aeaa857a] {
      width: 108px;
      height: 30px;
      background-size: contain;
      background-position: 50%;
      background-repeat: no-repeat;
      overflow: hidden;
      text-indent: 2000em;
      cursor: pointer
    }

    .header-top .hover[data-v-aeaa857a] {
      /* display: none; */
      height: auto;
      overflow: hidden;
      position: absolute;
      left: 0;
      top: 70px;
      width: 190px;
      background: #2d2c2b;
      border-radius: 8px;
      border: 1px solid #3d3832;
      -webkit-transition: all .5s linear;
      transition: all .5s linear
    }

    .header-top .hover.active[data-v-aeaa857a] {
      display: block
    }

    .header-top .hover .nav-arrow[data-v-aeaa857a] {
      background-size: contain;
      position: absolute;
      right: 8px;
      top: 50%;
      margin-top: -3px;
      vertical-align: middle;
      width: 12px;
      height: 12px
    }

    .header-top .hover .glod-line[data-v-aeaa857a] {
      width: 160px;
      height: 1px;
      bottom: 0;
      background: #3d3832;
      position: absolute;
      z-index: 1000
    }

    .header-top .hover>a[data-v-aeaa857a] {
      text-decoration: none;
      display: block;
      position: relative;
      padding: 12px 15px;
      color: #888683;
      font-size: 12px;
      line-height: 16px
    }

    .header-top .hover>a[data-v-aeaa857a]:hover,
    .header-top .hover>a[data-v-aeaa857a]:visited,
    ß .header-top .hover>a[data-v-aeaa857a]:link {
      text-decoration: none
    }

    .header-top .hover>a:hover h3[data-v-aeaa857a],
    .header-top .hover>a:hover p[data-v-aeaa857a] {
      color: #ddba82
    }

    .header-top .hover>a:hover .nav-arrow[data-v-aeaa857a] {
      background-size: contain
    }

    .header-top .hover>a[data-v-aeaa857a]:active,
    .header-top .hover>a[data-v-aeaa857a]:focus {
      text-decoration: none
    }

    .header-top .hover>a p[data-v-aeaa857a] {
      margin-top: 4px;
      color: #888683
    }

    .header-top .hover>a img[data-v-aeaa857a] {
      position: absolute;
      right: 15px;
      top: 50%;
      margin-top: -6px;
      vertical-align: middle;
      width: 12px;
      height: 12px
    }
</style>