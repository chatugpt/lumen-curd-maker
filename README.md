# lumen_make


lumen 框架 自动生成增删改查 接口 和管理后台


1，在bootstrap/app.php中加入一行

$app->register(Le2le\Maker\MakerServiceProvider::class);

2，访问/maker


会在resources/views/下新建3个文件

header.blade.php
index.blade.php
layout.blade.php
pagination.blade.php

如果已经存在，不会替换这个3个文件
