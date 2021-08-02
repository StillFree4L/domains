<?php
/**
 * Extend tr tag
 * 
 * @author Andy
 * Example: http://wiki.vpn/doku.php/revcom/%D0%B2%D0%B8%D0%B4%D0%B6%D0%B5%D1%82%D1%8B/parambootgridview
 */


Yii::import("bootstrap.widgets.TbGridView", true);

class ParamBootGridView extends TbGridView {

	protected $rowSetKeys = array();
	
	// Additional parametr
	public $extParam = null;

	public function init() {
		parent::init();
		$this->rowSetKeys = $this->dataProvider->getKeys();
	}

	/**
	 * @param $row
	 * add custom property to the generated tr element
	 */
	public function renderTableRow($row) {
		$params = '';
				
		// Isset additional parameters
		if (isset($this->extParam)) {
			$data_array = $this->dataProvider->data[$row] -> getAttributes();
			foreach ($this->extParam as $k=>$v) {
				if (array_key_exists($v, $data_array)) {					
					$params.= $k.'="'.$data_array[$v].'" ';
				}
			}
		}
				

		if ($this->rowCssClassExpression !== null) {
			$data = $this->dataProvider->data[$row];
			echo '<tr class="' . $this->evaluateExpression($this->rowCssClassExpression, array('row' => $row, 'data' => $data)) . '" ' . $params . '>';
		} else if (is_array($this->rowCssClass) && ($n = count($this->rowCssClass)) > 0) {
			echo '<tr class="' . $this->rowCssClass[$row % $n] . '" ' . $params . '>';
		} else {
			echo '<tr id=' . $params . '>';
		}
		
		foreach ($this->columns as $column) {
			$column->renderDataCell($row);
		}
		echo "</tr>\n";
	}
}

?>
