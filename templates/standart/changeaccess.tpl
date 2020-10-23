					<div class="blockr">
						<div class="formcontent">
							<form method="post" action="profile.php">
							<input type="hidden" name="userid" value="{userid}" />
								<fieldset class="regularfieldset" >
									<legend>{lang.useraccess}</legend>
									<ul>
										<li><label>{lang.accesslevel}:</label> <select class="iSelect" name="accesslevel">{accesslist}</select></li>
										<li><input type="submit" class="iButton" value="{lang.savechanges}" name="edit" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>