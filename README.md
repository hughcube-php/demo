<h1>Demo</h1>

## 创建项目
```shell

```

## 部署步骤

- 创建[阿里云函数服务](https://fcnext.console.aliyun.com/cn-hangzhou/services)
    - 创建时候**禁用**日志服务(因为会生成一个默认的**日志仓库**需要**费用**)
    - 编辑服务, **开启**nas存储, 挂载远程目录: /fun/**服务名**/storage,  挂载本地目录: /mnt/storage
  

- 创建**schedule**函数
    - 添加定时触发器
  

- 创建**api**函数
    - 添加禁止消费消息队列环境变量**HUGHCUBE_ALIFC_ALLOW_FIRE_JOB=0**
  


## 定时触发器**payload**
```json
{
    "uuid":"a5136bab-cee7-410b-aabe-f3422797b145",
    "displayName":"App\\Jobs\\AAAScheduleJob",
    "job":"Illuminate\\Queue\\CallQueuedHandler@call",
    "maxTries":null,
    "maxExceptions":null,
    "failOnTimeout":false,
    "backoff":null,
    "timeout":null,
    "retryUntil":null,
    "data":{
        "commandName":"App\\Jobs\\AAAScheduleJob",
        "command":"O:23:\"App\\Jobs\\AAAScheduleJob\":0:{}"
    },
    "createdAt":"2022-04-10T02:28:02.413940+08:00"
}
```
