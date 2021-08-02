<?php
/**
 * CheckBoxColumn class file.
 */

Yii::import('zii.widgets.grid.CCheckBoxColumn');

/**
 * CCheckBoxColumn represents a grid view column of checkboxes.
 */
class CheckBoxColumn extends CCheckBoxColumn
{

	public $checkAll = true;

	/**
	 * Renders the header cell content.
	 * This method will render a checkbox in the header when {@link selectableRows} is greater than 1
	 * or in case {@link selectableRows} is null when {@link CGridView::selectableRows} is greater than 1.
	 */
	protected function renderHeaderCellContent()
	{
		if(!$this->checkAll){
			return;
		}
		parent::renderHeaderCellContent();
	}

	/**
	 * Renders the data cell content.
	 * This method renders a checkbox in the data cell.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
	protected function renderDataCellContent($row,$data)
	{
		if($this->value!==null)
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		else if($this->name!==null)
			$value=CHtml::value($data,$this->name);
		else
			$value=$this->grid->dataProvider->keys[$row];

		$checked = false;
		if($this->checked!==null)
			$checked=$this->evaluateExpression($this->checked,array('data'=>$data,'row'=>$row));

		$options=$this->checkBoxHtmlOptions;
		if(isset($options['disabled']) && $options['disabled']!==null){
			$options['disabled']=$this->evaluateExpression($options['disabled'],array('data'=>$data,'row'=>$row));
		}
		$name=$options['name'];
		unset($options['name']);
		$options['value']=$value;
		$options['id']=$this->id.'_'.$row;
		echo CHtml::checkBox($name,$checked,$options);
	}

}
