
DROP TABLE IF EXISTS `api_admin`;
CREATE TABLE `api_admin`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 已删除 1 已通过',
  `addtime` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `rid` int(11) NOT NULL DEFAULT 0 COMMENT '角色ID',
  `salt` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '加盐扰码',
  `nickname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '管理员登录名',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码 md5',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `api_admin_log`;
CREATE TABLE `api_admin_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 登录 2 退出',
  `ip` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ip地址',
  `addtime` int(10) NOT NULL COMMENT '操作时间',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_goback
-- ----------------------------
DROP TABLE IF EXISTS `api_goback`;
CREATE TABLE `api_goback`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `list_id` int(10) UNSIGNED NOT NULL COMMENT '所属接口ID',
  `fid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属一级ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '参数名',
  `title_desc` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '参数说明',
  `isrequired` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否必含 1是 0否',
  `istop` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '额外参数 不在list内',
  `datatype` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '数据类型',
  `demo_desc` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '示例',
  `addtime` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `adduser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加人',
  `lasttime` int(10) UNSIGNED NOT NULL COMMENT '最后操作时间',
  `lastuser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '最后操作者',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 1 COMMENT '排序',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1删除',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `list_id`(`list_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 129 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '接口返回表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_lists
-- ----------------------------
DROP TABLE IF EXISTS `api_lists`;
CREATE TABLE `api_lists`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) UNSIGNED NOT NULL COMMENT '项目组 ',
  `module_id` int(10) UNSIGNED NOT NULL COMMENT '模块ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '接口名称',
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '接口地址',
  `url_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `restype` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 默认POST 0 GET',
  `ishtml` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1是网页',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 正常 1 弃用',
  `version` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '版本',
  `lastuser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '最后更新者',
  `lasttime` int(10) UNSIGNED NOT NULL COMMENT '最后更新时间',
  `adduser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加者',
  `addtime` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1正常 0删除',
  `tags` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `intro` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 67 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '接口表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_logs
-- ----------------------------
DROP TABLE IF EXISTS `api_logs`;
CREATE TABLE `api_logs`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `pubdate` int(11) NOT NULL DEFAULT 0 COMMENT '操作时间',
  `acttype` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `ipaddr` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录ip',
  `nick` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置',
  `infos` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作详细',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 169 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '操作日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_param
-- ----------------------------
DROP TABLE IF EXISTS `api_param`;
CREATE TABLE `api_param`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `list_id` int(10) UNSIGNED NOT NULL COMMENT '所属接口ID',
  `isrequired` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否必填 1是 0否',
  `isvalue` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否必填 1是 0否',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1删除',
  `sort` smallint(4) UNSIGNED NOT NULL DEFAULT 1 COMMENT '排序',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '参数名',
  `title_desc` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '参数说明',
  `datatype` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '数据类型',
  `demo_desc` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '示例',
  `addtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `lasttime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后操作时间',
  `adduser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加人',
  `lastuser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '最后操作者',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `list_id`(`list_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 114 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '接口参数表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_project
-- ----------------------------
DROP TABLE IF EXISTS `api_project`;
CREATE TABLE `api_project`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1删除',
  `addtime` int(11) UNSIGNED NOT NULL,
  `sort` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '排序 顺序',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '模块名',
  `key` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密钥',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '默认地址',
  `dingtalk_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '钉钉机器人token',
  `logo_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'logo地址',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `satus` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 正常  0废弃',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '接口模块表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_project_module
-- ----------------------------
DROP TABLE IF EXISTS `api_project_module`;
CREATE TABLE `api_project_module`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL,
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '模块名',
  `is_del` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1删除',
  `addtime` int(10) NOT NULL DEFAULT 0,
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '项目模块表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_project_test_user
-- ----------------------------
DROP TABLE IF EXISTS `api_project_test_user`;
CREATE TABLE `api_project_test_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '测试帐号名称',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '测试帐号密码',
  `is_del` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1删除',
  `addtime` int(10) NOT NULL DEFAULT 0,
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '项目测试账户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_role
-- ----------------------------
DROP TABLE IF EXISTS `api_role`;
CREATE TABLE `api_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 删除',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '角色名',
  `addtime` int(20) UNSIGNED NOT NULL COMMENT '添加时间',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_tags
-- ----------------------------
DROP TABLE IF EXISTS `api_tags`;
CREATE TABLE `api_tags`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `addtime` int(11) UNSIGNED NOT NULL COMMENT '操作时间',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '操作详细',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1删除',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT '排序 倒序',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '标签' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for api_user
-- ----------------------------
DROP TABLE IF EXISTS `api_user`;
CREATE TABLE `api_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `addtime` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色ID',
  `salt` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '加盐扰码',
  `nickname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '管理员登录名',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码 md5',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1 已删除',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_user_action
-- ----------------------------
DROP TABLE IF EXISTS `log_user_action`;
CREATE TABLE `log_user_action`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '用户id',
  `addtime` int(11) NOT NULL DEFAULT 0 COMMENT '操作时间',
  `act_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录ip',
  `nickname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '位置',
  `infos` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作详细',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 110 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户操作记录日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_user_login
-- ----------------------------
DROP TABLE IF EXISTS `log_user_login`;
CREATE TABLE `log_user_login`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 登录 2 退出',
  `ip` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ip地址',
  `addtime` int(10) UNSIGNED NOT NULL COMMENT '操作时间',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户登录记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_user_operate
-- ----------------------------
DROP TABLE IF EXISTS `log_user_operate`;
CREATE TABLE `log_user_operate`  (
  `id` bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `operate_type` tinyint(1) UNSIGNED NOT NULL COMMENT '操作类型 1添加 2修改 3删除',
  `obj_type` tinyint(2) UNSIGNED NOT NULL COMMENT '操作对象类型',
  `obj_id` int(10) UNSIGNED NOT NULL COMMENT '操作对象ID',
  `obj_data` json NOT NULL COMMENT '操作对象实体 ',
  `addtime` int(10) UNSIGNED NOT NULL COMMENT '操作时间',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户操作记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for system_api
-- ----------------------------
DROP TABLE IF EXISTS `system_api`;
CREATE TABLE `system_api`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) NOT NULL DEFAULT 1 COMMENT '项目组 默认1 我的项目',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '接口名称',
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '接口地址',
  `restype` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 默认POST 2 GET',
  `version` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '版本',
  `controller` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '控制器',
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '方法',
  `lastuser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '最后更新者',
  `lasttime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后更新时间',
  `adduser` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '添加者',
  `addtime` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `intro` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 正常 1 弃用',
  `last_update_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '接口表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
