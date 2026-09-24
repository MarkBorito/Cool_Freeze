<?php

require_once dirname(__DIR__, 2) . '/backend/bootstrap.php';



// Get the logged-in customer's name
$userName = $_SESSION['username'] ?? 'Customer';

$currentPage = 'home';

// Dashboard menu
$menu = [
    [
        'title' => 'Services',
        'items' => [
            ['label' => 'Cleaning', 'icon' => 'fa-fan', 'link' => 'service.php?id=1'],
            ['label' => 'Parts Replacement', 'icon' => 'fa-toolbox', 'link' => 'service.php?id=5'],
            ['label' => 'Repair', 'icon' => 'fa-screwdriver-wrench', 'link' => 'service.php?id=2'],
            ['label' => 'Maintenance', 'icon' => 'fa-gear', 'link' => 'service.php?id=3'],
            ['label' => 'Installation', 'icon' => 'fa-wind', 'link' => 'service.php?id=4'],
        ]
    ],
    [
        'title' => 'Cart',
        'items' => [
            ['label' => 'Service Cart', 'icon' => 'fa-cart-shopping', 'link' => 'cart.php']
        ]
    ],
    [
        'title' => 'Service Requests',
        'items' => [
            ['label' => 'My Requests', 'icon' => 'fa-clipboard-list', 'link' => 'requests.php']
        ]
    ],
    [
        'title' => 'Settings',
        'items' => [
            ['label' => 'Profile', 'icon' => 'fa-user', 'link' => 'profile.php'],
            ['label' => 'Security', 'icon' => 'fa-shield-halved', 'link' => 'security.php'],
            ['label' => 'FAQs & Help', 'icon' => 'fa-file-lines', 'link' => 'faqs.php']
        ]
    ]
];

// Services shown on dashboard
$services = [
    [
        'id' => 1,
        'title' => 'AC Cleaning',
        'description' => 'Keep your air conditioner clean and efficient.',
        'icon' => 'fa-fan'
    ],
    [
        'id' => 2,
        'title' => 'AC Repair',
        'description' => 'Get help with air conditioner problems.',
        'icon' => 'fa-screwdriver-wrench'
    ],
    [
        'id' => 3,
        'title' => 'AC Maintenance',
        'description' => 'Prevent problems with regular maintenance.',
        'icon' => 'fa-gear'
    ],
    [
        'id' => 4,
        'title' => 'AC Installation',
        'description' => 'Professional installation for your AC unit.',
        'icon' => 'fa-wind'
    ],
    [
        'id' => 5,
        'title' => 'Parts Replacement',
        'description' => 'Replace damaged or worn-out AC parts.',
        'icon' => 'fa-toolbox'
    ]
];

// Temporary request data
// We can connect this to MySQL later.
$recentRequests = [
    [
        'id' => 1,
        'service' => 'AC Cleaning',
        'date' => 'September 20, 2026',
        'status' => 'Complete'
    ],
    [
        'id' => 2,
        'service' => 'AC Repair',
        'date' => 'September 24, 2026',
        'status' => 'Pending'
    ]
];

// Prevent HTML injection
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CoolFreeze | Dashboard</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>frontend/assets/css/dashboardui.css"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <a href="index.php" class="brand">

            <i class="fa-regular fa-snowflake"></i>

            <span>COOLFREEZE</span>

        </a>


        <nav class="menu">

            <!-- HOME -->
            <a
                href="index.php"
                class="menu-home <?= $currentPage === 'home' ? 'active' : '' ?>"
            >
                <i class="fa-solid fa-house"></i>
                Home
            </a>


            <!-- MENU SECTIONS -->
            <?php foreach ($menu as $section): ?>

                <p class="menu-title">
                    <?= e($section['title']) ?>
                </p>

                <?php foreach ($section['items'] as $item): ?>

                    <a
                        href="<?= e($item['link']) ?>"
                        class="menu-link"
                    >

                        <i class="fa-solid <?= e($item['icon']) ?>"></i>

                        <?= e($item['label']) ?>

                    </a>

                <?php endforeach; ?>

            <?php endforeach; ?>

        </nav>


        <!-- LOGOUT -->
        <form
            action="<?= BASE_URL ?>backend/api/logout.php"
            method="POST"
            class="logout-form"
        >

            <button
                type="submit"
                class="logout"
            >

                <i class="fa-solid fa-right-from-bracket"></i>

                Log out

            </button>

        </form>

    </aside>


    <!-- MOBILE OVERLAY -->
    <div
        class="overlay"
        id="overlay"
    ></div>


    <!-- MAIN CONTENT -->
    <div class="main">


        <!-- TOPBAR -->
        <header class="topbar">

            <button
                type="button"
                class="menu-button"
                id="menuButton"
                aria-label="Open menu"
            >

                <i class="fa-solid fa-bars"></i>

            </button>


            <!-- SEARCH -->
            <form
                class="search"
                action="search.php"
                method="GET"
            >

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="search"
                    name="q"
                    placeholder="Search for services..."
                >

            </form>


            <!-- TOP ACTIONS -->
            <div class="top-actions">

                <a
                    href="notifications.php"
                    class="icon-link"
                    aria-label="Notifications"
                >

                    <i class="fa-regular fa-bell"></i>

                </a>


                <a
                    href="cart.php"
                    class="icon-link"
                    aria-label="Cart"
                >

                    <i class="fa-solid fa-cart-shopping"></i>

                </a>


                <a
                    href="profile.php"
                    class="profile-link"
                >

                    <i class="fa-regular fa-user"></i>

                    <span><?= e($userName) ?></span>

                </a>

            </div>

        </header>


        <!-- PAGE CONTENT -->
        <main class="content">


            <!-- WELCOME -->
            <section class="hero">

                <p class="hero-small">
                    Good day,
                </p>

                <h1>
                    Welcome to
                    <span>CoolFreeze!</span>
                </h1>

                <p class="hero-sub">
                    What would you like to do today?
                </p>

            </section>


            <!-- SERVICES -->
            <section class="panel">

                <h2 class="panel-title">

                    <i class="fa-solid fa-screwdriver-wrench"></i>

                    Services

                </h2>


                <div class="service-grid">

                    <?php foreach ($services as $service): ?>

                        <a
                            href="service.php?id=<?= (int) $service['id'] ?>"
                            class="service-card"
                        >

                            <span class="service-icon">

                                <i
                                    class="fa-solid <?= e($service['icon']) ?>"
                                ></i>

                            </span>


                            <h3>
                                <?= e($service['title']) ?>
                            </h3>


                            <p>
                                <?= e($service['description']) ?>
                            </p>


                            <span class="arrow-btn">

                                <i class="fa-solid fa-arrow-right"></i>

                            </span>

                        </a>

                    <?php endforeach; ?>

                </div>

            </section>


            <!-- RECENT REQUESTS -->
            <section class="panel">

                <h2 class="panel-title">

                    <i class="fa-regular fa-clock"></i>

                    My Recent Requests

                </h2>


                <div class="table">

                    <!-- TABLE HEADER -->
                    <div class="table-row table-head">

                        <span>
                            Service
                        </span>

                        <span>
                            Date Requested
                        </span>

                        <span>
                            Status
                        </span>

                        <span>
                            Action
                        </span>

                    </div>


                    <!-- REQUESTS -->
                    <?php if (empty($recentRequests)): ?>

                        <div class="empty-request">

                            <i class="fa-regular fa-folder-open"></i>

                            <p>
                                You don't have any service requests yet.
                            </p>

                            <a href="service.php">
                                Browse Services
                            </a>

                        </div>

                    <?php else: ?>

                        <?php foreach ($recentRequests as $request): ?>

                            <div
                                class="table-row"
                                data-request-id="<?= (int) $request['id'] ?>"
                            >

                                <span class="service-name">

                                    <span class="thumb">

                                        <i class="fa-solid fa-user-gear"></i>

                                    </span>

                                    <?= e($request['service']) ?>

                                </span>


                                <span class="date">

                                    <?= e($request['date']) ?>

                                </span>


                                <span>

                                    <span
                                        class="badge <?= strtolower(e($request['status'])) ?>"
                                    >

                                        <?= e($request['status']) ?>

                                    </span>

                                </span>


                                <a
                                    href="request-details.php?id=<?= (int) $request['id'] ?>"
                                    class="details-link"
                                >

                                    View Details

                                    <i class="fa-solid fa-arrow-right"></i>

                                </a>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </section>


        </main>

    </div>

</div>


<script src="<?= BASE_URL ?>frontend/assets/js/custom.js"></script>

</body>

</html>