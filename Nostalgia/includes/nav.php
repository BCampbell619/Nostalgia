	<?php
	
		foreach($navItems as $item) {?>
		<ul>
		    <?php echo "<li><a href=\"$item[slug]\">$item[title]</a></li>"; ?>
		</ul>
			
	<?php }?>