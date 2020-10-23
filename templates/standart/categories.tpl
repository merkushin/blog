			<div class="container">
				<div id="Registration" class="form">
					<h1>{lang.categoriespage}</h1>
					<div class="blockr">
						<div class="formcontent">
							<form action="categories.php" method="post">
								<fieldset class="regularfieldset">
									<ul>
										<li><label>{lang.catname}:</label> <input type="text" class="iText" name="newcategory" style="width: 70%;" /></li>
										<li><input type="submit" class="iSubmit" value="{lang.add}" /></li>
									</ul>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="userlist">
					<!--<h1>{lang.categories}</h1>-->
					<div class="blockr">
						<table>
							<thead>
								<tr>
									<th>{lang.catname}</th>
									<th>{lang.action}</th>
								</tr>
							</thead>
							<tbody>
								{categories}
							</tbody>
						</table>
					</div>
				</div>
			</div>