# Region
基于高德开放平台 的PHP 三级联动数据生成器。
## 安装
```
$ composer require lysice/laravel-region -vvv
```
## 配置
在使用本扩展之前，你需要去 高德开放平台 注册账号，然后创建应用，获取应用的 API Key。
## 使用
### 1.发布配置文件与迁移
```
    php artisan vendor:publish --provider=Lysice\Region\RegionServiceProvider
```
### 2.配置项
  config文件里生成的region.php中的配置项一共有四个分别是
- table 标识要生成迁移的表名
- connection 标识要使用的数据库连接配置名,对应database.php中的connections选项中的配置 如设置成mysql 则会使用 config('database.connections.mysql')的配置。
- key 高德开放平台创建应用的key
- prefix 标识要生成的表名前缀

### 3.生成迁移表
```
    php artisan migrate
```
### 4.生成数据 可以使用三种方式
#### 方法参数注入Region实例生成
```
    public function region(Region $region) 
    {
        $response = $region->region();
    }
```
#### 服务名访问实例方法
    public function edit() 
    {
        $response = app('region')->region();
    }
#### 命令行执行
```
    php artisan region:generate
```
之后在数据库你生成的表结构
## 参考
高德开放平台接口

## License
MIT
