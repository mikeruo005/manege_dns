<template>
    <div>
        <helpTop></helpTop>
        <div class="site-content">
    <div class="term containerBox">
        <h1 class="term-name">{{ showList.listTit }}</h1>
    </div>
        
        <ul class="containerBox ">
            <li v-for="(itm,id) in showList.data">
                <span class="post-title">{{ itm.titel.substring(2) }}</span>
                <p class="post-excerpt">{{ itm.content }}</p>
            </li>
        </ul>
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
            list:state => state.list
        }),
    },
    name: 'helpCont',
    created() {
    this.showList = this.list; 
  },

    data() {
        return{
            showList:null
        }
    },
    watch:{
        list(newValue, oldValue){
            this.showList = newValue
        }
    }
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