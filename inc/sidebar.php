<?php
// Define your sidebar pages as an array
$sidebar_pages = [
    [
        'url' => '',
        'label' => 'Dashboard',
        'icon' => 'bi-speedometer2'
    ],
    [
        'url' => 'add_students',
        'label' => 'Add Students',
        'icon' => 'bi-upload'
    ],
    [
        'url' => 'view_attendance',
        'label' => 'View Attendance',
        'icon' => 'bi-list-check'
    ]
];

// Get current page from URL for active link highlighting
$current_page = $_GET['url'] ?? '';
$current_page = trim($current_page, '/');
?>

<nav class="w-100 h-100 bg-light border-end shadow-sm">
    <ul class="nav flex-column py-4 px-2">
        <?php foreach ($sidebar_pages as $page):
            $is_active = ($current_page === $page['url'] || ($page['url'] === '' && $current_page === 'dashboard'));
        ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-dark fw-semibold rounded px-3 py-2
                <?php echo $is_active ? 'active' : 'hover-bg-primary'; ?>"
                    href="./<?php echo $page['url']; ?>">
                    <i class="bi <?php echo $page['icon']; ?> me-2"></i> <?php echo $page['label']; ?>
                </a>
            </li>
        <?php endforeach;
