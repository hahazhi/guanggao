<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MiniKan Control Panel</title>
<link rel="stylesheet" href="/styles/images/admincp.css" type="text/css" media="all" />
</head>
<body>
<div class="mainhd">
	<div class="logo">MiniKan Administrator's Control Panel</div>
	<div class="uinfo">
		<p>欢迎, <em></em> [ <a href="#" target="_top">退出</a> ]</p>
			
			<script type="text/javascript">
				function showmenu(ctrl) {
					ctrl.className = ctrl.className == 'otherson' ? 'othersoff' : 'otherson';
					var menu = parent.document.getElementById('toggle');
					if(!menu) {
						menu = parent.document.createElement('div');
						menu.id = 'toggle';
						menu.innerHTML = '<ul>' + document.getElementById('header_menu_menu').innerHTML + '</ul>';
						var obj = ctrl;
						var x = ctrl.offsetLeft;
						var y = ctrl.offsetTop;
						while((obj = obj.offsetParent) != null) {
							x += obj.offsetLeft;
							y += obj.offsetTop;
						}
						menu.style.left = x + 'px';
						menu.style.top = y + ctrl.offsetHeight + 'px';
						menu.className = 'togglemenu';
						menu.style.display = '';
						parent.document.body.appendChild(menu);
					} else {
						menu.style.display = menu.style.display == 'none' ? '' : 'none';
					}
				}
			</script>
	</div>
</div>
</body>
</html>