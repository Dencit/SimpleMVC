<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/6/3 Time: 17:22 **/

echo"
<div class='container'>
	<div class='row'>
		<div class='bs-callout bs-callout-info'>
			<h4>于<span class='red'>&nbsp;" . date('Y-m-d h:i:s', time()) . "&nbsp;</span>前 新登录<span class='red'>&nbsp;" . count($usersTotal) . "&nbsp;</span>个用户</h4>
		</div>
	</div>
</div>
";