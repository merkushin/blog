					<h1>{posttitle}</h1>
					{userpicture}
					<div class="blockr">
						<div class="postinfo">
							<p>{lang.author}: <a href="profile.php?id={authorid}" class="userlink" title="">{authorname}</a></p>
							<p>{lang.published}: {date}</p>
						</div>
						<div class="postaction"><p><a href="index.php?id={postid}&amp;showform=1" class="actionlink">{lang.commentcreate}</a> | <a href="index.php?id={postid}&amp;showcomments=1" class="actionlink">{lang.comments}</a> ({commentsnum}) | <a href="index.php?id={postid}" class="actionlink">{lang.link}</a></p></div>
					</div>
					<div class="blockr">
						<div class="text">
							{posttext}
						</div>
					</div>
					<div class="blockr">
						<div class="postaction"><p><a href="index.php?id={postid}&amp;showform=1" class="actionlink">{lang.commentcreate}</a> | <a href="index.php?id={postid}&amp;showcomments=1" class="actionlink">{lang.comments}</a> ({commentsnum}) | <a href="index.php?id={postid}" class="actionlink">{lang.link}</a></p></div>
					</div>