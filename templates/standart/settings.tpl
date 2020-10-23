			<div class="container">
				<div class="form">
					<h1>{lang.blogsettings}</h1>
					<div class="blockr">
						<div class="formcontent">
							<form method="post" action="settings.php">
								<fieldset class="regularfieldset">
									<ul>
										<li><label>{lang.blogtitle}:</label> <input type="text" class="iText" name="title" style="width: 99%;" value="{stitle}" /></li>
										<li><label>{lang.blogdescription}:</label> <input type="text" class="iText" name="description" style="width: 99%;" value="{sdescription}" /></li>
										<li><label>{lang.blogkeywords}:</label> <input type="text" class="iText" name="keywords" style="width: 99%;" value="{skeywords}" /></li>
										<li><label>{lang.blogsearchdsc}:</label> <input type="text" class="iText" name="searchdesc" style="width: 99%;" value="{ssearchdesc}" /></li>
										<li><label>{lang.listmode}:</label> <select class="iSelect" name="listmode">{slistmodes}</select></li>
										<li><label>{lang.language}:</label> <select class="iSelect" name="language">{slanglist}</select></li>
										<li><label>{lang.templates}:</label> <select class="iSelect" name="template">{stemplist}</select></li>
										<li><input type="submit" class="iButton" value="{lang.savechanges}" name="change" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
