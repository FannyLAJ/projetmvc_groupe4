
<li class="media well">
    <div class="media-body">
        <h4 class="media-heading"><?php echo $survey->getQuestion(); ?>
        </h4>

        <br>
        <?php
        
        foreach ($survey->getResponses() as $response) {
            $title = $response->getTitle();
            $responseId = $response->getId();
            $percentage = $response->getPercentage();

            echo '<div class="fluid-row">
			<div class="span2">'.$title.'</div>
			<div class="span2 progress progress-striped active">
				<div class="bar" style="width: '.$percentage.'%"></div>
			</div>
			<span class="span1">'.$response->getCount().' vote(s) </span>
			<form class="span1" method="post" action="'.$_SERVER["PHP_SELF"].'?action=Vote">
				<input type="hidden" name="responseId" value="'.$responseId.'"> 
				<input type="submit" style="margin-left:5px" class="span1 btn btn-small btn-danger" value="Voter">
			</form>		
		    </div>';
        }

        if ($_GET['action'] == 'GetMySurveys') {
            echo '<form class="span1" method="post" action="'.$_SERVER["PHP_SELF"].'?action=DeleteSurvey">
        <input type="hidden" name="surveyId" value="'.$survey->getId().'"> 
				<input type="submit" style="margin-left:5px" class="span2 btn btn-small btn-danger" value="Supprimer">
			</form>';
        }


        ?>
    </div>
</li>



