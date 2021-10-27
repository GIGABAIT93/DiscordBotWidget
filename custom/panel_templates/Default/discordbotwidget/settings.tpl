{include file='header.tpl'}

<body id="page-top">

	<!-- Wrapper -->
	<div id="wrapper">

		<!-- Sidebar -->
		{include file='sidebar.tpl'}

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main content -->
			<div id="content">

				<!-- Topbar -->
				{include file='navbar.tpl'}

				<!-- Begin Page Content -->
				<div class="container-fluid">

					<!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-4">


						<div class="row mb-2">
							<div class="col-sm-6">
								<h1 class="m-0 text-dark">{$TITLE}</h1>
							</div>
						</div>
					</div>

					<section class="content">
						{if isset($SUCCESS)}
							<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
								{$SUCCESS}
							</div>
						{/if}

						{if isset($ERRORS) && count($ERRORS)}
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
								<ul>
									{foreach from=$ERRORS item=error}
										<li>{$error}</li>
									{/foreach}
								</ul>
							</div>
						{/if}

						<div class="col">

							<form action="" method="POST">
								<div class="form-group">
									<label for="discord_id">{$DISCORD_ID_LABEL}</label>
									<input type="text" class="form-control" id="discord_id" name="discord_id" value="{$DISCORD_ID}">
								</div>


								<div class="form-group">
									<label for="channel_id">{$CHANNEL_ID_LABEL}</label>
									<input type="text" class="form-control" id="channel_id" name="channel_id" value="{$CHANNEL_ID}">
								</div>

								<div class="form-group">
									<label for="color_btn">{$COLOR_BTN_LABEL}</label>
									<input type="color" class="form-control" id="color_btn" name="color_btn" value="{$COLOR_BTN}">
								</div>

								<div class="form-group">
									<label for="btn_horizontal">{$HORIZONTAL_LOCATION_LABEL}</label>
									<select class="form-control" id="btn_horizontal" name="btn_horizontal">
										<option {if $BTN_HORIZONTAL == 'bottom'}selected{/if} value="bottom">Bottom</option>
										<option {if $BTN_HORIZONTAL == 'top'}selected{/if} value="top">Top</option>
									</select>
								</div>


								<div class="form-group">
									<label for="btn_vertical">{$VERTICAL_LOCATION_LABEL}</label>
									<select class="form-control" id="btn_vertical" name="btn_vertical">
										<option {if $BTN_VERTICAL == 'left'}selected{/if} value="left">Left</option>
										<option {if $BTN_VERTICAL == 'right'}selected{/if} value="right">Right</option>
									</select>
								</div>

								<div class="form-group">
									<input type="hidden" name="token" value="{$TOKEN}">
									<button style="width: 100%;" type="submit" class="btn btn-primary"><i
											class="fas fa-save"></i></button>
								</div>

							</form>
						</div>


					</section>
				</div>
			</div>
			{include file='footer.tpl'}
		</div>
	</div>
	{include file='scripts.tpl'}
</body>