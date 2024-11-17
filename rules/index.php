<?php
// Устанавливаем соединение с базой данных
$currentPage = 'rules';
include($_SERVER['DOCUMENT_ROOT'].'/includes/navbar.php');
$host = "localhost";
$database = "your_database";
$user = "your_user";
$password = "password_of_user";

$conn = new mysqli($host, $user, $password, $database);

// Проверяем соединение
if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Запрос для получения категорий и соответствующих правил
$sql = "SELECT category, GROUP_CONCAT(CONCAT('{\"id\":\"', id, '\",\"text_rule\":\"', REPLACE(text_rule, '\"', '\\\"'), '\",\"punish\":\"', REPLACE(punish, '\"', '\\\"'), '\"}')) AS rules_json 
        FROM ingame_rules
        GROUP BY category";

$result = $conn->query($sql);

$rules = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Проверяем, чтобы rules_json не было пустым
        if (!empty($row["rules_json"])) {
            $decoded_rules = json_decode("[" . $row["rules_json"] . "]", true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $rules[] = array(
                    "category" => $row["category"],
                    "rules" => $decoded_rules
                );
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vesper6 - Правила</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
         .rule-category {
        	 background-color: #0a1c36;
        	 border-left: 4px solid #F6D8AE;
        	 padding: 10px;
        	 margin-bottom: 20px;
        }
        .rule-item {
            display: flex;
            flex-direction: column;
            justify-content: center; /* Вертикальное выравнивание */
            background-color: #1a2d4f;
            border-left: 2px solid #F6D8AE;
            padding: 10px;
            margin-bottom: 10px;
        }
        .rule-text {
            margin-bottom: 5px; /* Уменьшает расстояние снизу */
        }
        
        .rule-punishment {
            font-style: italic;
            color: #F6D8AE;
            margin-top: 5px; /* Уменьшает расстояние сверху */
            margin-bottom: 5px;
        }
        .rules-header {
            position: -webkit-sticky;
            position: sticky;
            top: 70px;
        }
         .category-buttons {
        	 display: flex;
        	 flex-wrap: wrap;
        	 gap: 10px;
        	 margin-bottom: 20px;
        }
        .no-results {
            text-align: center;
            font-size: 18px;
            color: #777;
        }
         .category-button {
        	 background-color: #1a2d4f;
        	 color: white;
        	 border: 1px solid #F6D8AE;
        	 padding: 5px 10px;
        	 border-radius: 5px;
        	 cursor: pointer;
        	 transition: background-color 0.3s;
        }
         @media (hover: hover) and (pointer: fine) {
            .category-button:hover {
                background-color: #F6D8AE;
                color: #123461;
            }
        }

        /* Анимация мигания */
        @keyframes blink {
            0% { background-color: #1a2d4f; color: white; }
            50% { background-color: #F6D8AE; color: #123461; }
            100% { background-color: #1a2d4f; color: white; }
        }
        @media (hover: none) and (pointer: coarse) {
            .category-button:active {
                animation: blink 0.6s;
            }
        }
         .back-to-top {
        	 position: fixed;
        	 bottom: 20px;
        	 right: 20px;
        	 background-color: #F6D8AE;
        	 color: #123461;
        	 width: 50px;
        	 height: 50px;
        	 border-radius: 50%;
        	 border: none;
        	 cursor: pointer;
        	 box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        	 transition: background-color 0.3s, opacity 0.3s, visibility 0.3s;
        	 opacity: 0;
        	 visibility: hidden;
        }
         .back-to-top:hover {
        	 background-color: #f0c78d;
        }
         .back-to-top i {
        	 display: flex;
        	 justify-content: center;
        	 align-items: center;
        	 width: 100%;
        	 height: 100%;
        }
         .back-to-top.show {
        	 opacity: 1;
        	 visibility: visible;
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Правила сервера</h1>
        <div class='rules-header'>
            <div class="search-bar mb-2">
                <input type="text" id="search-input" class='form-control' placeholder="Поиск правил...">
            </div>
            <div id="category-buttons" class="category-buttons">
                <?php foreach ($rules as $index => $category): ?>
                    <button class="category-button" onclick="document.getElementById('category-<?php echo $index +1; ?>').scrollIntoView({ behavior: 'smooth' });">
                        <?php echo htmlspecialchars($category['category']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="rules-container">
            <?php foreach ($rules as $categoryIndex => $category): ?>
                <div class="rule-category" id="category-<?php echo $categoryIndex +1; ?>">
                    <h2><?php echo htmlspecialchars($category['category']); ?></h2>
                    <?php foreach ($category['rules'] as $ruleIndex => $rule): ?>
                        <?php
                            $ruleNumber = ($categoryIndex +1) . '.' . ($ruleIndex +1);
                            $ruleText = htmlspecialchars($rule['text_rule']);
                            $rulePunish = !empty($rule['punish']) ? htmlspecialchars($rule['punish']) : '';
                        ?>
                        <div class="rule-item" data-rule-number="<?php echo $ruleNumber; ?>">
                            <p class="rule-text"><strong><?php echo $ruleNumber . '. ' . $ruleText; ?></strong></p>
                            <?php if ($rulePunish): ?>
                                <p class="rule-punishment">Наказание: <?php echo $rulePunish; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="no-results" style="display: none;">Ничего не найдено</p>
    </div>

    <footer class="mt-5 py-3 text-center">
        <p>Сайт принадлежит Vesper6.</p>
    </footer>
    
    <button id="back-to-top" class="back-to-top" aria-label="Вернуться наверх">
        <i data-lucide="chevron-up"></i>
    </button>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('search-input').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase().trim();

            var ruleItems = document.querySelectorAll('.rule-item');
            var hasVisibleItems = false;

            ruleItems.forEach(function(item) {
                var text = item.querySelector('.rule-text').innerText.toLowerCase();
                var punish = item.querySelector('.rule-punishment') ? item.querySelector('.rule-punishment').innerText.toLowerCase() : '';
                var ruleNumber = item.getAttribute('data-rule-number').toLowerCase();

                if (text.includes(searchTerm) || punish.includes(searchTerm) || ruleNumber.includes(searchTerm)) {
                    item.style.display = '';
                    hasVisibleItems = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Скрываем или показываем категории, в которых нет видимых правил
            var ruleCategories = document.querySelectorAll('.rule-category');
            ruleCategories.forEach(function(category) {
                var visibleRules = category.querySelectorAll('.rule-item:not([style*="display: none"])');
                if (visibleRules.length === 0) {
                    category.style.display = 'none';
                } else {
                    category.style.display = '';
                }
            });

            // Если ничего не найдено, показываем сообщение
            var noResults = document.querySelector('.no-results');
            if (searchTerm !== '' && !hasVisibleItems) {
                noResults.style.display = '';
            } else {
                noResults.style.display = 'none';
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // Функционал кнопки "Наверх"
            const backToTopButton = document.getElementById('back-to-top');

            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 100) {
                    backToTopButton.classList.add('show');
                } else {
                    backToTopButton.classList.remove('show');
                }
            });

            backToTopButton.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>