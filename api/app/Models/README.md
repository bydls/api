#自动生成model层
安装  krlove/eloquent-model-generator  扩展

1.在app/Models下生成一个api_role表对应的model层

php artisan krlove:generate:model ApiRole --table-name=api_role --output-path=./Models --namespace=App\\Models 

2.在app/Models下生成一个mysql_log库对应的user_login表对应的model层

$ php artisan krlove:generate:model LogUserLogin --table-name=user_login --output-path=./Models --namespace=App\\Models --connection=mysql_log

注意：夸库取的表，在model层中添加  public $timestamps = false; 以取消默认的create_at 字段

