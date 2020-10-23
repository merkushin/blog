			<div class="container">
				<div id="Registration" class="form">
					<h1>{lang.register}</h1>
					<div class="blockr">
						<div class="formcontent">
							<form action="login.php" method="post">
							<input type="hidden" name="action" value="register" />
								<fieldset class="regularfieldset">
									<ul>
										<li><label>{lang.username}:</label> <input type="text" class="iText" name="username" style="width: 70%;" /></li>
										<li><label>{lang.email}:</label> <input type="text" class="iText" name="email" style="width: 70%;" />
										</li>
										<li><input type="submit" class="iSubmit" value="{lang.register}" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>