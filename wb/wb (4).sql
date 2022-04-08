-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 08 2022 г., 15:13
-- Версия сервера: 10.5.11-MariaDB-log
-- Версия PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `wb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `data_status`
--

CREATE TABLE `data_status` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `data_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `data_status`
--

INSERT INTO `data_status` (`id`, `userId`, `type`, `status`, `data_time`) VALUES
(19, 2, 2, 1, 1649420000),
(20, 2, 1, 0, 1649419672),
(21, 2, 6, 0, 1649419611),
(22, 2, 8, 0, 1649419675),
(23, 2, 7, 0, 1649419611),
(24, 2, 11, 0, 1649419612),
(25, 2, 10, 0, 1649419703),
(26, 2, 5, 0, 1649419612),
(27, 2, 9, 0, 1649419612);

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `edit` int(11) NOT NULL DEFAULT 0,
  `goods` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `realizationreport_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incomeId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplierArticle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `userId`, `type`, `edit`, `goods`, `realizationreport_id`, `incomeId`, `supplierArticle`, `barcode`, `name`, `value`) VALUES
(1366, 2, 11, 0, NULL, NULL, NULL, 'RD1', '2005425182001', 'strikethrough_price', '321'),
(1442, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'refund_color', ''),
(1443, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'image', '<a href=\"https://www.wildberries.ru/catalog/36474571/detail.aspx?targetUrl=MS\" target=_blank><img src=\"https://images.wbstatic.net/small/new/36470000/36474571.jpg\" style=\"height: 40px;\"></a>'),
(1444, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'supplierArticle', 'RD1'),
(1445, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'barcode', '2005425182001'),
(1446, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'subject', 'Рюкзаки'),
(1447, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'category', 'Аксессуары'),
(1448, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'brand', 'Рюкзаки ATF'),
(1449, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'strikethrough_price', '1745'),
(1450, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'defect', '0'),
(1451, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1452, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1453, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1454, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1455, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1456, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1457, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1458, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1459, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1460, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1461, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1462, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1463, 2, 12, 1, '1649327708', NULL, NULL, NULL, NULL, 'edit', '1'),
(1464, 2, 11, 0, NULL, NULL, NULL, 'RD1', '2005425182001', 'sale_percent', '12'),
(1465, 2, 11, 0, NULL, NULL, NULL, 'RD1', '2005425182001', 'totalPrice', '282.48'),
(1466, 2, 11, 0, NULL, NULL, NULL, 'RD1', '2005425182001', 'sale_total', '38.52'),
(1467, 2, 11, 0, NULL, NULL, NULL, 'RD1', '2005425182001', 'nalog7', '33.839999999999996'),
(1468, 2, 11, 0, NULL, NULL, NULL, 'RD1', '2005425182001', 'marga', '0'),
(1469, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'supplierArticle', ''),
(1470, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'barcode', ''),
(1471, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'subject', 'wq'),
(1472, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'category', 'we'),
(1473, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1474, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'strikethrough_price', '231'),
(1475, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'sale_percent', '1'),
(1476, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'totalPrice', '228.69'),
(1477, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'sale_total', '2.31'),
(1478, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'nalog7', '27.4428'),
(1479, 2, 12, 0, '1649327730794', NULL, NULL, NULL, NULL, 'marga', '0'),
(1480, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'supplierArticle', ''),
(1481, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'barcode', ''),
(1482, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'subject', 'wq'),
(1483, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'category', 'were'),
(1484, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1485, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'strikethrough_price', '231'),
(1486, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'sale_percent', '1'),
(1487, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'totalPrice', '228.69'),
(1488, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'sale_total', '2.31'),
(1489, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'nalog7', '27.4428'),
(1490, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'marga', '0'),
(1491, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'defect', '0'),
(1492, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1493, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1494, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1495, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1496, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1497, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1498, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1499, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1500, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1501, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1502, 2, 12, 0, '1649327749', NULL, NULL, NULL, NULL, 'edit', '0'),
(1503, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'supplierArticle', 'wq'),
(1504, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'barcode', 'qwe'),
(1505, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'subject', 'wq'),
(1506, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'category', 'we'),
(1507, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1508, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'strikethrough_price', '231'),
(1509, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'sale_percent', '1'),
(1510, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'totalPrice', '228.69'),
(1511, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'sale_total', '2.31'),
(1512, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1513, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'marga', '0'),
(1514, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'defect', '0'),
(1515, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1516, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1517, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1518, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1519, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1520, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1521, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1522, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1523, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1524, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1525, 2, 12, 0, '1649327761', NULL, NULL, NULL, NULL, 'edit', '0'),
(1526, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'refund_color', ''),
(1527, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'image', '<a href=\"https://www.wildberries.ru/catalog/36474988/detail.aspx?targetUrl=MS\" target=_blank><img src=\"https://images.wbstatic.net/small/new/36470000/36474988.jpg\" style=\"height: 40px;\"></a>'),
(1528, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'supplierArticle', 'Pn2'),
(1529, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'barcode', '2005425283005'),
(1530, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'subject', 'Рюкзаки'),
(1531, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'category', 'Аксессуары'),
(1532, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'brand', 'Рюкзаки ATF'),
(1533, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'defect', '0'),
(1534, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1535, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1536, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1537, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1538, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1539, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1540, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1541, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1542, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1543, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1544, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1545, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1546, 2, 12, 1, '1649327761', NULL, NULL, NULL, NULL, 'edit', '1'),
(1547, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'supplierArticle', 'wq'),
(1548, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'barcode', 'qwe'),
(1549, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'subject', 'wq'),
(1550, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'category', 'we'),
(1551, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1552, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'strikethrough_price', '154'),
(1553, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1554, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1555, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'sale_total', '154'),
(1556, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1557, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'marga', '0'),
(1558, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'defect', '0'),
(1559, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1560, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1561, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1562, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1563, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1564, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1565, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1566, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1567, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1568, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1569, 2, 12, 0, '1649327778', NULL, NULL, NULL, NULL, 'edit', '0'),
(1570, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'refund_color', ''),
(1571, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'image', '<a href=\"https://www.wildberries.ru/catalog/36474571/detail.aspx?targetUrl=MS\" target=_blank><img src=\"https://images.wbstatic.net/small/new/36470000/36474571.jpg\" style=\"height: 40px;\"></a>'),
(1572, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'supplierArticle', 'RD1'),
(1573, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'barcode', '2005425182001'),
(1574, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'subject', 'Рюкзаки'),
(1575, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'category', 'Аксессуары'),
(1576, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'brand', 'Рюкзаки ATF'),
(1577, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'strikethrough_price', '154'),
(1578, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'defect', '0'),
(1579, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1580, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1581, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1582, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1583, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1584, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1585, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1586, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1587, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1588, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1589, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1590, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1591, 2, 12, 1, '1649327778', NULL, NULL, NULL, NULL, 'edit', '1'),
(1592, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'supplierArticle', ''),
(1593, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'barcode', ''),
(1594, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'subject', ''),
(1595, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'category', 'we'),
(1596, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1597, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'strikethrough_price', '231'),
(1598, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'sale_percent', '1'),
(1599, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'totalPrice', '228.69'),
(1600, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'sale_total', '2.31'),
(1601, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'nalog7', '27.4428'),
(1602, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'marga', '0'),
(1603, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'defect', '0'),
(1604, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1605, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1606, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1607, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1608, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1609, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1610, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1611, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1612, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1613, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1614, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'edit', '0'),
(1615, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'supplierArticle', ''),
(1616, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'barcode', ''),
(1617, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'subject', ''),
(1618, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'category', 'we'),
(1619, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1620, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'strikethrough_price', '231'),
(1621, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'sale_percent', '1'),
(1622, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'totalPrice', '228.69'),
(1623, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'sale_total', '2.31'),
(1624, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'nalog7', '27.4428'),
(1625, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'marga', '0'),
(1626, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'defect', '0'),
(1627, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1628, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1629, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1630, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1631, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1632, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1633, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1634, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1635, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1636, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1637, 2, 12, 0, '1649327805', NULL, NULL, NULL, NULL, 'edit', '0'),
(1638, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'supplierArticle', 'wq'),
(1639, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'barcode', 'qwe'),
(1640, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'subject', 'wq'),
(1641, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'category', 'we'),
(1642, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1643, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'strikethrough_price', '121'),
(1644, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1645, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1646, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'sale_total', '121'),
(1647, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1648, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'marga', '0'),
(1649, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'defect', '0'),
(1650, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1651, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1652, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1653, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1654, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1655, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1656, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1657, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1658, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1659, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1660, 2, 12, 0, '1649327949000', NULL, NULL, NULL, NULL, 'edit', '0'),
(1661, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'supplierArticle', 'RD1'),
(1662, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'barcode', '2005425182001'),
(1663, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'subject', 'Рюкзаки'),
(1664, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'category', 'Аксессуары'),
(1665, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'brand', 'Рюкзаки ATF'),
(1666, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'strikethrough_price', '121'),
(1667, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1668, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1669, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'sale_total', '121'),
(1670, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1671, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'marga', '0'),
(1672, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'defect', '0'),
(1673, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1674, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1675, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1676, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1677, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1678, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1679, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1680, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1681, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1682, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1683, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'edit', '1'),
(1684, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'refund_color', ''),
(1685, 2, 12, 1, '1649327949000', NULL, NULL, NULL, NULL, 'image', '<a href=\"https://www.wildberries.ru/catalog/36474571/detail.aspx?targetUrl=MS\" target=_blank><img src=\"https://images.wbstatic.net/small/new/36470000/36474571.jpg\" style=\"height: 40px;\"></a>'),
(1686, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'supplierArticle', 'wq'),
(1687, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'barcode', 'qwe'),
(1688, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'subject', 'wq'),
(1689, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'category', 'we'),
(1690, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1691, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'strikethrough_price', '122'),
(1692, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1693, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1694, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'sale_total', '122'),
(1695, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1696, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'marga', '0'),
(1697, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'defect', '0'),
(1698, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1699, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1700, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1701, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1702, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1703, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1704, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1705, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1706, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1707, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1708, 2, 12, 0, '1649328001000', NULL, NULL, NULL, NULL, 'edit', '0'),
(1709, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'supplierArticle', 'RD1'),
(1710, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'barcode', '2005425182001'),
(1711, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'subject', 'Рюкзаки'),
(1712, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'category', 'Аксессуары'),
(1713, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'brand', 'Рюкзаки ATF'),
(1714, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'strikethrough_price', '122'),
(1715, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1716, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1717, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'sale_total', '122'),
(1718, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1719, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'marga', '0'),
(1720, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'defect', '0'),
(1721, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1722, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1723, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1724, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1725, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1726, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1727, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1728, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1729, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1730, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1731, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'edit', '1'),
(1732, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'refund_color', ''),
(1733, 2, 12, 1, '1649328001000', NULL, NULL, NULL, NULL, 'image', '<a href=\"https://www.wildberries.ru/catalog/36474571/detail.aspx?targetUrl=MS\" target=_blank><img src=\"https://images.wbstatic.net/small/new/36470000/36474571.jpg\" style=\"height: 40px;\"></a>'),
(1734, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'supplierArticle', 'wq'),
(1735, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'barcode', 'qwe'),
(1736, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'subject', 'wq'),
(1737, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'category', 'we'),
(1738, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1739, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'strikethrough_price', '123'),
(1740, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1741, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1742, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'sale_total', '123'),
(1743, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1744, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'marga', '0'),
(1745, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'defect', '0'),
(1746, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1747, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1748, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1749, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1750, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1751, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1752, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1753, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1754, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1755, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1756, 2, 12, 0, '1649328058000', NULL, NULL, NULL, NULL, 'edit', '0'),
(1757, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'supplierArticle', 'RD1'),
(1758, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'barcode', '2005425182001'),
(1759, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'subject', 'Рюкзаки'),
(1760, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'category', 'Аксессуары'),
(1761, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'brand', 'Рюкзаки ATF'),
(1762, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'strikethrough_price', '123'),
(1763, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1764, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1765, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'sale_total', '123'),
(1766, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1767, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'marga', '0'),
(1768, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'defect', '0'),
(1769, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1770, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1771, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1772, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1773, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1774, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1775, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1776, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1777, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1778, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1779, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'edit', '1'),
(1780, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'refund_color', ''),
(1781, 2, 12, 1, '1649328058000', NULL, NULL, NULL, NULL, 'image', '<a href=\"https://www.wildberries.ru/catalog/36474571/detail.aspx?targetUrl=MS\" target=_blank><img src=\"https://images.wbstatic.net/small/new/36470000/36474571.jpg\" style=\"height: 40px;\"></a>'),
(1782, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'supplierArticle', ''),
(1783, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'barcode', ''),
(1784, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'subject', ''),
(1785, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'category', 'we'),
(1786, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'brand', 'ew'),
(1787, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'strikethrough_price', '231'),
(1788, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'sale_percent', '1'),
(1789, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'totalPrice', '228.69'),
(1790, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'sale_total', '2.31'),
(1791, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'nalog7', '27.4428'),
(1792, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'marga', '0'),
(1793, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'defect', '0'),
(1794, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1795, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1796, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1797, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1798, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1799, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1800, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1801, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1802, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1803, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1804, 2, 12, 0, '1649328200', NULL, NULL, NULL, NULL, 'edit', '0'),
(1805, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'supplierArticle', 'RD1'),
(1806, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'barcode', '2005425182001'),
(1807, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'subject', 'Рюкзаки'),
(1808, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'category', 'Аксессуары'),
(1809, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'brand', 'Рюкзаки ATF'),
(1810, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'strikethrough_price', '11'),
(1811, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1812, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'totalPrice', '0'),
(1813, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'sale_total', '11'),
(1814, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'nalog7', '0'),
(1815, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'marga', '0'),
(1816, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'defect', '0'),
(1817, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'stoimost', '0'),
(1818, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'zatrat', '0'),
(1819, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'cost_delivery', '0'),
(1820, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'cost_amout', '0'),
(1821, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'cost_defect', '0'),
(1822, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'cost_wb_commission', '0'),
(1823, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'cost_log', '0'),
(1824, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'ransom', '0'),
(1825, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'wb_commission', '0'),
(1826, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'pribil', '0'),
(1827, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'edit', '1'),
(1828, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'refund_color', ''),
(1829, 2, 12, 1, '1649328201', NULL, NULL, NULL, NULL, 'image', '<a href=\"https://www.wildberries.ru/catalog/36474571/detail.aspx?targetUrl=MS\" target=_blank><img src=\"https://images.wbstatic.net/small/new/36470000/36474571.jpg\" style=\"height: 40px;\"></a>'),
(1830, 2, 12, 0, '1649327708', NULL, NULL, NULL, NULL, 'sale_percent', '100'),
(1831, 2, 12, 0, '1649327708', NULL, NULL, NULL, NULL, 'sale_total', '1745'),
(1832, 2, 12, 0, '1649328327314', NULL, NULL, NULL, NULL, 'supplierArticle', ''),
(1833, 2, 12, 0, '1649328327314', NULL, NULL, NULL, NULL, 'barcode', '546'),
(1834, 2, 12, 0, '1649328327314', NULL, NULL, NULL, NULL, 'subject', '456'),
(1835, 2, 12, 0, '1649328327314', NULL, NULL, NULL, NULL, 'category', '456'),
(1836, 2, 12, 0, '1649328327314', NULL, NULL, NULL, NULL, 'brand', '456');

-- --------------------------------------------------------

--
-- Структура таблицы `list`
--

CREATE TABLE `list` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `list` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `list`
--

INSERT INTO `list` (`id`, `userId`, `list`) VALUES
(1, 2, 'Затраты на поиск товара\n Затраты на забор товара\n Затраты на услуги фулфилмента\n Затраты на фото/видео материалы\n Затраты на внутреннюю рекламу\n Затраты на внешнюю рекламу\n Затраты на самовыкупы\n Затраты прочие');

-- --------------------------------------------------------

--
-- Структура таблицы `params`
--

CREATE TABLE `params` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `params`
--

INSERT INTO `params` (`id`, `userId`, `name`, `value`) VALUES
(2, 2, 'status', 'line'),
(3, 2, 'option', 'total'),
(5, 2, 'hide', '{\"\\u0421\\u0443\\u043c\\u043c\\u0430 \\u043f\\u0440\\u043e\\u0434\\u0430\\u0436(\\u0412\\u043e\\u0437\\u0432\\u0440\\u0430\\u0442\\u043e\\u0432)\":\"true\",\"\\u0421\\u0442\\u043e\\u0438\\u043c\\u043e\\u0441\\u0442\\u044c \\u0445\\u0440\\u0430\\u043d\\u0435\\u043d\\u0438\\u044f\":\"true\",\"\\u0421\\u0442\\u043e\\u0438\\u043c\\u043e\\u0441\\u0442\\u044c \\u043f\\u043b\\u0430\\u0442\\u043d\\u043e\\u0439 \\u043f\\u0440\\u0438\\u0435\\u043c\\u043a\\u0438\":\"true\",\"\\u041f\\u0440\\u043e\\u0447\\u0438\\u0435 \\u0443\\u0434\\u0435\\u0440\\u0436\\u0430\\u043d\\u0438\\u044f\":\"true\",\"\\u041a \\u043f\\u0435\\u0440\\u0435\\u0447\\u0438\\u0441\\u043b\\u0435\\u043d\\u0438\\u044e \\u041f\\u0440\\u043e\\u0434\\u0430\\u0432\\u0446\\u0443 \\u0437\\u0430 \\u0440\\u0435\\u0430\\u043b\\u0438\\u0437\\u043e\\u0432\\u0430\\u043d\\u043d\\u044b\\u0439 \\u0422\\u043e\\u0432\\u0430\\u0440\":\"true\",\"\\u0412\\u043e\\u0437\\u043d\\u0430\\u0433\\u0440\\u0430\\u0436\\u0434\\u0435\\u043d\\u0438\\u0435 \\u0412\\u0430\\u0439\\u043b\\u0434\\u0431\\u0435\\u0440\\u0440\\u0438\\u0437 (\\u0412\\u0412), \\u0431\\u0435\\u0437 \\u041d\\u0414\\u0421\":\"true\",\"\\u041d\\u0414\\u0421 \\u0441 \\u0412\\u043e\\u0437\\u043d\\u0430\\u0433\\u0440\\u0430\\u0436\\u0434\\u0435\\u043d\\u0438\\u044f \\u0412\\u0430\\u0439\\u043b\\u0434\\u0431\\u0435\\u0440\\u0440\\u0438\\u0437\":\"true\",\"\\u0421\\u0442\\u043e\\u0438\\u043c\\u043e\\u0441\\u0442\\u044c \\u043b\\u043e\\u0433\\u0438\\u0441\\u0442\\u0438\\u043a\\u0438\":\"true\",\"\\u0418\\u0442\\u043e\\u0433\\u043e \\u043a \\u043e\\u043f\\u043b\\u0430\\u0442\\u0435\":\"true\",\"\\u041a\\u043e\\u043b\\u0438\\u0447\\u0435\\u0441\\u0442\\u0432\\u043e \\u043f\\u0440\\u043e\\u0434\\u0430\\u0436\":\"true\",\"\\u041a\\u043e\\u043b-\\u0432\\u043e \\u0434\\u043e\\u0441\\u0442\\u0430\\u0432\\u043e\\u043a\":\"true\",\"\\u041a\\u043e\\u043b-\\u0432\\u043e \\u0432\\u043e\\u0437\\u0432\\u0440\\u0430\\u0442\\u043e\\u0432\":\"true\"}'),
(6, 2, 'api_key', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2Nlc3NJRCI6IjdkYjE0NGNhLWYxMTUtNDQ3Ny1hNWQyLWFlOGE1ODFmYjUwOCJ9.fjvZ3XkVrWjoaU61RKLLAAiYobef77w7GQjH54d1uLk'),
(7, 2, 'stats_key', 'ODJiYWE5YTUtZmE2ZC00YzFjLWFmZDgtZTY3ZmZmOTZlNThj'),
(8, 2, 'supplierId', 'b541a87c-d482-4161-9f30-5edc1fded445'),
(9, 2, 'perc', '12'),
(10, 2, 'pay', 'off'),
(11, 2, 'config_return', 'on'),
(18, 2, 'forcibly', '1649420000');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `data_status`
--
ALTER TABLE `data_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `list`
--
ALTER TABLE `list`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `params`
--
ALTER TABLE `params`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `data_status`
--
ALTER TABLE `data_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1837;

--
-- AUTO_INCREMENT для таблицы `list`
--
ALTER TABLE `list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `params`
--
ALTER TABLE `params`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
