<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks (
	'data_feeds_export',
	'ee_encode_file_price',
	'dispatch_assign_template'
);
