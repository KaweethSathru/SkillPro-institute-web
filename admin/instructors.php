<?php include 'header.php'; ?>

<?php
include '../components/connection.php';
?>

<div class="admin-content-header">
    <h1>Manage Instructors</h1>
    <div class="action-buttons">
        <a href="add_instructor.php" class="btn"><i class="fas fa-plus"></i> Add New Instructor</a>
    </div>
</div>

<div class="admin-content-body">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Instructor</th>
                    <th>Specialization</th>
                    <th>Qualifications</th>
                    <th>Branch</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM instructors ORDER BY created_at DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $instructors = $stmt->fetchAll();
                
                if(count($instructors) > 0):
                    foreach($instructors as $instructor):
                ?>
                <tr>
                    <td><?= $instructor['id'] ?></td>
                    <td>
                        <div class="instructor-info">
                            <div class="instructor-image">
                                <img src="assets/images/instructors/<?= $instructor['profile_image'] ?: 'default-instructor.jpg' ?>" alt="<?= $instructor['full_name'] ?>">
                            </div>
                            <div>
                                <strong><?= $instructor['full_name'] ?></strong>
                                <div class="instructor-email"><?= $instructor['email'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?= $instructor['specialization'] ?></td>
                    <td><?= substr($instructor['qualifications'], 0, 50) ?>...</td>
                    <td><?= $instructor['branch'] ?></td>
                    <td>
                        <span class="status-badge <?= $instructor['status'] === 'Active' ? 'active' : 'inactive' ?>">
                            <?= $instructor['status'] ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="edit_instructor.php?id=<?= $instructor['id'] ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="delete_instructor.php?id=<?= $instructor['id'] ?>" class="btn-icon delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this instructor?')"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No instructors found. Add your first instructor!</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>