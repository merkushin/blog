				<div id="BlogAccess" class="block blogaccess">
					<p class="bookmarks"><a href="index.php?viewby=date" class="menulink">{lang.bydate}</a> | <a href="index.php?viewby=calendar" class="menulink">{lang.bycalendar}</a> | <a href="index.php?viewby=categories" class="menulink">{lang.bycategories}</a></p>
					<table class="calendar" align="center">
						<thead>
							<tr>
								<th colspan="8"><a href="index.php?viewby=calendar&amp;month={prevmonth}&amp;year={prevyear}">&lt;&lt;</a> {month} {year} <a href="index.php?viewby=calendar&amp;month={nextmonth}&amp;year={nextyear}">&gt;&gt;</a></th>
							</tr>
							<tr>
								<th>{lang.monday}</th>
								<th>{lang.tuesday}</th>
								<th>{lang.wednesday}</th>
								<th>{lang.thursday}</th>
								<th>{lang.friday}</th>
								<th>{lang.saturday}</th>
								<th>{lang.sunday}</th>
							</tr>
						</thead>
						{calendar}
					</table>
				</div>
				<div class="break"></div>