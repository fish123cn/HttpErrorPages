<?php
// 数据库配置
$db_config = array(
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'db_username',
    'pass' => 'db_password',
    'dbname' => 'db_name',
    'charset' => 'utf8mb4'
);

// 错误码配置
$error_codes = array(
    '400' => 'Bad Request',
    '401' => 'Unauthorized',
    '403' => 'Forbidden',
    '404' => 'Not Found',
    '405' => 'Method Not Allowed',
    '408' => 'Request Timeout',
    '409' => 'Conflict',
    '413' => 'Payload Too Large',
    '429' => 'Too Many Requests',
    '500' => 'Internal Server Error',
    '502' => 'Bad Gateway',
    '503' => 'Service Unavailable',
    '504' => 'Gateway Timeout',
    '505' => 'HTTP Version Not Supported'
);

// 错误原因和责任方配置
$error_info = array(
    '400' => array(
        'reason' => '请求格式错误，服务器无法理解',
        'responsibility' => '客户端',
        'detail' => '请求语法有误，可能是请求参数格式错误、URL格式不正确或请求头信息不完整等原因导致服务器无法解析请求。'
    ),
    '401' => array(
        'reason' => '未授权访问',
        'responsibility' => '客户端',
        'detail' => '需要身份验证才能访问该资源，可能是缺少认证信息或认证信息无效。'
    ),
    '403' => array(
        'reason' => '禁止访问',
        'responsibility' => '服务器/客户端',
        'detail' => '服务器拒绝请求，可能是权限问题、IP被禁止或资源设置了访问限制。'
    ),
    '404' => array(
        'reason' => '资源不存在',
        'responsibility' => '客户端/服务器',
        'detail' => '请求的资源在服务器上不存在，可能是URL输入错误、资源已被删除或移动。'
    ),
    '405' => array(
        'reason' => '方法不允许',
        'responsibility' => '客户端',
        'detail' => '请求使用的HTTP方法在服务器上不被允许，例如尝试使用POST方法访问只允许GET请求的资源。'
    ),
    '408' => array(
        'reason' => '请求超时',
        'responsibility' => '客户端',
        'detail' => '服务器等待请求时发生超时，可能是客户端发送请求太慢或网络连接不稳定。'
    ),
    '409' => array(
        'reason' => '冲突',
        'responsibility' => '客户端',
        'detail' => '请求与服务器当前状态冲突，例如尝试创建一个已存在的资源或修改正在被其他进程修改的资源。'
    ),
    '413' => array(
        'reason' => '请求体过大',
        'responsibility' => '客户端',
        'detail' => '请求实体超出服务器定义的限制，可能是上传的文件太大或请求数据过多。'
    ),
    '429' => array(
        'reason' => '请求过多',
        'responsibility' => '客户端',
        'detail' => '客户端在短时间内发送了太多请求，服务器为了保护自身资源而拒绝请求。'
    ),
    '500' => array(
        'reason' => '服务器内部错误',
        'responsibility' => '服务器',
        'detail' => '服务器遇到意外情况，无法完成请求，可能是代码错误、数据库连接问题或服务器配置错误。'
    ),
    '502' => array(
        'reason' => '网关错误',
        'responsibility' => '服务器',
        'detail' => '作为网关或代理的服务器收到了无效的响应，可能是后端服务器故障或网络问题。'
    ),
    '503' => array(
        'reason' => '服务不可用',
        'responsibility' => '服务器',
        'detail' => '服务器暂时无法处理请求，可能是服务器过载、维护中或资源不足。'
    ),
    '504' => array(
        'reason' => '网关超时',
        'responsibility' => '服务器',
        'detail' => '作为网关或代理的服务器没有及时收到请求，可能是后端服务器响应太慢或网络连接问题。'
    ),
    '505' => array(
        'reason' => 'HTTP版本不支持',
        'responsibility' => '客户端',
        'detail' => '服务器不支持请求中使用的HTTP协议版本，可能是客户端使用了过旧或过新的HTTP版本。'
    )
);
?>