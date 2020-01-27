<div class="topMenu">
    <ul>
    <?  if (isset($_SESSION['login'])) : ?>
            <li>
                <a href="/authorization/logout/">Выйти</a>
            </li>
    <?  else : ?>
            <li>
                <a href="/authorization/">Войти</a>
            </li>
    <?  endif; ?>
    <?  if ($this->checkPermission("admin") !== false) : ?>
            <li>
                <a href="/pass/">
                    Список машин
                </a>
            </li>
            <li>
                <a href="/pass/create/">
                    Добавить машину
                </a>
            </li>
            <li>
                <a href="/organization/">
                    Организации
                </a>
            </li>
    <?  endif; ?>
    </ul>
</div>
