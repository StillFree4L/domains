<?
include("include/bd.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<meta name="description" content="Education website">
<meta name="keywords" content="education, learning, teaching">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<? include("include/menu.php");  ?> 
               
<?
if (isset ($_REQUEST['students']))
{

echo "Факультет - <select name=fak>";
$result = mysql_query ("select * from fak");
$myrow = mysql_fetch_array($result);
do
{
  echo "<option value=$myrow[id]>$myrow[fak]</option>";
}
while ($myrow = mysql_fetch_array($result));


echo "</select><br>Группа - <select></select>";

$result2 = mysql_query ("select * from users_info");
$myrow2 = mysql_fetch_array($result2);
do
{
  echo "<p>$myrow2[last_name]  $myrow2[first_name]  $myrow2[middle_name] </p>";
}
while ($myrow2 = mysql_fetch_array($result2));







}





?>

<?
if (isset ($_REQUEST['vtk']))
{?>
<table width=100% border=0>
<tr><td align=right>Ф.4.04-14-1</td></tr>
<tr><td align=center>Министерство образования и науки Республики Казахстан <br> ЦЕНТРАЛЬНО-КАЗАХСТАНСКАЯ АКАДЕМИЯ</td></tr>
<tr><td>&nbsp:</td></tr> 
<tr><td align=center>РУБЕЖНО-РЕЙТИНГОВАЯ ВЕДОМОСТЬ № 543 <br> 2017 - 2018 уч. год</td></tr>
</table> 
<br>
<table>
<tr><td>Факультет: Юридический факультет</td><td>Специальность: Юриспруденция</td><td>Форма обучения:Заочная</td></tr>
<tr><td>Группа: ЮВ-17-11 (р)</td><td>курс:  1</td><td>семестр:  2</td></tr> 
<tr><td>Дисциплина:  Административное право РК</td><td>Количество кредитов: 3</td><td>Ф.И.О. тьютора: Карпекин А. В.</td></tr> 
<tr><td>Дата: 20.03.2018</td><td></td><td></td></tr>
</table>


<table border=1 >
<tr><td>№</td>	<td>Ф.И.О обучающегося</td>		<td>Текущий контроль</td><td>Рубежный контроль</td><td>Рейтинг допуск</td><td>РД, с учетом аппеляции</td></tr>
<tr><td>1</td>	<td>АбаковаГульсараАзатбековна</td>	<td>76</td><td>80</td><td>78</td><td></td></tr>
<tr><td>2</td>	<td>Абенов Абай Джагпарович</td>	<td>72</td><td>72</td><td>72</td><td></td></tr>
<tr><td>3</td>	<td>АбзалиловРишатФаритович</td>	<td>72</td><td>80</td><td>76</td><td></td></tr>	
<tr><td>4</td>	<td>АбильдинаАйгеримЕсеновна</td>	<td>88</td><td>88</td><td>88</td><td></td></tr>
<tr><td>5</td>	<td>Бает БерікболатСерікұлы</td>	<td>72</td><td>76</td><td>74</td><td></td></tr>
<tr><td>6</td>	<td>БайболовЕркебуланСагиндыкович</td>	<td>88</td><td>84</td><td>86</td><td></td></tr>
<tr><td>7</td>	<td>Байгелдинова Динара Кайратовна</td>	<td>80</td><td>80</td><td>80</td><td></td></tr>
<tr><td>8</td>	<td>Баймурзина Динара Канатовна</td>	<td>84</td><td>84</td><td>84</td><td></td></tr>
<tr><td>9</td>	<td>Балтабаева ӘселБақытбекқызы</td>	<td>72</td><td>72</td><td>72</td><td></td></tr>
<tr><td>10</td>	<td>Дулатов Дархан Канатович</td>	<td>72</td><td>76</td><td>74</td><td></td></tr>
<tr><td>11</td>	<td>Дюсембаева Елена Амангалиевна</td>	<td>80</td><td>76</td><td>78</td><td></td></tr>
<tr><td>12</td>	<td>ЕльшинаМаншукБериковна</td>		<td>72</td><td>72</td><td>72</td><td></td></tr>
<tr><td>13</td>	<td>ЕсенамановСаматСулейменович</td>	<td>72</td><td>76</td><td>74</td><td></td></tr>
<tr><td>14</td>	<td>ЕсимоваАдинаАйдарқызы</td>		<td>64</td><td>68</td><td>66</td><td></td></tr>
<tr><td>15</td>	<td>КалиеваАйжанАбаевна</td>		<td>72</td><td>72</td><td>72</td><td></td></tr>
<tr><td>16</td>	<td>КарашулаковСайранЕрикович	</td>	<td>84</td><td>76</td><td>80</td><td></td></tr>
<tr><td>17</td>	<td>Курмышев Дархан Джамбулович</td>	<td>68</td><td>80</td><td>74</td><td></td></tr>
<tr><td>18</td>	<td>КусаиновАрманБайгабылович</td>	<td>72</td><td>76</td><td>74</td><td></td></tr>
<tr><td>19</td>	<td>КызыловаЖанеркеБолатбековна</td>	<td>72</td><td>72</td><td>72</td><td></td></tr>
<tr><td>20</td>	<td>МадинАрманМайкенович</td>		<td>88</td><td>84</td><td>86</td><td></td></tr>
<tr><td>21</td>	<td>Малик Динара Максимжанқызы</td>	<td>72</td><td>72</td><td>72</td><td></td></tr>
<tr><td>22</td>	<td>Миронова Светлана Алексеевна</td>	<td>72</td><td>76</td><td>74</td><td></td></tr>
<tr><td>23</td>	<td>МухамедиярұлыБағдат</td>		<td>72</td><td>72</td><td>72</td><td></td></tr>
<tr><td>24</td>	<td>СандыкбаевЕрболатТолегенович</td>	<td>80</td><td>76</td><td>78</td><td></td></tr>
<tr><td>25</td>	<td>СатымбаевЕркебуланАманкелдыулы</td>	<td>0</td><td>0</td><td>0</td><td></td></tr>
<tr><td>26</td>	<td>СейтбекҰлпанМаратқызы</td>		<td>84</td><td>84</td><td>84</td><td></td></tr>
<tr><td>27</td>	<td>СейтказиновМедетТолеубекович</td>	<td>87</td><td>93</td><td>90</td><td></td></tr>
<tr><td>28</td>	<td>Сетаева Жанна Жанбырбаевна</td>	<td>72</td><td>76</td><td>74</td><td></td></tr>
<tr><td>29</td>	<td>Сиденков Геннадий Сергеевич</td>	<td>76</td><td>76</td><td>76</td><td></td></tr>
<tr><td>30</td>	<td>ТустикбаеваАйгулСакбаевна</td>	<td>76</td><td>80</td><td>78</td><td></td></tr>
<tr><td>31</td>	<td>ТөленЕрмұханСағынтайұлы</td>	<td>72</td><td>72</td><td>72</td><td></td></tr>
<tr><td>32</td>	<td>ТөлеубайБатырханСерікбайұлы</td>	<td>84</td><td>80</td><td>82</td><td></td></tr>
<tr><td>33</td>	<td>ХасеновЕрланЕрмекович</td>		<td>72</td><td>76</td><td>74</td><td></td></tr>
<tr><td>34</td>	<td>ҚалиасқарСұлтанҚуатұлы</td>		<td>88</td><td>76</td><td>82</td><td></td></tr>
</table>
<br>
<table>
<tr><td>Тьютор: _________ </td><td>Декан: _________</td><td>Офис регистратор: _________

<tr><td>Итого:</td><td>отлично  1</td><td>хорошо  15</td><td>удовлетворительно  17</td><td>неудовлетворительно  0 не явка 1
</table>
<br>
<b>Примечание:</b><br>
1) Преподаватель ответственен за подсчет итоговой оценки
<table border=1>
<tr><td>Рейтинг</td><td>0-49</td><td>50-54</td><td>55-59</td><td>60-64</td><td>65-69</td><td>70-74</td><td>75-79</td><td>80-84</td><td>85-89</td><td>90-94</td><td>95-100</td></tr>
<tr><td>Балл</td><td>0</td><td>1</td><td>1.33</td><td>1.67</td><td>2</td><td>2.33</td><td>2.67</td><td>3</td><td>3.33</td><td>3.67</td><td>4</td></tr>
<tr><td>Буквенный эквивалент</td><td>F</td><td>D</td><td>D+</td><td>C-</td><td>C</td><td>C+</td><td>B-</td><td>B</td><td>B+</td><td>A-</td><td>A</td></tr>
<tr><td>Оценка</td><td>Неуд.</td><td colspan=5>Удовлетворительно</td><td colspan=3>Хорошо</td><td colspan=2>Отлично</td></tr>
</table>
2) Внесение изменений и корректив в рейтинговую ведомость не допускается<br>
3) Члены апеляционной комиссии подписывают ведомость в случае проведения апелляции

<?
}
?>



<?
if (isset ($_REQUEST['vrk1']))
{?>
<table width=100% border=0>
<tr><td align=right>Ф.4.04-15</td></tr>
<tr><td align=center>Министерство образования и науки Республики Казахстан <br> ЦЕНТРАЛЬНО-КАЗАХСТАНСКАЯ АКАДЕМИЯ</td></tr>
<tr><td>&nbsp:</td></tr> 
<tr><td align=center>ЭКЗАМЕНАЦИОННАЯ ВЕДОМОСТЬ№ 596 <br> 2017 - 2018 уч. год</td></tr>
</table> 
<br>
<table>
<tr><td>Факультет: Юридический факультет</td><td>Специальность: Юриспруденция</td><td>Форма обучения:Заочная</td></tr>
<tr><td>Группа: ЮВ-17-11 (р)</td><td>курс:  1</td><td>семестр:  2</td></tr> 
<tr><td>Дисциплина:  Административное право РК</td><td>Количество кредитов: 3</td><td>Ф.И.О. тьютора: Карпекин А. В.</td></tr> 
<tr><td>Дата: 06.04.2018</td><td></td><td></td></tr>
</table>


<table border=1 >

<tr><td rowspan=2>№</td><td rowspan=2>Ф.И.О обучающегося</td><td rowspan=2>РД</td><td rowspan=2>Экзамена ционная оценка (в %)</td><td colspan=4>	Итоговая оценка И = РД*0.6 + Э*0.4</td></tr>
												<tr><td>В процентах</td><td>Буквенная</td><td>В баллах</td><td>Традиционная</td></tr>
<tr><td>	1	</td><td>	АбаковаГульсараАзатбековна	</td><td>	78	</td><td>	76	</td><td>	77	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	2	</td><td>	Абенов Абай Джагпарович	</td><td>	72	</td><td>	80	</td><td>	75	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	3	</td><td>	АбзалиловРишатФаритович	</td><td>	76	</td><td>	76	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	4	</td><td>	АбильдинаАйгеримЕсеновна	</td><td>	88	</td><td>	88	</td><td>	88	</td><td>	B+	</td><td>	3.33	</td><td>	Хор.	</td></tr>
<tr><td>	5	</td><td>	Бает БерікболатСерікұлы	</td><td>	74	</td><td>	80	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	6	</td><td>	БайболовЕркебуланСагиндыкович	</td><td>	86	</td><td>	88	</td><td>	86	</td><td>	B+	</td><td>	3.33	</td><td>	Хор.	</td></tr>
<tr><td>	7	</td><td>	Байгелдинова Динара Кайратовна	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	B	</td><td>	3	</td><td>	Хор.	</td></tr>
<tr><td>	8	</td><td>	Баймурзина Динара Канатовна	</td><td>	84	</td><td>	84	</td><td>	84	</td><td>	B	</td><td>	3	</td><td>	Хор.	</td></tr>
<tr><td>	9	</td><td>	Балтабаева ӘселБақытбекқызы	</td><td>	72	</td><td>	80	</td><td>	75	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	10	</td><td>	Дулатов Дархан Канатович	</td><td>	74	</td><td>	80	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	11	</td><td>	Дюсембаева Елена Амангалиевна	</td><td>	78	</td><td>	76	</td><td>	77	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	12	</td><td>	ЕльшинаМаншукБериковна	</td><td>	72	</td><td>	80	</td><td>	75	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	13	</td><td>	ЕсенамановСаматСулейменович	</td><td>	74	</td><td>	80	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	14	</td><td>	ЕсимоваАдинаАйдарқызы	</td><td>	66	</td><td>	76	</td><td>	70	</td><td>	C+	</td><td>	2.33	</td><td>	Удов.	</td></tr>
<tr><td>	15	</td><td>	КалиеваАйжанАбаевна	</td><td>	72	</td><td>	80	</td><td>	75	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	16	</td><td>	КарашулаковСайранЕрикович	</td><td>	80	</td><td>	72	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	17	</td><td>	Курмышев Дархан Джамбулович	</td><td>	74	</td><td>	80	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	18	</td><td>	КусаиновАрманБайгабылович	</td><td>	74	</td><td>	80	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	19	</td><td>	КызыловаЖанеркеБолатбековна	</td><td>	72	</td><td>	80	</td><td>	75	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	20	</td><td>	МадинАрманМайкенович	</td><td>	86	</td><td>	80	</td><td>	83	</td><td>	B	</td><td>	3	</td><td>	Хор.	</td></tr>
<tr><td>	21	</td><td>	Малик Динара Максимжанқызы	</td><td>	72	</td><td>	80	</td><td>	75	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	22	</td><td>	Миронова Светлана Алексеевна	</td><td>	74	</td><td>	88	</td><td>	79	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	23	</td><td>	МухамедиярұлыБағдат	</td><td>	72	</td><td>	80	</td><td>	75	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	24	</td><td>	СандыкбаевЕрболатТолегенович	</td><td>	78	</td><td>	80	</td><td>	78	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	25	</td><td>	СатымбаевЕркебуланАманкелдыулы	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>		</td><td>		</td><td>		</td></tr>
<tr><td>	26	</td><td>	СейтбекҰлпанМаратқызы	</td><td>	84	</td><td>	88	</td><td>	85	</td><td>	B+	</td><td>	3.33	</td><td>	Хор.	</td></tr>
<tr><td>	27	</td><td>	СейтказиновМедетТолеубекович	</td><td>	90	</td><td>	88	</td><td>	89	</td><td>	B+	</td><td>	3.33	</td><td>	Хор.	</td></tr>
<tr><td>	28	</td><td>	Сетаева Жанна Жанбырбаевна	</td><td>	74	</td><td>	80	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	29	</td><td>	Сиденков Геннадий Сергеевич	</td><td>	76	</td><td>	84	</td><td>	79	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	30	</td><td>	ТустикбаеваАйгулСакбаевна	</td><td>	78	</td><td>	80	</td><td>	78	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	31	</td><td>	ТөленЕрмұханСағынтайұлы	</td><td>	72	</td><td>	80	</td><td>	75	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	32	</td><td>	ТөлеубайБатырханСерікбайұлы	</td><td>	82	</td><td>	84	</td><td>	82	</td><td>	B	</td><td>	3	</td><td>	Хор.	</td></tr>
<tr><td>	33	</td><td>	ХасеновЕрланЕрмекович	</td><td>	74	</td><td>	80	</td><td>	76	</td><td>	B-	</td><td>	2.67	</td><td>	Хор.	</td></tr>
<tr><td>	34	</td><td>	ҚалиасқарСұлтанҚуатұлы	</td><td>	82	</td><td>	84	</td><td>	82	</td><td>	B	</td><td>	3	</td><td>	Хор.	</td></tr>




</table>
<br>
<table>
<tr><td>Тьютор: _________ </td><td>Декан: _________</td><td>Офис регистратор: _________

<tr><td>Итого:</td><td>отлично  1</td><td>хорошо  15</td><td>удовлетворительно  17</td><td>неудовлетворительно  0 не явка 1
</table>
<br>
<b>Примечание:</b><br>
1) Преподаватель ответственен за подсчет итоговой оценки
<table border=1>
<tr><td>Рейтинг</td><td>0-49</td><td>50-54</td><td>55-59</td><td>60-64</td><td>65-69</td><td>70-74</td><td>75-79</td><td>80-84</td><td>85-89</td><td>90-94</td><td>95-100</td></tr>
<tr><td>Балл</td><td>0</td><td>1</td><td>1.33</td><td>1.67</td><td>2</td><td>2.33</td><td>2.67</td><td>3</td><td>3.33</td><td>3.67</td><td>4</td></tr>
<tr><td>Буквенный эквивалент</td><td>F</td><td>D</td><td>D+</td><td>C-</td><td>C</td><td>C+</td><td>B-</td><td>B</td><td>B+</td><td>A-</td><td>A</td></tr>
<tr><td>Оценка</td><td>Неуд.</td><td colspan=5>Удовлетворительно</td><td colspan=3>Хорошо</td><td colspan=2>Отлично</td></tr>
</table>
2) Внесение изменений и корректив в рейтинговую ведомость не допускается<br>
3) Члены апеляционной комиссии подписывают ведомость в случае проведения апелляции

<?
}
?>





<?
if (isset ($_REQUEST['vrk2']))
{?>
<table width=100% border=0>
<tr><td align=right>Ф.4.04-16</td></tr>


<tr><td align=center>ЦЕНТРАЛЬНО-КАЗАХСТАНСКАЯ АКАДЕМИЯ<br>
____________________________________________________<br>
<br>
СВОДНАЯ ВЕДОМОСТЬ УСПЕВАЕМОСТИ<br>
Учебный год:  2017 - 2018          Семестр:  2 <br>
Специальность:  Юриспруденция          Язык обучения: Русский<br>
Форма обучения:  Заочная          Курс:  1           Группа:  ЮВ-17-11 (р) <br></td></tr>
</table> 
<br>


<table border=1 >

<tr><td rowspan=2>Ф.И.О. студента</td><td colspan=4>GPA</td><td colspan=24>Дисциплина</td></tr>
<tr><td>GPA итог (в %)</td><td>GPA Итог (Буквенная)</td><td>GPA Итог (Балл)</td><td>GPA Итог (Традиционная)</td>
<td>Административное право РК</td><td>История государства и права зарубежных стран</td><td>История государства и права Республики Казахстан</td><td>История правовых и политических учений</td><td>Конституционное право зарубежных стран</td><td>Логика</td><td>Общая и юридическая психология</td><td>Правоохранительные органы РК</td><td>Профессиональная этика юриста </td><td>Профессионально-ориентированный иностранный язык	</td><td>Профессиональный казахский язык 	</td><td>Римское право 	</td><td>Судебная риторика 	</td><td>Трудовое право РК 	</td><td>Уголовное право РК (общая часть) 	</td><td>Финансовое право РК 	</td><td>Экологическое право РК 	</td><td>Международное публичное право 	</td><td>Гражданское право РК (общая часть) (курс.)	</td><td>Гражданское право РК (общая часть)	</td><td>Теория государства и права (курс.)	</td><td>Теория государства и права 	</td><td>Конституционное право РК (курс.)	</td><td>Конституционное право РК </td></tr>
<tr><td>Количество кредитов: 48	</td></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>3</td><td>2</td><td>2</td><td>2</td><td>2</td><td>2</td><td>2</td><td>2</td><td>2</td><td>2</td><td>2</td><td>3</td><td>2</td><td>3</td><td>3</td><td>2</td><td>2</td><td>3</td><td>3</td><td>2</td><td>2</td></tr>

<tr><td>	1	</td><td>	АбаковаГульсараАзатбековна	</td><td>	79	</td><td>	B-	</td><td>	2.84	</td><td>	Хор.	</td><td>	77	</td><td>	76	</td><td>	78	</td><td>	81	</td><td>	86	</td><td>	75	</td><td>	82	</td><td>	80	</td><td>	80	</td><td>	79	</td><td>	76	</td><td>	80	</td><td>	86	</td><td>	78	</td><td>	78	</td><td>	81	</td><td>	78	</td><td>	80	</td><td>	85	</td><td>	78	</td><td>	75	</td><td>	79	</td><td>	80	</td><td>	79	</td></tr>
<tr><td>	2	</td><td>	Абенов Абай Джагпарович	</td><td>	82	</td><td>	B	</td><td>	3.02	</td><td>	Хор.	</td><td>	75	</td><td>	76	</td><td>	77	</td><td>	84	</td><td>	80	</td><td>	88	</td><td>	78	</td><td>	84	</td><td>	76	</td><td>	81	</td><td>	85	</td><td>	89	</td><td>	90	</td><td>	90	</td><td>	87	</td><td>	78	</td><td>	88	</td><td>	78	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	81	</td><td>	80	</td><td>	84	</td></tr>
<tr><td>	3	</td><td>	АбзалиловРишатФаритович	</td><td>	79	</td><td>	B-	</td><td>	2.8	</td><td>	Хор.	</td><td>	76	</td><td>	78	</td><td>	77	</td><td>	77	</td><td>	76	</td><td>	75	</td><td>	80	</td><td>	79	</td><td>	84	</td><td>	75	</td><td>	81	</td><td>	81	</td><td>	78	</td><td>	75	</td><td>	77	</td><td>	78	</td><td>	84	</td><td>	80	</td><td>	85	</td><td>	82	</td><td>	76	</td><td>	78	</td><td>	76	</td><td>	84	</td></tr>
<tr><td>	4	</td><td>	АбильдинаАйгеримЕсеновна	</td><td>	87	</td><td>	B+	</td><td>	3.34	</td><td>	Хор.	</td><td>	88	</td><td>	88	</td><td>	85	</td><td>	88	</td><td>	88	</td><td>	83	</td><td>	90	</td><td>	84	</td><td>	85	</td><td>	88	</td><td>	88	</td><td>	86	</td><td>	89	</td><td>	90	</td><td>	88	</td><td>	86	</td><td>	88	</td><td>	88	</td><td>	85	</td><td>	86	</td><td>	75	</td><td>	86	</td><td>	85	</td><td>	87	</td></tr>
<tr><td>	5	</td><td>	Бает БерікболатСерікұлы	</td><td>	79	</td><td>	B-	</td><td>	2.84	</td><td>	Хор.	</td><td>	76	</td><td>	76	</td><td>	78	</td><td>	80	</td><td>	77	</td><td>	77	</td><td>	82	</td><td>	83	</td><td>	78	</td><td>	77	</td><td>	76	</td><td>	80	</td><td>	86	</td><td>	79	</td><td>	78	</td><td>	82	</td><td>	77	</td><td>	80	</td><td>	85	</td><td>	78	</td><td>	75	</td><td>	80	</td><td>	80	</td><td>	86	</td></tr>
<tr><td>	6	</td><td>	БайболовЕркебуланСагиндыкович	</td><td>	86	</td><td>	B+	</td><td>	3.27	</td><td>	Хор.	</td><td>	86	</td><td>	86	</td><td>	86	</td><td>	86	</td><td>	83	</td><td>	86	</td><td>	88	</td><td>	83	</td><td>	82	</td><td>	86	</td><td>	86	</td><td>	86	</td><td>	85	</td><td>	92	</td><td>	88	</td><td>	92	</td><td>	70	</td><td>	86	</td><td>	80	</td><td>	86	</td><td>	75	</td><td>	83	</td><td>	76	</td><td>	88	</td></tr>
<tr><td>	7	</td><td>	Байгелдинова Динара Кайратовна	</td><td>	82	</td><td>	B	</td><td>	2.98	</td><td>	Хор.	</td><td>	80	</td><td>	88	</td><td>	81	</td><td>	82	</td><td>	85	</td><td>	80	</td><td>	78	</td><td>	81	</td><td>	79	</td><td>	78	</td><td>	88	</td><td>	83	</td><td>	86	</td><td>	78	</td><td>	81	</td><td>	78	</td><td>	82	</td><td>	82	</td><td>	80	</td><td>	82	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	85	</td></tr>
<tr><td>	8	</td><td>	Баймурзина Динара Канатовна	</td><td>	81	</td><td>	B	</td><td>	2.99	</td><td>	Хор.	</td><td>	84	</td><td>	80	</td><td>	78	</td><td>	82	</td><td>	85	</td><td>	76	</td><td>	81	</td><td>	78	</td><td>	81	</td><td>	80	</td><td>	85	</td><td>	80	</td><td>	82	</td><td>	82	</td><td>	82	</td><td>	83	</td><td>	81	</td><td>	82	</td><td>	80	</td><td>	84	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	81	</td></tr>
<tr><td>	9	</td><td>	Балтабаева ӘселБақытбекқызы	</td><td>	80	</td><td>	B	</td><td>	2.88	</td><td>	Хор.	</td><td>	75	</td><td>	76	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	84	</td><td>	76	</td><td>	84	</td><td>	84	</td><td>	77	</td><td>	78	</td><td>	75	</td><td>	85	</td><td>	78	</td><td>	83	</td><td>	80	</td><td>	86	</td><td>	86	</td><td>	80	</td><td>	76	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	80	</td></tr>
<tr><td>	10	</td><td>	Дулатов Дархан Канатович	</td><td>	80	</td><td>	B	</td><td>	2.87	</td><td>	Хор.	</td><td>	76	</td><td>	78	</td><td>	78	</td><td>	84	</td><td>	77	</td><td>	77	</td><td>	84	</td><td>	80	</td><td>	86	</td><td>	77	</td><td>	76	</td><td>	80	</td><td>	86	</td><td>	76	</td><td>	80	</td><td>	83	</td><td>	78	</td><td>	84	</td><td>	80	</td><td>	78	</td><td>	70	</td><td>	80	</td><td>	60	</td><td>	82	</td></tr>
<tr><td>	11	</td><td>	Дюсембаева Елена Амангалиевна	</td><td>	79	</td><td>	B-	</td><td>	2.79	</td><td>	Хор.	</td><td>	77	</td><td>	79	</td><td>	78	</td><td>	77	</td><td>	78	</td><td>	76	</td><td>	79	</td><td>	80	</td><td>	78	</td><td>	78	</td><td>	86	</td><td>	80	</td><td>	82	</td><td>	80	</td><td>	78	</td><td>	82	</td><td>	79	</td><td>	78	</td><td>	80	</td><td>	76	</td><td>	75	</td><td>	79	</td><td>	80	</td><td>	80	</td></tr>
<tr><td>	12	</td><td>	ЕльшинаМаншукБериковна	</td><td>	80	</td><td>	B	</td><td>	2.9	</td><td>	Хор.	</td><td>	75	</td><td>	76	</td><td>	77	</td><td>	80	</td><td>	84	</td><td>	79	</td><td>	81	</td><td>	84	</td><td>	83	</td><td>	77	</td><td>	86	</td><td>	85	</td><td>	79	</td><td>	78	</td><td>	84	</td><td>	78	</td><td>	86	</td><td>	76	</td><td>	80	</td><td>	77	</td><td>	70	</td><td>	83	</td><td>	60	</td><td>	86	</td></tr>
<tr><td>	13	</td><td>	ЕсенамановСаматСулейменович	</td><td>	81	</td><td>	B	</td><td>	2.94	</td><td>	Хор.	</td><td>	76	</td><td>	80	</td><td>	84	</td><td>	86	</td><td>	77	</td><td>	75	</td><td>	80	</td><td>	81	</td><td>	86	</td><td>	83	</td><td>	76	</td><td>	80	</td><td>	77	</td><td>	80	</td><td>	83	</td><td>	78	</td><td>	77	</td><td>	86	</td><td>	85	</td><td>	78	</td><td>	80	</td><td>	86	</td><td>	80	</td><td>	83	</td></tr>
<tr><td>	14	</td><td>	ЕсимоваАдинаАйдарқызы	</td><td>	74	</td><td>	C+	</td><td>	2.47	</td><td>	Удов.	</td><td>	70	</td><td>	81	</td><td>	75	</td><td>	74	</td><td>	84	</td><td>	68	</td><td>	77	</td><td>	68	</td><td>	68	</td><td>	73	</td><td>	90	</td><td>	72	</td><td>	80	</td><td>	82	</td><td>	66	</td><td>	71	</td><td>	78	</td><td>	72	</td><td>	85	</td><td>	67	</td><td>	85	</td><td>	74	</td><td>	88	</td><td>	71	</td></tr>
<tr><td>	15	</td><td>	КалиеваАйжанАбаевна	</td><td>	80	</td><td>	B	</td><td>	2.9	</td><td>	Хор.	</td><td>	75	</td><td>	76	</td><td>	77	</td><td>	81	</td><td>	77	</td><td>	80	</td><td>	75	</td><td>	84	</td><td>	81	</td><td>	79	</td><td>	80	</td><td>	80	</td><td>	77	</td><td>	82	</td><td>	80	</td><td>	78	</td><td>	81	</td><td>	80	</td><td>	85	</td><td>	85	</td><td>	75	</td><td>	80	</td><td>	86	</td><td>	84	</td></tr>
<tr><td>	16	</td><td>	КарашулаковСайранЕрикович	</td><td>	74	</td><td>	C+	</td><td>	2.47	</td><td>	Удов.	</td><td>	76	</td><td>	68	</td><td>	63	</td><td>	72	</td><td>	81	</td><td>	63	</td><td>	82	</td><td>	70	</td><td>	84	</td><td>	63	</td><td>	86	</td><td>	76	</td><td>	64	</td><td>	83	</td><td>	65	</td><td>	82	</td><td>	84	</td><td>	62	</td><td>	80	</td><td>	71	</td><td>	75	</td><td>	85	</td><td>	80	</td><td>	77	</td></tr>
<tr><td>	17	</td><td>	Курмышев Дархан Джамбулович	</td><td>	84	</td><td>	B	</td><td>	3.15	</td><td>	Хор.	</td><td>	76	</td><td>	86	</td><td>	90	</td><td>	89	</td><td>	62	</td><td>	85	</td><td>	80	</td><td>	82	</td><td>	84	</td><td>	91	</td><td>	87	</td><td>	93	</td><td>	85	</td><td>	92	</td><td>	78	</td><td>	86	</td><td>	86	</td><td>	82	</td><td>	80	</td><td>	85	</td><td>	70	</td><td>	85	</td><td>	60	</td><td>	71	</td></tr>
<tr><td>	18	</td><td>	КусаиновАрманБайгабылович	</td><td>	80	</td><td>	B	</td><td>	2.92	</td><td>	Хор.	</td><td>	76	</td><td>	80	</td><td>	77	</td><td>	81	</td><td>	81	</td><td>	81	</td><td>	88	</td><td>	84	</td><td>	78	</td><td>	85	</td><td>	80	</td><td>	86	</td><td>	76	</td><td>	77	</td><td>	83	</td><td>	78	</td><td>	77	</td><td>	76	</td><td>	80	</td><td>	86	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	79	</td></tr>
<tr><td>	19	</td><td>	КызыловаЖанеркеБолатбековна	</td><td>	83	</td><td>	B	</td><td>	3.05	</td><td>	Хор.	</td><td>	75	</td><td>	76	</td><td>	80	</td><td>	84	</td><td>	86	</td><td>	77	</td><td>	86	</td><td>	84	</td><td>	88	</td><td>	81	</td><td>	90	</td><td>	84	</td><td>	80	</td><td>	86	</td><td>	82	</td><td>	88	</td><td>	80	</td><td>	84	</td><td>	80	</td><td>	77	</td><td>	80	</td><td>	83	</td><td>	80	</td><td>	87	</td></tr>
<tr><td>	20	</td><td>	МадинАрманМайкенович	</td><td>	85	</td><td>	B+	</td><td>	3.19	</td><td>	Хор.	</td><td>	83	</td><td>	84	</td><td>	84	</td><td>	86	</td><td>	81	</td><td>	77	</td><td>	90	</td><td>	90	</td><td>	90	</td><td>	83	</td><td>	86	</td><td>	87	</td><td>	82	</td><td>	89	</td><td>	82	</td><td>	82	</td><td>	90	</td><td>	87	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	81	</td><td>	80	</td><td>	83	</td></tr>
<tr><td>	21	</td><td>	Малик Динара Максимжанқызы	</td><td>	80	</td><td>	B	</td><td>	2.88	</td><td>	Хор.	</td><td>	75	</td><td>	76	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	84	</td><td>	76	</td><td>	84	</td><td>	82	</td><td>	77	</td><td>	78	</td><td>	75	</td><td>	85	</td><td>	78	</td><td>	83	</td><td>	80	</td><td>	86	</td><td>	86	</td><td>	80	</td><td>	77	</td><td>	80	</td><td>	78	</td><td>	76	</td><td>	80	</td></tr>
<tr><td>		</td><td>		</td><td>		</td><td>		</td><td>	3	</td><td>	2	</td><td>	2	</td><td>	2	</td><td>	2	</td><td>	2	</td><td>	2	</td><td>	2	</td><td>	2	</td><td>	2	</td><td>	2	</td><td>	3	</td><td>	2	</td><td>	3	</td><td>	3	</td><td>	2	</td><td>	2	</td><td>	3	</td><td>		</td><td>	3	</td><td>		</td><td>	2	</td><td>		</td><td>	2	</td><td>		</td><td>		</td></tr>
<tr><td>	22	</td><td>	Миронова Светлана Алексеевна	</td><td>	74	</td><td>	C+	</td><td>	2.41	</td><td>	Удов.	</td><td>	79	</td><td>	74	</td><td>	73	</td><td>	74	</td><td>	72	</td><td>	73	</td><td>	74	</td><td>	74	</td><td>	74	</td><td>	74	</td><td>	72	</td><td>	80	</td><td>	73	</td><td>	75	</td><td>	73	</td><td>	74	</td><td>	72	</td><td>	74	</td><td>	80	</td><td>	74	</td><td>	70	</td><td>	73	</td><td>	60	</td><td>	74	</td></tr>
<tr><td>	23	</td><td>	МухамедиярұлыБағдат	</td><td>	79	</td><td>	B-	</td><td>	2.88	</td><td>	Хор.	</td><td>	75	</td><td>	80	</td><td>	81	</td><td>	80	</td><td>	80	</td><td>	81	</td><td>	80	</td><td>	84	</td><td>	80	</td><td>	80	</td><td>	83	</td><td>	78	</td><td>	79	</td><td>	76	</td><td>	76	</td><td>	83	</td><td>	84	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	76	</td><td>	80	</td><td>	77	</td></tr>
<tr><td>	24	</td><td>	СандыкбаевЕрболатТолегенович	</td><td>	81	</td><td>	B	</td><td>	2.92	</td><td>	Хор.	</td><td>	78	</td><td>	80	</td><td>	82	</td><td>	82	</td><td>	81	</td><td>	78	</td><td>	81	</td><td>	80	</td><td>	81	</td><td>	80	</td><td>	89	</td><td>	80	</td><td>	82	</td><td>	82	</td><td>	80	</td><td>	80	</td><td>	83	</td><td>	76	</td><td>	76	</td><td>	79	</td><td>	80	</td><td>	79	</td><td>	80	</td><td>	82	</td></tr>
<tr><td>	25	</td><td>	СатымбаевЕркебуланАманкелдыулы	</td><td>	0	</td><td>		</td><td>	0	</td><td>		</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td><td>	0	</td></tr>
<tr><td>	26	</td><td>	СейтбекҰлпанМаратқызы	</td><td>	85	</td><td>	B+	</td><td>	3.28	</td><td>	Хор.	</td><td>	85	</td><td>	85	</td><td>	89	</td><td>	82	</td><td>	87	</td><td>	77	</td><td>	87	</td><td>	85	</td><td>	86	</td><td>	77	</td><td>	89	</td><td>	86	</td><td>	88	</td><td>	90	</td><td>	85	</td><td>	80	</td><td>	82	</td><td>	90	</td><td>	80	</td><td>	86	</td><td>	80	</td><td>	86	</td><td>	86	</td><td>	88	</td></tr>
<tr><td>	27	</td><td>	СейтказиновМедетТолеубекович	</td><td>	87	</td><td>	B+	</td><td>	3.35	</td><td>	Хор.	</td><td>	89	</td><td>	89	</td><td>	89	</td><td>	80	</td><td>	90	</td><td>	86	</td><td>	90	</td><td>	85	</td><td>	88	</td><td>	88	</td><td>	88	</td><td>	86	</td><td>	80	</td><td>	88	</td><td>	88	</td><td>	88	</td><td>	88	</td><td>	86	</td><td>	80	</td><td>	88	</td><td>	75	</td><td>	86	</td><td>	85	</td><td>	90	</td></tr>
<tr><td>	28	</td><td>	Сетаева Жанна Жанбырбаевна	</td><td>	80	</td><td>	B	</td><td>	2.84	</td><td>	Хор.	</td><td>	76	</td><td>	77	</td><td>	77	</td><td>	83	</td><td>	78	</td><td>	84	</td><td>	76	</td><td>	84	</td><td>	85	</td><td>	78	</td><td>	83	</td><td>	77	</td><td>	84	</td><td>	80	</td><td>	84	</td><td>	80	</td><td>	79	</td><td>	76	</td><td>	80	</td><td>	77	</td><td>	80	</td><td>	84	</td><td>	80	</td><td>	76	</td></tr>
<tr><td>	29	</td><td>	Сиденков Геннадий Сергеевич	</td><td>	74	</td><td>	C+	</td><td>	2.4	</td><td>	Удов.	</td><td>	79	</td><td>	74	</td><td>	79	</td><td>	75	</td><td>	65	</td><td>	89	</td><td>	61	</td><td>	85	</td><td>	77	</td><td>	75	</td><td>	78	</td><td>	76	</td><td>	76	</td><td>	68	</td><td>	65	</td><td>	61	</td><td>	66	</td><td>	76	</td><td>	80	</td><td>	65	</td><td>	80	</td><td>	79	</td><td>	80	</td><td>	65	</td></tr>
<tr><td>	30	</td><td>	ТустикбаеваАйгулСакбаевна	</td><td>	79	</td><td>	B-	</td><td>	2.78	</td><td>	Хор.	</td><td>	78	</td><td>	79	</td><td>	78	</td><td>	78	</td><td>	79	</td><td>	79	</td><td>	78	</td><td>	77	</td><td>	77	</td><td>	78	</td><td>	76	</td><td>	81	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	78	</td><td>	75	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	81	</td></tr>
<tr><td>	31	</td><td>	ТөленЕрмұханСағынтайұлы	</td><td>	80	</td><td>	B	</td><td>	2.88	</td><td>	Хор.	</td><td>	75	</td><td>	76	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	84	</td><td>	79	</td><td>	84	</td><td>	84	</td><td>	77	</td><td>	78	</td><td>	75	</td><td>	85	</td><td>	78	</td><td>	83	</td><td>	80	</td><td>	86	</td><td>	86	</td><td>	80	</td><td>	77	</td><td>	80	</td><td>	78	</td><td>	77	</td><td>	80	</td></tr>
<tr><td>	32	</td><td>	ТөлеубайБатырханСерікбайұлы	</td><td>	81	</td><td>	B	</td><td>	2.96	</td><td>	Хор.	</td><td>	82	</td><td>	80	</td><td>	77	</td><td>	84	</td><td>	78	</td><td>	81	</td><td>	83	</td><td>	82	</td><td>	82	</td><td>	82	</td><td>	87	</td><td>	80	</td><td>	82	</td><td>	82	</td><td>	84	</td><td>	79	</td><td>	81	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	80	</td><td>	81	</td><td>	75	</td><td>	78	</td></tr>
<tr><td>	33	</td><td>	ХасеновЕрланЕрмекович	</td><td>	80	</td><td>	B	</td><td>	2.88	</td><td>	Хор.	</td><td>	76	</td><td>	85	</td><td>	80	</td><td>	84	</td><td>	80	</td><td>	83	</td><td>	80	</td><td>	84	</td><td>	84	</td><td>	77	</td><td>	80	</td><td>	78	</td><td>	83	</td><td>	80	</td><td>	78	</td><td>	80	</td><td>	80	</td><td>	77	</td><td>	85	</td><td>	79	</td><td>	80	</td><td>	84	</td><td>	80	</td><td>	79	</td></tr>
<tr><td>	34	</td><td>	ҚалиасқарСұлтанҚуатұлы	</td><td>	79	</td><td>	B-	</td><td>	2.76	</td><td>	Хор.	</td><td>	82	</td><td>	77	</td><td>	78	</td><td>	78	</td><td>	79	</td><td>	84	</td><td>	80	</td><td>	76	</td><td>	82	</td><td>	78	</td><td>	80	</td><td>	78	</td><td>	77	</td><td>	77	</td><td>	79	</td><td>	77	</td><td>	80	</td><td>	79	</td><td>	80	</td><td>	77	</td><td>	80	</td><td>	78	</td><td>	78	</td><td>	78	</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td></td><td></td><td></td><td></td><td></td>
<td>отлично</td>
<td></td>
<td></td>
<td>1</td>
<td></td>
<td>1</td>
<td></td>
<td>3</td><td>1</td><td>1</td><td>1</td><td>2</td><td>1</td><td>1</td><td>5</td><td></td><td>1</td><td>1</td><td>1</td><td></td><td></td><td></td><td></td><td></td><td>1</td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td>хорошо</td><td>31</td><td>30</td><td>30</td><td>30</td><td>29</td><td>30</td><td>28</td><td>29</td><td>30</td><td>29</td><td>30</td><td>30</td><td>30</td><td>27</td><td>29</td><td>29</td><td>29</td><td>29</td><td>33</td><td>29</td><td>19</td><td>31</td><td>19</td><td>28</td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td>удовлетворительно</td><td>2</td><td>3</td><td>2</td><td>3</td><td>3</td><td>3</td><td>2</td><td>3</td><td>2</td><td>3</td><td>1</td><td>2</td><td>2</td><td>1</td><td>4</td><td>3</td><td>3</td><td>3</td><td>4</td><td>2</td><td>4</td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td>неудовлетворительно</td><td>1</td><td>15</td><td>15</td></tr>	
<tr><td></td><td></td><td></td><td></td><td></td><td>Неявка</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td></tr>




</table>
<br>
<table>
<tr><td>Тьютор: _________ </td><td>Декан: _________</td><td>Офис регистратор: _________

<tr><td>Итого:</td><td>отлично  1</td><td>хорошо  15</td><td>удовлетворительно  17</td><td>неудовлетворительно  0 не явка 1
</table>
<br>
<b>Примечание:</b><br>
1) Преподаватель ответственен за подсчет итоговой оценки
<table border=1>
<tr><td>Рейтинг</td><td>0-49</td><td>50-54</td><td>55-59</td><td>60-64</td><td>65-69</td><td>70-74</td><td>75-79</td><td>80-84</td><td>85-89</td><td>90-94</td><td>95-100</td></tr>
<tr><td>Балл</td><td>0</td><td>1</td><td>1.33</td><td>1.67</td><td>2</td><td>2.33</td><td>2.67</td><td>3</td><td>3.33</td><td>3.67</td><td>4</td></tr>
<tr><td>Буквенный эквивалент</td><td>F</td><td>D</td><td>D+</td><td>C-</td><td>C</td><td>C+</td><td>B-</td><td>B</td><td>B+</td><td>A-</td><td>A</td></tr>
<tr><td>Оценка</td><td>Неуд.</td><td colspan=5>Удовлетворительно</td><td colspan=3>Хорошо</td><td colspan=2>Отлично</td></tr>
</table>
2) Внесение изменений и корректив в рейтинговую ведомость не допускается<br>
3) Члены апеляционной комиссии подписывают ведомость в случае проведения апелляции

<?
}
?>


</body>
</html>
