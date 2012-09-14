<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2012 <http://kozianka-online.de/>
 * @author     Martin Kozianka <http://kozianka-online.de/>
 * @package    fussball_widget 
 * @license    LGPL 
 * @filesource
 */

/**
 * Class GoalGetterListContent 
 *
 * @copyright  Martin Kozianka 2012
 * @author     Martin Kozianka <http://kozianka-online.de>
 * @package    fussball_widget
 */

class GoalGetterListContent extends ContentElement {
	protected $strTemplate = 'ce_fussball_goalgetter';
	private $ggArr = null;
	
	public function generate() {
		$this->ggArr = unserialize($this->fussball_goalgetter);
		
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### GoalGetterList ###<table>';

			foreach ($this->ggArr as $gg) {
				$objTemplate->wildcard .= '<tr><td style="padding:2px 6px;">'.$gg['fussball_gg_name'].'</td>';
				$objTemplate->wildcard .= '<td style="padding:2px 6px;">'.$gg['fussball_gg_goals'].'</td></tr>';
			}
			$objTemplate->wildcard .= '</table>';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}		
		return parent::generate();
	}
			
	protected function compile() {
		$arr = array();
		foreach ($this->ggArr as $gg) {
			$name  = $gg['fussball_gg_name'];
			$goals = $gg['fussball_gg_goals'];
			
			if (array_key_exists($goals, $arr)) {
				$arr[$goals]->names[] = $name;
			} else {
				$arr[$goals] = new stdClass();
				$arr[$goals]->goals = $goals;
				$arr[$goals]->names = array($name);
			}
		}
		
		krsort($arr);
		$i        = 0;
		$position = 1;
		foreach ($arr as &$row) {
			$row->position  = $position;
			$row->cssClass  = 'row_'.$i;
			$row->cssClass .= ($i++ == 0) ? ' first': '';
			$row->cssClass .= ($i % 2 == 0 ) ? ' odd': ' even';
			
			$row->goals_view  = (count($row->names) > 1) ? 'je ' : '';
			$row->goals_view .= $row->goals;
			$row->goals_view .= ($row->goals==1) ? ' Tor' : ' Tore';
			
			$position      += count($row->names);
			
			
		}
		$row->cssClass .= ' last';
		
		$this->Template->goalgetter_list = $arr; 
	}

}

?>