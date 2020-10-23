	<div id="Content">
		<form method="post" action="save.php">
		<input type="hidden" name="postid" value="{postid}" />
		<SCRIPT type="text/javascript">
		function addcode(text){
			var msgarea = document.getElementById('tarea');
			if (msgarea) {
				msgarea.innerHTML += text;
				msgarea.focus();
			}
			return;
		}
	</SCRIPT>
		<div id="C1">
			<div class="form">
				<h1>{lang.createpost}</h1>
				<div class="blockr">
					<div class="formcontent">
						<fieldset class="postfieldset">
							<ul>
								<li><label>{lang.theme}:</label> <input type="text" class="iText" style="width: 99%;" name="title" value="{title}" /></li>
								<li><label>{lang.text}:</label>
									<fieldset class="bbcodes">
										<input type="button" class="iBBcode" value=" B " onclick="javascript:addcode('[b][/b]');void(0);" />
										<input type="button" class="iBBcode" value=" I " onclick="javascript:addcode('[i][/i]');void(0);" />
										<input type="button" class="iBBcode" value=" U " onclick="javascript:addcode('[u][/u]');void(0);" />
										<input type="button" class="iBBcode" value=" Q " onclick="javascript:addcode('[q][/q]');void(0);" />
										<input type="button" class="iBBcode" value=" P " onclick="javascript:addcode('[p][/p]');void(0);" />
										<input type="button" class="iBBcode" value=" IMG " onclick="javascript:addcode('[img][/img]');void(0);" />
										<input type="button" class="iBBcode" value=" URL " onclick="javascript:addcode('[url][/url]');void(0);" />
									</fieldset>
									<textarea id="tarea" class="iTextarea" rows="20" cols="auto" style="width: 99%;" name="text">{text}</textarea></li>
								<li><li><label>{lang.accesslevel}:</label> <select class="iSelect" name="access">{accesslist}</select></li></li>
								<li><li><label>{lang.showposts}:</label> <select class="iSelect" name="mode">{listmodes}</select></li></li>
								<li><input type="submit" class="iButton" value="{lang.save}" name="draft" /> <input type="submit" class="iButton" value="{lang.publish}" name="post" /></li>
							</ul>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		<div id="C2">
			{userpanel}
			<div class="container">
				<div class="block">
					<div class="form">
						<fieldset class="catfieldset">
							<ul>
								<li><label>{lang.categories}:</label>
									<fieldset class="checkboxes">
										<ul>
											{selectcats}
										</ul>
									</fieldset>
								</li>
							</ul>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>