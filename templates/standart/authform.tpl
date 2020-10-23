			<div class="container">
				<div id="Authorize" class="block">
				<form method="post" action="login.php">
				<input type="hidden" name="action" value="login" />
					<fieldset class="smallfieldset">
						<ul>
							<li><label>{lang.username}:</label> <input type="text" class="iText" name="username" /></li>
							<li><label>{lang.password}:</label> <input type="password" class="iText" name="password" /></li>
							<li><input type="submit" class="iSubmit" value="{lang.login}" /></li>
							<li><p>{lang.wantto} <a href="login.php?action=register">{lang.register}</a>?</p></li>
						</ul>
					</fieldset>
				</form>
				</div>
			</div>