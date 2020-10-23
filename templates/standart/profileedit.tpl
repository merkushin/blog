			<div class="container">
				<div id="ProfileProperties" class="form">
					<h1>{lang.editprofile}</h1>
					<div class="blockr">
						<div class="formcontent">
							<form method="post" action="profile.php">
							<input type="hidden" name="userid" value="{userid}" />
								<fieldset class="regularfieldset">
									<legend>{lang.changepassword}</legend>
									<ul>
										<li><label>{lang.currentpwd}:</label> <input type="password" class="iText" name="oldpassword" /></li>
										<li><label>{lang.newpwd}:</label> <input type="password" class="iText" name="newpassword" /></li>
										<li><label>{lang.retypepwd}:</label> <input type="password" class="iText" name="repassword" /></li>
										<li><input type="submit" class="iButton" value="{lang.savechanges}" name="edit" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="blockr">
						<div class="formcontent">
							<form method="post" action="profile.php">
							<input type="hidden" name="userid" value="{userid}" />
								<fieldset class="regularfieldset">
									<legend>{lang.changeemail}</legend>
									<ul>
										<li><label>{lang.currentemail}:</label> <input type="text" class="iText" name="oldemail" value="{currentemail}" /></li>
										<li><label>{lang.newemail}:</label> <input type="text" class="iText" name="newemail" /></li>
										<li><input type="submit" class="iButton" value="{lang.savechanges}" name="edit" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="blockr">
						<div class="formcontent">
							<form method="post"	action="profile.php" enctype="multipart/form-data">
							<input type="hidden" name="userid" value="{userid}" />
								<fieldset class="regularfieldset">
									<legend>{lang.userpicmanage}</legend>
									<ul>
										{currentuserpic}
										<li><label>{lang.uploaduserpic}:</label> <input type="file" class="iFile" name="userpic" /></li>
										<li><input type="submit" class="iButton" value="{lang.savechanges}" name="edit" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="blockr">
						<div class="formcontent">
							<form method="post" action="profile.php">
							<input type="hidden" name="userid" value="{userid}" />
								<fieldset class="regularfieldset">
									<legend>{lang.personalinfo}</legend>
									<ul>
										<li><label>{lang.realname}:</label> <input type="text" class="iText" name="realname" value="{realname}" />
											<fieldset class="checkboxes">
												<ul><li><input type="checkbox" name="showrealname" class="iCheck" {showrealname_checked} /><label>{lang.hidename}</label></li></ul>
											</fieldset>
										</li>
										<li><label>{lang.usersex}: </label> <select class="iSelect" name="usersex">{sexlist}</select></li>
										<li><label>{lang.birthdate}: </label>
											<fieldset class="joining"><span>{lang.day}</span> <input type="text" class="iText" size="2" maxlength="2" name="DOB" value="{DOB}" /> <span>{lang.month}</span> <select class="iSelect" name="MOB">{MOB}</select> <span>{lang.year}</span> <input type="text" class="iText" size="4" maxlength="4" name="YOB" value="{YOB}" />
											</fieldset>
											<fieldset class="checkboxes">
												<ul><li><input type="checkbox" class="iCheck" name="showbirthdate" {showbirthdate_checked} /><label> {lang.hidebirthdate}</label></li></ul>
											</fieldset>
										</li>
										<li><label>{lang.userabout}:</label> <textarea rows="10" cols="50" name="userabout">{userabout}</textarea></li>
										<li><input type="submit" class="iButton" value="{lang.savechanges}" name="edit" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="blockr">
						<div class="formcontent">
							<form method="post" action="profile.php">
							<input type="hidden" name="userid" value="{userid}" />
								<fieldset class="regularfieldset" >
									<legend>{lang.interface}</legend>
									<ul>
										<li><label>{lang.viewby}:</label> <select class="iSelect" name="viewslist">{viewslist}</select></li>
										<li><label>{lang.showposts}:</label> <select class="iSelect" name="listmodes">{listmodes}</select></li>
										<li><label>{lang.showcomments}</label> <select class="iSelect" name="showcomments">{commentslist}</select>
										<li><input type="submit" class="iButton" value="{lang.savechanges}" name="edit" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
					{changeaccess}
				</div>
			</div>