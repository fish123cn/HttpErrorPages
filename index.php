<?php
// 引入配置文件获取错误码信息
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创意错误码网站 - Better Error Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 90%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        h1 {
            font-size: 40px;
            color: #3498db;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }
        
        section {
            margin-bottom: 40px;
        }
        
        h2 {
            font-size: 24px;
            color: #3498db;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        p {
            margin-bottom: 15px;
        }
        
        .feature-list {
            list-style: none;
            margin: 20px 0;
        }
        
        .feature-list li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }
        
        .feature-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #27ae60;
            font-weight: bold;
        }
        
        .error-codes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
            margin: 20px 0;
        }
        
        .error-code-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.2s;
        }
        
        .error-code-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .error-code-item .code {
            font-size: 20px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 5px;
        }
        
        .error-code-item .message {
            font-size: 12px;
            color: #666;
        }
        
        .nginx-config {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            font-family: 'Courier New', Courier, monospace;
            margin: 20px 0;
            white-space: pre-wrap;
            line-height: 1.4;
        }
        
        .config-generator {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        
        .config-generator h3 {
            margin-bottom: 15px;
            color: #3498db;
        }
        
        #configForm {
            margin: 20px 0;
        }
        
        #domainInput {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 300px;
            margin-right: 10px;
        }
        
        #configForm button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        #configForm button:hover {
            background-color: #2980b9;
        }
        
        @media (max-width: 768px) {
            #domainInput {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            #configForm button {
                width: 100%;
            }
        }
        
        footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 30px;
            }
            
            h2 {
                font-size: 20px;
            }
            
            .error-codes {
                grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>创意错误码网站</h1>
            <p class="subtitle">为您的服务器提供美观、详细的错误页面<br>不过无论如何，还是祝您的网站永远不会出问题！</p>
        </header>
        
        <section id="about">
            <h2>关于本网站</h2>
            <p>这是一个创意错误码网站，专为Nginx服务器设计。当您的网站遇到错误时，我们提供美观、详细的错误页面，帮助访问者了解错误原因并明确责任方。</p>
            <p>其他服务器可以通过简单的Nginx配置拉取我们的错误页面，为您的网站增添专业感和用户友好性。</p>
        </section>
        
        <section id="features">
            <h2>功能特点</h2>
            <ul class="feature-list">
                <li>美观的响应式设计，适配不同设备屏幕</li>
                <li>详细的错误原因分析和责任方说明</li>
                <li>实时统计错误页面被展示的次数</li>
                <li>显示拉取错误页面的网站域名</li>
                <li>现代化的视觉效果，包含错误码图片</li>
                <li>简单易用的Nginx集成方案</li>
            </ul>
        </section>
        
        <section id="supported-codes">
            <h2>支持的错误码</h2>
            <p>点击下方错误码查看预览效果：</p>
            <div class="error-codes">
                <?php foreach ($error_codes as $code => $message): ?>
                    <div class="error-code-item">
                        <a href="<?php echo $code; ?>.php" style="text-decoration: none; color: inherit;">
                            <div class="code"><?php echo $code; ?></div>
                            <div class="message"><?php echo $message; ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section id="nginx-config">
            <h2>Nginx配置示例</h2>
            <p>将以下配置添加到您的Nginx配置文件中，即可使用我们的错误页面：</p>
            
            <!-- 域名生成器 -->
            <div class="config-generator">
                <h3>配置生成器（宝塔Nginx）</h3>
                <p>输入您的网站域名，生成适合您网站的完整Nginx配置<br>如果网站之前已经对Nginx配置下过手，请备份好之前的Nginx配置，这个配置提供给使用宝塔面板的，输入你的域然后直接粘贴就能用：</p>
                <form id="configForm">
                    <input type="text" id="domainInput" placeholder="例如：www.yourdomain.com" value="xiaofublog.cn">
                    <button type="button" onclick="generateConfig()">生成配置</button>
                </form>
            </div>
            
            <div class="nginx-config" id="configOutput">
<?php
$host = 'aaa.fuxiaoyu.cn';
echo "server\n";
echo "{\n";
echo "    listen 80;\n";
echo "    # 移除SSL相关监听，避免无证书导致配置失败\n";
echo "    # listen 443 ssl;\n";
echo "    # listen 443 quic;\n";
echo "    # http2 on;\n";
echo "    server_name $host;\n";
echo "    index index.php index.html index.htm default.php default.htm default.html;\n";
echo "    root /wwwroot/wwwroot/$host;\n";
echo "    #CERT-APPLY-CHECK--START\n";
echo "    include /www/server/panel/vhost/nginx/well-known/$host.conf;\n";
echo "    #CERT-APPLY-CHECK--END\n";
echo "    include /www/server/panel/vhost/nginx/extension/$host/*.conf;\n";
echo "    \n";
echo "    #SSL-START SSL相关配置（保留，不影响80端口）\n";
echo "    #error_page 404/404.html;\n";
echo "    #SSL-END\n";
echo "\n";
echo "    # ========== 核心：反向代理加载根目录错误页面（传递原始域名） ==========\n";
echo "    # 注释宝塔默认错误页，避免冲突\n";
echo "    #error_page 404 /404.html;\n";
echo "    #error_page 502 /502.html;\n";
echo "    \n";
echo "    # 定义错误码映射，=200保证浏览器不显示原生错误提示\n";
echo "    error_page 400 =200 @error400;\n";
echo "    error_page 401 =200 @error401;\n";
echo "    error_page 403 =200 @error403;\n";
echo "    error_page 404 =200 @error404;\n";
echo "    error_page 405 =200 @error405;\n";
echo "    error_page 408 =200 @error408;\n";
echo "    error_page 409 =200 @error409;\n";
echo "    error_page 413 =200 @error413;\n";
echo "    error_page 429 =200 @error429;\n";
echo "    error_page 500 =200 @error500;\n";
echo "    error_page 502 =200 @error502;\n";
echo "    error_page 503 =200 @error503;\n";
echo "    error_page 504 =200 @error504;\n";
echo "    error_page 505 =200 @error505;\n";
echo "\n";
echo "    # 404错误页（修复Host头，传递原始域名）\n";
echo "    location @error404 {\n";
echo "        rewrite ^ /404.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host error.fish1234.cn;  # 关键修复：使用目标服务器域名，避免403错误\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;  # 额外传递原始域名头\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 400错误页\n";
echo "    location @error400 {\n";
echo "        rewrite ^ /400.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host error.fish1234.cn;  # 关键修复：使用目标服务器域名，避免403错误\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 401错误页\n";
echo "    location @error401 {\n";
echo "        rewrite ^ /401.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 403错误页\n";
echo "    location @error403 {\n";
echo "        rewrite ^ /403.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 405错误页\n";
echo "    location @error405 {\n";
echo "        rewrite ^ /405.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 408错误页\n";
echo "    location @error408 {\n";
echo "        rewrite ^ /408.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 409错误页\n";
echo "    location @error409 {\n";
echo "        rewrite ^ /409.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 413错误页\n";
echo "    location @error413 {\n";
echo "        rewrite ^ /413.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 429错误页\n";
echo "    location @error429 {\n";
echo "        rewrite ^ /429.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 500错误页\n";
echo "    location @error500 {\n";
echo "        rewrite ^ /500.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 502错误页\n";
echo "    location @error502 {\n";
echo "        rewrite ^ /502.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 503错误页\n";
echo "    location @error503 {\n";
echo "        rewrite ^ /503.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 504错误页\n";
echo "    location @error504 {\n";
echo "        rewrite ^ /504.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "    # 505错误页\n";
echo "    location @error505 {\n";
echo "        rewrite ^ /505.php break;\n";
echo "        proxy_pass https://error.fish1234.cn;\n";
echo "        proxy_set_header Host $host;\n";
echo "        proxy_set_header X-Real-IP $remote_addr;\n";
echo "        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n";
echo "        proxy_set_header X-Forwarded-Proto $scheme;\n";
echo "        proxy_set_header X-Forwarded-Host $host;\n";
echo "        proxy_ssl_verify off;\n";
echo "        proxy_connect_timeout 5s;\n";
echo "        proxy_read_timeout 10s;\n";
echo "    }\n";
echo "\n";
echo "    # 核心路由：无嵌套if，符合Nginx语法，直接触发404\n";
echo "    location / {\n";
echo "        try_files $uri $uri/ =404;\n";
echo "    }\n";
echo "\n";
echo "    # PHP文件单独处理，保证站点PHP程序正常运行\n";
echo "    location ~ \.php$ {\n";
echo "        try_files $uri =404;\n";
echo "        fastcgi_pass  unix:/tmp/php-cgi-82.sock;\n";
echo "        fastcgi_index index.php;\n";
echo "        include fastcgi.conf;\n";
echo "    }\n";
echo "\n";
echo "    #PHP-INFO-START  保留宝塔原有PHP配置\n";
echo "    include enable-php-82.conf;\n";
echo "    #PHP-INFO-END\n";
echo "\n";
echo "    #REWRITE-START  保留宝塔伪静态规则引用\n";
echo "    # 注释掉rewrite配置引用，避免文件不存在导致配置失败\n";
echo "    # include /www/server/panel/vhost/rewrite/$host.conf;\n";
echo "    #REWRITE-END\n";
echo "\n";
echo "    # 禁止访问的敏感文件（保留宝塔原有规则）\n";
echo "    location ~* (\.user.ini|\.htaccess|\.htpasswd|\.env.*|\.project|\.bashrc|\.bash_profile|\.bash_logout|\.DS_Store|\.gitignore|\.gitattributes|LICENSE|README\.md|CLAUDE\.md|CHANGELOG\.md|CHANGELOG|CONTRIBUTING\.md|TODO\.md|FAQ\.md|composer\.json|composer\.lock|package(-lock)?\.json|yarn\.lock|pnpm-lock\.yaml|\.\w+~|\.swp|\.swo|\.bak(up)?|\.old|\.tmp|\.temp|\.log|\.sql(\.gz)?|docker-compose\.yml|docker\.env|Dockerfile|\.csproj|\.sln|Cargo\.toml|Cargo\.lock|go\.mod|go\.sum|phpunit\.xml|phpunit\.xml|pom\.xml|build\.gradl|pyproject\.toml|requirements\.txt|application(-\w+)?\.(ya?ml|properties))$\n";
echo "    {\n";
echo "        return 404;\n";
echo "    }\n";
echo "    \n";
echo "    # 禁止访问的敏感目录（保留宝塔原有规则）\n";
echo "    location ~* /(\.git|\.svn|\.bzr|\.vscode|\.claude|\.idea|\.ssh|\.github|\.npm|\.yarn|\.pnpm|\.cache|\.husky|\.turbo|\.next|\.nuxt|node_modules|runtime)/ {\n";
echo "        return 404;\n";
echo "    }\n";
echo "\n";
echo "    #一键申请SSL证书验证目录相关设置\n";
echo "    location ~ \.well-known{\n";
echo "        allow all;\n";
echo "    }\n";
echo "\n";
echo "    #禁止在证书验证目录放入敏感文件\n";
echo "    if ( $uri ~ \"^/\.well-known/.*\.(php|jsp|py|js|css|lua|ts|go|zip|tar\.gz|rar|7z|sql|bak)$\" ) {\n";
echo "        return 403;\n";
echo "    }\n";
echo "\n";
echo "    # 静态文件缓存规则（保留宝塔原有规则+监控）\n";
echo "    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$\n";
echo "    {\n";
echo "        expires      30d;\n";
echo "        access_log  /dev/null;\n";
echo "\terror_log  /dev/null;\n";
echo "\t		#Monitor-Config-Start 网站监控报表日志发送配置\n";
echo "\t		access_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__access monitor;\n";
echo "\t		error_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__error;\n";
echo "\t		#Monitor-Config-End\n";
echo "    }\n";
echo "\n";
echo "    # 静态文件缓存规则（保留宝塔原有规则+监控）\n";
echo "    location ~ .*\.(js|css)?$\n";
echo "    {\n";
echo "        expires      12h;\n";
echo "        access_log  /dev/null;\n";
echo "\terror_log  /dev/null;\n";
echo "\t		#Monitor-Config-Start 网站监控报表日志发送配置\n";
echo "\t		access_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__access monitor;\n";
echo "\t		error_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__error;\n";
echo "\t		#Monitor-Config-End\n";
echo "    }\n";
echo "    # 日志配置（保留宝塔原有规则）\n";
echo "    access_log  /www/wwwlogs/$host.log;\n";
echo "    error_log  /www/wwwlogs/$host.error.log;\n";
echo "\n";
echo "    #Monitor-Config-Start 网站监控报表日志发送配置\n";
echo "    access_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__access monitor;\n";
echo "    error_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__error;\n";
echo "    #Monitor-Config-End\n";
echo "}\n";
?>
            </div>
            
            <script>
                function generateConfig() {
                    const domain = document.getElementById('domainInput').value.trim();
                    if (!domain) {
                        alert('请输入您的网站域名');
                        return;
                    }
                    
                    let config = '';
                    config += `server\n`;
                    config += `{\n`;
                    config += `    listen 80;\n`;
                    config += `    # 移除SSL相关监听，避免无证书导致配置失败\n`;
                    config += `    # listen 443 ssl;\n`;
                    config += `    # listen 443 quic;\n`;
                    config += `    # http2 on;\n`;
                    config += `    server_name ${domain};\n`;
                    config += `    index index.php index.html index.htm default.php default.htm default.html;\n`;
                    config += `    root /wwwroot/wwwroot/${domain};\n`;
                    config += `    #CERT-APPLY-CHECK--START\n`;
                    config += `    include /www/server/panel/vhost/nginx/well-known/${domain}.conf;\n`;
                    config += `    #CERT-APPLY-CHECK--END\n`;
                    config += `    include /www/server/panel/vhost/nginx/extension/${domain}/*.conf;\n`;
                    config += `    \n`;
                    config += `    #SSL-START SSL相关配置（保留，不影响80端口）\n`;
                    config += `    #error_page 404/404.html;\n`;
                    config += `    #SSL-END\n`;
                    config += `\n`;
                    config += `    # ========== 核心：反向代理加载根目录错误页面（传递原始域名） ==========\n`;
                    config += `    # 注释宝塔默认错误页，避免冲突\n`;
                    config += `    #error_page 404 /404.html;\n`;
                    config += `    #error_page 502 /502.html;\n`;
                    config += `    \n`;
                    config += `    # 定义错误码映射，=200保证浏览器不显示原生错误提示\n`;
                    
                    // 错误码映射
                    const errorCodes = ['400', '401', '403', '404', '405', '408', '409', '413', '429', '500', '502', '503', '504', '505'];
                    errorCodes.forEach(code => {
                        config += `    error_page ${code} =200 @error${code};\n`;
                    });
                    
                    config += `\n`;
                    
                    // 错误页面配置
                    errorCodes.forEach(code => {
                        config += `    # ${code}错误页\n`;
                        config += `    location @error${code} {\n`;
                        config += `        rewrite ^ /${code}.php break;\n`;
                        config += `        proxy_pass https://error.fish1234.cn;\n`;
                        config += `        proxy_set_header Host error.fish1234.cn;  # 关键修复：使用目标服务器域名，避免403错误\n`;
                        config += `        proxy_set_header X-Real-IP $remote_addr;\n`;
                        config += `        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n`;
                        config += `        proxy_set_header X-Forwarded-Proto $scheme;\n`;
                        config += `        proxy_set_header X-Forwarded-Host ${domain};  # 额外传递原始域名头\n`;
                        config += `        proxy_ssl_verify off;\n`;
                        config += `        proxy_connect_timeout 5s;\n`;
                        config += `        proxy_read_timeout 10s;\n`;
                        config += `    }\n`;
                    });
                    
                    config += `\n`;
                    config += `    # 核心路由：无嵌套if，符合Nginx语法，直接触发404\n`;
                    config += `    location / {\n`;
                    config += `        try_files $uri $uri/ =404;\n`;
                    config += `    }\n`;
                    config += `\n`;
                    config += `    # PHP文件单独处理，保证站点PHP程序正常运行\n`;
                    config += `    location ~ \.php$ {\n`;
                    config += `        try_files $uri =404;\n`;
                    config += `        fastcgi_pass  unix:/tmp/php-cgi-82.sock;\n`;
                    config += `        fastcgi_index index.php;\n`;
                    config += `        include fastcgi.conf;\n`;
                    config += `    }\n`;
                    config += `\n`;
                    config += `    #PHP-INFO-START  保留宝塔原有PHP配置\n`;
                    config += `    include enable-php-82.conf;\n`;
                    config += `    #PHP-INFO-END\n`;
                    config += `\n`;
                    config += `    #REWRITE-START  保留宝塔原有PHP配置\n`;
                    config += `    # 注释掉rewrite配置引用，避免文件不存在导致配置失败\n`;
                    config += `    # include /www/server/panel/vhost/rewrite/${domain}.conf;\n`;
                    config += `    #REWRITE-END\n`;
                    config += `\n`;
                    config += `    # 禁止访问的敏感文件（保留宝塔原有规则）\n`;
                    config += `    location ~* (\.user.ini|\.htaccess|\.htpasswd|\.env.*|\.project|\.bashrc|\.bash_profile|\.bash_logout|\.DS_Store|\.gitignore|\.gitattributes|LICENSE|README\.md|CLAUDE\.md|CHANGELOG\.md|CHANGELOG|CONTRIBUTING\.md|TODO\.md|FAQ\.md|composer\.json|composer\.lock|package(-lock)?\.json|yarn\.lock|pnpm-lock\.yaml|\.\w+~|\.swp|\.swo|\.bak(up)?|\.old|\.tmp|\.temp|\.log|\.sql(\.gz)?|docker-compose\.yml|docker\.env|Dockerfile|\.csproj|\.sln|Cargo\.toml|Cargo\.lock|go\.mod|go\.sum|phpunit\.xml|phpunit\.xml|pom\.xml|build\.gradl|pyproject\.toml|requirements\.txt|application(-\w+)?\.(ya?ml|properties))$\n`;
                    config += `    {\n`;
                    config += `        return 404;\n`;
                    config += `    }\n`;
                    config += `    \n`;
                    config += `    # 禁止访问的敏感目录（保留宝塔原有规则）\n`;
                    config += `    location ~* /(\.git|\.svn|\.bzr|\.vscode|\.claude|\.idea|\.ssh|\.github|\.npm|\.yarn|\.pnpm|\.cache|\.husky|\.turbo|\.next|\.nuxt|node_modules|runtime)/ {\n`;
                    config += `        return 404;\n`;
                    config += `    }\n`;
                    config += `\n`;
                    config += `    #一键申请SSL证书验证目录相关设置\n`;
                    config += `    location ~ \.well-known{\n`;
                    config += `        allow all;\n`;
                    config += `    }\n`;
                    config += `\n`;
                    config += `    #禁止在证书验证目录放入敏感文件\n`;
                    config += `    if ( $uri ~ \"^/\.well-known/.*\.(php|jsp|py|js|css|lua|ts|go|zip|tar\.gz|rar|7z|sql|bak)$\" ) {\n`;
                    config += `        return 403;\n`;
                    config += `    }\n`;
                    config += `\n`;
                    config += `    # 静态文件缓存规则（保留宝塔原有规则+监控）\n`;
                    config += `    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$\n`;
                    config += `    {\n`;
                    config += `        expires      30d;\n`;
                    config += `        access_log  /dev/null;\n`;
                    config += `\terror_log  /dev/null;\n`;
                    config += `\t		#Monitor-Config-Start 网站监控报表日志发送配置\n`;
                    config += `\t		access_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__access monitor;\n`;
                    config += `\t		error_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__error;\n`;
                    config += `\t		#Monitor-Config-End\n`;
                    config += `    }\n`;
                    config += `\n`;
                    config += `    # 静态文件缓存规则（保留宝塔原有规则+监控）\n`;
                    config += `    location ~ .*\.(js|css)?$\n`;
                    config += `    {\n`;
                    config += `        expires      12h;\n`;
                    config += `        access_log  /dev/null;\n`;
                    config += `\terror_log  /dev/null;\n`;
                    config += `\t		#Monitor-Config-Start 网站监控报表日志发送配置\n`;
                    config += `\t		access_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__access monitor;\n`;
                    config += `\t		error_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__error;\n`;
                    config += `\t		#Monitor-Config-End\n`;
                    config += `    }\n`;
                    config += `    # 日志配置（保留宝塔原有规则）\n`;
                    config += `    access_log  /www/wwwlogs/${domain}.log;\n`;
                    config += `    error_log  /www/wwwlogs/${domain}.error.log;\n`;
                    config += `\n`;
                    config += `    #Monitor-Config-Start 网站监控报表日志发送配置\n`;
                    config += `    access_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__access monitor;\n`;
                    config += `    error_log syslog:server=unix:/tmp/bt-monitor.sock,nohostname,tag=62__error;\n`;
                    config += `    #Monitor-Config-End\n`;
                    config += `}\n`;
                    
                    document.getElementById('configOutput').textContent = config;
                }
            </script>
        </section>
        
        <footer>
            <p>© 2026 创意错误码网站 | 由 <a href="https://error.fish1234.cn">error.fish1234.cn</a> 提供</p>
        </footer>
    </div>
</body>
</html>