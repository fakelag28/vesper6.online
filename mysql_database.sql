-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 17 2024 г., 12:57
-- Версия сервера: 5.7.27-30
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `u2761871_OnlineBD`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `discord_id` bigint(20) NOT NULL,
  `num_warnings` int(11) DEFAULT '0',
  `admin_prefix` varchar(50) DEFAULT NULL,
  `admin_points` int(11) DEFAULT '0',
  `appointment_date` date DEFAULT NULL,
  `last_site_login` datetime DEFAULT NULL,
  `last_game_login` datetime DEFAULT NULL,
  `log_visibility` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`admin_id`, `nickname`, `discord_id`, `num_warnings`, `admin_prefix`, `admin_points`, `appointment_date`, `last_site_login`, `last_game_login`, `log_visibility`) VALUES
(1, 'CoolMod123', 123456789012345678, 0, 'Mod', 50, '2021-10-01', '2023-10-10 10:00:00', '2023-10-10 09:30:00', 1),
(2, 'SuperAdmin', 987654321098765432, 1, 'Admin', 150, '2020-05-15', '2023-10-09 20:00:00', '2023-10-09 19:45:00', 1),
(3, 'Nicolas Reed', 938766161322078280, 0, 'Reed', 0, '2024-10-03', '2024-10-06 21:16:35', '2024-10-09 19:45:00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `admins_roles`
--

CREATE TABLE `admins_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `role_color` varchar(7) DEFAULT NULL,
  `role_logo_url` varchar(255) DEFAULT NULL,
  `can_edit_rules` tinyint(1) DEFAULT '0',
  `can_modify_admins` tinyint(1) DEFAULT '0',
  `can_manage_warnings` tinyint(1) DEFAULT '0',
  `can_manage_admin_points` tinyint(1) DEFAULT '0',
  `can_view_summary_logs` tinyint(1) DEFAULT '0',
  `can_view_full_logs` tinyint(1) DEFAULT '0',
  `has_executive_rights` tinyint(1) DEFAULT '0',
  `is_secondary_role` tinyint(1) DEFAULT '0',
  `is_senior_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `admins_roles`
--

INSERT INTO `admins_roles` (`role_id`, `role_name`, `role_color`, `role_logo_url`, `can_edit_rules`, `can_modify_admins`, `can_manage_warnings`, `can_manage_admin_points`, `can_view_summary_logs`, `can_view_full_logs`, `has_executive_rights`, `is_secondary_role`, `is_senior_admin`) VALUES
(1, 'Руководитель проекта', '#00FF00', 'https://example.com/moderator-logo.png', 1, 0, 1, 0, 1, 0, 0, 0, 0),
(2, 'Administrator', '#FF0000', 'https://example.com/admin-logo.png', 1, 1, 1, 1, 1, 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `admin_roles`
--

CREATE TABLE `admin_roles` (
  `admin_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `admin_roles`
--

INSERT INTO `admin_roles` (`admin_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `ingame_logs`
--

CREATE TABLE `ingame_logs` (
  `log_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `log_type` varchar(50) NOT NULL,
  `log_message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `ingame_rules`
--

CREATE TABLE `ingame_rules` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `text_rule` varchar(255) NOT NULL,
  `punish` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `ingame_rules`
--

INSERT INTO `ingame_rules` (`id`, `category`, `text_rule`, `punish`) VALUES
(1, 'Введение', 'Незнание правил не освобождает от ответственности.', ''),
(2, 'Введение', 'Следящий за правилами имеет право менять правила без оповещения.', ''),
(3, 'Введение', 'Правила могут меняться раз в 24 часа.', ''),
(4, 'Введение', 'Блокировать на 365 дней имеет право только Главный Администратор, при этом имея на то причины', ''),
(5, 'Основные правила сервера', 'Запрещены любые действия направленные на унижение, причинение вреда серверу.', 'Блокировка от 1 до 365 дней'),
(6, 'Основные правила сервера', 'Запрещено использование стороннего программного обеспечения для получения преимущества над другими игроками.', 'Блокировка от 30 до 365 дней');

-- --------------------------------------------------------

--
-- Структура таблицы `ingame_rules_edit_logs`
--

CREATE TABLE `ingame_rules_edit_logs` (
  `log_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `editor_nickname` varchar(100) NOT NULL,
  `editor_discord_id` varchar(100) NOT NULL,
  `change_type` enum('Новое','изменение старого') NOT NULL,
  `rule_before` text,
  `rule_after` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Индексы таблицы `admins_roles`
--
ALTER TABLE `admins_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Индексы таблицы `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`admin_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Индексы таблицы `ingame_logs`
--
ALTER TABLE `ingame_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Индексы таблицы `ingame_rules`
--
ALTER TABLE `ingame_rules`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `ingame_rules_edit_logs`
--
ALTER TABLE `ingame_rules_edit_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `admins_roles`
--
ALTER TABLE `admins_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `ingame_logs`
--
ALTER TABLE `ingame_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ingame_rules_edit_logs`
--
ALTER TABLE `ingame_rules_edit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD CONSTRAINT `admin_roles_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`),
  ADD CONSTRAINT `admin_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `admins_roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
