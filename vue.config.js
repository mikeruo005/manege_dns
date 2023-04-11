module.exports = {
    lintOnSave: false,
    chainWebpack: config => {
        config.plugin('html').tap(args => {
            args[0].title = '优币-数字货币支付工具'
            return args
        })
    },
    devServer: {
        proxy: {
            '/v1': {
                target: 'http://www.google.com', //对应自己的接口
                changeOrigin: true,
                ws: true,
                // pathRewrite: {
                //     '^/api': ''  
                // }
            }
        }
    }
}
