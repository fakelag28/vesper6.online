<!-- navbar.php -->
<!DOCTYPE html>
<style>
        .navbar-nav .nav-item + .nav-item {
            border-left: 1px solid rgba(255, 255, 255, 0.3);
            margin-left: 1rem;
            padding-left: 1rem;
        }
        
        @media (max-width: 991.98px) {
            .navbar-nav .nav-item + .nav-item {
                border-left: none;
                margin-left: 0;
                padding-left: 0;
            }
        }
        
        /* Стиль для аватарки */
        .avatar-img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid;
        }
        
        /* Дополнительные стили для выпадающего меню */
        .dropdown-menu {
            min-width: 160px;
        }
        
        .dropdown-item-text {
            font-weight: bold;
            padding: 8px 16px;
            display: block;
        }
    </style>
     <nav class="navbar navbar-expand-lg navbar-dark">
         <div class="container">
             <a class="navbar-brand" href="..">
                 <img src="../assets/img/logo.png" alt="Vesper6 Logo" class="d-inline-block align-top">
             </a>
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="collapse navbar-collapse" id="navbarNav">
                 <ul class="navbar-nav ms-auto text-center">
                     <li class="nav-item">
                         <a class="nav-link <?php if($currentPage == 'home') echo 'active'; ?>" href="..">Главная</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link <?php if($currentPage == 'rules') echo 'active'; ?>" href="/rules">Правила</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link <?php if($currentPage == 'admins') echo 'active'; ?>" href="/admins">Состав администрации</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link <?php if($currentPage == 'admin') echo 'active'; ?>" href="https://admin.vesper6.online/">Админ-панель</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link <?php if($currentPage == 'connect') echo 'active'; ?>" href="../#connect">Подключиться</a>
                     </li>
                     <?php
                     #<li class="nav-item">
                     #    <a class="nav-link <?php if($currentPage == 'profile') echo 'active'; " href="../profile">Профиль</a>
                     #</li>
                     ?>
                 </ul>
             </div>
         </div>
     </nav>