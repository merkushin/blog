			<div class="container">
				<div class="form">
					<h1>{lang.commentcreate}</h1>
					<div class="blockr">
						<div class="formcontent">
							<form action="save.php" method="post">
							<input type="hidden" name="action" value="comment" />
							<input type="hidden" name="postid" value="{postid}" />
								<fieldset class="regularfieldset">
									<ul>
										<li><label>{lang.commentsubject}:</label> <input type="text" class="iText" name="subject" style="width: 99%;" /></li>
										<li><label>{lang.commenttext}:</label> <textarea rows="10" cols="auto" style="width: 99%;" name="text"></textarea></li>
										<li><input type="submit" class="iSubmit" value="{lang.publish}" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>