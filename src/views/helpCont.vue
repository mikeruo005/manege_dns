<template>
    <div>
        <helpTop></helpTop>
        <div class="site-content">
    <div class="content-area">
        <div class="site-main">
            <div class="post-header">
                <h1 class="post-title">{{cont_list.titel }}</h1>
            </div>
            <div class="kg-canvas" v-for="(itam,ixd) in cont_list.link">
                <p :class="{ active:itam.style == 'red' }">{{ itam.h1 }}</p>
                <img :src="itam.image" class="kg-image">
                <img :src="itam.image1" class="kg-image">
            </div>
        </div>
    </div>
    <div class="related-posts container">
        <h3 class="related-title">
            你可能喜欢...
        </h3>
        <div class="post-feed">
            <h4 class="post-title">
                如何卖出USDT?
            </h4>
            <div class="post-excerpt">
            1、在首页点击“卖币”或“买卖币”按钮进入出售页面。  2、输入需要出售的金额（单位只有USDT无法输入人民币金额）；选择收款方式（
            </div>
        </div>
    </div>

   </div>
    </div>
</template>
<script>
import helpTop from '@/components/help_top.vue'
import { mapState } from 'vuex';
export default {
    components:{
        helpTop
    },
    computed: {
        ...mapState({
            cont:state => state.cont
        }),
    },
    name: 'helpCont',
    data() {
        return{
            cont_list:null
        }
    },
    created() {
    this.cont_list = this.cont; // 在组件的 created 生命周期钩子函数中，将映射的 list 数据赋值给组件的 data 属性 listData
  },
}
</script>
<style>
@import '../assets/css/screen.css';
.post-feed{
    position: relative;
    display: flex;
    padding: 35px 0 33px;
    flex-wrap: wrap;
}
.related-posts{
    margin-top: 60px;
}
.related-title{
    margin-bottom: 0;
    color: #999;
    font-size: 14px;
    letter-spacing: 2px;
    text-transform: uppercase;
}
.post-title{
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: 500;
    width: 100%;
}
.content-area{
    flex-grow: 1;
    padding-top: 60px;
    padding-bottom: 30px;
}
.post-header{
    margin-right: auto;
    margin-left: auto;
    padding-right: 4vw;
    padding-left: 4vw;
    max-width: calc(1130px + 8vw);
    width: 100%;

}
.post-title{
    margin-bottom: 10px;
    font-size: 30px;
    font-weight: 500;
}
.kg-canvas{
    margin-top: 45px;
}
.kg-canvas .active{
    color: red;
}
.kg-image{
    margin-right: auto;
    margin-left: auto;
    display: block;
    max-width: 100%;
    height: auto;
}
.site-content{
    flex-grow: 1;
    padding-top: 60px;
    padding-bottom: 30px;
}
.term{
    margin-top: 30px;
    margin-bottom: 30px;
    text-align: center;
}
.containerBox{
    margin-right: auto;
    margin-left: auto;
    padding-right: 4vw;
    padding-left: 4vw;
    max-width: calc(1130px + 8vw);
    width: 100%;
    list-style-type: none;
}
.containerBox li {
    display: flex;
    padding: 35px 0 33px;
    border-bottom: 1px solid #e0dddd;
    flex-wrap: wrap;
    cursor: pointer;
}
.term-name{
    margin-bottom: 0;
    font-size: 20px;
    letter-spacing: 2px;
    text-transform: uppercase;
    line-height: 1.3;
    font-weight: 700;
    color: #6c5ce7;
}
.post-title{
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: 500;
}
.post-excerpt{
    color:#999;
    font-size: 15px;
    line-height: 1.5;
}
</style>