<?php 

function generateInputForResponse($n) {
	?>
	<div class="control-group">
		<label class="control-label" for="responseSurvey<?php echo $n; ?>">Réponse <?php echo $n; ?></label>
		<div class="controls">
			<input class="span3" type="text" name="responseSurvey<?php echo $n; ?>" placeholder="Réponse <?php echo $n; ?>">
		</div>
	</div>
<?php
}
?>

<form method="post" action="index.php?action=AddSurvey" class="modal">
	<div class="modal-header">
		<h3>Création d'un sondage</h3>
	</div>
	<div class="form-horizontal modal-body">
		<?php	if ($this->message!=="")
			echo '<div class="alert '.$this->style.'">'.$this->message.'</div>';
		?>
		<div class="control-group">
			<label class="control-label" for="questionSurvey">Question</label>
			<div class="controls">
				<input class="span3" type="text" name="questionSurvey" placeholder="Question">
			</div>
		</div>
		<br>
		<?php 
			for ($i = 1; $i <= 5; $i++)
				generateInputForResponse($i);
		?>
	</div>
	<div class="modal-footer">
		<input class="btn btn-danger" type="submit"	value="Poster le sondage" />
	</div>
</form>



