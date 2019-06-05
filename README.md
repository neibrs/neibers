# Get the code 
1. composer
  > composer require drupal/neibers
2. download the code to modules directory
  > wget 'https://ftp.drupal.org/files/projects/neibers-8.x-1.0.tar.gz'
  
# Install
There are two methods to install this module:
1. install drupal as normal
> core/install.php
2. install this module with command line(set the database account as you need)
> sh modules/neibers/bin/install.sh

# Recommended development environment(docker)
Use this: https://github.com/neibrs/laradock

#### Build development environment
1. git clone https://github.com/neibrs/laradock
2. cd laradock
3. sh build.sh
The new directory named `laradrupal` is the web directory with drupal.


# 中文自述
此安装文件是为了提供一个完整的IDC运营套件。
安装完成后即实现对IDC行业，特别是适用于小型IDC公司销售服务器，IP，硬件资源等管理.

此后，通过本框架进行扩展，往大型IDC行业服务软件上应用.

# 获得代码方式
1. 通过composer获得
  > composer require drupal/neibers
2. 直接下载模块文件到drupal/modules目录
  > wget 'https://ftp.drupal.org/files/projects/neibers-8.x-1.0.tar.gz'
# Install(安装)
本模块有两种安装方式:
1. 通过drupal安装方式
> core/install.php
2. 通过命令行方式安装(本地数据用户名密码根据需要设置)
> sh modules/neibers/bin/install.sh

# 开发环境推荐(docker)
推荐使用https://github.com/neibrs/laradock

#### 构建开发环境
1. git clone https://github.com/neibrs/laradock
2. cd laradock
3. sh build.sh

生成的laradrupal目录即为drupal代码目录

