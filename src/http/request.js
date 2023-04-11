import axios from "axios"
axios.defaults.baseURL = process.env.NODE_ENV == "" ? "" : ''


// //添加请求拦截器<==>请求发起前做的事
// axios.interceptors.request.use（function（config）{
//     //在发送请求之前做某事
//     return config;
// }，function（error）{
//     //请求错误时做些事
//     return Promise.reject（error）;
// }）;

//添加响应拦截器<==>响应回来后做的事
axios.interceptors.response.use(function(response){
    //对响应数据做些事
    return response.data;
},function(error){
    //请求错误时做些事
    return Promise.reject(error);
})
// 如果你以后可能需要删除拦截器。、
//     var myInterceptor = axios.interceptors.request.use(function () {/*...*/});
// axios.interceptors.request.eject(myInterceptor);
// 你可以将拦截器添加到axios的自定义实例
//
// var instance = axios.create();
// instance.interceptors.request.use(function () {/*...*/});

export default axios
