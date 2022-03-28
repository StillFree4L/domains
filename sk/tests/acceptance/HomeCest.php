<?php

use yii\helpers\Url;

class HomeCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));        
        $I->see('SK');
        /*
        $I->seeLink('КОНТАКТЫ');
        $I->click('КОНТАКТЫ');
        $I->wait(2); // wait for page to be opened
        
        $I->see('Свяжитесь с сотрудниками SK, чтобы задать вопрос онлайн или о том, как воспользоваться нашими услугами');
    */
    }
}
