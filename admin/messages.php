<?php include 'header.php'; ?>

<?php
include '../components/connection.php';

// Handle message deletion
if (isset($_GET['delete'])) {
    $message_id = $_GET['delete'];
    
    $delete_sql = "DELETE FROM contact_us WHERE id = :id";
    $stmt = $conn->prepare($delete_sql);
    $stmt->execute(['id' => $message_id]);
    
    if ($stmt->rowCount() > 0) {
        $success_message = "Message deleted successfully!";
    } else {
        $error_message = "Failed to delete message.";
    }
}

// Handle search
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM contact_us 
            WHERE name LIKE :search 
            OR email LIKE :search 
            OR message LIKE :search 
            ORDER BY id DESC";  // Changed to id DESC since created_at doesn't exist
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
} else {
    $sql = "SELECT * FROM contact_us ORDER BY id DESC";  // Changed to id DESC
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$messages = $stmt->fetchAll();
?>

<div class="admin-content-header">
    <h1>Student Messages</h1>
    <p>View and manage messages sent by students through the contact form.</p>
</div>

<?php if (isset($success_message)): ?>
    <div class="admin-alert success">
        <i class="fas fa-check-circle"></i> <?= $success_message ?>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="admin-alert error">
        <i class="fas fa-exclamation-circle"></i> <?= $error_message ?>
    </div>
<?php endif; ?>

<div class="admin-content-body">
    <div class="admin-filters">
        <form method="GET" action="messages.php" class="search-form">
            <div class="search-group">
                <input type="text" name="search" placeholder="Search messages..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn-icon"><i class="fas fa-search"></i></button>
            </div>
            <a href="messages.php" class="btn secondary">Clear Filters</a>
        </form>
        
        <div class="filter-group">
            <label for="status-filter">Filter by Status:</label>
            <select id="status-filter" class="admin-select">
                <option value="all">All Messages</option>
                <option value="unread">Unread</option>
                <option value="replied">Replied</option>
                <option value="archived">Archived</option>
            </select>
        </div>
    </div>

    <div class="messages-container">
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $message): ?>
            <div class="message-card">
                <div class="message-header">
                    <div class="sender-info">
                        <div class="sender-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div>
                            <h3><?= $message['name'] ?></h3>
                            <div class="sender-email">
                                <i class="fas fa-envelope"></i> <?= $message['email'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="message-meta">
                        <span class="message-date">Message ID: <?= $message['id'] ?></span>
                        <div class="message-actions">
                            <a href="mailto:<?= $message['email'] ?>?subject=RE: Your message to SkillPro Institute" 
                               class="btn-icon" title="Reply">
                                <i class="fas fa-reply"></i>
                            </a>
                            <a href="messages.php?delete=<?= $message['id'] ?>" 
                               class="btn-icon delete-btn" title="Delete"
                               onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="message-content">
                    <?= nl2br(htmlspecialchars($message['message'])) ?>
                </div>
                
                <div class="message-footer">
                    <div class="status-badge unread">Unread</div>
                    <button class="btn small">Mark as Read</button>
                    <button class="btn small secondary">Archive</button>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-comment-slash fa-3x"></i>
                <h3>No Messages Found</h3>
                <p>Try adjusting your search or check back later</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="pagination">
        <a href="#" class="page-item disabled"><i class="fas fa-chevron-left"></i></a>
        <a href="#" class="page-item active">1</a>
        <a href="#" class="page-item">2</a>
        <a href="#" class="page-item">3</a>
        <a href="#" class="page-item"><i class="fas fa-chevron-right"></i></a>
    </div>
</div>

<script>
    // Filter functionality
    document.getElementById('status-filter').addEventListener('change', function() {
        const status = this.value;
        const cards = document.querySelectorAll('.message-card');
        
        for (let card of cards) {
            if (status === 'all') {
                card.style.display = 'block';
            } else {
                const badge = card.querySelector('.status-badge').className;
                if (badge.includes(status)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        }
    });
    
    // Mark as read functionality
    document.querySelectorAll('.message-footer .btn.small').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.message-card');
            const badge = card.querySelector('.status-badge');
            
            badge.className = 'status-badge replied';
            badge.textContent = 'Replied';
            
            this.textContent = 'Marked';
            this.disabled = true;
        });
    });
</script>

<?php include 'footer.php'; ?>