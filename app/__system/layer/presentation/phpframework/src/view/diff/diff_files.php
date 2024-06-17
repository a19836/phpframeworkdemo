<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$head = '
<style>
	.diff {
		width:100%;
		table-layout:fixed;
	}
	.diff td {
		width:50%;
		vertical-align: top;
		white-space:pre;
		white-space:pre-wrap;
		overflow-wrap:break-word;
		font-family:var(--main-font-family);
	}
	.diff td:first-child {
		border-right:1px solid #ccc;
		background:#f7f7f7;
	}
	.diff td.diffDeleted {
		background:#ff00002b;
	}
	.diff td.diffInserted {
		background:#00800040;
	}
</style>'; $main_content = $html; ?>
