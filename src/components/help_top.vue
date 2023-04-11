<template>
      <header class="site-header"> 
    <div class="container"> 
     <div class="navbar"> 
      <div class="navbar-left"> 
       <a class="logo"> <img class="logo-image" src="../assets/images/logo3.png" alt="帮助中心"> </a> 
      </div> 
      <div class="navbar-right"> 
       <nav class="main-nav hidden-xs hidden-sm hidden-md"> 
        <a class="menu-item menu-item-shou-ye menu-item-current" @click="$router.push('/help')">首页</a> 
        <!-- <a class="menu-item menu-item-gong-gao-zhong-xin" href="http://help.yibifu.one/tag/news/">公告中心</a>  -->
        <a class="menu-item menu-item-otcchang-jian-wen-ti" @click="goList(golistData[0])">OTC常见问题</a> 
        <a class="menu-item menu-item-xin-shou-zhi-nan" @click="goList(golistData[4])">新手指南</a> 
        <a class="menu-item menu-item-xie-yi-tiao-kuan"  @click="goList(golistData[5])">协议条款</a> 
        <!-- <a class="menu-item menu-item-qu-kuai-lian-zhi-shi" href="http://help.yibifu.one/tag/information/">区块链知识</a>  -->
        <!-- <a class="menu-item menu-item-yun-ying-huo-dong" href="http://help.yibifu.one/activity/">运营活动</a>  -->
       </nav> 
       <div class="burger hidden-lg hidden-xl"> 
        <div class="burger-bar"></div> 
        <div class="burger-bar"></div> 
       </div> 
       <div class="mobile-menu hidden-lg hidden-xl"> 
        <div class="container"> 
         <nav class="mobile-nav"> 
          <a class="menu-item menu-item-shou-ye menu-item-current" href="http://help.yibifu.one/">首页</a> 
          <!-- <a class="menu-item menu-item-gong-gao-zhong-xin" href="http://help.yibifu.one/tag/news/">公告中心</a>  -->
          <a class="menu-item menu-item-otcchang-jian-wen-ti" href="http://help.yibifu.one/tag/FAQ/">OTC常见问题</a> 
          <a class="menu-item menu-item-xin-shou-zhi-nan" href="http://help.yibifu.one/tag/guide/">新手指南</a> 
          <a class="menu-item menu-item-xie-yi-tiao-kuan" href="http://help.yibifu.one/tag/agreement/">协议条款</a> 
          <!-- <a class="menu-item menu-item-qu-kuai-lian-zhi-shi" href="http://help.yibifu.one/tag/information/">区块链知识</a>  -->
          <!-- <a class="menu-item menu-item-yun-ying-huo-dong" href="http://help.yibifu.one/activity/">运营活动</a>  -->
         </nav> 
        </div> 
       </div> 
      </div> 
     </div> 
    </div> 
   </header> 
</template>
<script>
import { mapState } from 'vuex';
export default {
    computed: {
        ...mapState({
            listData:state => state.listData
        }),
    },
  data() {
    return {
        golistData:null
    }
},
created() {
    this.golistData = this.listData; 
  },
methods:{
    goList(item){
        if(this.$route.path != '/helpList'){
            this.$router.push({
                path:'/helpList',
            })
        }
        this.$store.commit('updateList', item);
    },
}
};
</script>
<style>
@import '../assets/css/screen.css';
.main-nav a{
    cursor: pointer;
}
</style>
