<template>
    <div class="help">
    <div class="site"> 
     <helpTop></helpTop>   
   <div class="site-content"> 
    <div class="content-area"> 
     <main class="site-main container"> 

      <div class="topic-feed"> 
       <div class="row"> 
        <div class="col-md-6"  v-for="(item,id) in list" > 
         <section class="topic"> 
          <header class="topic-header"> 
           <h2 class="topic-name">{{ item.listTit }}</h2> 
           <!-- <div class="topic-count">
             {{item.data.length}} 篇文章 
           </div>  -->
          </header> 
          <ul class="topic-article-feed u-plain-list" style="cursor: pointer;"> 
           <li v-for="(it,ix) in item.data" :key="ix" class="topic-article" @click="openCont(it)"> <h3 class="topic-article-title"> <a class="topic-article-link"> </a>{{ it.titel }}</h3> </li> 
          </ul> 
          <a @click="goList(item)" style="cursor: pointer;" class="topic-more button-arrow button-arrow-right">查看所有</a> 
         </section> 
        </div> 
       </div> 
      </div> 
     </main> 
    </div> 
   </div> 
   <!-- <footer class="site-footer container"> 
    <div class="footer-social"> 
     <a class="footer-social-item footer-social-item-facebook" href="https://www.facebook.com/yibifuexchange" target="_blank" rel="noopener noreferrer" aria-label="Facebook"> <i class="icon icon-facebook"></i> </a> 
     <a class="footer-social-item footer-social-item-twitter" href="https://twitter.com/yibifuexchange" target="_blank" rel="noopener noreferrer" aria-label="Twitter"> <i class="icon icon-twitter"></i> </a> 
     <a class="footer-social-item footer-social-item-rss" href="https://feedly.com/i/subscription/feed/http://help.yibifu.one/rss/" target="_blank" rel="noopener noreferrer" aria-label="RSS"> <i class="icon icon-rss"></i> </a> 
    </div> 
    <div class="copyright">
      Powered by 
     <a href="https://ghost.org/" target="_blank">Ghost</a> 
    </div> 
   </footer>  -->
  </div> 
    </div>
</template>
<script>
import helpTop from '@/components/help_top.vue'
export default {
    name: 'help',
    components: {
        helpTop
    },
    data() {
    return {
        list:[
            {
                listTit:'A-OTC常见问题',
                data:[
                    {
                        titel:'1.如何买入USDT',
                        content:'第一步：点击首页“买卖币”按钮,第二步：可选择按金额购买（单位: 人民币），也可按数量购买（单位: USDT,第三步：选择买币转账方式（可选银行卡，支付宝或微信）,第四步：输入购买金额，点击“购买USDT”; 转账成功，等待到账',
                        link:[
                            {
                                h1:'第一步：点击首页“买卖币”按钮',
                                image:require('@/assets/images/cont/buy_1.jpg')
                            },
                            {
                                h1:'第二步：可选择按金额购买（单位: 人民币），也可按数量购买（单位: USDT)',
                                image:require('@/assets/images/cont/buy_2.jpg'),
                                image1:require('@/assets/images/cont/buy_3.jpg')
                            },
                            {
                                h1:'第三步：选择买币转账方式（可选银行卡，支付宝或微信）',
                                image:require('@/assets/images/cont/buy_4.jpg')
                            },
                            {
                                h1:'第四步：输入购买金额，点击“购买USDT”; 转账成功，等待到账',
                                image:require('@/assets/images/cont/buy_5.jpg')
                            },
                        ]
                    },
                    {
                        titel:'2.如何卖 USDT',
                        content:'第一步：点击首页“买卖币”按钮,第二步：可选择按金额购买（单位: 人民币），也可按数量购买（单位: USDT）,第三步：选择卖币转账方式（可选银行卡，支付宝或微信）,第四步：输入卖币金额，点击贩售USDT; 等待到账',
                        link:[
                            {
                                h1:'第一步：点击首页“买卖币”按钮',
                                image:require('@/assets/images/cont/sell_1.jpg')
                            },
                            {
                                h1:'第二步：可选择按金额购买（单位: 人民币），也可按数量购买（单位: USDT）',
                                image:require('@/assets/images/cont/sell_2.jpg'),
                                image1:require('@/assets/images/cont/sell_3.jpg')
                            },
                            {
                                h1:'第三步：选择卖币转账方式（可选银行卡，支付宝或微信）',
                                image:require('@/assets/images/cont/sell_4.jpg')
                            },
                            {
                                h1:'第四步：输入卖币金额，点击贩售USDT; 等待到账',
                                image:require('@/assets/images/cont/sell_5.jpg')
                            },
                        ]
                    },
                    {
                        titel:'3.我发起了卖币，但是想取消怎么办？',
                        content:'首先查询收款卡是否有收到帐；未收到帐的情况下，与钱包客服咨询是否可以取消并提供卖币订单号至客服； 等待客服告知取消结果卖币订单号获取处：第一步：进入卖币页面，点击右上方“纸页”按钮进入买卖币交易记录页面,第二步：点击卖币订单号旁“复制按钮”，复制订单号,第三步：将此订单号提供至钱包客服咨询卖币进度',
                        link:[
                            {
                                h1:'首先查询收款卡是否有收到帐；未收到帐的情况下，与钱包客服咨询是否可以取消并提供卖币订单号至客服； 等待客服告知取消结果',
                            },
                            {
                                h1:'卖币订单号获取处：第一步：进入卖币页面，点击右上方“纸页”按钮进入买卖币交易记录页面',
                            },
                            {
                                h1:'第二步：点击卖币订单号旁“复制按钮”，复制订单号',
                            },
                            {
                                h1:'第三步：将此订单号提供至钱包客服咨询卖币进度',
                                image:require('@/assets/images/cont/imga1.jpg'),
                                image1:require('@/assets/images/cont/imga2.jpg')
                            },
                        ]
                    },
                    {
                        titel:'4.如何设置资金密码',
                        content:'第一步：钱包注册成功后，登录时设置资金密码',
                        link:[
                            {
                                h1:'第一步：钱包注册成功后，登录时设置资金密码',
                            },
                            {
                                h1:'注：资金密码为纯数字',
                                style:'red'
                            },
                            {
                                image:require('@/assets/images/cont/passy.jpg'),
                            },
                        ]
                    },
                    {
                        titel:'5.如何修改资金密码',
                        content:'第一步：进入钱包首页，点击“设置”按钮,第二步：选择安全中心,第三步：选择资金密码；输入旧资金密码；输入新资金密码，并获取手机验证码并完成重置',
                        link:[
                            {
                                h1:'第一步：进入钱包首页，点击“设置”按钮',
                                image:require('@/assets/images/cont/revise_1.jpg')
                            },
                            {
                                h1:'第二步：选择安全中心',
                                image:require('@/assets/images/cont/revise_2.jpg'),
                            },
                            {
                                h1:'第三步：选择资金密码；输入旧资金密码；输入新资金密码，并获取手机验证码并完成重置',
                            },
                            {
                                h1:'注意资金密码需6位，为纯数字',
                                style:'red',
                                image:require('@/assets/images/cont/revise_3.jpg'),
                                image:require('@/assets/images/cont/revise_4.jpg'),
                            },
                        ]
                    },
                ]
            },
            {
                listTit:'B-交易相关问题',
                data:[
                    {
                        titel:'1.优币（Ubit）支持哪些付款方式？',
                        link:''
                    },
                    {
                        titel:'2.交易收取费用吗？',
                        link:''
                    },
                    ]
            },
            {
                listTit:'C-账户相关问题',
                data:[
                    {
                        titel:'1.如何注册钱包账户？',
                        link:''
                    },
                    {
                        titel:'2.如何设置登录密码',
                        link:''
                    },
                    {
                        titel:'3.如何修改登录密码',
                        link:''
                    },
                    {
                        titel:'4.如何绑定谷歌验证码',
                        link:''
                    },
                    {
                        titel:'5.如何绑定银行卡',
                        link:''
                    },
                ]
            },
            {
                listTit:'D-存提币问题',
                data:[
                    {
                        titel:'1.如何查看存币(收款)地址',
                        link:''
                    },
                    {
                        titel:'2.如何存币',
                        link:''
                    },
                    {
                        titel:'3.如何提币',
                        link:''
                    },
                    ]
            },
            {
                listTit:'E-新手指南',
                data:[
                    {
                        titel:'1.如何注册钱包账户',
                        link:''
                    },
                    {
                        titel:'2.如何设置登录密码',
                        link:''
                    },
                    {
                        titel:'3.如何修改登录密码',
                        link:''
                    },
                    {
                        titel:'4.如何绑定谷歌验证码',
                        link:''
                    },
                    {
                        titel:'5.如何设置资金密码',
                        link:''
                    },
                    {
                        titel:'6.如何修改资金密码',
                        link:''
                    },
                    {
                        titel:'7.如何联系在线客服',
                        link:''
                    },
                    {
                        titel:'8.如何查看买卖币流水',
                        link:''
                    },
                    {
                        titel:'9.如何查看资产流水',
                        link:''
                    },
                    {
                        titel:'10.如何买币',
                        link:''
                    },
                    {
                        titel:'10.如何卖币',
                        link:''
                    },
                    ]
            },
            {
                listTit:'F-协议条款',
                data:[
                    {
                        titel:'服务条款',
                        link:''
                    },
                    ]
            },
        ],
    };
  },
  methods:{
    goList(item){
        this.$router.push({
            path:'/helpList',
        })
        this.$store.commit('updateList', item);
    },
    openCont(item){
        this.$router.push({
            path:'/helpCont',
        })
        this.$store.commit('updateCont', item);
    }
  },
  mounted(){
    this.$store.commit('updatelistData', this.list);
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