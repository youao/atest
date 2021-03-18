<?php

$member = checkAuth();
if (!$member) {
    exitRequestJson('登录验证失败', 402);
}

exitRequestJson('登录验证成功', 1, $member);