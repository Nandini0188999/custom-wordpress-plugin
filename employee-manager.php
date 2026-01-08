<?php
/**
 * Plugin Name: Employee Manager
 * Description: Simple plugin to manage employees in WordPress.
 * Version: 1.0
 * Author: Nandini
 */

if (!defined('ABSPATH')) exit;

// Create DB table on activation
function em_create_table() {
    global $wpdb;
    $table = $wpdb->prefix . "employees";

    $sql = "CREATE TABLE $table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100)
    )";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'em_create_table');

// Add admin menu
function em_menu() {
    add_menu_page(
        'Employee Manager',
        'Employees',
        'manage_options',
        'employee-manager',
        'em_page'
    );
}
add_action('admin_menu', 'em_menu');

// Admin page UI
function em_page() {
    ?>
    <div class="wrap">
        <h2>Add Employee</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Name" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="submit" name="submit" value="Save">
        </form>
    </div>
    <?php

    if (isset($_POST['submit'])) {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . "employees",
            [
                'name' => sanitize_text_field($_POST['name']),
                'email' => sanitize_email($_POST['email'])
            ]
        );
        echo "<p>Employee Added!</p>";
    }
}
