# Задача 1

Дана таблица:
```sql
CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` int NOT NULL COMMENT 'Сумма операции',
  `date` date NOT NULL
) ENGINE=InnoDB;

INSERT INTO `transactions` (`id`, `user_id`, `amount`, `date`) VALUES
(1, 1, 150, '2024-01-03'),
(2, 2, 278, '2024-01-03'),
(3, 2, 86, '2024-01-04'),
(4, 2, 34, '2024-01-05'),
(5, 1, -45, '2024-01-06'),
(6, 3, 64, '2024-01-06');

ALTER TABLE `transactions`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `transactions`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
```

```
+----+---------+--------+------------+
| id | user_id | amount | date       |
+----+---------+--------+------------+
|  1 |       1 |    150 | 2024-01-03 |
|  2 |       2 |    278 | 2024-01-03 |
|  3 |       2 |     86 | 2024-01-04 |
|  4 |       2 |     34 | 2024-01-05 |
|  5 |       1 |    -45 | 2024-01-06 |
|  6 |       3 |     64 | 2024-01-06 |
+----+---------+--------+------------+
```

Нужно написать sql-запрос, который выведет следующую таблицу (без вложенных подзапросов):
```
+----+---------+------------+---------+
| id | user_id | date       | balance |
+----+---------+------------+---------+
|  1 |       1 | 2024-01-03 |     150 |
|  2 |       2 | 2024-01-03 |     278 |
|  3 |       2 | 2024-01-04 |     364 |
|  4 |       2 | 2024-01-05 |     398 |
|  5 |       1 | 2024-01-06 |     105 |
|  6 |       3 | 2024-01-06 |      64 |
+----+---------+------------+---------+
```
Здесь поле balance - нарастающий итог, который обозначает баланс клиента после данной транзакции

# Задача 2
Дана таблица:
```sql
CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
 (1, 'Обувь', NULL),
 (2, 'Одежда', NULL),
 (3, 'Сапоги', 1),
 (4, 'Тапочки', 1),
 (5, 'Кроссовки', 1),
 (6, 'Ботинки', 1),
 (7, 'Кофты', 2),
 (8, 'Футболки', 2),
 (9, 'Куртки', 2),
 (10, 'Штаны', 2),
 (11, 'Прочее', NULL),
 (12, 'Куртки зимние', 9),
 (13, 'Куртки весенние/осенние', 9);

ALTER TABLE `categories`
    ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);
ALTER TABLE `categories`
    MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
ALTER TABLE `categories`
    ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
```

Надо написать скрипт, который выведет следующий JSON:
```json
[
    {
        "id": 1,
        "name": "Обувь",
        "parent_id": null,
        "children": [
            {
                "id": 3,
                "name": "Сапоги",
                "parent_id": 1,
                "children": []
            },
            {
                "id": 4,
                "name": "Тапочки",
                "parent_id": 1,
                "children": []
            },
            {
                "id": 5,
                "name": "Кроссовки",
                "parent_id": 1,
                "children": []
            },
            {
                "id": 6,
                "name": "Ботинки",
                "parent_id": 1,
                "children": []
            }
        ]
    },
    {
        "id": 2,
        "name": "Одежда",
        "parent_id": null,
        "children": [
            {
                "id": 7,
                "name": "Кофты",
                "parent_id": 2,
                "children": []
            },
            {
                "id": 8,
                "name": "Футболки",
                "parent_id": 2,
                "children": []
            },
            {
                "id": 9,
                "name": "Куртки",
                "parent_id": 2,
                "children": [
                    {
                        "id": 12,
                        "name": "Куртки зимние",
                        "parent_id": 9,
                        "children": []
                    },
                    {
                        "id": 13,
                        "name": "Куртки весенние\/осенние",
                        "parent_id": 9,
                        "children": []
                    }
                ]
            },
            {
                "id": 10,
                "name": "Штаны",
                "parent_id": 2,
                "children": []
            }
        ]
    },
    {
        "id": 11,
        "name": "Прочее",
        "parent_id": null,
        "children": []
    }
]
```

Решение без рекурсивных запросов будет плюсом.