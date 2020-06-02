#Lumen7+JWT组件的后端接口管理系统的demo

composer 组件：

    "dingo/api": "^3.0", //Dingo  统一返回的格式、为了后期方便多版本代码控制，以及oauth2.0的扩展
    "gregwar/captcha": "^1.1",  //验证码 / 可去掉
    "krlove/eloquent-model-generator": "^1.3", // 生成model层的组件  可去掉
    "tymon/jwt-auth": "1.0.*"  //JWT 


特别说明：
1.接口管理平台不与实际业务交互，仅仅用做接口管理，方便与前端对接，以及后端调试接口返回数据格式。
2.接口管理平台中的接口测试功能，请求的是实际项目，采用token验证方式，建议实际项目的测试服务上开放一个获取token接口（域名/api/token?id=user_id），仅仅用做测试服调试。

