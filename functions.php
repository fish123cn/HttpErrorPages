<?php
// 引入配置文件
require_once 'config.php';

/**
 * 数据库连接函数
 * @return mysqli|false 数据库连接对象
 */
function get_db_connection() {
    global $db_config;
    
    $conn = new mysqli(
        $db_config['host'],
        $db_config['user'],
        $db_config['pass'],
        $db_config['dbname']
    );
    
    if ($conn->connect_error) {
        return false;
    }
    
    $conn->set_charset($db_config['charset']);
    return $conn;
}

/**
 * 更新错误计数
 * @param string $error_code 错误码
 * @return int 更新后的计数
 */
function update_error_count($error_code) {
    // 获取客户端IP
    $client_ip = get_client_ip();
    
    // 防重复计数：基于IP和时间
    $lock_file = sys_get_temp_dir() . "/error_count_" . md5($client_ip . "_" . $error_code) . ".lock";
    
    // 检查是否在最近2秒内已经计数过
    if (file_exists($lock_file) && (time() - filemtime($lock_file) < 2)) {
        // 如果最近2秒内已经计数过，直接返回当前计数
        return get_error_count($error_code);
    }
    
    // 创建或更新锁文件
    file_put_contents($lock_file, time());
    
    $conn = get_db_connection();
    if (!$conn) {
        return 0;
    }
    
    // 检查记录是否存在
    $stmt = $conn->prepare("SELECT id, count FROM error_counts WHERE error_code = ?");
    $stmt->bind_param("s", $error_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // 更新现有记录
        $row = $result->fetch_assoc();
        $new_count = $row['count'] + 1;
        $update_stmt = $conn->prepare("UPDATE error_counts SET count = ?, last_access = NOW() WHERE id = ?");
        $update_stmt->bind_param("ii", $new_count, $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // 创建新记录
        $new_count = 1;
        $insert_stmt = $conn->prepare("INSERT INTO error_counts (error_code, count, last_access) VALUES (?, ?, NOW())");
        $insert_stmt->bind_param("si", $error_code, $new_count);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    
    $stmt->close();
    $conn->close();
    
    return $new_count;
}

/**
 * 获取客户端真实IP
 * @return string 客户端IP
 */
function get_client_ip() {
    $ip_keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (isset($_SERVER[$key]) && $_SERVER[$key]) {
            $ip = trim($_SERVER[$key]);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

/**
 * 获取错误计数
 * @param string $error_code 错误码
 * @return int 错误计数
 */
function get_error_count($error_code) {
    $conn = get_db_connection();
    if (!$conn) {
        return 0;
    }
    
    $stmt = $conn->prepare("SELECT count FROM error_counts WHERE error_code = ?");
    $stmt->bind_param("s", $error_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
    } else {
        $count = 0;
    }
    
    $stmt->close();
    $conn->close();
    
    return $count;
}

/**
 * 获取当前页面域名
 * @return string 当前页面域名
 */
function get_current_domain() {
    // 优先从反向代理头获取客户端实际访问的域名
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        return $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    // 检查X-Real-Host头
    if (isset($_SERVER['HTTP_X_REAL_HOST'])) {
        return $_SERVER['HTTP_X_REAL_HOST'];
    }
    // 检查X-Forwarded-Host的变体
    if (isset($_SERVER['HTTP_X_FORWARDED_SERVER'])) {
        return $_SERVER['HTTP_X_FORWARDED_SERVER'];
    }
    // 其次从Host头获取
    if (isset($_SERVER['HTTP_HOST'])) {
        return $_SERVER['HTTP_HOST'];
    }
    // 再次从HTTP_REFERER获取
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
        $parsed_url = parse_url($referer);
        if (isset($parsed_url['host'])) {
            return $parsed_url['host'];
        }
    }
    // 最后使用SERVER_NAME
    return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'unknown';
}

/**
 * 获取错误信息
 * @param string $error_code 错误码
 * @return array 错误信息数组
 */
function get_error_info($error_code) {
    global $error_info, $error_codes;
    
    $info = array(
        'code' => $error_code,
        'message' => isset($error_codes[$error_code]) ? $error_codes[$error_code] : 'Unknown Error',
        'reason' => isset($error_info[$error_code]['reason']) ? $error_info[$error_code]['reason'] : '未知原因',
        'responsibility' => isset($error_info[$error_code]['responsibility']) ? $error_info[$error_code]['responsibility'] : '未知',
        'detail' => isset($error_info[$error_code]['detail']) ? $error_info[$error_code]['detail'] : '暂无详细信息'
    );
    
    return $info;
}

/**
 * 生成错误页面HTML
 * @param string $error_code 错误码
 * @return string HTML内容
 */
function generate_error_page($error_code) {
    $error_info = get_error_info($error_code);
    $current_domain = get_current_domain();
    $count = update_error_count($error_code);
    // 使用基于原始服务器的绝对路径
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    // 使用SERVER_NAME作为图片路径的域名，确保图片始终从正确的服务器加载
    $server_domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
    $image_path = "{$protocol}://{$server_domain}/error-photos/{$error_code}.jpg";
    
    $html = <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$error_info['code']} {$error_info['message']}</title>
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
            text-align: center;
        }
        
        .error-header {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            padding: 0 10px;
        }
        
        .error-code {
            font-size: 60px;
            font-weight: bold;
            color: #3498db;
        }
        
        .error-message {
            font-size: 60px;
            font-weight: bold;
            color: #3498db;
        }
        
        .error-image {
            max-width: 80%;
            max-height: 50vh;
            margin: 5px auto;
            display: block;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
        .error-details {
            text-align: left;
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            max-width: 100%;
        }
        
        .error-details h3 {
            margin-bottom: 15px;
            color: #3498db;
        }
        
        .detail-item {
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #999;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            
            .error-header {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
            
            .error-code {
                font-size: 50px;
            }
            
            .error-message {
                font-size: 50px;
                font-weight: bold;
            }
            
            .error-image {
                max-width: 90%;
                max-height: 40vh;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-header">
            <span class="error-code">{$error_info['code']}</span>
            <span class="error-message">{$error_info['message']}</span>
        </div>
        
        <img src="{$image_path}" alt="{$error_info['code']} Error" class="error-image">
        
        <div class="error-details">
            <h3>错误详情</h3>
            <div class="detail-item">
                <span class="detail-label">原因：</span>
                {$error_info['reason']}
            </div>
            <div class="detail-item">
                <span class="detail-label">责任方：</span>
                {$error_info['responsibility']}
            </div>
            <div class="detail-item">
                <span class="detail-label">详细说明：</span>
                {$error_info['detail']}
            </div>
            <div class="detail-item">
                <span class="detail-label">被展示次数：</span>
                {$count} 次
            </div>
        </div>
        
        <div class="footer">
            <p>© 2026 创意错误码网站 | 由 <a href="https://error.fish1234.cn">error.fish1234.cn</a> 提供 | 错误由Nginx返回，此错误页面只是美化了Nginx的错误页面</p>
        </div>
    </div>
</body>
</html>
HTML;
    
    return $html;
}
?>