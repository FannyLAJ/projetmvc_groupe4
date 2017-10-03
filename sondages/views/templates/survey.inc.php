
<li class="media well">
	<div class="media-body">
		<h4 class="media-heading"><?php= $survey->getQuestion() ?></h4>
		<br>
	  <?php
	  foreach ($survey->getResponses() as $response) {
	  /* TODO START */ 
		/* TODO END */
		} 
		?>
		
		<!--<div class="fluid-row">
			<div class="span2">Réponse 1</div>
			<div class="span2 progress progress-striped active">
				<div class="bar" style="width: 20%"></div>
			</div>
			<span class="span1">(20%)</span>
			<form class="span1" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?action=Vote';?>">
				<input type="hidden" name="responseId" value="1"> 
				<input type="submit" style="margin-left:5px" class="span1 btn btn-small btn-danger" value="Voter">
			</form>
		</div>

		<div class="fluid-row">
			<div class="span2">Réponse 2</div>
			<div class="span3 progress progress-striped active">
				<div class="bar" style="width: 80%"></div>
			</div>
			<span class="span1">(80%)</span>
			<form class="span1" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?action=Vote';?>">
				<input type="hidden" name="responseId" value="2"> 
				<input type="submit" style="margin-left:5px" class="span1 btn btn-small btn-danger" value="Voter">
			</form>
		</div>-->
		
	</div>
</li>



