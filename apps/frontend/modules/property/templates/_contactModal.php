<div style="display:none;">
	<div id="contactFormWrapper">
		<aside id="uiContactForm">
			<header><h2>Contact form</h2></header>
			<section class="formWrapper">
				<form method="post" action="<?php echo url_for('property/mail?id='.$property->getId()) ?>">
					<ul>
						<li><label for="name">Name</label><input name="name" id="name" type="text"/></li>
						<li><label for="email">E-mail</label><input name="email" id="email" type="text"/></li>
						<li><label for="phone">Phone</label><input name="phone" id="phone" type="text"/></li>
						<li><label for="message">Message</label><textarea name="message" id="message"></textarea></li>
						<li class="submitRow"><input type="submit" value="Submit"/></li>
					</ul>
				</form>
			</section>
		</aside>
	</div>
</div>