<?php

namespace app\widgets\EPager;

use yii\widgets\LinkPager;

// TODO REWORK

class EPager extends LinkPager {

    const CSS_FIRST_PAGE='first';
    const CSS_LAST_PAGE='last';
    const CSS_PREVIOUS_PAGE='previous';
    const CSS_NEXT_PAGE='next';
    const CSS_INTERNAL_PAGE='page';
    const CSS_HIDDEN_PAGE='disabled';
    const CSS_SELECTED_PAGE='active';

    /**
     * @var string the CSS class for the first page button. Defaults to 'first'.
     * @since 1.1.11
     */
    public $firstPageCssClass=self::CSS_FIRST_PAGE;
    /**
     * @var string the CSS class for the last page button. Defaults to 'last'.
     * @since 1.1.11
     */
    public $lastPageCssClass=self::CSS_LAST_PAGE;
    /**
     * @var string the CSS class for the previous page button. Defaults to 'previous'.
     * @since 1.1.11
     */
    public $previousPageCssClass=self::CSS_PREVIOUS_PAGE;
    /**
     * @var string the CSS class for the next page button. Defaults to 'next'.
     * @since 1.1.11
     */
    public $nextPageCssClass=self::CSS_NEXT_PAGE;
    /**
     * @var string the CSS class for the internal page buttons. Defaults to 'page'.
     * @since 1.1.11
     */
    public $internalPageCssClass=self::CSS_INTERNAL_PAGE;
    /**
     * @var string the CSS class for the hidden page buttons. Defaults to 'hidden'.
     * @since 1.1.11
     */
    public $hiddenPageCssClass=self::CSS_HIDDEN_PAGE;
    /**
     * @var string the CSS class for the selected page buttons. Defaults to 'selected'.
     * @since 1.1.11
     */
    public $selectedPageCssClass=self::CSS_SELECTED_PAGE;


    public function init()
    {
        if(!isset($this->options['class']))
            $this->options['class']='pagination';
        return parent::init();
    }
}
?>