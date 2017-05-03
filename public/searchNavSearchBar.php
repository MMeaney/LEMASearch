<nav class="navbar navbar-default navbar-static-top">

<div class="container-fluid">
<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a href="/index.php" class="navbar-left">
			<img src=".\img\epa-logo-small-trns.gif" alt="Environmental Protection Agency (EPA) Ireland" 
				style="width:83px;height:50px;">
		</a><!-- ./navbar-left img -->	
		
	</div><!-- ./navbar-header -->
		
		
	<div class="navbar-header pull-left">
		<div class="col-sm-8 col-md-8">
		<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="navbar-form navbar-center" role="search" id="formSearchDataCatalogue">
			
			<div class="input-group" id="divNavSearchBar">
				<div class="form-group has-feedback has-clear">
					<input id="inputSearchString" name="q" value="<?php echo $_REQUEST['q']; ?>" type="text" placeholder="What would you like to search for?" class="form-control input-md" size="50" autofocus onfocus="this.value = this.value;" />
					<span class="form-control-clear glyphicon glyphicon-remove-circle form-control-feedback hidden" id="searchClear"></span>
				</div><!-- ./form-group -->
					<input id="inputSearchButton" type="hidden" name="submitted" value="true" />
					<span class="input-group-btn">
					<button class="btn btn-primary btn-md" type="submit">
						<i class="glyphicon glyphicon-search"></i> <!-- Bootstrap Search Glyphicon -->
						<!-- <i class="fa fa-search-plus" aria-hidden="true"></i> --> <!-- Font Awesome Search Plus Icon -->
						<!-- <i class="fa fa-search" aria-hidden="true"></i> --> <!-- Font Awesome Search Icon -->
					</button>
					</span><!-- ./input-group-btn -->
			</div><!-- ./input-group -->	
			
			<!-- &nbsp; &nbsp; -->
			<!-- 
				<span id="show" class="btn btn-info btn-xs">+ Expand All Results</span>
				<span id="expand_delimiter">|</span>
				<span id="hide" class="btn btn-info btn-xs">- Collapse All Results</span>
			 -->
			
				<!-- <span id="show" class="expand">+ Expand all</span> -->
				<!-- <span id="expand_delimiter" class="expand">|</span> -->
				<!-- <span id="hide" class="expand">- Collapse all</span> -->	
			<!-- </form> -->
		</div><!-- ./col-sm-8 col-md-8 -->
	</div><!-- ./navbar-header pull-left -->
	
	<div id="navbar" class="navbar-collapse collapse">
		<ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul><!-- ./dropdown-menu -->
            </li><!-- ./dropdown -->
            <li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li>
            <li>
				<ul class="nav pull-left">
	            <li class="nav navbar-text" id="navSignedInUser">User Name</li>	            
		            <li class="dropdown pull-right">
						<a href="#" data-toggle="dropdown" style="color:#777; margin-top: 5px;" class="dropdown-toggle">
							<span class="glyphicon glyphicon-user"></span>
							<b class="caret"></b>
						</a>
					<ul class="dropdown-menu">
						<li><a href="/users/id" title="Profile">Profile</a></li>
		            	<li><a href="/logout" title="Logout">Logout</a></li>
					</ul><!-- ./dropdown-menu -->
					</li><!-- ./dropdown pull-right -->
				</ul><!-- ./nav pull-left -->
			</li>
		</ul><!-- ./navbar-right -->
	</div><!--/.nav-collapse -->		

</div><!-- /.container-fluid -->
</nav>