<table cellspacing="0" style="width: 600px;font-size: 14px;font-family: 'Times New Roman';color: black;text-align: justify; margin: auto;">
    <tr>
        <td colspan="6" style="font-size: 20px;font-weight: bold;text-align: center;">Договор на ремонт техники  № <?= $model->receipt ?></td>
    </tr>
    <tr height="15px;">
        <td colspan="6"></td>
    </tr><br>
    <tr>
        <td colspan="6" style="text-align: right;">г. Караганда, ул. Нуркена-Абдирова 47/1</td>

    </tr>
    <tr>
        <td colspan="6" style="text-align: right;"><?= $model->date ?></td>
    </tr>
    <br><br>
    <tr>
        <td colspan="6">ТОО «Статус Караганда», именуемое в дальнейшем «Сервисный центр», с одной стороны  и Куаныш, именуемый в дальнейшем «Клиент СЦ», с другой стороны, заключили настоящий договор о нижеследующем:</td>
    </tr>
    <br><br>
    <tr>
        <td colspan="6" style="font-weight: bold;text-align: center;">1.	Предмет и срок действия договора</td>
    </tr>
    <tr>
        <td colspan="6">1.1.	Клиент СЦ поручает, а Сервисный центр принимает на себя обязательства по выполнению диагностики и ремонтных работ в отношении Оборудования Клиента СЦ. Выполняемые Сервисным центром работы включают в себя: диагностику неисправностей (ремонт, замена, техническое обслуживание и т.д.).
            Гарантийный талон № от  срок гарантии  месяцев.
        </td>
    </tr>
    <tr>
        <td colspan="6">Гарантийный талон № от срок гарантии месяцев.
        </td>
    </tr>
    <br><br><br>
    <tr>
        <td colspan="6" style="font-weight: bold;">Сведения о передаваемом Оборудовании :</td>
    </tr>
    <br><br>
    <tr style="font-weight: bold;text-align: center;">
        <td colspan="5" style="border: 1px solid #000;border-top: 3px solid #000;border-left: 3px solid #000;border-bottom: 2px solid #000;">Оборудование</td>
        <td style="border: 1px solid #000;border-top: 3px solid #000;border-bottom: 2px solid #000;">Серийный Номер</td>
    </tr>
    <tr>
        <td colspan="5" style="border: 1px solid #000;border-top: 1px solid #000;border-left: 3px solid #000;border-bottom: 0px solid #000;"><?= $model->equipment ?></td>
        <td style="border: 1px solid #000;border-top: 1px solid #000;border-bottom: 0px solid #000;"><?= $model->serial_id ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border-top: 3px solid #000;"></td>
    </tr>
    <br>
    <tr style="font-weight: bold;text-align: center;">
        <td colspan="6" style="border: 1px solid #000;border-top: 3px solid #000;border-left: 3px solid #000;border-bottom: 2px solid #000;">Заявляемая неисправность:</td>
    </tr>
    <tr>
        <td colspan="6" style="border: 1px solid #000;border-top: 1px solid #000;border-left: 3px solid #000;border-bottom: 0px solid #000;"><?= $model->problem ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border-top: 3px solid #000;"></td>
    </tr>
    <br>
    <tr style="font-weight: bold;text-align: center;">
        <td colspan="6" style="border: 1px solid #000;border-top: 3px solid #000;border-left: 3px solid #000;border-bottom: 2px solid #000;">Сопутствующие комплектующие:</td>
    </tr>
    <tr>
        <td colspan="6" style="border: 1px solid #000;border-top: 1px solid #000;border-left: 3px solid #000;border-bottom: 0px solid #000;"><?= $model->facilities ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border-top: 3px solid #000;"></td>
    </tr>
    <tr height="15px;">
        <td colspan="6"></td>
    </tr>
    <br>
    <tr>
        <td colspan="6" style="font-weight: bold;text-align: center;">1.	Права и обязанности сторон.</td>
    </tr>
    <br><br>
    <tr>
        <td></td>
        <td colspan="6" style="font-weight: bold;">Сервисный центр обязан:</td>
    </tr>
    <tr>
        <td colspan="6">1.1.1.	Провести диагностику Оборудования в срок не более 7 календарных дней, с даты поступления Оборудования в СЦ.</td>
    </tr>
    <tr>
        <td colspan="6">1.1.2.	Провести сервисные работы(ремонт) в срок не более 20 (двадцати) календарных дней, с даты получения комплектующих изделий. Датой начала сервисных работ является следующий день после дня завершения диагностики. Срок проведения сервисных работ может быть продлен по соглашению сторон путем подписания дополнительного соглашения.
            </td>
    </tr>
    <tr>
        <td colspan="6">1.1.3.	Устранить за свой счет неисправности, которые могут возникнуть в течение 10-ти дней после проведения сервисных работ, в случае если это явилось результатом низкого качества проведенных Сервисным центром  работ, при условии соблюдения Клиентом СЦ правил эксплуатации.
        </td>
    </tr>
    <br><br>
    <tr>
        <td></td>
        <td colspan="6" style="font-weight: bold;">Сервисный центр имеет право:</td>
    </tr>
    <tr>
        <td colspan="6">2.1.1.	В случае нарушения Клиентом СЦ условий предоставления гарантии на ремонт, установленных  Сервисным центром (наличие окисления, следов несанкционированного ремонта) выявленных в период повторной диагностики вправе отказать  в  повторном  ремонте (устранении неисправности) за счет Сервисного центра
            </td>
    </tr>
    <tr>
        <td colspan="6">2.2.2.	Принять в собственность Оборудование Клиента СЦ, в случае если Оборудование Клиентом СЦ не истребуется по истечении срока свыше 2 месяцев с момента уведомления о завершении ремонта, диагностики или отказа Сервисным центром от такового. Сервисный центр надлежаще обязан уведомить о переходе права на ремонтируемое изделие и использовать его по своему усмотрению (утилизировать, использовать на запчасти, продать и т. д.).
        </td>
    </tr>
    <br><br>
    <tr>
        <td></td>
        <td colspan="6" style="font-weight: bold;">Порядок оплаты:</td>
    </tr>
    <tr>
        <td colspan="6">3.1.1	Оплата работ, выполняемых Сервисным центром согласно условий настоящего договора производится Клиентом СЦ безналичным или наличным  расчетом в течении текущего банковского дня с даты выставления счета на ремонт .
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="6" style="font-weight: bold;">Ответственность сторон:</td>
    </tr>
    <tr>
        <td colspan="6">4.1.1	Меры ответственности сторон применяются в соответствии с нормами гражданского законодательства, действующего на территории РК.</td>
    </tr>
    <tr>
        <td colspan="6">4.1.2	В случае нарушения Клиентом СЦ условий эксплуатации оборудования, том числе при выявлении в процессе диагностики наличия следов коррозии, попадания влаги, механических повреждений и т.д., Сервисный центр ответственности не несет, </td>
    </tr>
    <tr>
        <td colspan="6">4.1.3.	Клиент СЦ признает компетентность сервисных инженеров Сервисного центра, полностью доверяет действиям сотрудников Сервисного центра и дает согласие на проведение диагностики без своего участия.</td>
    </tr>
    <br><br>
    <tr>
        <td></td>
        <td colspan="6" style="font-weight: bold;">Порядок разрешения споров:</td>
    </tr>
    <tr>
        <td colspan="6">5.1.1	Споры и разногласия, которые могут возникнуть при исполнении настоящего договора, будут по возможности разрешаться путем переговоров между сторонами.
        </td>
    </tr>
    <br><br>
    <tr>
        <td></td>
        <td colspan="6" style="font-weight: bold;">Заключительные положения:</td>
    </tr>
    <tr>
        <td colspan="6">6.1.1	Настоящий договор регулирует порядок взаимодействия между его сторонами. Все спорные вопросы стороны решают путем переговоров.</td>
    </tr>
    <tr>
        <td colspan="6">6.1.2.	Настоящий договор составлен в двух экземплярах по одному для каждой из сторон.</td>
    </tr>
    <br><br><br><br>
    <tr>
        <td colspan="6" style="font-weight: bold;">ТОО "Статус Караганда",Республика Казахстан, г. Караганда, пр. Н.Абдирова, дом № 47/1, к.63, тел.: 8-705-910-22-00</td>
    </tr>
    <tr>
        <td colspan="6" style="font-weight: bold;">Выполнил : <?=$model->username?>______________________________________</td>
    </tr>

</table>