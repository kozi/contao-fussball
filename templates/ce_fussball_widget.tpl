<style type="text/css">
#fussball_widget_highlighted_row td {
	background-color:#FF7300 !important;
	font-size:12px !important;;	
	font-weight:bold !important;
	color:#fff !important;	
}

#fussball_widget_highlighted_row td.fbdeAPIAufsteigerInactA {
	background-color:#FF7300 !important;
}

#fussball_widget_highlighted_row a {
	font-size:12px !important;;	
	font-weight:bold !important;
	color:#fff !important;	
}
</style>
<?php $id = 'fw'.$this->fussball_saison.$this->fussball_wettbewerbs_id; ?>

<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>

<?php if ($this->headline): ?>
	<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif;?>

	<p class="small">Die <a href="#ergebnisse" onclick="return <?php echo $id;?>.ergebnisse();">Ergebnisse</a> des aktuellen Spieltags, sowie die aktuelle
	<a href="#tabelle" onclick="return <?php echo $id;?>.tabelle();">Tabelle</a> könnt ihr euch in dem Widget direkt ansehen. Alle anderen Links führen zu den Seiten von fussball.de.</p>

	<div id="id<?php echo $id;?>"><noscript><strong>Bitte Javascript aktivieren.</strong></noscript></div>

<script type="text/javascript">
  var <?php echo $id;?> = new FussballWidget();	
  <?php echo $id;?>.init('<?php echo implode("','", array($id, 
  														$this->fussball_saison,
  														$this->fussball_wettbewerbs_id,
  														$this->fussball_team)); ?>');
  <?php echo $id;?>.ergebnisse();
</script>

</div>



