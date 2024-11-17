<?php
// Устанавливаем соединение с базой данных
$currentPage = 'admins';
include($_SERVER['DOCUMENT_ROOT'].'/includes/navbar.php');
// Настройки подключения к базе данных
$host = "localhost";
$database = "your_database";
$user = "your_user";
$password = "password_of_user";

// Создаем подключение
$conn = new mysqli($host, $user, $password, $database);

// Проверяем подключение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Устанавливаем кодировку
$conn->set_charset("utf8");

// Запрос для получения администраторов и их ролей
$sql_list1 = "
    SELECT 
        a.admin_id, 
        a.nickname, 
        a.discord_id, 
        GROUP_CONCAT(ar.role_name SEPARATOR ', ') AS roles,
        a.admin_prefix
    FROM 
        admins a
    JOIN 
        admin_roles arl ON a.admin_id = arl.admin_id
    JOIN 
        admins_roles ar ON arl.role_id = ar.role_id
    GROUP BY 
        a.admin_id
    ORDER BY 
        a.admin_id ASC
";

// Запрос для второго списка (все данные)
$sql_list2 = "SELECT * FROM admins ORDER BY admin_id ASC";

// Выполняем запросы
$result_list1 = $conn->query($sql_list1);
$result_list2 = $conn->query($sql_list2);

// Закрываем подключение после получения данных
$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vesper6 - Состав администрации</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
.table {
  color: white;
}

.table thead th {
  background-color: #1a2d4f;
  color: #F6D8AE;
  border-color: #2c4a7c;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(26, 45, 79, 0.5);
}

.table-hover tbody tr:hover {
  background-color: rgba(246, 216, 174, 0.1);
}

.table-responsive {
  border-radius: 15px;
  overflow: hidden;
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
        <h1 class="text-center mb-4">Состав администрации</h1>
        <!-- Первый список: Основная информация -->
        <div class="mb-5">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>NickName</th>
                        <th>Discord ID</th>
                        <th>Должность</th>
                        <th>Префикс</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_list1 && $result_list1->num_rows > 0): ?>
                        <?php while($row = $result_list1->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['admin_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nickname']); ?></td>
                                <td><?php echo htmlspecialchars($row['discord_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['roles']); ?></td>
                                <td><?php echo htmlspecialchars($row['admin_prefix']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Данные отсутствуют</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-5 py-3 text-center">
        <p>Сайт принадлежит Vesper6.</p>
    </footer>
    
    <button id="back-to-top" class="back-to-top" aria-label="Вернуться наверх">
        <i data-lucide="chevron-up"></i>
    </button>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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