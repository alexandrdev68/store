<nav class="navbar navbar-inverse navbar-fixed-bottom _admin-panel" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?=(isset($arPar['home']['link']) ? $arPar['home']['link'] : '#')?>"><?=(isset($arPar['home']['name']) ? $arPar['home']['name'] : '')?></a>
	</div>
		
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
	<?if(isset($arPar['menu']) && $arPar['menu'] === true):?>
	<ul class="nav navbar-nav">
		<li class="active"><a href="#">Link</a></li>
		<li><a href="#">Link</a></li>
		<!-- <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
			<ul class="dropdown-menu">
				<li><a href="#">Action</a></li>
				<li><a href="#">Another action</a></li>
				<li><a href="#">Something else here</a></li>
				<li class="divider"></li>
				<li><a href="#">Separated link</a></li>
				<li class="divider"></li>
				<li><a href="#">One more separated link</a></li>
			</ul>
		</li>-->
	</ul>
	<?endif?>
	<?if(isset($arPar['search']) && $arPar['search'] === true):?>
	<form class="navbar-form navbar-left" role="search">
		<div class="form-group">
		<input type="text" class="form-control" placeholder="Search">
		</div>
		<button type="submit" class="btn btn-default">Submit</button>
	</form>
	<?endif?>
</div>
</nav>