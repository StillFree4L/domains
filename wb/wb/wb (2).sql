-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 05 2022 г., 08:33
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
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
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

INSERT INTO `goods` (`id`, `userId`, `type`, `goods`, `realizationreport_id`, `incomeId`, `supplierArticle`, `barcode`, `name`, `value`) VALUES
(158, 2, 5, '', '8065790', NULL, NULL, NULL, 'other_deductions', '500'),
(159, 2, 5, '', '8348378', NULL, NULL, NULL, 'other_deductions', '564'),
(160, 2, 5, '', '8348378', NULL, NULL, NULL, 'acceptance_fee', '34'),
(161, 2, 5, '', '8348378', NULL, NULL, NULL, 'storage_cost', '46'),
(162, 2, 5, '', '8065790', NULL, NULL, NULL, 'storage_cost', '400'),
(163, 2, 5, '', '7701861', NULL, NULL, NULL, 'storage_cost', ''),
(164, 2, 5, '', '7701861', NULL, NULL, NULL, 'acceptance_fee', '4326'),
(165, 2, 5, '', '7528010', NULL, NULL, NULL, 'other_deductions', '436'),
(166, 2, 5, '', '8534976', NULL, NULL, NULL, 'storage_cost', '2000'),
(167, 2, 5, '', '8534976', NULL, NULL, NULL, 'acceptance_fee', '5000'),
(168, 2, 5, '', '8534976', NULL, NULL, NULL, 'other_deductions', '451'),
(169, 2, 5, '', '8764187', NULL, NULL, NULL, 'storage_cost', '50000'),
(170, 2, 5, '', '8764187', NULL, NULL, NULL, 'acceptance_fee', '10000'),
(171, 2, 5, '', '8764187', NULL, NULL, NULL, 'other_deductions', '20000'),
(172, 2, 5, '', '7195475', NULL, NULL, NULL, 'storage_cost', '500'),
(173, 2, 5, '', '7195475', NULL, NULL, NULL, 'acceptance_fee', ''),
(174, 2, 5, '', '7195475', NULL, NULL, NULL, 'other_deductions', '245'),
(175, 2, 5, '', '7035337', NULL, NULL, NULL, 'acceptance_fee', '50000'),
(178, 2, 7, '', NULL, '6051704', '6х2,5спб115', '2011333590012', 'Zatraty_na_zabor_tovara', '325'),
(179, 2, 7, '', NULL, '6101078', '6х2,5спб115', '2011333590012', 'Zatraty_na_uslugi_fulfilmenta', '23'),
(180, 2, 7, '', NULL, '6051704', '6х2,5спб115', '2011333590012', 'Zatraty_na_foto_video_materialy', '235'),
(181, 2, 7, '', NULL, '6101078', '6х2,5спб115', '2011333590012', 'Zatraty_na_vnutrennyuyu_reklamu', '325'),
(182, 2, 7, '', NULL, '6051704', '6х2,5спб115', '2011333590012', 'Zatraty_na_vneshnyuyu_reklamu', '325'),
(183, 2, 7, '', NULL, '6101078', '6х2,5спб115', '2011333590012', 'Zatraty_na_samovykupy', '325'),
(184, 2, 7, '', NULL, '6051704', '6х2,5спб115', '2011333590012', 'Zatraty_prochie', '235'),
(187, 2, 7, '', NULL, '6988769', '6х2,6спбATF', '2009901810006', 'Zatraty_na_zabor_tovara', '346'),
(188, 2, 7, '', NULL, '6988769', '6х2,6спбATF', '2009901810006', 'Zatraty_na_uslugi_fulfilmenta', '436'),
(189, 2, 7, '', NULL, '6988769', '6х2,6спбATF', '2009901810006', 'Zatraty_na_foto_video_materialy', '346'),
(190, 2, 7, '', NULL, '6988769', '6х2,6спбATF', '2009901810006', 'Zatraty_na_vnutrennyuyu_reklamu', '346'),
(191, 2, 7, '', NULL, '6988769', '6х2,6спбATF', '2009901810006', 'Zatraty_na_vneshnyuyu_reklamu', '4378'),
(192, 2, 7, '', NULL, '6988769', '6х2,6спбATF', '2009901810006', 'Zatraty_na_samovykupy', '658956'),
(193, 2, 7, '', NULL, '6988769', '6х2,6спбATF', '2009901810006', 'Zatraty_prochie', '569'),
(194, 2, 5, '', '7035337', NULL, NULL, NULL, 'other_deductions', '22'),
(195, 2, 5, '', '7035337', NULL, NULL, NULL, 'storage_cost', '500'),
(196, 2, 5, '', '7956923', NULL, NULL, NULL, 'other_deductions', '23000'),
(197, 2, 5, '', '7359140', NULL, NULL, NULL, 'other_deductions', '100'),
(198, 2, 5, '', '7359140', NULL, NULL, NULL, 'acceptance_fee', '12'),
(199, 2, 5, '', '6879455', NULL, NULL, NULL, 'other_deductions', '30000'),
(200, 2, 5, '', '6879455', NULL, NULL, NULL, 'storage_cost', '21'),
(201, 2, 5, '', '6879455', NULL, NULL, NULL, 'acceptance_fee', '3'),
(202, 2, 5, '', '7359140', NULL, NULL, NULL, 'storage_cost', '3'),
(203, 2, 5, '', '7701861', NULL, NULL, NULL, 'other_deductions', '20000'),
(493, 2, 11, NULL, NULL, NULL, '5х2,6спбATF', '2009901470002', 'sale_total', '800'),
(494, 2, 11, NULL, NULL, NULL, '3х2,6спбATF', '2009900417008', 'sale_total', '800'),
(495, 2, 11, NULL, NULL, NULL, '2х2,4спбATF', '2008729941008', 'sale_total', '800'),
(496, 2, 11, NULL, NULL, NULL, '6х2,5спбATF', '2008728874000', 'sale_total', '800'),
(497, 2, 11, NULL, NULL, NULL, '3х2,5спбATF', '2008727995003', 'sale_total', '800'),
(498, 2, 11, NULL, NULL, NULL, '2х2,5спбATF', '2008727229009', 'sale_total', '800'),
(499, 2, 11, NULL, NULL, NULL, '5х2,7спбATF', '2008724955000', 'sale_total', '800'),
(500, 2, 11, NULL, NULL, NULL, '4х2,7спбATF', '2008203369007', 'sale_total', '800'),
(501, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'sale_total', '800'),
(502, 2, 11, NULL, NULL, NULL, '4м', '2005207392000', 'sale_total', '800'),
(504, 2, 11, NULL, NULL, NULL, '3м', '2005207263003', 'sale_total', '800'),
(505, 2, 11, NULL, NULL, NULL, 'BZ1', '2004622281005', 'sale_total', '800'),
(506, 2, 11, NULL, NULL, NULL, 'RD1', '2005425182001', 'sale_total', '800'),
(507, 2, 11, NULL, NULL, NULL, '4х2,5м', '2006732841001', 'sale_total', '800'),
(508, 2, 11, NULL, NULL, NULL, '2х2,7спбATF', '2008203244007', 'sale_total', '800'),
(509, 2, 11, NULL, NULL, NULL, '6х2,7спбATF', '2008726714001', 'sale_total', '800'),
(510, 2, 11, NULL, NULL, NULL, 'Yl1', '2005425328003', 'sale_total', '800'),
(511, 2, 11, NULL, NULL, NULL, '4х2,5спбATF', '2008728485008', 'sale_total', '800'),
(512, 2, 11, NULL, NULL, NULL, '3х2,7спбATF', '2008723945002', 'sale_total', '800'),
(513, 2, 11, NULL, NULL, NULL, '5х2,4спб', '2008203766004', 'sale_total', '800'),
(514, 2, 11, NULL, NULL, NULL, '6х2,6спбATF', '2009901810006', 'sale_total', '800'),
(515, 2, 11, NULL, NULL, NULL, '4х2,6спбATF', '2009901159006', 'sale_total', '800'),
(516, 2, 11, NULL, NULL, NULL, '3х2,4спбATF', '2008730420004', 'sale_total', '800'),
(517, 2, 11, NULL, NULL, NULL, '4х2,4спб', '2008203706000', 'sale_total', '800'),
(518, 2, 11, NULL, NULL, NULL, '6м', '2005207438005', 'sale_total', '800'),
(519, 2, 11, NULL, NULL, NULL, '6х2,4спб', '2008203811001', 'sale_total', '800'),
(520, 2, 11, NULL, NULL, NULL, '5х2,5спб', '2008203497007', 'sale_total', '800'),
(521, 2, 11, NULL, NULL, NULL, '5х2,6спбATF', '2009901470002', 'strikethrough_price', '4000'),
(522, 2, 11, NULL, NULL, NULL, '5х2,6спбATF', '2009901470002', 'sale_percent', '20'),
(523, 2, 11, NULL, NULL, NULL, '3х2,6спбATF', '2009900417008', 'strikethrough_price', '4000'),
(524, 2, 11, NULL, NULL, NULL, '3х2,6спбATF', '2009900417008', 'sale_percent', '20'),
(527, 2, 11, NULL, NULL, NULL, '2х2,4спбATF', '2008729941008', 'strikethrough_price', '4000'),
(528, 2, 11, NULL, NULL, NULL, '2х2,4спбATF', '2008729941008', 'sale_percent', '20'),
(529, 2, 11, NULL, NULL, NULL, '6х2,5спбATF', '2008728874000', 'strikethrough_price', '4000'),
(530, 2, 11, NULL, NULL, NULL, '6х2,5спбATF', '2008728874000', 'sale_percent', '20'),
(531, 2, 11, NULL, NULL, NULL, '3х2,5спбATF', '2008727995003', 'strikethrough_price', '4000'),
(532, 2, 11, NULL, NULL, NULL, '3х2,5спбATF', '2008727995003', 'sale_percent', '20'),
(533, 2, 11, NULL, NULL, NULL, '2х2,5спбATF', '2008727229009', 'strikethrough_price', '4000'),
(534, 2, 11, NULL, NULL, NULL, '2х2,5спбATF', '2008727229009', 'sale_percent', '20'),
(535, 2, 11, NULL, NULL, NULL, '5х2,7спбATF', '2008724955000', 'strikethrough_price', '4000'),
(536, 2, 11, NULL, NULL, NULL, '5х2,7спбATF', '2008724955000', 'sale_percent', '20'),
(537, 2, 11, NULL, NULL, NULL, '4х2,7спбATF', '2008203369007', 'strikethrough_price', '4000'),
(538, 2, 11, NULL, NULL, NULL, '4х2,7спбATF', '2008203369007', 'sale_percent', '20'),
(539, 2, 11, NULL, NULL, NULL, '4х2,5м', '2006732841001', 'strikethrough_price', '4000'),
(540, 2, 11, NULL, NULL, NULL, '4х2,5м', '2006732841001', 'sale_percent', '20'),
(541, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'strikethrough_price', '4000'),
(542, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'sale_percent', '20'),
(543, 2, 11, NULL, NULL, NULL, 'RD1', '2005425182001', 'strikethrough_price', '4000'),
(544, 2, 11, NULL, NULL, NULL, 'RD1', '2005425182001', 'sale_percent', '20'),
(545, 2, 11, NULL, NULL, NULL, '4м', '2005207392000', 'strikethrough_price', '4000'),
(546, 2, 11, NULL, NULL, NULL, '4м', '2005207392000', 'sale_percent', '20'),
(547, 2, 11, NULL, NULL, NULL, '3м', '2005207263003', 'strikethrough_price', '4000'),
(548, 2, 11, NULL, NULL, NULL, '3м', '2005207263003', 'sale_percent', '20'),
(549, 2, 11, NULL, NULL, NULL, 'BZ1', '2004622281005', 'strikethrough_price', '4000'),
(550, 2, 11, NULL, NULL, NULL, 'BZ1', '2004622281005', 'sale_percent', '20'),
(551, 2, 11, NULL, NULL, NULL, '6х2,7спбATF', '2008726714001', 'strikethrough_price', '4000'),
(552, 2, 11, NULL, NULL, NULL, '6х2,7спбATF', '2008726714001', 'sale_percent', '20'),
(553, 2, 11, NULL, NULL, NULL, '2х2,7спбATF', '2008203244007', 'strikethrough_price', '4000'),
(554, 2, 11, NULL, NULL, NULL, '2х2,7спбATF', '2008203244007', 'sale_percent', '20'),
(555, 2, 11, NULL, NULL, NULL, 'Yl1', '2005425328003', 'strikethrough_price', '4000'),
(556, 2, 11, NULL, NULL, NULL, 'Yl1', '2005425328003', 'sale_percent', '20'),
(557, 2, 11, NULL, NULL, NULL, '4х2,5спбATF', '2008728485008', 'strikethrough_price', '4000'),
(558, 2, 11, NULL, NULL, NULL, '4х2,5спбATF', '2008728485008', 'sale_percent', '20'),
(559, 2, 11, NULL, NULL, NULL, '3х2,7спбATF', '2008723945002', 'strikethrough_price', '4000'),
(560, 2, 11, NULL, NULL, NULL, '3х2,7спбATF', '2008723945002', 'sale_percent', '20'),
(561, 2, 11, NULL, NULL, NULL, '6х2,4спб', '2008203811001', 'strikethrough_price', '4000'),
(562, 2, 11, NULL, NULL, NULL, '6х2,4спб', '2008203811001', 'sale_percent', '20'),
(563, 2, 11, NULL, NULL, NULL, '5х2,4спб', '2008203766004', 'strikethrough_price', '4000'),
(564, 2, 11, NULL, NULL, NULL, '5х2,4спб', '2008203766004', 'sale_percent', '20'),
(565, 2, 11, NULL, NULL, NULL, '5х2,5спб', '2008203497007', 'strikethrough_price', '4000'),
(566, 2, 11, NULL, NULL, NULL, '5х2,5спб', '2008203497007', 'sale_percent', '20'),
(567, 2, 11, NULL, NULL, NULL, '6х2,6спбATF', '2009901810006', 'strikethrough_price', '4000'),
(568, 2, 11, NULL, NULL, NULL, '6х2,6спбATF', '2009901810006', 'sale_percent', '20'),
(569, 2, 11, NULL, NULL, NULL, '4х2,6спбATF', '2009901159006', 'strikethrough_price', '4000'),
(570, 2, 11, NULL, NULL, NULL, '4х2,6спбATF', '2009901159006', 'sale_percent', '20'),
(571, 2, 11, NULL, NULL, NULL, '3х2,4спбATF', '2008730420004', 'strikethrough_price', '4000'),
(572, 2, 11, NULL, NULL, NULL, '3х2,4спбATF', '2008730420004', 'sale_percent', '20'),
(573, 2, 11, NULL, NULL, NULL, '4х2,4спб', '2008203706000', 'strikethrough_price', '4000'),
(574, 2, 11, NULL, NULL, NULL, '4х2,4спб', '2008203706000', 'sale_percent', '20'),
(575, 2, 11, NULL, NULL, NULL, '6м', '2005207438005', 'strikethrough_price', '4000'),
(576, 2, 11, NULL, NULL, NULL, '6м', '2005207438005', 'sale_percent', '20'),
(587, 2, 7, NULL, NULL, '6051704', '6х2,5спб112', '2011333590036', 'Zatraty_na_zabor_tovara', '325'),
(588, 2, 7, NULL, NULL, '5961289', '6х2,5спб112', '2011333590036', 'Zatraty_na_zabor_tovara', '325'),
(591, 2, 7, NULL, NULL, '6159727', '6х2,5спб112', '2011333590036', 'Zatraty_na_poisk_tovara', '255'),
(593, 2, 7, NULL, NULL, '6101078', '6х2,5спб112', '2011333590036', 'Zatraty_na_poisk_tovara', '255'),
(595, 2, 7, NULL, NULL, '6051704', '6х2,5спб112', '2011333590036', 'Zatraty_na_poisk_tovara', '255'),
(597, 2, 7, NULL, NULL, '6005117', '6х2,5спб112', '2011333590036', 'Zatraty_na_poisk_tovara', '255'),
(599, 2, 7, NULL, NULL, '5961289', '6х2,5спб112', '2011333590036', 'Zatraty_na_poisk_tovara', '255'),
(601, 2, 7, NULL, NULL, '5881367', '6х2,5спб112', '2011333590036', 'Zatraty_na_poisk_tovara', '255'),
(602, 2, 7, NULL, NULL, '6101078', '6х2,5спб112', '2011333590036', 'Zatraty_na_uslugi_fulfilmenta', '6856'),
(603, 2, 7, NULL, NULL, '6005117', '6х2,5спб112', '2011333590036', 'Zatraty_na_foto_video_materialy', '86'),
(604, 2, 7, NULL, NULL, '6005117', '6х2,5спб112', '2011333590036', 'Zatraty_na_uslugi_fulfilmenta', ''),
(605, 2, 7, NULL, NULL, '6051704', '6х2,5спб112', '2011333590036', 'Zatraty_na_foto_video_materialy', '68'),
(606, 2, 7, NULL, NULL, '6159727', '6х2,5спб112', '2011333590036', 'Zatraty_na_foto_video_materialy', '568'),
(607, 2, 7, NULL, NULL, '5961289', '6х2,5спб112', '2011333590036', 'Zatraty_na_uslugi_fulfilmenta', '658'),
(608, 2, 7, NULL, NULL, '5881367', '6х2,5спб112', '2011333590036', 'Zatraty_na_foto_video_materialy', '658'),
(609, 2, 7, NULL, NULL, '6159727', '6х2,5спб112', '2011333590036', 'Stoimosty_edinicy_tovara', '1111'),
(610, 2, 7, NULL, NULL, '6101078', '6х2,5спб112', '2011333590036', 'Stoimosty_edinicy_tovara', '1111'),
(611, 2, 7, NULL, NULL, '6051704', '6х2,5спб112', '2011333590036', 'Stoimosty_edinicy_tovara', '1111'),
(612, 2, 7, NULL, NULL, '6005117', '6х2,5спб112', '2011333590036', 'Stoimosty_edinicy_tovara', '1111'),
(613, 2, 7, NULL, NULL, '5961289', '6х2,5спб112', '2011333590036', 'Stoimosty_edinicy_tovara', '1111'),
(614, 2, 7, NULL, NULL, '5881367', '6х2,5спб112', '2011333590036', 'Stoimosty_edinicy_tovara', '1111'),
(615, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'totalPrice', '3200'),
(616, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'nalog7', '384'),
(617, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'marga', '-43.75'),
(618, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'stoimost', '500'),
(619, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'pribil', '-672'),
(620, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'zatrat', '100'),
(621, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'wb_commission', '15'),
(622, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'cost_wb_commission', '480'),
(623, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'defect', '12'),
(624, 2, 11, NULL, NULL, NULL, 'Pn2', '2005425283005', 'cost_defect', '72'),
(633, 2, 5, NULL, '8065790', NULL, NULL, NULL, 'acceptance_fee', '5000'),
(634, 2, 5, NULL, '7956923', NULL, NULL, NULL, 'acceptance_fee', '10000'),
(635, 2, 7, NULL, NULL, '6977103', '6х2,7спбATF', '2008726714001', 'Stoimosty_edinicy_tovara', '50000'),
(636, 2, 7, NULL, NULL, '6977103', '6х2,7спбATF', '2008726714001', 'Zatraty_na_poisk_tovara', '3200'),
(637, 2, 5, NULL, '7528010', NULL, NULL, NULL, 'acceptance_fee', '20000'),
(638, 2, 5, NULL, '7528010', NULL, NULL, NULL, 'storage_cost', '18000'),
(648, 2, 11, NULL, NULL, NULL, '2х2,4спбATF', '2008729941008', 'totalPrice', '3200'),
(649, 2, 11, NULL, NULL, NULL, '2х2,4спбATF', '2008729941008', 'marga', '0'),
(650, 2, 11, NULL, NULL, NULL, '2х2,4спбATF', '2008729941008', 'nalog7', '384'),
(651, 2, 11, NULL, NULL, NULL, '2х2,6спбATF', '2009899865002', 'strikethrough_price', '50000'),
(652, 2, 11, NULL, NULL, NULL, '2х2,6спбATF', '2009899865002', 'sale_percent', '10'),
(653, 2, 11, NULL, NULL, NULL, '2х2,6спбATF', '2009899865002', 'totalPrice', '45000'),
(654, 2, 11, NULL, NULL, NULL, '2х2,6спбATF', '2009899865002', 'sale_total', '5000'),
(655, 2, 11, NULL, NULL, NULL, '2х2,6спбATF', '2009899865002', 'nalog7', '5400'),
(656, 2, 11, NULL, NULL, NULL, '2х2,6спбATF', '2009899865002', 'marga', '0'),
(657, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'strikethrough_price', '23000'),
(658, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'brand', 'ertew'),
(659, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'sale_percent', '46'),
(660, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'totalPrice', '12420'),
(661, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'sale_total', '10580'),
(662, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'marga', '-0.2676659528907923'),
(663, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'nalog7', '1490.3999999999999'),
(664, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'stoimost', '4'),
(665, 2, 12, '1648915506853', NULL, NULL, NULL, NULL, 'pribil', '-4');

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
(5, 2, 'hide', '{\"\\u0421\\u0443\\u043c\\u043c\\u0430 \\u043f\\u0440\\u043e\\u0434\\u0430\\u0436(\\u0412\\u043e\\u0437\\u0432\\u0440\\u0430\\u0442\\u043e\\u0432)\":\"false\",\"\\u0421\\u0442\\u043e\\u0438\\u043c\\u043e\\u0441\\u0442\\u044c \\u0445\\u0440\\u0430\\u043d\\u0435\\u043d\\u0438\\u044f\":\"false\"}'),
(6, 2, 'api_key', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCYvQfaPaJzWbWjr5Ro6JJ1Cq6U6HiD1U'),
(7, 2, 'stats_key', 'ODJiYWE5YTUtZmE2ZC00YzFjLWFmZDgtZTY3ZmZmOTZlNThj'),
(8, 2, 'supplierId', 'b541a87c-d482-4161-9f30-5edc1fded445'),
(9, 2, 'perc', '12'),
(10, 2, 'pay', 'off'),
(11, 2, 'config_return', 'off');

-- --------------------------------------------------------

--
-- Структура таблицы `wb_data`
--

CREATE TABLE `wb_data` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wb_data`
--

INSERT INTO `wb_data` (`id`, `userId`, `type`, `status`) VALUES
(14, 2, 2, 2),
(15, 2, 7, 2),
(16, 2, 1, 2),
(17, 2, 6, 3),
(18, 2, 5, 2),
(19, 2, 8, 2),
(20, 2, 9, 2),
(21, 2, 10, 3),
(22, 2, 11, 2);

--
-- Индексы сохранённых таблиц
--

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
-- Индексы таблицы `wb_data`
--
ALTER TABLE `wb_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=666;

--
-- AUTO_INCREMENT для таблицы `list`
--
ALTER TABLE `list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `params`
--
ALTER TABLE `params`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `wb_data`
--
ALTER TABLE `wb_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
