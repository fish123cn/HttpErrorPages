# HttpErrorPages创意错误码网站介绍与部署指南
为您的Nginx Web服务器提供美观、详细的HTTP错误页面，支持常见的14种错误代码。Provide your Nginx web server with beautiful and detailed HTTP error pages, supporting 14 common error codes.
![Uploading image.png…]()

## 项目介绍

这是一个创意错误码网站，专为Nginx服务器设计。当您的网站遇到错误时，我们提供美观、详细的错误页面，帮助访问者了解错误原因并明确责任方。

其他服务器可以通过简单的Nginx配置拉取我们的错误页面，为您的网站增添专业感和用户友好性。

**项目仓库**：[https://github.com/fish123cn/HttpErrorPages](https://github.com/fish123cn/HttpErrorPages)

## 功能特点

- 🎨 **美观的响应式设计**：适配不同设备屏幕尺寸
- 📊 **详细的错误分析**：每个错误都有详细的原因说明和责任方分析
- 🔢 **实时访问统计**：准确记录错误页面被展示的次数
- 🌐 **当前域名显示**：显示拉取错误页面的网站域名
- 🖼️ **错误码图片展示**：大尺寸错误码图片，提升视觉效果
- 🚀 **易于集成**：提供简单的Nginx配置示例

## 部署环境要求

- PHP 7.0+ （推荐PHP 8.0+）
- MySQL 5.6+ （用于存储错误页面访问次数）
- Nginx 1.16+ （用于反向代理或直接部署）
- 支持的操作系统：Linux、Windows、MacOS

## 部署步骤

### 1. 克隆或上传项目文件

将项目文件上传到您的Web服务器目录，例如：

```bash
# 克隆项目（如果使用Git）
git clone https://github.com/fish123cn/HttpErrorPages /www/wwwroot/littlefish/errorpage

# 或直接上传项目文件到指定目录
```

### 2. 配置数据库

#### 2.1 创建数据库

使用MySQL客户端或phpMyAdmin创建数据库：

```sql
CREATE DATABASE error_fish1234 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 2.2 创建数据库用户

```sql
CREATE USER 'error_fish1234'@'localhost' IDENTIFIED BY 'nH7s8zAptWBzS9pT';
GRANT ALL PRIVILEGES ON error_fish1234.* TO 'error_fish1234'@'localhost';
FLUSH PRIVILEGES;
```

#### 2.3 导入数据库表

项目会自动创建数据库表，首次访问错误页面时会自动初始化。

### 3. 配置Web服务器

#### 3.1 Nginx配置示例

创建或修改Nginx配置文件，例如 `/www/server/panel/vhost/nginx/error.fish1234.cn.conf`：

```nginx
server {
    listen 80;
    server_name error.fish1234.cn;
    index index.php index.html index.htm;
    root /www/wwwroot/littlefish/errorpage;

    # PHP文件处理
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/tmp/php-cgi-82.sock;
        fastcgi_index index.php;
        include fastcgi.conf;
    }

    # 静态文件缓存
    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$ {
        expires 30d;
        access_log off;
    }

    location ~ .*\.(js|css)?$ {
        expires 12h;
        access_log off;
    }

    # 日志配置
    access_log /www/wwwlogs/error.fish1234.cn.log;
    error_log /www/wwwlogs/error.fish1234.cn.error.log;
}
```

#### 3.2 重启Nginx服务

```bash
# 重启Nginx服务
systemctl restart nginx

# 或使用面板重启
```

### 4. 测试部署

访问您的错误页面地址，例如：

```
http://error.fish1234.cn/404.php
```

如果看到美观的404错误页面，说明部署成功！

## 如何让其他网站使用您的错误页面

### 方法1：直接使用我们的公共错误页面

将以下配置添加到您的Nginx配置文件中：

```nginx
# 定义错误码映射
error_page 400 =200 @error400;
error_page 401 =200 @error401;
error_page 403 =200 @error403;
error_page 404 =200 @error404;
error_page 405 =200 @error405;
error_page 408 =200 @error408;
error_page 409 =200 @error409;
error_page 413 =200 @error413;
error_page 429 =200 @error429;
error_page 500 =200 @error500;
error_page 502 =200 @error502;
error_page 503 =200 @error503;
error_page 504 =200 @error504;
error_page 505 =200 @error505;

# 错误页面配置
location @error400 {
    rewrite ^ /400.php break;
    proxy_pass https://error.fish1234.cn;
    proxy_set_header Host error.fish1234.cn;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host $host;
    proxy_ssl_verify off;
    proxy_connect_timeout 5s;
    proxy_read_timeout 10s;
}

# 其他错误页面配置（省略，与@error400类似，只需修改错误码）
# ...
```

### 方法2：使用配置生成器

1. 访问我们部署好的错误网站工具首页：`https://error.fish1234.cn/index.php`
2. 在"配置生成器"部分输入您的网站域名
3. 点击"生成配置"按钮
4. 复制生成的完整配置代码到您的Nginx配置文件中
5. 重启Nginx服务

## 支持的错误码

| 错误码 | 错误信息 | 错误原因 | 责任方 |
|--------|---------|---------|--------|
| 400 | Bad Request | 请求格式错误 | 客户端 |
| 401 | Unauthorized | 未授权访问 | 客户端 |
| 403 | Forbidden | 禁止访问 | 服务器/客户端 |
| 404 | Not Found | 资源不存在 | 客户端/服务器 |
| 405 | Method Not Allowed | 方法不允许 | 客户端 |
| 408 | Request Timeout | 请求超时 | 客户端 |
| 409 | Conflict | 冲突 | 客户端 |
| 413 | Payload Too Large | 请求体过大 | 客户端 |
| 429 | Too Many Requests | 请求过多 | 客户端 |
| 500 | Internal Server Error | 服务器内部错误 | 服务器 |
| 502 | Bad Gateway | 网关错误 | 服务器 |
| 503 | Service Unavailable | 服务不可用 | 服务器 |
| 504 | Gateway Timeout | 网关超时 | 服务器 |
| 505 | HTTP Version Not Supported | HTTP版本不支持 | 客户端 |

## 常见问题解决

### Q1: 错误页面显示403 Forbidden

**原因**：反向代理配置中的Host头设置错误

**解决方法**：确保Nginx配置中的`proxy_set_header Host`设置为错误网站的域名，例如：

```nginx
# 正确配置
proxy_set_header Host error.fish1234.cn;
proxy_set_header X-Forwarded-Host $host;
```

### Q2: 错误页面不显示当前域名

**原因**：反向代理配置中缺少X-Forwarded-Host头

**解决方法**：确保添加以下配置：

```nginx
proxy_set_header X-Forwarded-Host $host;
```

### Q3: Nginx配置测试失败，提示"no ssl_certificate is defined"

**原因**：配置中包含了SSL监听，但未配置SSL证书

**解决方法**：移除SSL相关监听配置，只保留80端口：

```nginx
listen 80;
# 注释掉SSL相关配置
# listen 443 ssl;
# listen 443 quic;
# http2 on;
```

### Q4: 错误页面访问次数不增加

**原因**：数据库连接失败或数据库表未创建

**解决方法**：
1. 检查config.php中的数据库配置是否正确
2. 确保MySQL服务正在运行
3. 首次访问错误页面时会自动创建数据库表

## 自定义配置

### 修改错误信息

编辑 `config.php` 文件，您可以：
- 修改错误码的英文描述
- 更新错误原因和责任方分析
- 添加新的错误码支持

### 更换错误码图片

将您自己的错误码图片上传到 `error-photos` 目录，图片命名格式为：`{错误码}.jpg`，例如 `404.jpg`。

### 修改页面样式

编辑 `functions.php` 文件中的CSS样式部分，您可以：
- 修改页面颜色方案
- 调整布局结构
- 添加自定义动画效果

## 技术支持

如果您在部署或使用过程中遇到问题，请：

1. 仔细阅读本部署指南
2. 检查Nginx错误日志
3. 确认数据库连接正常
4. 查看PHP错误日志

## 许可证

本项目采用 MIT 许可证，您可以自由使用、修改和分发。

## 贡献

欢迎提交 Issue 和 Pull Request，帮助我们改进这个项目！

---

**祝您好运！** 🎉

希望这个错误码网站能够为您的网站增添专业感和用户友好性。如果您有任何建议或反馈，欢迎联系我们。
